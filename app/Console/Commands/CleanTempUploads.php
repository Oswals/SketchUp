<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CleanTempUploads extends Command
{
    protected $signature   = 'temp:clean-uploads';
    protected $description = 'Supprime les fichiers .tmp et .skp orphelins de plus de 5h';

    public function handle()
    {
        $this->info('Démarrage du nettoyage des fichiers temporaires...');

        $directory = storage_path('app/temp');
        $expiration = Carbon::now()->subHours(5)->timestamp;
        $deleted = 0;
        $errors = 0;

        foreach (['*.tmp', '*.skp'] as $pattern) {
            foreach (glob($directory . '/' . $pattern) as $file) {
                if (filemtime($file) < $expiration) {
                    try {
                        unlink($file);
                        $deleted++;
                        Log::info('Fichier supprimé: ' . basename($file));
                    } catch (\Exception $e) {
                        $errors++;
                        Log::error('Erreur suppression: ' . $e->getMessage());
                    }
                }
            }
        }

        $message = "Nettoyage temp terminé — Supprimés: {$deleted}, Erreurs: {$errors}";
        $this->info($message);
        Log::info($message);

        return Command::SUCCESS;
    }
}