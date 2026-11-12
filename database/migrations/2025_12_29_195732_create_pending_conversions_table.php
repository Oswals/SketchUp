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
        Schema::create('pending_conversions', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->unique();
            $table->string('session_id')->nullable();
            $table->json('data'); // Contient les infos de pending_conversion
            $table->timestamp('created_at');
            $table->timestamp('expires_at')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_conversions');
    }
};
