<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class SketchUpService
{
    protected $detectorPath;
    protected $converterPath;

    protected $supportedVersions = [
        '2013', '2014', '2015', '2016', '2017', '2018', 
        '2019', '2020', '2021', '2022', '2023', '2024', 
        '2025', '2026'
    ];

    // 🆕 Groupes de versions par format
    protected $versionGroups = [
        'pre_modern' => ['2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
        'versionless' => ['2021', '2022', '2023'], // Format identique
        'modern_distinct' => ['2024', '2025', '2026'], // Formats légèrement différents
    ];

    public function __construct()
    {
        $binPath = storage_path('app/bin/release');
        $this->detectorPath = $binPath . '/detector.exe';
        $this->converterPath = $binPath . '/converter.exe';
        
        Log::info('📂 Chemins des exécutables:', [
            'detector' => $this->detectorPath,
            'converter' => $this->converterPath,
            'detector_exists' => file_exists($this->detectorPath),
            'converter_exists' => file_exists($this->converterPath)
        ]);
    }

    /**
     * 🆕 Détecter la version avec analyse approfondie
     */
    public function detectVersion($filePath)
    {
        try {
            if (!file_exists($filePath)) {
                Log::error('❌ Fichier introuvable: ' . $filePath);
                return 'Inconnue';
            }

            if (!file_exists($this->detectorPath)) {
                Log::error('❌ detector.exe introuvable: ' . $this->detectorPath);
                return 'Inconnue';
            }

            $command = sprintf('"%s" "%s" 2>&1', $this->detectorPath, $filePath);
            Log::info('🔍 Détection de version: ' . basename($filePath));

            $output = [];
            $returnCode = 0;
            exec($command, $output, $returnCode);

            // Extraire le JSON
            $jsonLine = '';
            foreach ($output as $line) {
                $trimmed = trim($line);
                if (strpos($trimmed, '{') === 0) {
                    $jsonLine = $trimmed;
                    break;
                }
            }

            $result = json_decode($jsonLine, true);

            if ($result && isset($result['success']) && $result['success']) {
                $estimatedVersion = $result['estimated_version'] ?? null;
                
                // 🆕 Analyser les méthodes de détection disponibles
                $detectionMethods = $result['detection_methods'] ?? [];
                $confidenceNote = $result['confidence_note'] ?? '';

                // 🎯 LOGIQUE AMÉLIORÉE POUR POST-2021
                if ($estimatedVersion >= 2021) {
                    $versionInfo = $this->analyzeModernVersionDetection($result);
                    
                    Log::info('✅ Version moderne détectée', $versionInfo);
                    
                    // Si détection incertaine (2021-2023), retourner un range
                    if ($versionInfo['is_uncertain']) {
                        return $versionInfo['display_version']; // Ex: "2021-2023"
                    }
                    
                    return (string) $estimatedVersion;
                }

                // Pre-2021: fiable
                Log::info('✅ Version pré-moderne détectée: ' . $estimatedVersion);
                return (string) $estimatedVersion;
            }

            Log::warning('⚠️ Échec de détection', [
                'return_code' => $returnCode,
                'output' => implode("\n", $output)
            ]);

            return 'Inconnue';

        } catch (\Exception $e) {
            Log::error('❌ Exception détection version: ' . $e->getMessage());
            return 'Inconnue';
        }
    }

    /**
     * 🆕 Analyser la détection pour versions modernes (2021+)
     */
    private function analyzeModernVersionDetection($detectionResult)
    {
        $estimated = $detectionResult['estimated_version'] ?? 2021;
        $methods = $detectionResult['detection_methods'] ?? [];
        
        $stringPattern = $methods['string_patterns'] ?? -1;
        $xmlMetadata = $methods['xml_metadata'] ?? -1;
        $apiVersion = $methods['api_version'] ?? -1;

        // Compteur de sources concordantes
        $versions = array_filter([$stringPattern, $xmlMetadata, $apiVersion], fn($v) => $v > 0);
        $uniqueVersions = array_unique($versions);

        $info = [
            'estimated_version' => $estimated,
            'is_uncertain' => false,
            'display_version' => (string) $estimated,
            'confidence' => 'high',
            'methods_used' => [],
        ];

        // 🎯 CAS 1: Détection claire (2024-2026)
        if ($estimated >= 2024 && $stringPattern === $estimated) {
            $info['confidence'] = 'high';
            $info['methods_used'][] = 'string_patterns (reliable)';
            Log::info("🎯 Détection haute confiance: {$estimated} (string patterns)");
            return $info;
        }

        // 🎯 CAS 2: Format versionless (2021-2023)
        if ($estimated >= 2021 && $estimated <= 2023) {
            
            // Si string pattern détecte une version spécifique
            if ($stringPattern >= 2021 && $stringPattern <= 2023) {
                $info['estimated_version'] = $stringPattern;
                $info['display_version'] = (string) $stringPattern;
                $info['confidence'] = 'medium';
                $info['methods_used'][] = 'string_patterns';
                Log::info("⚠️ Détection moyenne confiance: {$stringPattern} (versionless format)");
                return $info;
            }

            // Si XML détecte une version
            if ($xmlMetadata >= 2021 && $xmlMetadata <= 2023) {
                $info['estimated_version'] = $xmlMetadata;
                $info['display_version'] = (string) $xmlMetadata;
                $info['confidence'] = 'medium';
                $info['methods_used'][] = 'xml_metadata';
                Log::info("⚠️ Détection moyenne confiance: {$xmlMetadata} (XML metadata)");
                return $info;
            }

            // Aucune source fiable → retourner un range
            $info['is_uncertain'] = true;
            $info['display_version'] = '2021-2023';
            $info['confidence'] = 'low';
            $info['methods_used'][] = 'fallback_range';
            Log::warning("⚠️ Détection incertaine: format versionless 2021-2023");
            return $info;
        }

        // Cas par défaut
        return $info;
    }

    /**
     * 🆕 Vérifier la compatibilité avec gestion du versionless
     */
    public function canConvert($sourceVersion, $targetVersion)
    {
        // Vérifier que la version cible est supportée
        if (!in_array($targetVersion, $this->supportedVersions)) {
            return [
                'can_convert' => false,
                'message' => "Version {$targetVersion} non supportée. Versions disponibles : " . implode(', ', $this->supportedVersions)
            ];
        }

        // 🆕 Gérer les détections incertaines (range)
        if (strpos($sourceVersion, '-') !== false) {
            // Source est un range (ex: "2021-2023")
            list($minVer, $maxVer) = explode('-', $sourceVersion);
            $targetYear = (int) $targetVersion;
            
            if ($targetYear >= (int)$minVer && $targetYear <= (int)$maxVer) {
                return [
                    'can_convert' => true,
                    'message' => "Fichier au format versionless ({$sourceVersion}). Compatible avec {$targetVersion}."
                ];
            }
            
            return [
                'can_convert' => true,
                'message' => "Conversion depuis format versionless ({$sourceVersion}) vers {$targetVersion}."
            ];
        }

        // Version source inconnue
        if ($sourceVersion === 'Inconnue') {
            return [
                'can_convert' => true,
                'message' => 'Version source inconnue. La conversion sera tentée.'
            ];
        }

        $sourceYear = (int) $sourceVersion;
        $targetYear = (int) $targetVersion;

        // Même version
        if ($sourceYear === $targetYear) {
            return [
                'can_convert' => true,
                'message' => 'Même version, aucune conversion nécessaire.'
            ];
        }

        // 🆕 Cas spécial: Groupe versionless (2021-2023)
        $isSourceVersionless = in_array($sourceVersion, $this->versionGroups['versionless']);
        $isTargetVersionless = in_array($targetVersion, $this->versionGroups['versionless']);

        if ($isSourceVersionless && $isTargetVersionless) {
            return [
                'can_convert' => true,
                'message' => "Format versionless identique ({$sourceVersion} ≈ {$targetVersion}). Aucune conversion réelle nécessaire."
            ];
        }

        // Conversion standard
        $diff = abs($sourceYear - $targetYear);
        $type = $sourceYear > $targetYear ? 'descendante' : 'ascendante';

        if ($diff > 5) {
            $warning = $type === 'descendante' 
                ? 'Certaines fonctionnalités peuvent être perdues.' 
                : 'Le fichier sera mis à jour au nouveau format.';
                
            return [
                'can_convert' => true,
                'message' => "Conversion {$type} avec grande différence ({$sourceYear} → {$targetYear}). {$warning}"
            ];
        }

        return [
            'can_convert' => true,
            'message' => "Conversion {$type} possible ({$sourceYear} → {$targetYear})"
        ];
    }

    /**
     * Convertir un fichier SketchUp
     */
    public function convertFile($sourcePath, $targetVersion, $outputPath)
    {
        try {
            if (!file_exists($this->converterPath)) {
                Log::error('❌ converter.exe introuvable: ' . $this->converterPath);
                return false;
            }

            if (!file_exists($sourcePath)) {
                Log::error('❌ Fichier source introuvable: ' . $sourcePath);
                return false;
            }

            if (!in_array($targetVersion, $this->supportedVersions)) {
                Log::error('❌ Version non supportée: ' . $targetVersion);
                return false;
            }

            $outputDir = dirname($outputPath);
            if (!file_exists($outputDir)) {
                mkdir($outputDir, 0755, true);
            }

            $command = sprintf(
                '"%s" "%s" "%s" %s 2>&1',
                $this->converterPath,
                $sourcePath,
                $outputPath,
                $targetVersion
            );

            Log::info('=== DÉBUT DE LA CONVERSION ===');
            Log::info('Commande: ' . $command);
            Log::info('Source: ' . $sourcePath . ' (' . round(filesize($sourcePath) / 1024 / 1024, 2) . ' MB)');
            Log::info('Destination: ' . $outputPath);
            Log::info('Version cible: ' . $targetVersion);

            $output = [];
            $returnCode = 0;
            
            $startTime = microtime(true);
            exec($command, $output, $returnCode);
            $executionTime = round(microtime(true) - $startTime, 2);

            $outputString = implode("\n", $output);
            Log::info('Sortie du convertisseur: ' . $outputString);
            Log::info('Code de retour: ' . $returnCode);
            Log::info('Temps d\'exécution: ' . $executionTime . 's');

            // Extraire JSON
            $jsonLine = '';
            foreach ($output as $line) {
                $trimmed = trim($line);
                if (strpos($trimmed, '{') === 0) {
                    $jsonLine = $trimmed;
                    break;
                }
            }

            $result = json_decode($jsonLine, true);

            if ($returnCode === 0 && $result && isset($result['success']) && $result['success']) {
                if (file_exists($outputPath) && filesize($outputPath) > 0) {
                    Log::info('✅ Conversion réussie!', [
                        'size' => round(filesize($outputPath) / 1024 / 1024, 2) . ' MB',
                        'source_version' => $result['source_version'] ?? 'N/A',
                        'target_version' => $result['target_version'] ?? 'N/A',
                        'conversion_type' => $result['conversion_type'] ?? 'N/A'
                    ]);
                    Log::info('=== FIN DE LA CONVERSION (SUCCÈS) ===');
                    return true;
                }
            }

            Log::error('❌ Échec de la conversion', [
                'return_code' => $returnCode,
                'error' => $result['error'] ?? 'Erreur inconnue',
                'output' => $outputString
            ]);
            Log::info('=== FIN DE LA CONVERSION (ÉCHEC) ===');
            return false;

        } catch (\Exception $e) {
            Log::error('❌ Exception lors de la conversion: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtenir des informations sur un fichier
     */
    public function getFileInfo($filePath)
    {
        if (!file_exists($filePath)) {
            return null;
        }

        $version = $this->detectVersion($filePath);

        return [
            'size' => filesize($filePath),
            'size_mb' => round(filesize($filePath) / 1024 / 1024, 2),
            'version' => $version,
            'version_type' => $this->getVersionType($version),
            'modified' => filemtime($filePath),
            'modified_readable' => date('Y-m-d H:i:s', filemtime($filePath)),
            'extension' => pathinfo($filePath, PATHINFO_EXTENSION),
            'filename' => pathinfo($filePath, PATHINFO_FILENAME),
        ];
    }

    /**
     * 🆕 Identifier le type de version
     */
    private function getVersionType($version)
    {
        if ($version === 'Inconnue') return 'unknown';
        if (strpos($version, '-') !== false) return 'range';
        
        $year = (int) $version;
        
        if ($year <= 2020) return 'legacy';
        if ($year >= 2021 && $year <= 2023) return 'versionless';
        if ($year >= 2024) return 'modern';
        
        return 'unknown';
    }

    /**
     * Vérifier la configuration du convertisseur
     */
    public function checkConfiguration()
    {
        $checks = [
            'detector_exists' => file_exists($this->detectorPath),
            'detector_path' => $this->detectorPath,
            'converter_exists' => file_exists($this->converterPath),
            'converter_path' => $this->converterPath,
            'temp_writable' => is_writable(storage_path('app/temp')),
            'conversions_writable' => is_writable(storage_path('app/conversions')),
        ];

        Log::info('🔍 Configuration du service:', $checks);
        
        return $checks;
    }

    /**
     * Obtenir les versions supportées
     */
    public function getSupportedVersions()
    {
        return $this->supportedVersions;
    }

    /**
     * Vérifier si les convertisseurs sont disponibles
     */
    public function isConverterAvailable()
    {
        return file_exists($this->converterPath) && file_exists($this->detectorPath);
    }
}