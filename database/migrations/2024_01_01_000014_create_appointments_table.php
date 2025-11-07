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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id('id_cita');
            $table->foreignId('id_usuario_paciente')->constrained('users', 'id_usuario')->onDelete('cascade');
            $table->foreignId('id_usuario_medico')->constrained('users', 'id_usuario')->onDelete('cascade');
            $table->foreignId('id_servicio')->constrained('services', 'id_servicio')->onDelete('cascade');
            $table->foreignId('id_usuario_agenda')->nullable()->constrained('users', 'id_usuario')->onDelete('cascade');
            $table->foreignId('id_usuario_cancela')->nullable()->constrained('users', 'id_usuario')->onDelete('cascade');
            //$table->foreignId('id_pago')->nullable()->constrained('payments', 'id_pago')->onDelete('set null');
            //Por ahora no supe como es lo de id_pago

            $table->dateTime('fecha_hora_inicio');
            $table->dateTime('fecha_hora_fin');
            $table->string('estado', 20)->default('Programada');
            $table->text('notas')->nullable();
            $table->string('motivo_cancelacion', 255)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
