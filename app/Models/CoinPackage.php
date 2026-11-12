<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'coins',
        'price',
        'stripe_price_id',
        'description',
        'is_popular',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Relation avec les achats
     */
    public function purchases()
    {
        return $this->hasMany(CoinPurchase::class);
    }

    /**
     * Obtenir le prix par coin (= prix par fichier)
     */
    public function getPricePerCoinAttribute()
    {
        return $this->price / $this->coins;
    }

    /**
     * Obtenir l'économie réalisée
     */
    public function getSavingsAttribute()
    {
        $basePrice = 0.90; // Prix de 1 fichier (1 FILE = $0.90)
        $normalPrice = $this->coins * $basePrice;
        return $normalPrice - $this->price;
    }

    /**
     * Obtenir le pourcentage d'économie
     */
    public function getSavingsPercentageAttribute()
    {
        $basePrice = 0.90; // Prix de 1 fichier (1 FILE = $0.90)
        $normalPrice = $this->coins * $basePrice;
        if ($normalPrice == 0) return 0;
        return round((($normalPrice - $this->price) / $normalPrice) * 100);
    }

    /**
     * Obtenir les packages actifs
     */
    public static function active()
    {
        return self::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('coins')
            ->get();
    }

    /**
     * Obtenir le prix formaté EN DOLLARS
     */
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2, '.', ',');
    }
}