<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CleanupPendingConversions extends Command
{
    protected $signature = 'conversions:cleanup-pending
                            {--hours=4 : Supprimer les fichiers temp_uploads plus vieux que N heures}
                            {--dry-run : Simuler sans supprimer}';

    protected $description = 'Nettoyer les conversions en attente expirées (table + fichiers temp_uploads orphelins)';

    public function handle()
    {
        $hours  = (int) $this->option('hours');
        $dryRun = $this->option('dry-run');

        // ──────────────────────────────────────────────
        // 1. Nettoyage de la table pending_conversions
        //    (conservé au cas où elle serait utilisée plus tard)
        // ──────────────────────────────────────────────
        $expired = DB::table('pending_conversions')
            ->where('expires_at', '<', now())
            ->get();

        foreach ($expired as $pending) {
            $data = json_decode($pending->data, true);

            if (isset($data['temp_file_path']) && file_exists($data['temp_file_path'])) {
                if (!$dryRun) {
                    unlink($data['temp_file_path']);
                }
                Log::info('🗑️ [pending_conversions] Fichier supprimé: ' . $data['temp_file_path']);
                $this->line('  Supprimé (table): ' . $data['temp_file_path']);
            }
        }

        $count = 0;
        if (!$dryRun) {
            $count = DB::table('pending_conversions')
                ->where('expires_at', '<', now())
                ->delete();
        }

        $this->info("✅ {$count} conversions en attente expirées nettoyées (table)");
        Log::info("✅ {$count} pending_conversions nettoyées (table)");

        // ──────────────────────────────────────────────
        // 2. Nettoyage des fichiers orphelins dans temp_uploads
        //    Les fichiers sont sauvegardés en SESSION, pas en base.
        //    On supprime donc tout fichier .skp de plus de N heures.
        // ──────────────────────────────────────────────
        $this->info("🧹 Nettoyage du disque [temp_uploads] — fichiers > {$hours}h" . ($dryRun ? ' [DRY RUN]' : ''));

        $cutoff   = now()->subHours($hours)->timestamp;
        $files    = Storage::disk('temp_uploads')->allFiles();
        $deleted  = 0;
        $skipped  = 0;

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) !== 'skp') {
                continue;
            }

            $lastModified = Storage::disk('temp_uploads')->lastModified($file);

            if ($lastModified < $cutoff) {
                if ($dryRun) {
                    $this->line("  [dry-run] Serait supprimé : {$file}");
                } else {
                    Storage::disk('temp_uploads')->delete($file);
                    Log::info("🗑️ [CleanupPendingConversions] Fichier orphelin supprimé : {$file}");
                }
                $deleted++;
            } else {
                $skipped++;
            }
        }

        $label = $dryRun ? 'À supprimer' : 'Supprimés';
        $this->info("✅ {$label} : {$deleted} fichier(s) | Ignorés (récents) : {$skipped}");
        Log::info("✅ [CleanupPendingConversions] temp_uploads — {$deleted} supprimés, {$skipped} ignorés");

        return 0;
    }
}