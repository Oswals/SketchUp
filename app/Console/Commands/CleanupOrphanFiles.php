<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CleanupOrphanFiles extends Command
{
    protected $signature   = 'conversions:cleanup-orphans';
    protected $description = 'Supprime les fichiers physiques orphelins de plus de 3 jours';

    public function handle()
    {
        $directory = storage_path('app/conversions');
        $expiration = Carbon::now()->subHours(5);
        $deleted = 0;

        foreach (glob($directory . '/*') as $file) {
            if (filemtime($file) < $expiration->timestamp) {
                unlink($file);
                $deleted++;
                Log::info("Fichier orphelin supprimé : " . basename($file));
            }
        }

        $message = "Nettoyage orphelins terminé — Supprimés: {$deleted}";
        $this->info($message);
        Log::info($message);

        return Command::SUCCESS;
    }
}
