<?php

namespace App\Http\Controllers;

use App\Models\CoinPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    /**
     * Gérer les webhooks Stripe
     */
    public function handleWebhook(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            // Vérifier la signature du webhook
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $webhookSecret
            );
        } catch (\Exception $e) {
            Log::error('Webhook Stripe - Signature invalide', [
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        Log::info('Webhook Stripe reçu', [
            'type' => $event->type,
            'id' => $event->id,
        ]);

        // Traiter l'événement
        switch ($event->type) {
            case 'checkout.session.completed':
                $this->handleCheckoutCompleted($event->data->object);
                break;

            case 'payment_intent.succeeded':
                $this->handlePaymentSucceeded($event->data->object);
                break;

            case 'payment_intent.payment_failed':
                $this->handlePaymentFailed($event->data->object);
                break;

            default:
                Log::info('Webhook Stripe - Événement non géré', [
                    'type' => $event->type,
                ]);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Gérer la fin du checkout
     */
    private function handleCheckoutCompleted($session)
    {
        Log::info('Webhook - Checkout completed', [
            'session_id' => $session->id,
            'payment_status' => $session->payment_status,
        ]);

        $purchaseId = $session->metadata->coin_purchase_id ?? null;

        if (!$purchaseId) {
            Log::error('Webhook - Purchase ID manquant', [
                'session_id' => $session->id,
            ]);
            return;
        }

        $purchase = CoinPurchase::find($purchaseId);

        if (!$purchase) {
            Log::error('Webhook - Purchase introuvable', [
                'purchase_id' => $purchaseId,
            ]);
            return;
        }

        // Si le paiement est réussi, créditer les coins
        if ($session->payment_status === 'paid') {
            $this->creditCoinsToUser($purchase, $session->payment_intent);
        }
    }

    /**
     * Gérer un paiement réussi
     */
    private function handlePaymentSucceeded($paymentIntent)
    {
        Log::info('Webhook - Payment succeeded', [
            'payment_intent_id' => $paymentIntent->id,
        ]);

        // Chercher l'achat correspondant via la session
        $purchase = CoinPurchase::where('stripe_checkout_session_id', $paymentIntent->metadata->checkout_session_id ?? null)
            ->orWhere('stripe_payment_intent_id', $paymentIntent->id)
            ->first();

        if (!$purchase) {
            Log::warning('Webhook - Purchase non trouvé pour payment_intent', [
                'payment_intent_id' => $paymentIntent->id,
            ]);
            return;
        }

        // Créditer les coins si pas encore fait
        if ($purchase->status === 'pending') {
            $this->creditCoinsToUser($purchase, $paymentIntent->id);
        }
    }

    /**
     * Gérer un paiement échoué
     */
    private function handlePaymentFailed($paymentIntent)
    {
        Log::error('Webhook - Payment failed', [
            'payment_intent_id' => $paymentIntent->id,
            'failure_message' => $paymentIntent->last_payment_error->message ?? 'Unknown error',
        ]);

        $purchase = CoinPurchase::where('stripe_payment_intent_id', $paymentIntent->id)->first();

        if ($purchase) {
            $purchase->update(['status' => 'failed']);
            
            Log::info('Webhook - Purchase marqué comme échoué', [
                'purchase_id' => $purchase->id,
            ]);
        }
    }

  /**
     * Créditer les coins à l'utilisateur (avec protection double crédit)
     */
    private function creditCoinsToUser($purchase, $paymentIntentId)
    {
        // ✅ CORRECTION : S'assurer que $paymentIntentId est une string
        if (is_object($paymentIntentId)) {
            $paymentIntentId = $paymentIntentId->id;
        }

        // ✅ Vérifier si déjà complété (protection double crédit)
        if ($purchase->status === 'completed') {
            Log::info('⚠️ Webhook - Purchase déjà complété, ignoré', [
                'purchase_id' => $purchase->id,
                'current_status' => $purchase->status,
            ]);
            return;
        }

        try {
            // 🔒 Utiliser une transaction database pour éviter les race conditions
            \DB::transaction(function () use ($purchase, $paymentIntentId) {
                
                // Re-vérifier le statut dans la transaction
                $purchase->refresh();
                
                if ($purchase->status === 'completed') {
                    Log::info('⚠️ Race condition évitée - Purchase déjà complété', [
                        'purchase_id' => $purchase->id,
                    ]);
                    return;
                }

                // Mettre à jour l'achat
                $purchase->update([
                    'stripe_payment_intent_id' => $paymentIntentId,
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);

                // Créditer les coins à l'utilisateur
                $purchase->user->addCoins(
                    $purchase->coins_purchased,
                    $purchase->id,
                    "Achat de {$purchase->coins_purchased} coin(s)"
                );

                Log::info('✅ Webhook - Coins crédités avec succès', [
                    'user_id' => $purchase->user_id,
                    'coins' => $purchase->coins_purchased,
                    'new_balance' => $purchase->user->fresh()->coin_balance,
                    'purchase_id' => $purchase->id,
                    'source' => 'webhook',
                ]);
            });

            // 📧 Optionnel : Envoyer un email de confirmation
            // Mail::to($purchase->user->email)->send(new CoinsPurchasedMail($purchase));

        } catch (\Exception $e) {
            Log::error('❌ Webhook - Erreur lors du crédit des coins', [
                'purchase_id' => $purchase->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            // Ne pas lever d'exception pour ne pas faire échouer le webhook
            // Stripe retentera automatiquement
        }
    }
}