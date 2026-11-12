<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CleanTempFiles extends Command
{
    protected $signature = 'temp:clean
                            {--hours=4 : Supprimer les fichiers plus vieux que N heures}
                            {--dry-run : Simuler sans supprimer}';

    protected $description = 'Supprime les fichiers .skp orphelins du disque temp (utilisateurs authentifiés) de plus de 4h';

    public function handle(): int
    {
        $hours  = (int) $this->option('hours');
        $dryRun = $this->option('dry-run');
        $cutoff = now()->subHours($hours)->timestamp;

        $this->info("🧹 Nettoyage du disque [temp] — fichiers > {$hours}h" . ($dryRun ? ' [DRY RUN]' : ''));

        $files   = Storage::disk('temp')->allFiles();
        $deleted = 0;
        $skipped = 0;

        foreach ($files as $file) {
            // On ne traite que les .skp
            if (pathinfo($file, PATHINFO_EXTENSION) !== 'skp') {
                continue;
            }

            $lastModified = Storage::disk('temp')->lastModified($file);

            if ($lastModified < $cutoff) {
                if ($dryRun) {
                    $this->line("  [dry-run] Serait supprimé : {$file}");
                } else {
                    Storage::disk('temp')->delete($file);
                    Log::info("🗑️ [CleanTempFiles] Fichier orphelin supprimé : {$file}");
                }
                $deleted++;
            } else {
                $skipped++;
            }
        }

        $label = $dryRun ? 'À supprimer' : 'Supprimés';
        $this->info("✅ {$label} : {$deleted} fichier(s) | Ignorés (récents) : {$skipped}");
        Log::info("✅ [CleanTempFiles] {$deleted} fichier(s) supprimés, {$skipped} ignorés");

        return 0;
    }
}