<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coin_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('coin_package_id')->constrained()->onDelete('cascade');
            
            // Informations de la transaction
            $table->integer('coins_purchased'); // Nombre de coins achetés
            $table->decimal('amount_paid', 8, 2); // Montant payé
            
            // Stripe
            $table->string('stripe_payment_intent_id')->nullable()->unique();
            $table->string('stripe_checkout_session_id')->nullable();
            
            // Statut
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->timestamp('completed_at')->nullable();
            
            $table->timestamps();
            
            // Index
            $table->index(['user_id', 'status']);
            $table->index('stripe_payment_intent_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coin_purchases');
    }
};