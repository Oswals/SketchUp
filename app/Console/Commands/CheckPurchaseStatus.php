<?php

namespace App\Console\Commands;

use App\Models\CoinPurchase;
use Illuminate\Console\Command;

class CheckPurchaseStatus extends Command
{
    protected $signature = 'coins:check-purchase {purchase_id}';
    protected $description = 'Vérifier et forcer le crédit d\'un achat';

    public function handle()
    {
        $purchaseId = $this->argument('purchase_id');
        $purchase = CoinPurchase::find($purchaseId);

        if (!$purchase) {
            $this->error("❌ Achat #{$purchaseId} introuvable");
            return 1;
        }

        $this->info("📦 Achat #{$purchase->id}");
        $this->info("👤 User: {$purchase->user->username} (#{$purchase->user_id})");
        $this->info("💰 Coins: {$purchase->coins_purchased}");
        $this->info("📊 Status: {$purchase->status}");
        $this->info("🏦 Balance actuelle: {$purchase->user->coin_balance} coins");

        if ($purchase->status === 'completed') {
            $this->warn("⚠️ Cet achat est déjà complété");
            return 0;
        }

        if ($this->confirm('Voulez-vous forcer le crédit des coins ?')) {
            try {
                $purchase->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);

                $purchase->user->addCoins(
                    $purchase->coins_purchased,
                    $purchase->id,
                    "Crédit manuel - Achat #{$purchase->id}"
                );

                $this->info("✅ Coins crédités avec succès !");
                $this->info("🏦 Nouveau solde: {$purchase->user->fresh()->coin_balance} coins");

            } catch (\Exception $e) {
                $this->error("❌ Erreur: " . $e->getMessage());
                return 1;
            }
        }

        return 0;
    }
}