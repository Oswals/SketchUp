<?php

namespace App\Http\Controllers;

use App\Models\CoinPackage;
use App\Models\CoinPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class CoinController extends Controller
{
    /**
     * ✅ Afficher la page de checkout (avant Stripe)
     */
    public function checkout(CoinPackage $package)
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Please log in to purchase files.');
        }

        return view('frontend.coins.checkout', compact('package'));
    }

    /**
     * ✅ Traiter le formulaire et créer la session Stripe
     */
    public function processCheckout(Request $request, CoinPackage $package)
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Please log in to purchase files.');
        }

        $user = auth()->user();

        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $stripeCustomer = $this->getOrCreateStripeCustomer($user);

            $purchase = CoinPurchase::create([
                'user_id' => $user->id,
                'coin_package_id' => $package->id,
                'coins_purchased' => $package->coins,
                'amount_paid' => $package->price,
                'status' => 'pending',
            ]);

            Log::info('💳 Création achat en attente', [
                'user_id' => $user->id,
                'purchase_id' => $purchase->id,
                'package' => $package->name,
            ]);

            $session = StripeSession::create([
                'customer' => $stripeCustomer->id,
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $package->name,
                            'description' => $package->description,
                            'images' => [asset('frontend/assets/img/coin-icon.png')],
                        ],
                        'unit_amount' => (int) round($package->price * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('coins.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('coins.checkout', $package) . '?canceled=true',
                'metadata' => [
                    'user_id' => $user->id,
                    'coin_package_id' => $package->id,
                    'coin_purchase_id' => $purchase->id,
                ],
                'billing_address_collection' => 'auto',
                'locale' => 'en',
            ]);

            $purchase->update([
                'stripe_checkout_session_id' => $session->id,
            ]);

            Log::info('✅ Session Stripe créée', [
                'user_id' => $user->id,
                'package' => $package->name,
                'session_id' => $session->id,
                'purchase_id' => $purchase->id,
            ]);

            return redirect($session->url);

        } catch (\Stripe\Exception\CardException $e) {
            Log::error('❌ Erreur Stripe - Carte refusée', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Your card was declined: ' . $e->getError()->message);

        } catch (\Stripe\Exception\RateLimitException $e) {
            Log::error('❌ Erreur Stripe - Rate limit', ['user_id' => $user->id]);
            return back()->with('error', 'Too many attempts. Please try again in a few moments.');

        } catch (\Stripe\Exception\InvalidRequestException $e) {
            Log::error('❌ Erreur Stripe - Requête invalide', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Configuration error. Please contact support.');

        } catch (\Stripe\Exception\AuthenticationException $e) {
            Log::error('❌ Erreur Stripe - Authentification', ['error' => $e->getMessage()]);
            return back()->with('error', 'Authentication error with Stripe. Please contact support.');

        } catch (\Stripe\Exception\ApiConnectionException $e) {
            Log::error('❌ Erreur Stripe - Connexion', ['error' => $e->getMessage()]);
            return back()->with('error', 'Unable to contact Stripe. Please check your connection.');

        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('❌ Erreur Stripe API', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'An error occurred with our payment system. Please try again.');

        } catch (\Exception $e) {
            Log::error('❌ Erreur création session Stripe', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'An error occurred. Please try again or contact support.');
        }
    }

    /**
     * ✅ Page de succès après paiement - AVEC CRÉDIT AUTOMATIQUE ET EMAIL
     */
    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            Log::warning('⚠️ Tentative accès page succès sans session_id');
            return redirect()->route('home')
                ->with('error', 'Invalid payment session.');
        }

        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            
            $session = StripeSession::retrieve([
                'id' => $sessionId,
                'expand' => ['customer', 'payment_intent'],
            ]);

            Log::info('📄 Page succès - Session récupérée', [
                'session_id' => $sessionId,
                'payment_status' => $session->payment_status,
            ]);

            $purchaseId = $session->metadata->coin_purchase_id ?? null;
            
            $purchase = null;
            if ($purchaseId) {
                $purchase = CoinPurchase::find($purchaseId);
            }

            if (!$purchase) {
                $purchase = CoinPurchase::where('stripe_checkout_session_id', $sessionId)->first();
            }

            if (!$purchase) {
                Log::error('❌ Purchase introuvable', [
                    'session_id' => $sessionId,
                    'purchase_id' => $purchaseId,
                ]);
                
                return redirect()->route('home')
                    ->with('error', 'Purchase not found. Please contact support.');
            }

            if ($purchase->user_id !== auth()->id()) {
                abort(403, 'Unauthorized access');
            }

            // 🎯 PARTIE CRITIQUE : CRÉDITER LES COINS ET ENVOYER L'EMAIL
            if ($session->payment_status === 'paid' && $purchase->status === 'pending') {
                
                Log::info('💰 Début crédit automatique depuis page success', [
                    'purchase_id' => $purchase->id,
                    'user_id' => $purchase->user_id,
                    'coins' => $purchase->coins_purchased,
                ]);

                try {
                    \DB::transaction(function () use ($purchase, $session) {
                        
                        $purchase->refresh();
                        
                        if ($purchase->status === 'completed') {
                            Log::info('⚠️ Race condition évitée - déjà complété', [
                                'purchase_id' => $purchase->id,
                            ]);
                            return;
                        }

                        $paymentIntentId = is_string($session->payment_intent) 
                            ? $session->payment_intent 
                            : $session->payment_intent->id;
                        
                        $purchase->update([
                            'stripe_payment_intent_id' => $paymentIntentId,
                            'status' => 'completed',
                            'completed_at' => now(),
                        ]);

                        Log::info('✅ Purchase mis à jour', [
                            'purchase_id' => $purchase->id,
                            'status' => 'completed',
                        ]);

                        // Créditer les coins
                        $oldBalance = $purchase->user->coin_balance;
                        $purchase->user->addCoins(
                            $purchase->coins_purchased,
                            $purchase->id,
                            "Purchase of {$purchase->coins_purchased} file(s)"
                        );

                        Log::info('✅✅✅ COINS CRÉDITÉS AVEC SUCCÈS', [
                            'user_id' => $purchase->user_id,
                            'coins_added' => $purchase->coins_purchased,
                            'old_balance' => $oldBalance,
                            'new_balance' => $purchase->user->coin_balance,
                            'purchase_id' => $purchase->id,
                        ]);

                        // 📧 ENVOI DE L'EMAIL DE CONFIRMATION
                        try {
                            $this->sendCoinsPurchaseEmail($purchase);
                            
                            Log::info('📧 Email de confirmation envoyé', [
                                'user_id' => $purchase->user_id,
                                'email' => $purchase->user->email,
                                'purchase_id' => $purchase->id,
                            ]);
                        } catch (\Exception $emailError) {
                            // On log l'erreur mais on ne bloque pas le processus
                            Log::error('⚠️ Erreur envoi email (coins crédités quand même)', [
                                'user_id' => $purchase->user_id,
                                'error' => $emailError->getMessage(),
                            ]);
                        }
                    });

                    $purchase = $purchase->fresh();
                    auth()->user()->refresh();

                    Log::info('🎉 Transaction complète - redirection vers la vue', [
                        'user_balance' => auth()->user()->coin_balance,
                        'purchase_status' => $purchase->status,
                    ]);

                    return view('frontend.coins.success', [
                        'session' => $session,
                        'purchase' => $purchase,
                        'coins_credited' => true,
                    ]);

                } catch (\Exception $e) {
                    Log::error('❌❌❌ ERREUR CRITIQUE lors du crédit des coins', [
                        'purchase_id' => $purchase->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ]);
                    
                    return view('frontend.coins.success', [
                        'session' => $session,
                        'purchase' => $purchase,
                        'coins_credited' => false,
                        'credit_error' => true,
                    ]);
                }
            }

            Log::info('ℹ️ Affichage page sans crédit', [
                'purchase_status' => $purchase->status,
                'payment_status' => $session->payment_status,
            ]);

            return view('frontend.coins.success', [
                'session' => $session,
                'purchase' => $purchase,
                'coins_credited' => $purchase->status === 'completed',
            ]);

        } catch (\Stripe\Exception\InvalidRequestException $e) {
            Log::error('❌ Erreur page succès - Session invalide', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('home')
                ->with('error', 'Invalid or expired payment session.');

        } catch (\Exception $e) {
            Log::error('❌ Erreur page succès', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('home')
                ->with('error', 'An error occurred. Please contact support if the problem persists.');
        }
    }

    /**
     * 📧 Envoyer l'email de confirmation de crédit de coins
     */
    private function sendCoinsPurchaseEmail($purchase)
    {
        $user = $purchase->user;
        $package = $purchase->coinPackage;
        
        Mail::send([], [], function ($message) use ($user, $purchase, $package) {
            $message->to($user->email)
                ->subject('🎉 Your files have been credited!')
                ->html("
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <meta charset='utf-8'>
                        <style>
                            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                            .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                            .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                            .coin-box { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; border-left: 4px solid #667eea; }
                            .balance { font-size: 24px; font-weight: bold; color: #667eea; }
                            .button { display: inline-block; padding: 12px 30px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
                            .footer { text-align: center; margin-top: 30px; color: #888; font-size: 12px; }
                        </style>
                    </head>
                    <body>
                        <div class='container'>
                            <div class='header'>
                                <h1>🎉 Thank you for your purchase!</h1>
                            </div>
                            <div class='content'>
                                <p>Hello <strong>{$user->username}</strong>,</p>
                                
                                <p>Your payment has been successfully confirmed! Your files have been added to your account.</p>
                                
                                <div class='coin-box'>
                                    <h3 style='margin-top: 0; color: #667eea;'>📦 Purchase Details</h3>
                                    <p><strong>Package:</strong> {$package->name}</p>
                                    <p><strong>Files purchased:</strong> {$purchase->coins_purchased} file(s)</p>
                                    <p><strong>Amount paid:</strong> \${$purchase->amount_paid}</p>
                                    <p style='margin-top: 20px;'><strong>Your new balance:</strong></p>
                                    <p class='balance'>{$user->coin_balance} files</p>
                                </div>
                                
                                <p>You can now use your files to convert your SketchUp files!</p>
                                
                                <center>
                                    <a href='" . route('coins.history') . "' class='button'>Access my account</a>
                                </center>
                                
                                <div class='footer'>
                                    <p>This email confirms the addition of files to your SKP Converter account.</p>
                                    <p>You also received a payment receipt from Stripe for accounting purposes.</p>
                                    <p>If you have any questions, please don't hesitate to contact us.</p>
                                </div>
                            </div>
                        </div>
                    </body>
                    </html>
                ");
        });
    }

    /**
     * ✅ Historique des achats
     */
    public function history()
    {
        $user = auth()->user();
        
        $purchases = $user->coinPurchases()
            ->with('coinPackage')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $transactions = $user->coinTransactions()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('frontend.coins.history', compact('purchases', 'transactions'));
    }

    /**
     * ✅ Obtenir ou créer un client Stripe
     */
    private function getOrCreateStripeCustomer($user)
    {
        try {
            $existingPurchase = $user->coinPurchases()
                ->whereNotNull('stripe_payment_intent_id')
                ->latest()
                ->first();

            if ($existingPurchase) {
                try {
                    Stripe::setApiKey(config('services.stripe.secret'));
                    
                    $paymentIntent = \Stripe\PaymentIntent::retrieve($existingPurchase->stripe_payment_intent_id);
                    
                    if ($paymentIntent->customer) {
                        Log::info('✅ Client Stripe existant trouvé', [
                            'user_id' => $user->id,
                            'customer_id' => $paymentIntent->customer,
                        ]);
                        
                        return \Stripe\Customer::retrieve($paymentIntent->customer);
                    }
                } catch (\Exception $e) {
                    Log::warning('⚠️ Customer Stripe introuvable, création d\'un nouveau', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            $customer = \Stripe\Customer::create([
                'email' => $user->email,
                'name' => $user->username,
                'metadata' => [
                    'user_id' => $user->id,
                    'platform' => 'SKP Converter',
                ],
            ]);

            Log::info('✅ Nouveau client Stripe créé', [
                'user_id' => $user->id,
                'customer_id' => $customer->id,
            ]);

            return $customer;

        } catch (\Exception $e) {
            Log::error('❌ Erreur création client Stripe', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }
}