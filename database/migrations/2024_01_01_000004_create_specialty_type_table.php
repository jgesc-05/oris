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
        Schema::create('specialty_type', function (Blueprint $table) {
            $table->id('id_tipos_especialidad');
            $table->string('nombre', 100);
            $table->string('descripcion', 255);
            $table->string('estado', 20)->default('activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specialty_type');
    }
};
