<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Conversion;
use Carbon\Carbon;

class CleanupConversions extends Command
{
    protected $signature   = 'conversions:cleanup';
    protected $description = 'Supprime les fichiers physiques de plus de 3 jours (BDD conservée)';

    public function handle()
    {
        $this->info('Démarrage du nettoyage des fichiers physiques...');

        $expiration = Carbon::now()->subHours(5);

        $conversions = Conversion::withTrashed()
            ->where('created_at', '<', $expiration)
            ->whereNotNull('converted_file_path')
            ->get();

        if ($conversions->isEmpty()) {
            $this->info('Aucun fichier expiré trouvé.');
            return Command::SUCCESS;
        }

        $deleted  = 0;
        $notFound = 0;
        $errors   = 0;

        foreach ($conversions as $conversion) {
            try {
                // Supprimer le fichier physique
                if ($conversion->fileExists()) {
                    $conversion->deleteFile();
                    $deleted++;
                    Log::info("Fichier physique supprimé — conversion #{$conversion->id}");
                } else {
                    $notFound++;
                }

                // Mettre converted_file_path à null pour éviter
                // des tentatives futures inutiles, sans toucher updated_at
                $conversion->timestamps = false;
                $conversion->converted_file_path = null;
                $conversion->save();

            } catch (\Exception $e) {
                $errors++;
                Log::error("Erreur nettoyage conversion #{$conversion->id} : " . $e->getMessage());
                $this->error("Erreur sur ID {$conversion->id} : " . $e->getMessage());
            }
        }

        $message = "Nettoyage terminé — Supprimés: {$deleted}, Introuvables: {$notFound}, Erreurs: {$errors}";
        Log::info($message);
        $this->info($message);

        return Command::SUCCESS;
    }
}