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
        Schema::create('notifications_channel', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('id_channel')->constrained('channel', 'id_canal')->onDelete('cascade');
            $table->foreignId('id_notifications')->constrained('notifications', 'id_notificaciones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications_channel');
    }
};
