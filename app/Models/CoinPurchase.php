<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'coin_package_id',
        'coins_purchased',
        'amount_paid',
        'stripe_payment_intent_id',
        'stripe_checkout_session_id',
        'status',
        'completed_at',
        'paypal_order_id',
        'payment_gateway',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec le package
     */
    public function coinPackage()
    {
        return $this->belongsTo(CoinPackage::class);
    }

    /**
     * Marquer comme complété
     */
    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Créditer les coins à l'utilisateur
        $this->user->addCoins(
            $this->coins_purchased,
            $this->id,
            "Achat de {$this->coins_purchased} coins"
        );
    }

    /**
     * Vérifier si l'achat est complété
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }
}
