<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coin_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('conversion_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('coin_purchase_id')->nullable()->constrained()->onDelete('set null');
            
            // Type de transaction
            $table->enum('type', ['credit', 'debit']); // credit = ajout, debit = utilisation
            $table->integer('coins'); // Nombre de coins (positif ou négatif)
            $table->integer('balance_after'); // Solde après transaction
            
            // Description
            $table->string('description'); // Ex: "Achat de 5 coins", "Conversion fichier.skp"
            
            $table->timestamp('created_at')->useCurrent();
            
            // Index
            $table->index(['user_id', 'created_at']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coin_transactions');
    }
};