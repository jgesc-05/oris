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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('id_notificaciones');
            $table->string('estado', 50);
            $table->json('contenido_json')->nullable();
            $table->string('plantilla', 100)->nullable();
            $table->dateTime('fecha_envio')->nullable();
            $table->foreignId('id_canal')
                ->nullable()
                ->constrained('channel', 'id_canal')
                ->nullOnDelete();
            $table->foreignId('id_cita')->nullable()->constrained('appointments', 'id_cita')->onDelete('cascade');
            $table->foreignId('id_usuario')->nullable()->constrained('users', 'id_usuario')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
