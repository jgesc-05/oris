<?php

use App\Models\Appointment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('appointments')
            ->where('estado', 'Completada')
            ->update(['estado' => Appointment::STATUS_ATENDIDA]);

        DB::table('appointments')
            ->whereIn('estado', ['Confirmada', 'Reprogramada'])
            ->update(['estado' => Appointment::STATUS_PROGRAMADA]);
    }

    public function down(): void
    {
        DB::table('appointments')
            ->where('estado', Appointment::STATUS_ATENDIDA)
            ->update(['estado' => 'Completada']);

        DB::table('appointments')
            ->where('estado', Appointment::STATUS_PROGRAMADA)
            ->update(['estado' => 'Confirmada']);
    }
};
