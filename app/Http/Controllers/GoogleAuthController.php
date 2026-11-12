<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    /**
     * ✅ Rediriger vers Google OAuth avec sauvegarde de session
     */
    public function redirectToGoogle()
    {
        try {
            // 🔥 CRUCIAL : Sauvegarder pending_conversion avant redirection
            if (session()->has('pending_conversion')) {
                $pendingData = session('pending_conversion');

                Log::info('📦 Sauvegarde pending_conversion avant Google OAuth', [
                    'original_filename' => $pendingData['original_filename'] ?? 'N/A',
                    'target_version' => $pendingData['target_version'] ?? 'N/A',
                    'session_id' => session()->getId(),
                ]);

                // Stocker dans un cookie sécurisé (expire dans 1h)
                cookie()->queue('pending_conversion_data', json_encode($pendingData), 60);
            }

            return Socialite::driver('google')->redirect();

        } catch (\Exception $e) {
            Log::error('❌ Erreur redirection Google: ' . $e->getMessage());
            return redirect()->route('login')
                ->withErrors(['error' => 'Impossible de se connecter avec Google. Réessayez plus tard.']);
        }
    }

    /**
     * ✅ Gérer le callback de Google
     */
    public function handleGoogleCallback()
    {
        try {
            // 🔥 RESTAURER pending_conversion depuis le cookie
            $cookieValue = request()->cookie('pending_conversion_data');

            if ($cookieValue) {
                try {
                    $pendingData = json_decode($cookieValue, true);

                    if ($pendingData && isset($pendingData['temp_file_path'])) {
                        session(['pending_conversion' => $pendingData]);

                        Log::info('📦 Restauration pending_conversion depuis cookie', [
                            'original_filename' => $pendingData['original_filename'] ?? 'N/A',
                            'session_id' => session()->getId(),
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('❌ Erreur décodage cookie: ' . $e->getMessage());
                }
            }

            // Récupérer les informations de l'utilisateur depuis Google
            $googleUser = Socialite::driver('google')->user();

            Log::info('🔐 Tentative de connexion Google', [
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'has_pending_conversion' => session()->has('pending_conversion'),
            ]);

            // Chercher un utilisateur existant avec cet email ou google_id
            $user = User::where('email', $googleUser->getEmail())
                ->orWhere('google_id', $googleUser->getId())
                ->first();

            if ($user) {
                // ✅ Utilisateur existant

                Log::info('👤 Utilisateur Google existant trouvé', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'status' => $user->status,
                ]);

                // Vérifier le statut du compte
                if ($user->status === 0) {
                    Log::warning('⛔ Compte désactivé: ' . $user->email);
                    return redirect()->route('login')
                        ->withErrors(['error' => 'Votre compte a été désactivé. Contactez l\'administrateur.']);
                }

                if ($user->status === 2) {
                    Log::warning('⏳ Compte en attente: ' . $user->email);
                    return redirect()->route('login')
                        ->withErrors(['error' => 'Votre compte est en attente de validation.']);
                }

                // Mettre à jour les informations Google si nécessaire
                if (!$user->google_id || $user->google_id !== $googleUser->getId()) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                        'provider' => 'google',
                    ]);

                    Log::info('🔄 Informations Google mises à jour', [
                        'user_id' => $user->id,
                    ]);
                }

                // Mise à jour de l'avatar si changé
                if ($user->avatar !== $googleUser->getAvatar()) {
                    $user->update(['avatar' => $googleUser->getAvatar()]);
                }

            } else {
                // 🆕 Nouvel utilisateur Google

                Log::info('🆕 Création d\'un nouveau compte Google', [
                    'email' => $googleUser->getEmail(),
                ]);

                $coinsOnRegistration = setting('coins_on_registration', 1);
                $manualValidation = setting('manual_user_validation', false);
                $userStatus = $manualValidation ? 2 : 1;

                // Générer un username unique depuis l'email
                $username = explode('@', $googleUser->getEmail())[0];
                $baseUsername = $username;
                $counter = 1;

                while (User::where('username', $username)->exists()) {
                    $username = $baseUsername . $counter;
                    $counter++;
                }

                $user = User::create([
                    'username' => $username,
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'provider' => 'google',
                    'password' => Hash::make(Str::random(32)), // Mot de passe aléatoire
                    'status' => $userStatus,
                    'clean_password' => false,
                    'coin_balance' => $coinsOnRegistration,
                    'role' => 'user',
                ]);

                Log::info("📝 Inscription Google: {$user->email} ({$coinsOnRegistration} coins offerts, statut: {$userStatus})");

                // Créer la transaction de coins
                if ($coinsOnRegistration > 0) {
                    \App\Models\CoinTransaction::create([
                        'user_id' => $user->id,
                        'type' => 'credit',
                        'coins' => $coinsOnRegistration,
                        'balance_after' => $coinsOnRegistration,
                        'description' => "Bonus d'inscription via Google - Bienvenue ! 🎉",
                    ]);
                }

                // Envoyer l'email de bienvenue si activé
                if (setting('email_notifications_enabled', true)) {
                    try {
                        \Illuminate\Support\Facades\Mail::to($user->email)
                            ->send(new \App\Mail\WelcomeMail($user));
                        Log::info('✉️ Email de bienvenue envoyé: ' . $user->email);
                    } catch (\Exception $e) {
                        Log::error('❌ Erreur envoi email: ' . $e->getMessage());
                    }
                }

                // Si validation manuelle requise
                if ($manualValidation) {
                    Log::warning('⏳ Compte Google en attente de validation: ' . $user->email);
                    return redirect()->route('login')
                        ->with('warning', '⏳ Votre compte a été créé via Google mais doit être validé par un administrateur avant de pouvoir vous connecter.');
                }

                Log::info('✅ Nouveau compte Google créé et activé: ' . $user->email);
            }

            // ✅ CONNEXION DE L'UTILISATEUR (nouveau ou existant)
            Auth::login($user, true); // true = remember me
            request()->session()->regenerate();

            Log::info('✅ Connexion Google réussie', [
                'user_id' => $user->id,
                'email' => $user->email,
                'has_pending_conversion' => session()->has('pending_conversion'),
            ]);

            // 🎯 GESTION DES REDIRECTIONS

            // 1️⃣ Si admin → Dashboard admin
            if ($user->isAdmin()) {
                Log::info('🔑 Admin connecté via Google: ' . $user->email);
                return redirect()->route('admin.index')
                    ->with('success', 'Bienvenue Admin ' . $user->username);
            }

            // 2️⃣ Si conversion en attente → Traiter la conversion
            if (session()->has('pending_conversion')) {
                Log::info('🔄 Redirection vers conversion en attente après Google OAuth');
                return redirect()->route('conversion.processPending');
            }

            // 3️⃣ Sinon → Page d'accueil avec message de bienvenue
            $welcomeMessage = 'Bienvenue ' . $user->username . ' ! ';
            $welcomeMessage .= $user->coin_balance > 0
                ? 'Vous avez ' . $user->coin_balance . ' coin(s) disponible(s). 🪙'
                : 'Achetez des coins pour convertir vos fichiers !';

            return redirect()->route('home')
                ->with('success', $welcomeMessage);

        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            Log::error('❌ Erreur InvalidState Google: ' . $e->getMessage());

            return redirect()->route('login')
                ->withErrors(['error' => 'Session expirée. Veuillez réessayer.']);

        } catch (\Exception $e) {
            Log::error('❌ Erreur callback Google: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()->route('login')
                ->withErrors(['error' => 'Une erreur est survenue lors de la connexion avec Google: ' . $e->getMessage()]);
        }
    }
}
