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
        Schema::create('agenda_block', function (Blueprint $table) {
            $table->id('id_bloqueo');
            $table->dateTime('fecha_hora_inicio');
            $table->dateTime('fecha_hora_fin');
            $table->string('motivo', 255);
            $table->foreignId('id_usuario_odontologo')->constrained('users', 'id_usuario')->onDelete('cascade');
            $table->foreignId('id_usuario')->nullable()->constrained('users', 'id_usuario')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_block');
    }
};
