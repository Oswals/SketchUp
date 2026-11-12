<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // ========== GÉNÉRAL ==========
            [
                'key' => 'coins_on_registration',
                'value' => '1',
                'type' => 'integer',
                'group' => 'general',
                'label' => 'Coins offerts à l\'inscription',
                'description' => 'Nombre de coins offerts aux nouveaux utilisateurs (actuellement: 1 coins)',
            ],
            [
                'key' => 'coins_per_conversion',
                'value' => '1',
                'type' => 'integer',
                'group' => 'general',
                'label' => 'Coût en coins par conversion',
                'description' => 'Nombre de coins nécessaires pour effectuer une conversion',
            ],
            [
                'key' => 'manual_user_validation',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'general',
                'label' => 'Validation manuelle des nouveaux utilisateurs',
                'description' => 'Activer pour valider manuellement chaque inscription',
            ],

            // ========== CONVERSION ==========
            [
                'key' => 'file_expiration_hours',
                'value' => '24',
                'type' => 'integer',
                'group' => 'conversion',
                'label' => 'Durée de validité des fichiers (heures)',
                'description' => 'Durée pendant laquelle les fichiers convertis restent disponibles',
            ],
            [
                'key' => 'max_file_size_mb',
                'value' => '50',
                'type' => 'integer',
                'group' => 'conversion',
                'label' => 'Taille maximale des fichiers (MB)',
                'description' => 'Taille maximale autorisée pour les fichiers uploadés',
            ],
            [
                'key' => 'conversion_timeout_seconds',
                'value' => '300',
                'type' => 'integer',
                'group' => 'conversion',
                'label' => 'Timeout de conversion (secondes)',
                'description' => 'Durée maximale d\'une conversion avant timeout',
            ],
            [
                'key' => 'supported_versions',
                'value' => json_encode(['2024', '2023', '2022', '2021', '2020', '2019', '2018', '2017']),
                'type' => 'json',
                'group' => 'conversion',
                'label' => 'Versions SketchUp supportées',
                'description' => 'Liste des versions SketchUp disponibles pour la conversion',
            ],

            // ========== EMAIL ==========
            [
                'key' => 'email_notifications_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'email',
                'label' => 'Activer les notifications email',
                'description' => 'Envoyer des emails aux utilisateurs',
            ],
            [
                'key' => 'admin_email',
                'value' => 'admin@sketchupconverter.com',
                'type' => 'string',
                'group' => 'email',
                'label' => 'Email administrateur',
                'description' => 'Adresse email pour recevoir les notifications admin',
            ],
            [
                'key' => 'email_from_name',
                'value' => 'SketchUp Converter',
                'type' => 'string',
                'group' => 'email',
                'label' => 'Nom de l\'expéditeur',
                'description' => 'Nom affiché dans les emails envoyés',
            ],
            [
                'key' => 'email_from_address',
                'value' => 'noreply@sketchupconverter.com',
                'type' => 'string',
                'group' => 'email',
                'label' => 'Email de l\'expéditeur',
                'description' => 'Adresse email utilisée pour envoyer les emails',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}