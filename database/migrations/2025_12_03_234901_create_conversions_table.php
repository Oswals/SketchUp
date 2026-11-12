<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversions', function (Blueprint $table) {
            $table->id();
            
            // Informations utilisateur
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            
            // Informations fichier source
            $table->string('original_filename');
            $table->unsignedBigInteger('original_size');
            $table->string('source_version', 10);
            
            // Informations fichier converti (NULLABLE car remplis APRÈS la conversion)
            $table->string('converted_filename')->nullable();
            $table->string('converted_file_path')->nullable();
            $table->unsignedBigInteger('converted_size')->nullable();
            $table->string('target_version', 10);
            
            // Métadonnées de conversion
            $table->decimal('conversion_duration', 8, 2)->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('processing');
            $table->text('error_message')->nullable();
            
            // Téléchargement
            $table->boolean('downloaded')->default(false);
            $table->timestamp('downloaded_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index
            $table->index('user_id');
            $table->index('session_id');
            $table->index('status');
            $table->index('created_at');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('conversions');
    }
};