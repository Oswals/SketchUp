<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coin_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 1 Coin, 5 Coins, 10 Coins
            $table->integer('coins'); // Nombre de coins
            $table->decimal('price', 8, 2); // Prix en euros
            $table->string('stripe_price_id')->nullable(); // ID du prix Stripe
            $table->text('description')->nullable();
            $table->boolean('is_popular')->default(false); // Package populaire
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0); // Ordre d'affichage
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coin_packages');
    }
};
