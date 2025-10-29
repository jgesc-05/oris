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
        Schema::create('access_tokens', function (Blueprint $table) {
            $table->id('id_token');
            $table->unsignedBigInteger('id_usuario');
            $table->string('token', 255)->unique();
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_expiracion');
            $table->boolean('usado')->default(false);
            $table->timestamps();
        
            $table->foreign('id_usuario')->references('id_usuario')->on('users')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_tokens');
    }
};
