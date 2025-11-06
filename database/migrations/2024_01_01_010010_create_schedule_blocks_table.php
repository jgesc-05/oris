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
        Schema::create('schedule_blocks', function (Blueprint $table) {
            $table->id('id_bloque');
            $table->unsignedBigInteger('medico_id');
            $table->date('fecha');
            $table->time('hora_desde');
            $table->time('hora_hasta');
            $table->string('motivo', 255)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('medico_id')
                ->references('id_usuario')
                ->on('users')
                ->cascadeOnDelete();

            $table->foreign('created_by')
                ->references('id_usuario')
                ->on('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_blocks');
    }
};
