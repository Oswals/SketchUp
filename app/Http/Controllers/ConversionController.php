<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SketchUpService;
use App\Models\Conversion;
use App\Middleware\CheckConversionLimit;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ConversionController extends Controller
{
    protected $sketchUpService;

    public function __construct(SketchUpService $sketchUpService)
    {
        $this->sketchUpService = $sketchUpService;
    }

    /**
     * 🆕 AMÉLIORÉ - Gérer les versions "versionless" avec logique intelligente
     */
    private function handleModernVersion($requestedVersion)
    {
        // Versions 2024-2026 : détectables individuellement
        if (in_array($requestedVersion, ['2024', '2025', '2026'])) {
            return [
                'actual_version' => $requestedVersion,
                'display_version' => $requestedVersion,
                'is_modern' => true,
                'message' => "✅ Conversion to SketchUp {$requestedVersion} (modern detectable format)."
            ];
        }
        
        // Versions 2021-2023 : versionless (même format)
        if (in_array($requestedVersion, ['2022', '2023'])) {
            return [
                'actual_version' => '2021',
                'display_version' => $requestedVersion,
                'is_modern' => true,
                'message' => "ℹ️ Versions 2021-2023 share the same format. Your file will be compatible with SketchUp {$requestedVersion}."
            ];
        }
        
        // Versions pré-2021 ou 2021 exact
        return [
            'actual_version' => $requestedVersion,
            'display_version' => $requestedVersion,
            'is_modern' => false,
            'message' => null
        ];
    }

    /**
     * Afficher la page d'accueil
     */
    public function index()
    {
        return view('frontend.home');
    }

    /**
     * Afficher la page de contact
     */
    public function contact()
    {
        return view('frontend.section.contact');
    }

    /**
     * Traiter la conversion
     */
    public function convert(Request $request)
    {
        // 🔒 VÉRIFICATION AUTHENTIFICATION
        if (!auth()->check()) {
            Log::warning('🔒 Tentative de conversion sans authentification');
            
            // Validation
            try {
                $request->validate([
                    'sketchup_file' => [
                        'required',
                        'file',
                        'max:102400',
                        function ($attribute, $value, $fail) {
                            $extension = $value->getClientOriginalExtension();
                            if (strtolower($extension) !== 'skp') {
                                $fail('The file must be in .skp format');
                            }
                        },
                    ],
                    'target_version' => 'required|in:2017,2018,2019,2020,2021,2022,2023,2024,2025,2026'
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                Log::error('❌ Validation failed: ' . json_encode($e->errors()));
                
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation error',
                        'errors' => $e->errors()
                    ], 422);
                }
                return back()->withErrors($e->errors());
            }
            
            try {
                $file = $request->file('sketchup_file');
                
                if (!$file) {
                    throw new \Exception('No file received');
                }
                
                Log::info('📁 Fichier reçu: ' . $file->getClientOriginalName());
                
                // ✅ Utiliser le disque temp_uploads
                $tempFileName = 'temp_' . Str::uuid() . '.skp';
                
                // Sauvegarder via Storage
                $storedPath = Storage::disk('temp_uploads')->putFileAs(
                    '',
                    $file,
                    $tempFileName
                );
                
                if (!$storedPath) {
                    throw new \Exception('File save failed');
                }
                
                // Obtenir le chemin complet
                $fullTempPath = Storage::disk('temp_uploads')->path($tempFileName);
                
                Log::info('✅ Fichier sauvegardé: ' . $fullTempPath);
                Log::info('📊 Taille: ' . round($file->getSize() / 1024 / 1024, 2) . ' MB');
                
                // Vérifier que le fichier existe
                if (!Storage::disk('temp_uploads')->exists($tempFileName)) {
                    throw new \Exception('The file was not saved correctly');
                }
                
                // Sauvegarder en session
                session([
                    'pending_conversion' => [
                        'temp_file_path' => $fullTempPath,
                        'original_filename' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'target_version' => $request->target_version,
                        'timestamp' => now()->toDateTimeString()
                    ]
                ]);
                
                Log::info('💾 Informations sauvegardées en session');
                
                // Retourner JSON pour AJAX
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'requires_auth' => true,
                        'message' => 'File saved. Login required to continue.',
                        'redirect_url' => route('login')
                    ], 403);
                }
                
                return redirect()->route('login')
                    ->with('info', 'Please log in to convert your files.');
                    
            } catch (\Exception $e) {
                Log::error('❌ Erreur: ' . $e->getMessage());
                Log::error('Stack: ' . $e->getTraceAsString());
                
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error: ' . $e->getMessage()
                    ], 500);
                }
                
                return back()->withErrors(['error' => $e->getMessage()]);
            }
        }

        // ✅ UTILISATEUR AUTHENTIFIÉ - Continuer avec la conversion normale
        return $this->processConversion($request);
    }

    /**
     * Traiter une conversion en attente après login
     */
    public function processPendingConversion()
    {
        if (!session()->has('pending_conversion')) {
            return redirect()->route('home');
        }

        $user = auth()->user();
        $pendingData = session('pending_conversion');
        
        // 🪙 VÉRIFIER SI L'UTILISATEUR PEUT CONVERTIR
        if (!$user->canConvert()) {
            Log::warning('⚠️ Tentative de conversion sans crédit', [
                'user_id' => $user->id,
                'coin_balance' => $user->coin_balance,
            ]);

            // Nettoyer le fichier temporaire
            if (file_exists($pendingData['temp_file_path'])) {
                unlink($pendingData['temp_file_path']);
            }
            session()->forget('pending_conversion');

            return redirect()->route('home')
                ->with('error', 'You don\'t have any files left. <a href="#pricing" class="alert-link">Buy more conversions</a> to continue!');
        }
        
        // Vérifier que le fichier temporaire existe encore
        if (!file_exists($pendingData['temp_file_path'])) {
            session()->forget('pending_conversion');
            return redirect()->route('home')
                ->withErrors(['error' => 'The temporary file has expired. Please try again.']);
        }

        try {
            $conversionsDir = storage_path('app/conversions');

            // 🆕 AMÉLIORÉ - Détection intelligente de la version source
            $sourceVersion = $this->sketchUpService->detectVersion($pendingData['temp_file_path']);
            
            Log::info('🔍 Version source détectée', [
                'version' => $sourceVersion,
                'file' => basename($pendingData['original_filename']),
                'version_type' => $this->getVersionDescription($sourceVersion)
            ]);

            // 🆕 Gérer les versions modernes avec logique améliorée
            $versionInfo = $this->handleModernVersion($pendingData['target_version']);
            $actualTargetVersion = $versionInfo['actual_version'];
            $displayVersion = $versionInfo['display_version'];

            // Vérifier la compatibilité
            $compatibility = $this->sketchUpService->canConvert($sourceVersion, $actualTargetVersion);
            
            if (!$compatibility['can_convert']) {
                unlink($pendingData['temp_file_path']);
                session()->forget('pending_conversion');
                return redirect()->route('home')
                    ->withErrors(['error' => $compatibility['message']]);
            }

            // 🆕 Logger le message de compatibilité
            if (!empty($compatibility['message'])) {
                Log::info('📋 Compatibilité: ' . $compatibility['message']);
            }

            // Créer l'enregistrement
            $conversion = Conversion::create([
                'user_id' => auth()->id(),
                'session_id' => session()->getId(),
                'ip_address' => request()->ip(),
                'original_filename' => $pendingData['original_filename'],
                'original_size' => $pendingData['file_size'],
                'source_version' => $sourceVersion,
                'target_version' => $displayVersion,
                'status' => 'processing',
                'expires_at' => now()->addDays(7)
            ]);

            // Préparer la sortie
            $outputFileName = pathinfo($pendingData['original_filename'], PATHINFO_FILENAME) . '_v' . $displayVersion . '.skp';
            $outputUniqueFileName = 'conversion_' . $conversion->id . '_' . Str::uuid() . '.skp';
            $outputPath = $conversionsDir . DIRECTORY_SEPARATOR . $outputUniqueFileName;

            Log::info('🔄 Traitement de la conversion en attente #' . $conversion->id, [
                'source' => $sourceVersion,
                'target_display' => $displayVersion,
                'target_actual' => $actualTargetVersion,
                'is_modern' => $versionInfo['is_modern']
            ]);

            // Conversion
            $conversionStartTime = microtime(true);
            $success = $this->sketchUpService->convertFile(
                $pendingData['temp_file_path'],
                $actualTargetVersion,
                $outputPath
            );
            $conversionDuration = round(microtime(true) - $conversionStartTime, 2);

            // Nettoyer le fichier temporaire
            if (file_exists($pendingData['temp_file_path'])) {
                unlink($pendingData['temp_file_path']);
            }

            if (!$success || !file_exists($outputPath) || filesize($outputPath) === 0) {
                $conversion->update(['status' => 'failed']);
                throw new \Exception('Conversion failed');
            }

            $outputSize = filesize($outputPath);

            // Mettre à jour
            $conversion->update([
                'converted_filename' => $outputFileName,
                'converted_file_path' => $outputPath,
                'converted_size' => $outputSize,
                'conversion_duration' => $conversionDuration,
                'status' => 'completed'
            ]);

            // 🆕 Stocker le message d'information
            if ($versionInfo['message']) {
                session(['modern_version_info' => $versionInfo['message']]);
            }

            // 🪙 DÉDUIRE 1 COIN
            $user->useConversion($conversion->id);
            Log::info('✅ Coin utilisé (pending) - Balance: ' . $user->coin_balance, [
                'user_id' => $user->id,
                'conversion_id' => $conversion->id,
            ]);

            Log::info('✅ Conversion en attente #' . $conversion->id . ' complétée!');

            // Nettoyer la session
            session()->forget('pending_conversion');
            
            // 🆕 TÉLÉCHARGEMENT AUTOMATIQUE - Sauvegarder l'ID en session
            session([
                'conversion_completed' => true,
                'auto_download_conversion_id' => $conversion->id
            ]);

            // Message de succès personnalisé (FILES)
            $successMessage = $this->getConversionSuccessMessage($user);

            return redirect()->route('conversion.result')
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            Log::error('❌ Erreur conversion en attente: ' . $e->getMessage());
            
            if (isset($pendingData['temp_file_path']) && file_exists($pendingData['temp_file_path'])) {
                unlink($pendingData['temp_file_path']);
            }
            
            session()->forget('pending_conversion');
            
            return redirect()->route('home')
                ->withErrors(['error' => 'Conversion error: ' . $e->getMessage()]);
        }
    }

    /**
     * Traiter la conversion (utilisateur authentifié)
     */
    protected function processConversion(Request $request)
    {
        // Validation complète
        $request->validate([
            'sketchup_file' => [
                'required',
                'file',
                'max:102400',
                function ($attribute, $value, $fail) {
                    $extension = $value->getClientOriginalExtension();
                    if (strtolower($extension) !== 'skp') {
                        $fail('The file must be in .skp format');
                    }
                },
            ],
            'target_version' => 'required|in:2017,2018,2019,2020,2021,2022,2023,2024,2025,2026'
        ], [
            'sketchup_file.required' => 'Please select a SketchUp file',
            'sketchup_file.max' => 'The file must not exceed 100 MB',
            'target_version.required' => 'Please select a target version'
        ]);

        $user = auth()->user();

        // 🪙 VÉRIFICATION DES CRÉDITS (Backend = coins)
        if (!$user->canConvert()) {
            Log::warning('⚠️ Tentative de conversion sans crédit', [
                'user_id' => $user->id,
                'coin_balance' => $user->coin_balance,
            ]);

            return redirect()->route('home')
                ->with('error', 'You don\'t have any files left. <a href="#pricing" class="alert-link">Buy more conversions</a> to continue!');
        }

        // Vérifier que le convertisseur est disponible
        if (!$this->sketchUpService->isConverterAvailable()) {
            Log::error('❌ Convertisseur non disponible');
            return back()->withErrors(['error' => 'The conversion service is temporarily unavailable.']);
        }

        $fullPath = null;
        $outputPath = null;
        $conversion = null;

        try {
            // Récupérer le fichier
            $file = $request->file('sketchup_file');
            $originalName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            
            Log::info('📁 Fichier uploadé: ' . $originalName . ' (' . round($fileSize / 1024 / 1024, 2) . ' MB)', [
                'user_id' => $user->id,
            ]);
            
            // Sauvegarder temporairement avec Storage
            $tempFileName = Str::uuid() . '.skp';
            Storage::disk('temp')->putFileAs('', $file, $tempFileName);
            $fullPath = Storage::disk('temp')->path($tempFileName);
            
            if (!file_exists($fullPath)) {
                throw new \Exception('File not saved after upload');
            }

            Log::info('✅ Fichier sauvegardé: ' . $fullPath);

            // 🆕 AMÉLIORÉ - Détection intelligente
            $sourceVersion = $this->sketchUpService->detectVersion($fullPath);
            
            Log::info('🔍 Version source détectée', [
                'version' => $sourceVersion,
                'file' => $originalName,
                'version_type' => $this->getVersionDescription($sourceVersion)
            ]);

            // 🆕 Gérer les versions modernes
            $versionInfo = $this->handleModernVersion($request->target_version);
            $actualTargetVersion = $versionInfo['actual_version'];
            $displayVersion = $versionInfo['display_version'];

            // Vérifier la compatibilité
            $compatibility = $this->sketchUpService->canConvert($sourceVersion, $actualTargetVersion);
            
            if (!$compatibility['can_convert']) {
                if (file_exists($fullPath)) unlink($fullPath);
                return back()->withErrors(['error' => $compatibility['message']]);
            }

            // 🆕 Logger le message de compatibilité
            if (!empty($compatibility['message'])) {
                Log::info('📋 Compatibilité: ' . $compatibility['message']);
            }

            // Créer l'enregistrement de conversion
            $conversion = Conversion::create([
                'user_id' => auth()->id(),
                'session_id' => session()->getId(),
                'ip_address' => $request->ip(),
                'original_filename' => $originalName,
                'original_size' => $fileSize,
                'source_version' => $sourceVersion,
                'target_version' => $displayVersion,
                'status' => 'processing',
                'expires_at' => now()->addDays(7)
            ]);

            Log::info('📝 Conversion créée: #' . $conversion->id, [
                'source' => $sourceVersion,
                'target_display' => $displayVersion,
                'target_actual' => $actualTargetVersion,
                'is_modern' => $versionInfo['is_modern']
            ]);

            // Préparer le fichier de sortie
            $outputFileName = pathinfo($originalName, PATHINFO_FILENAME) . '_v' . $displayVersion . '.skp';
            $outputUniqueFileName = 'conversion_' . $conversion->id . '_' . Str::uuid() . '.skp';
            
            // Utiliser Storage pour le fichier de sortie
            $outputPath = Storage::disk('conversions')->path($outputUniqueFileName);

            Log::info('🔄 Début de la conversion #' . $conversion->id);

            // Conversion
            $conversionStartTime = microtime(true);
            $success = $this->sketchUpService->convertFile(
                $fullPath, 
                $actualTargetVersion,
                $outputPath
            );
            $conversionDuration = round(microtime(true) - $conversionStartTime, 2);

            // Nettoyer le fichier temporaire
            if (file_exists($fullPath)) {
                unlink($fullPath);
                Log::info('🗑️ Fichier temporaire supprimé');
            }

            if (!$success) {
                $conversion->update([
                    'status' => 'failed',
                    'error_message' => 'Conversion failed'
                ]);
                
                if (file_exists($outputPath)) unlink($outputPath);
                throw new \Exception('Conversion failed.');
            }

            // Vérifier le fichier de sortie
            if (!file_exists($outputPath) || filesize($outputPath) === 0) {
                $conversion->update([
                    'status' => 'failed',
                    'error_message' => 'Invalid output file'
                ]);
                throw new \Exception('The output file is invalid');
            }

            $outputSize = filesize($outputPath);
            Log::info('✅ Conversion réussie! Taille: ' . round($outputSize / 1024 / 1024, 2) . ' MB');

            // Mettre à jour la conversion
            $conversion->update([
                'converted_filename' => $outputFileName,
                'converted_file_path' => $outputPath,
                'converted_size' => $outputSize,
                'conversion_duration' => $conversionDuration,
                'status' => 'completed'
            ]);

            // 🆕 Stocker le message d'information
            if ($versionInfo['message']) {
                session(['modern_version_info' => $versionInfo['message']]);
            }

            Log::info('✅ Conversion #' . $conversion->id . ' complétée! (' . $conversionDuration . 's)');

            // 🪙 DÉDUIRE 1 COIN (Backend reste en coins)
            $user->useConversion($conversion->id);
            Log::info('✅ Coin utilisé pour conversion - Balance: ' . $user->coin_balance, [
                'user_id' => $user->id,
                'conversion_id' => $conversion->id,
            ]);

            // 🆕 TÉLÉCHARGEMENT AUTOMATIQUE - Sauvegarder l'ID en session
            session([
                'conversion_completed' => true,
                'auto_download_conversion_id' => $conversion->id
            ]);

            // Message de succès personnalisé (FILES pour l'utilisateur)
            $successMessage = $this->getConversionSuccessMessage($user);

            return redirect()->route('conversion.result')
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            Log::error('❌ Exception: ' . $e->getMessage());
            Log::error('📍 Trace: ' . $e->getTraceAsString());
            
            if ($conversion) {
                $conversion->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage()
                ]);
            }
            
            // Nettoyage
            foreach ([$fullPath, $outputPath] as $path) {
                if (isset($path) && file_exists($path)) {
                    unlink($path);
                }
            }
            
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * 🆕 Helper pour décrire une version détectée
     */
    private function getVersionDescription($version): string
    {
        if ($version === 'Inconnue') {
            return 'Version non identifiable';
        }
        
        if (strpos($version, '-') !== false) {
            return 'Format versionless (range)';
        }
        
        $year = (int) $version;
        
        if ($year <= 2020) {
            return 'Format legacy (pré-2021)';
        }
        
        if ($year >= 2021 && $year <= 2023) {
            return 'Format versionless moderne';
        }
        
        if ($year >= 2024) {
            return 'Format moderne distinct';
        }
        
        return 'Format inconnu';
    }

    /**
     * 📄 Obtenir le message de succès (affichage FILES)
     */
    private function getConversionSuccessMessage($user): string
    {
        $remaining = $user->coin_balance; // Backend = coins
        
        if ($remaining > 0) {
            // Frontend = files
            $filesText = $remaining === 1 ? 'file' : 'files';
            return "✅ Conversion successful! You have {$remaining} {$filesText} remaining. 📄";
        } else {
            return '✅ Conversion successful! You have used all your files. Buy more to continue!';
        }
    }

    /**
     * Afficher le résultat
     */
    public function result()
    {
        $user = auth()->user();

        $conversions = Conversion::query()
            ->forUser(auth()->id())
            ->completed()
            ->notExpired()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('frontend.result', [
            'conversions' => $conversions,
            'coin_balance' => $user->coin_balance, // Backend = coins, la vue l'affichera en "files"
            'can_convert' => $user->canConvert(),
        ]);
    }

    /**
     * Télécharger un fichier
     */
    public function download($id)
    {
        $conversion = Conversion::findOrFail($id);

        if ($conversion->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        if (!$conversion->fileExists()) {
            return redirect()->route('conversion.result')
                ->withErrors(['error' => 'The file is no longer available.']);
        }

        if ($conversion->hasExpired()) {
            return redirect()->route('conversion.result')
                ->withErrors(['error' => 'The file has expired.']);
        }

        Log::info('📥 Téléchargement: conversion #' . $conversion->id);

        $conversion->update([
            'downloaded' => true,
            'downloaded_at' => now()
        ]);

        // 🆕 NETTOYER LA SESSION DU TÉLÉCHARGEMENT AUTO
        session()->forget('auto_download_conversion_id');

        return response()->download(
            $conversion->converted_file_path,
            $conversion->converted_filename
        );
    }

    /**
     * Supprimer une conversion
     */
    public function delete($id)
    {
        $conversion = Conversion::findOrFail($id);

        if ($conversion->user_id !== auth()->id()) {
            abort(403);
        }

        $conversion->deleteFile();
        $conversion->delete();

        Log::info('🗑️ Conversion #' . $id . ' supprimée');

        return redirect()->route('conversion.result')
            ->with('success', 'File deleted');
    }

    /**
     * Annuler toutes les conversions
     */
    public function cancel()
    {
        $conversions = Conversion::forUser(auth()->id())->notExpired()->get();

        foreach ($conversions as $conversion) {
            $conversion->deleteFile();
            $conversion->delete();
        }

        return redirect()->route('home')
            ->with('info', 'Conversions cancelled');
    }
}