<?php

namespace App\Http\Controllers;

use App\Models\CoinPackage;
use App\Models\CoinPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalController extends Controller
{
    /**
     * 🟡 Créer une commande PayPal et rediriger vers PayPal
     */
    public function createOrder(CoinPackage $package)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        try {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            // Créer le purchase en pending (même logique que Stripe)
            $purchase = CoinPurchase::create([
                'user_id'         => $user->id,
                'coin_package_id' => $package->id,
                'coins_purchased' => $package->coins,
                'amount_paid'     => $package->price,
                'status'          => 'pending',
                'payment_gateway' => 'paypal',
            ]);

            Log::info('🟡 Création order PayPal', [
                'user_id'     => $user->id,
                'purchase_id' => $purchase->id,
                'package'     => $package->name,
            ]);

            $order = $provider->createOrder([
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'reference_id' => (string) $purchase->id,
                    'description'  => $package->name,
                    'amount'       => [
                        'currency_code' => 'USD',
                        'value'         => number_format($package->price, 2, '.', ''),
                    ],
                ]],
                'application_context' => [
                    'return_url' => route('coins.paypal.success', $package),
                    'cancel_url' => route('coins.checkout', $package) . '?canceled=true',
                ],
            ]);

            // Sauvegarder l'order_id PayPal
            $purchase->update(['paypal_order_id' => $order['id']]);

            Log::info('✅ Order PayPal créé', [
                'order_id'    => $order['id'],
                'purchase_id' => $purchase->id,
            ]);

            // Rediriger l'utilisateur vers PayPal
            foreach ($order['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return redirect()->away($link['href']);
                }
            }

            return back()->with('error', 'Unable to connect to PayPal. Please try again.');

        } catch (\Exception $e) {
            Log::error('❌ Erreur création order PayPal', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
            return back()->with('error', 'PayPal error. Please try again or use card payment.');
        }
    }

    /**
     * ✅ Capturer le paiement après approbation de l'utilisateur
     */
    public function captureOrder(Request $request, CoinPackage $package)
    {
        $token = $request->get('token'); // PayPal order ID

        if (!$token) {
            return redirect()->route('home')->with('error', 'Invalid PayPal session.');
        }

        $user = auth()->user();

        try {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $result = $provider->capturePaymentOrder($token);

            if (!isset($result['status']) || $result['status'] !== 'COMPLETED') {
                Log::error('❌ PayPal capture non complété', [
                    'token'  => $token,
                    'result' => $result,
                ]);
                return redirect()->route('home')
                    ->with('error', 'Payment not completed. Please try again.');
            }

            // Retrouver le purchase via l'order_id PayPal
            $purchase = CoinPurchase::where('paypal_order_id', $token)
                ->where('user_id', $user->id)
                ->first();

            if (!$purchase) {
                Log::error('❌ Purchase PayPal introuvable', [
                    'token'   => $token,
                    'user_id' => $user->id,
                ]);
                return redirect()->route('home')
                    ->with('error', 'Purchase not found. Please contact support.');
            }

            // Créditer les coins (même logique que Stripe)
            if ($purchase->status === 'pending') {

                \DB::transaction(function () use ($purchase, $result) {

                    $purchase->refresh();

                    if ($purchase->status === 'completed') {
                        Log::info('⚠️ Race condition évitée - déjà complété', [
                            'purchase_id' => $purchase->id,
                        ]);
                        return;
                    }

                    // L'ID de capture PayPal (équivalent du payment_intent Stripe)
                    $captureId = $result['purchase_units'][0]['payments']['captures'][0]['id'] ?? null;

                    $purchase->update([
                        'stripe_payment_intent_id' => $captureId, // colonne réutilisée comme payment_ref
                        'status'                   => 'completed',
                        'completed_at'             => now(),
                    ]);

                    Log::info('✅ Purchase PayPal mis à jour', [
                        'purchase_id' => $purchase->id,
                        'capture_id'  => $captureId,
                    ]);

                    // Créditer les coins (identique à Stripe)
                    $oldBalance = $purchase->user->coin_balance;
                    $purchase->user->addCoins(
                        $purchase->coins_purchased,
                        $purchase->id,
                        "Purchase of {$purchase->coins_purchased} file(s) via PayPal"
                    );

                    Log::info('✅✅✅ COINS CRÉDITÉS VIA PAYPAL', [
                        'user_id'      => $purchase->user_id,
                        'coins_added'  => $purchase->coins_purchased,
                        'old_balance'  => $oldBalance,
                        'new_balance'  => $purchase->user->coin_balance,
                        'purchase_id'  => $purchase->id,
                    ]);
                });

                // Envoyer l'email de confirmation (même que Stripe)
                try {
                    $this->sendConfirmationEmail($purchase->fresh());
                } catch (\Exception $e) {
                    Log::warning('⚠️ Email non envoyé (coins crédités quand même)', [
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            auth()->user()->refresh();

            return view('frontend.coins.success', [
                'session'        => null,
                'purchase'       => $purchase->fresh(),
                'coins_credited' => true,
                'via_paypal'     => true,
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Erreur capture PayPal', [
                'user_id' => $user->id,
                'token'   => $token,
                'error'   => $e->getMessage(),
            ]);
            return redirect()->route('home')
                ->with('error', 'PayPal capture error. Please contact support.');
        }
    }

    /**
     * ❌ Annulation PayPal
     */
    public function cancelOrder(CoinPackage $package)
    {
        Log::info('🚫 Paiement PayPal annulé', [
            'user_id'    => auth()->id(),
            'package_id' => $package->id,
        ]);

        return redirect()->route('coins.checkout', $package)
            ->with('error', 'PayPal payment cancelled. You can try again.');
    }

    /**
     * 📧 Email de confirmation (même template que CoinController)
     */
    private function sendConfirmationEmail($purchase)
    {
        $user    = $purchase->user;
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
                            <div class='header'><h1>🎉 Thank you for your purchase!</h1></div>
                            <div class='content'>
                                <p>Hello <strong>{$user->username}</strong>,</p>
                                <p>Your PayPal payment has been confirmed! Your files have been added to your account.</p>
                                <div class='coin-box'>
                                    <h3 style='margin-top:0;color:#667eea;'>📦 Purchase Details</h3>
                                    <p><strong>Package:</strong> {$package->name}</p>
                                    <p><strong>Files purchased:</strong> {$purchase->coins_purchased} file(s)</p>
                                    <p><strong>Amount paid:</strong> \${$purchase->amount_paid}</p>
                                    <p><strong>Payment method:</strong> PayPal</p>
                                    <p style='margin-top:20px;'><strong>Your new balance:</strong></p>
                                    <p class='balance'>{$user->coin_balance} files</p>
                                </div>
                                <center>
                                    <a href='" . route('coins.history') . "' class='button'>Access my account</a>
                                </center>
                                <div class='footer'>
                                    <p>This email confirms the addition of files to your SKP Converter account.</p>
                                    <p>If you have any questions, please contact us.</p>
                                </div>
                            </div>
                        </div>
                    </body>
                    </html>
                ");
        });
    }
}