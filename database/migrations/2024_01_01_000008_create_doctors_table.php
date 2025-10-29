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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id('id_usuario'); 
            $table->foreign('id_usuario')->references('id_usuario')->on('users')->onDelete('cascade');
            $table->string('universidad', 100);
            $table->string('numero_licencia', 50);
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dentists');
    }
};
