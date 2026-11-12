<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coin_purchases', function (Blueprint $table) {
            // Agrandir la colonne de VARCHAR(255) à TEXT pour être sûr
            $table->string('stripe_payment_intent_id', 500)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('coin_purchases', function (Blueprint $table) {
            $table->string('stripe_payment_intent_id', 255)->nullable()->change();
        });
    }
};