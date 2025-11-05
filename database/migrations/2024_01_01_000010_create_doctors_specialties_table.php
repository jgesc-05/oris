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
        Schema::create('doctors_specialties', function (Blueprint $table) {
            $table->unsignedBigInteger('id_tipos_especialidad');
            $table->unsignedBigInteger('id_usuario_doctor');
        
            $table->foreign('id_tipos_especialidad')->references('id_tipos_especialidad')->on('specialty_type');
            $table->foreign('id_usuario_doctor')->references('id_usuario')->on('doctors');
        
            $table->primary(['id_tipos_especialidad', 'id_usuario_doctor']);
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors_specialties');
    }
};
