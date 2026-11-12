<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function checkUser(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['exists' => false]);
        }

        return response()->json([
            'exists' => true,
            'status' => $user->status,
            'cleanPassword' => $user->clean_password,
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ], [
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->only('email'));
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Aucun compte trouvé avec cet email.'])->withInput($request->only('email'));
        }

        if ($user->status === 0) {
            return back()->withErrors(['email' => 'Votre compte a été désactivé.'])->withInput($request->only('email'));
        }

        if ($user->status === 2) {
            return back()->withErrors(['email' => 'Votre compte est en attente de validation.'])->withInput($request->only('email'));
        }

        if ($user->clean_password) {
            $validator = Validator::make($request->all(), [
                'password_confirmation' => 'required|same:password',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput($request->only('email'));
            }

            $user->password = Hash::make($request->password);
            $user->clean_password = false;
            $user->save();

            Auth::login($user, $request->filled('remember'));
            $request->session()->regenerate();

            if ($user->isAdmin()) {
                Log::info('🔑 Admin connecté: ' . $user->email);
                return redirect()->route('admin.index')
                    ->with('success', 'Bienvenue Admin ' . $user->username);
            }

            if (session()->has('pending_conversion')) {
                return redirect()->route('conversion.processPending');
            }

            return redirect()->intended('dashboard')
                ->with('success', 'Bienvenue ' . $user->username . ' ! Vous avez ' . $user->coin_balance . ' coins. 🪙');
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Mot de passe incorrect.'])->withInput($request->only('email'));
        }

        Auth::login($user, $request->filled('remember'));
        $request->session()->regenerate();

        Log::info('✅ Connexion: ' . $user->email);

        if ($user->isAdmin()) {
            Log::info('🔑 Admin connecté: ' . $user->email);
            return redirect()->route('admin.index')
                ->with('success', 'Bienvenue Admin ' . $user->username);
        }

        if (session()->has('pending_conversion')) {
            return redirect()->route('conversion.processPending');
        }

        $welcomeMessage = 'Bienvenue ' . $user->username . ' ! ';
        $welcomeMessage .= $user->coin_balance > 0 
            ? 'Vous avez ' . $user->coin_balance . ' coin(s) disponible(s). 🪙'
            : 'Achetez des coins pour convertir vos fichiers !';

        return redirect()->route('home')->with('success', $welcomeMessage);
    }

    // ==================== FORGOT PASSWORD ====================
    
    public function showForgotPasswordForm()
    {
        return view('auth.forgotPassword');
    }

    public function sendResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'email.exists' => 'Aucun compte trouvé avec cet email.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::where('email', $request->email)->first();
        
        if ($user->status === 0) {
            return back()->withErrors(['email' => 'Votre compte a été désactivé.'])->withInput();
        }

        if ($user->status === 2) {
            return back()->withErrors(['email' => 'Votre compte est en attente de validation.'])->withInput();
        }

        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                Log::info('📧 Lien de réinitialisation envoyé à: ' . $request->email);
                return back()->with('success', '✉️ Un lien de réinitialisation a été envoyé à votre adresse email.');
            }

            return back()->withErrors(['email' => 'Impossible d\'envoyer le lien de réinitialisation.']);

        } catch (\Exception $e) {
            Log::error('❌ Erreur envoi reset link: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Une erreur est survenue. Veuillez réessayer.']);
        }
    }

    public function showResetPasswordForm(Request $request, $token)
    {
        return view('auth.resetPassword', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'email.exists' => 'Aucun compte trouvé avec cet email.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->only('email'));
        }

        try {
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (User $user, string $password) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                        'clean_password' => false,
                    ])->setRememberToken(Str::random(60));

                    $user->save();

                    event(new PasswordReset($user));

                    Log::info('🔒 Mot de passe réinitialisé: ' . $user->email);
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return redirect()->route('login')
                    ->with('success', '✅ Votre mot de passe a été réinitialisé avec succès ! Vous pouvez maintenant vous connecter.');
            }

            return back()->withErrors(['email' => 'Le lien de réinitialisation est invalide ou a expiré.']);

        } catch (\Exception $e) {
            Log::error('❌ Erreur reset password: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Une erreur est survenue. Veuillez réessayer.']);
        }
    }

    // ==================== FIN FORGOT PASSWORD ====================

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'username.required' => 'L\'username est obligatoire.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->except('password', 'password_confirmation'));
        }

        try {
            $coinsOnRegistration = setting('coins_on_registration', 1);
            $manualValidation = setting('manual_user_validation', false);
            
            $userStatus = $manualValidation ? 2 : 1;

            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => $userStatus,
                'clean_password' => false,
                'coin_balance' => $coinsOnRegistration,
                'role' => 'user',
            ]);

            Log::info("📝 Inscription: {$user->email} ({$coinsOnRegistration} coins offerts, statut: {$userStatus})");

            // Créer la transaction de coins
            if ($coinsOnRegistration > 0) {
                \App\Models\CoinTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'credit',
                    'coins' => $coinsOnRegistration,
                    'balance_after' => $coinsOnRegistration,
                    'description' => "Bonus d'inscription - Bienvenue ! 🎉",
                ]);
            }

            // Envoyer l'email de bienvenue
            if (setting('email_notifications_enabled', true)) {
                try {
                    Mail::to($user->email)->send(new \App\Mail\WelcomeMail($user));
                    Log::info('✉️ Email envoyé: ' . $user->email);
                } catch (\Exception $e) {
                    Log::error('❌ Email failed: ' . $e->getMessage());
                }
            }

            // ✅ SI VALIDATION MANUELLE REQUISE : Ne pas connecter, rediriger vers login
            if ($manualValidation) {
                Log::info('⏳ Compte en attente de validation: ' . $user->email);
                return redirect()->route('login')
                    ->with('warning', '⏳ Votre compte a été créé mais doit être validé par un administrateur avant de pouvoir vous connecter.');
            }

            // ✅ SINON : CONNECTER AUTOMATIQUEMENT L'UTILISATEUR
            Auth::login($user);
            $request->session()->regenerate();

            Log::info('✅ Connexion automatique après inscription: ' . $user->email);

            // Message de bienvenue personnalisé
            $welcomeMessage = "Inscription réussie ! 🎉 Bienvenue {$user->username} ! ";
            $welcomeMessage .= $coinsOnRegistration > 0 
                ? "Vous avez {$coinsOnRegistration} coins offerts pour commencer. 🪙"
                : "Achetez des coins pour convertir vos fichiers !";

            // ✅ Si conversion en attente (cas rare mais possible)
            if (session()->has('pending_conversion')) {
                Log::info('🔄 Redirection vers conversion en attente après inscription');
                return redirect()->route('conversion.processPending')
                    ->with('success', $welcomeMessage);
            }

            // ✅ Redirection vers la page d'accueil (connecté)
            return redirect()->route('home')
                ->with('success', $welcomeMessage);

        } catch (\Exception $e) {
            Log::error('❌ Inscription error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Une erreur est survenue.'])->withInput($request->except('password', 'password_confirmation'));
        }
    }

    public function logout(Request $request)
    {
        $username = auth()->user()->username ?? 'Utilisateur';
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('🔓 Déconnexion: ' . $username);

        return redirect()->route('home')->with('success', 'Déconnecté avec succès.');
    }
}