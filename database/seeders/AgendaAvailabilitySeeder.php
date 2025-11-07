<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AgendaAvailabilitySeeder extends Seeder
{
    public function run(): void
    {
        $medicoTypeId = DB::table('user_types')
            ->where('nombre', 'Médico')
            ->value('id_tipo_usuario');

        if (!$medicoTypeId) {
            return;
        }

        $doctorIds = DB::table('users')
            ->where('id_tipo_usuario', $medicoTypeId)
            ->pluck('id_usuario');

        if ($doctorIds->isEmpty()) {
            return;
        }

        DB::table('agenda_availability')->truncate();

        $startDate = Carbon::today();
        $endDate = $startDate->copy()->addMonth();

        $daysOfWeek = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

        $timeSlots = [
            ['08:00:00', '12:00:00'],
            ['14:00:00', '18:00:00'],
        ];

        $records = [];

        foreach ($doctorIds as $doctorId) {
            foreach ($daysOfWeek as $dayName) {
                foreach ($timeSlots as [$horaInicio, $horaFin]) {
                    $records[] = [
                        'hora_inicio' => $horaInicio,
                        'hora_fin' => $horaFin,
                        'dia_semana' => $dayName,
                        'vigencia_desde' => $startDate->toDateString(),
                        'vigencia_hasta' => $endDate->toDateString(),
                        'id_usuario_odontologo' => $doctorId,
                    ];
                }
            }
        }

        DB::table('agenda_availability')->insert($records);
    }
}
