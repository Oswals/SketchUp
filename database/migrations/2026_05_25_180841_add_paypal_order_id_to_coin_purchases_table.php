<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('coin_purchases', function (Blueprint $table) {
            $table->string('paypal_order_id')->nullable()->after('stripe_checkout_session_id');
            $table->string('payment_gateway')->default('stripe')->after('paypal_order_id'); // 'stripe' ou 'paypal'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coin_purchases', function (Blueprint $table) {
            //
        });
    }
};
