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
        Schema::create('services', function (Blueprint $table) {
            $table->id('id_servicio');
            $table->foreignId('id_tipos_especialidad')->constrained('specialty_type', 'id_tipos_especialidad')->onDelete('cascade');
            $table->string('nombre', 100);
            $table->integer('duracion');
            $table->decimal('precio_base', 10, 2);
            $table->string('estado', 50);
            $table->string('descripcion', 255);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
