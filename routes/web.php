<?php

use App\Http\Controllers\ConversionController;
use App\Http\Controllers\CoinController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ConversionManagementController; 
use App\Http\Controllers\Admin\CoinManagementController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\PayPalController;

// ==================== ROUTES PUBLIQUES ====================

Route::get('/', [ConversionController::class, 'index'])->name('home');
Route::get('/contact', [ConversionController::class, 'contact'])->name('contact');
// Route pour traiter l'envoi du formulaire
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');


 // Routes Google OAuth
    Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])
        ->name('google.redirect');
        
    Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])
        ->name('google.callback');

   Route::post('/conversion/convert', [ConversionController::class, 'convert'])
        ->name('conversion.convert');



// ==================== ROUTES AUTH (GUEST) ====================

Route::middleware(['guest'])->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    
    // Register
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
    
    // Check user (AJAX)
    Route::post('/check-user', [AuthController::class, 'checkUser'])->name('check.user');

    // ==================== FORGOT PASSWORD ROUTES ====================
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])
        ->name('password.request');

    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
        ->name('password.email');

    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])
        ->name('password.reset');

    Route::post('/reset-password', [AuthController::class, 'resetPassword'])
        ->name('password.update');

    
    });


   
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/', [AdminController::class, 'index'])->name('index');

     // Gestion des utilisateurs
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::get('/{user}', [UserManagementController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
        
        // Actions spécifiques
        Route::post('/{user}/activate', [UserManagementController::class, 'activate'])->name('activate');
        Route::post('/{user}/deactivate', [UserManagementController::class, 'deactivate'])->name('deactivate');
        Route::post('/{user}/add-coins', [UserManagementController::class, 'addCoins'])->name('addCoins');
    });


     // Gestion des conversions
    Route::prefix('conversions')->name('conversions.')->group(function () {
        Route::get('/', [ConversionManagementController::class, 'index'])->name('index');
        Route::get('/{conversion}', [ConversionManagementController::class, 'show'])->name('show');
        Route::delete('/{conversion}', [ConversionManagementController::class, 'destroy'])->name('destroy');
        Route::post('/{conversion}/retry', [ConversionManagementController::class, 'retry'])->name('retry');
    });
   

     Route::prefix('coins')->name('coins.')->group(function () {
        Route::get('/transactions', [CoinManagementController::class, 'transactions'])->name('transactions');
       
    });


    // Paramètres système
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::put('/', [SettingsController::class, 'update'])->name('update');
        Route::get('/reset', [SettingsController::class, 'reset'])->name('reset');
    });

    // Analytics & Statistiques
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/', [AnalyticsController::class, 'index'])->name('index');
        Route::get('/export/csv', [AnalyticsController::class, 'exportCSV'])->name('export.csv');
    });
});


// ==================== ROUTES AUTHENTIFIÉES ====================

Route::middleware(['auth'])->group(function () {
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // ==================== CONVERSIONS ====================
    
    // Conversion avec vérification des crédits
   
    
    // Traiter une conversion en attente (après login/register)
    Route::get('/conversion/process-pending', [ConversionController::class, 'processPendingConversion'])
        ->name('conversion.processPending');
    
    // Afficher les résultats
    Route::get('/conversion/result', [ConversionController::class, 'result'])
        ->name('conversion.result');


     // Analyser un fichier (AJAX)
    Route::post('/conversion/analyze', [ConversionController::class, 'analyze'])
        ->name('analyze');
    
    // Obtenir les avertissements (AJAX)
    Route::post('/conversion/warnings', [ConversionController::class, 'getWarnings'])
        ->name('warnings');    
    
    // Télécharger un fichier converti
    Route::get('/conversion/download/{id}', [ConversionController::class, 'download'])
        ->name('conversion.download');
    
    // Supprimer une conversion
    Route::delete('/conversion/delete/{id}', [ConversionController::class, 'delete'])
        ->name('conversion.delete');
    
    // Annuler toutes les conversions
    Route::post('/conversion/cancel', [ConversionController::class, 'cancel'])
        ->name('conversion.cancel');
    
    // ==================== COINS ====================
    
  // ✅ Afficher la page de checkout (AVANT Stripe)
     // Page de checkout (affichage du formulaire)
    Route::get('/coins/checkout/{package}', [CoinController::class, 'checkout'])
        ->name('coins.checkout');
    
    // Traitement du formulaire + création session Stripe (✅ ROUTE MANQUANTE)
    Route::post('/coins/checkout/{package}/process', [CoinController::class, 'processCheckout'])
        ->name('coins.checkout.process');
    
    // Ancienne route (pour compatibilité)
    Route::post('/coins/checkout/{package}', [CoinController::class, 'createCheckoutSession'])
        ->name('coins.checkout.session');
    
    // Page de succès après paiement
    Route::get('/coins/success', [CoinController::class, 'success'])
        ->name('coins.success');
    
    // Historique des achats
    Route::get('/coins/history', [CoinController::class, 'history'])
        ->name('coins.history');
    
    // ==================== PROFIL ====================
    
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.delete');
    
    // Statistiques (bonus)
    Route::get('/profile/statistics', [ProfileController::class, 'statistics'])->name('profile.statistics');
    
    // Export données RGPD (bonus)
    Route::get('/profile/export', [ProfileController::class, 'exportData'])->name('profile.export');
});

// ==================== WEBHOOK STRIPE (SANS AUTH/CSRF) ====================

// ⚠️ TRÈS IMPORTANT : Cette route doit être SANS middleware auth et SANS CSRF
Route::post('/webhook/stripe', [StripeWebhookController::class, 'handleWebhook'])
    ->name('stripe.webhook')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);


// ==================== PAYPAL ROUTES ====================

Route::middleware('auth')->group(function () {
    Route::get('/paypal/create/{package}',  [PayPalController::class, 'createOrder'])->name('coins.paypal.create');
    Route::get('/paypal/success/{package}', [PayPalController::class, 'captureOrder'])->name('coins.paypal.success');
    Route::get('/paypal/cancel/{package}',  [PayPalController::class, 'cancelOrder'])->name('coins.paypal.cancel');
});