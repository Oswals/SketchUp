<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'username',
        'email',
        'password',
        'google_id', 
        'avatar',
        'provider',
        'status',
        'clean_password',
        'role', 
        'coin_balance'// ✅ Ajout du champ role
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'clean_password' => 'boolean',
        ];
    }

    // ✅ Vérifier si c'est un admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Accesseur pour le nom complet
    public function getFullNameAttribute(): string
    {
        return "{$this->username}";
    }

    // Vérifier si l'utilisateur est actif
    public function isActive(): bool
    {
        return $this->status === 1;
    }

    // Vérifier si l'utilisateur est en attente
    public function isPending(): bool
    {
        return $this->status === 2;
    }

    /**
     * Relation avec les achats de coins
     */
    public function coinPurchases()
    {
        return $this->hasMany(CoinPurchase::class);
    }

    /**
     * Relation avec les transactions de coins
     */
    public function coinTransactions()
    {
        return $this->hasMany(CoinTransaction::class);
    }

    /**
     * Relation avec les conversions
     */
    public function conversions()
    {
        return $this->hasMany(Conversion::class);
    }

    /**
     * ✅ Vérifier si l'utilisateur peut convertir
     */
    public function canConvert(): bool
    {
        return $this->coin_balance >= 1;
    }

    /**
     * ✅ Utiliser 1 coin pour une conversion
     */
    public function useConversion($conversionId = null)
    {
        if ($this->coin_balance < 1) {
            throw new \Exception('Solde de coins insuffisant');
        }

        $this->decrement('coin_balance');
        $this->refresh();

        // Logger la transaction
        CoinTransaction::create([
            'user_id' => $this->id,
            'conversion_id' => $conversionId,
            'type' => 'debit',
            'coins' => -1,
            'balance_after' => $this->coin_balance,
            'description' => 'Conversion de fichier SketchUp',
        ]);

        \Log::info('✅ Coin utilisé pour conversion', [
            'user_id' => $this->id,
            'conversion_id' => $conversionId,
            'new_balance' => $this->coin_balance,
        ]);

        return true;
    }

   /**
     * ✅ Ajouter des coins - VERSION AVEC LOGS DÉTAILLÉS
     */
    public function addCoins($coins, $purchaseId, $description)
    {
        \Log::info('🔵 Début addCoins()', [
            'user_id' => $this->id,
            'current_balance' => $this->coin_balance,
            'coins_to_add' => $coins,
            'purchase_id' => $purchaseId,
        ]);

        try {
            // Incrémenter le solde
            $oldBalance = $this->coin_balance;
            $this->increment('coin_balance', $coins);
            $this->refresh(); // Important : recharger le modèle
            
            \Log::info('🟢 Solde incrémenté', [
                'user_id' => $this->id,
                'old_balance' => $oldBalance,
                'new_balance' => $this->coin_balance,
                'increment_value' => $coins,
            ]);

            // Créer la transaction
            $transaction = CoinTransaction::create([
                'user_id' => $this->id,
                'coin_purchase_id' => $purchaseId,
                'type' => 'credit',
                'coins' => $coins,
                'balance_after' => $this->coin_balance,
                'description' => $description,
            ]);

            \Log::info('✅✅ Coins ajoutés avec succès', [
                'user_id' => $this->id,
                'coins_added' => $coins,
                'old_balance' => $oldBalance,
                'new_balance' => $this->coin_balance,
                'transaction_id' => $transaction->id,
            ]);

            return true;

        } catch (\Exception $e) {
            \Log::error('❌ Erreur dans addCoins()', [
                'user_id' => $this->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            throw $e;
        }
    }

    /**
     * ✅ Obtenir le nombre de conversions restantes
     */
    public function getConversionsRemainingAttribute()
    {
        return $this->coin_balance;
    }


    // Dans User.php — ajouter cet accessor
    public function getDisplayBalanceAttribute(): string
    {
        return $this->coin_balance >= 9999 ? '∞' : (string) $this->coin_balance;
    }
}