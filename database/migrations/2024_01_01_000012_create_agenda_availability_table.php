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
        Schema::create('agenda_availability', function (Blueprint $table) {
            $table->id('id_disponibilidad');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->string('dia_semana', 20);
            $table->date('vigencia_desde');
            $table->date('vigencia_hasta');
            $table->foreignId('id_usuario_odontologo')->constrained('users', 'id_usuario')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_availability');
    }
};
