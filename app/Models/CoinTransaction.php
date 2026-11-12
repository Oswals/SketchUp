<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinTransaction extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'conversion_id',
        'coin_purchase_id',
        'type',
        'coins',
        'balance_after',
        'description',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec la conversion
     */
    public function conversion()
    {
        return $this->belongsTo(Conversion::class);
    }

    /**
     * Relation avec l'achat
     */
    public function coinPurchase()
    {
        return $this->belongsTo(CoinPurchase::class);
    }

    /**
     * Créer une transaction de crédit
     */
    public static function credit($userId, $coins, $purchaseId, $description)
    {
        $user = User::find($userId);
        
        return self::create([
            'user_id' => $userId,
            'coin_purchase_id' => $purchaseId,
            'type' => 'credit',
            'coins' => $coins,
            'balance_after' => $user->coin_balance,
            'description' => $description,
        ]);
    }

    /**
     * Créer une transaction de débit
     */
    public static function debit($userId, $coins, $conversionId, $description)
    {
        $user = User::find($userId);
        
        return self::create([
            'user_id' => $userId,
            'conversion_id' => $conversionId,
            'type' => 'debit',
            'coins' => -$coins,
            'balance_after' => $user->coin_balance,
            'description' => $description,
        ]);
    }
}
