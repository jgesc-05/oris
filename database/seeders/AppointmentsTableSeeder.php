<?php

namespace Database\Seeders;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AppointmentsTableSeeder extends Seeder
{
    public function run(): void
    {
        $doctorTypeId = DB::table('user_types')->where('nombre', 'Médico')->value('id_tipo_usuario');
        $patientTypeId = DB::table('user_types')->where('nombre', 'Paciente')->value('id_tipo_usuario');
        $secretaryTypeId = DB::table('user_types')->where('nombre', 'Secretaria')->value('id_tipo_usuario');

        if (!$doctorTypeId || !$patientTypeId) {
            return;
        }

        $doctorIds = DB::table('users')->where('id_tipo_usuario', $doctorTypeId)->pluck('id_usuario')->toArray();
        $patientIds = DB::table('users')->where('id_tipo_usuario', $patientTypeId)->pluck('id_usuario')->toArray();
        $serviceIds = DB::table('services')->pluck('id_servicio')->toArray();
        $secretaryId = DB::table('users')->where('id_tipo_usuario', $secretaryTypeId)->value('id_usuario');

        if (empty($doctorIds) || empty($patientIds) || empty($serviceIds) || !$secretaryId) {
            return;
        }

        Schema::disableForeignKeyConstraints();
        DB::table('notifications')->truncate();
        DB::table('appointments')->truncate();
        Schema::enableForeignKeyConstraints();

        // Las citas de demostración se generan alrededor de la fecha actual para que
        // las agendas y los paneles siempre muestren datos al instalar el proyecto
        $today = Carbon::today();
        $startDate = $today->copy()->subDays(30);
        $endDate = $today->copy()->addDays(30);
        $holidays = collect();

        $timeRanges = [
            ['08:00:00', '12:00:00'],
            ['14:00:00', '18:00:00'],
        ];

        $allSlots = [];
        $dates = [];
        for ($cursor = $startDate->copy(); $cursor->lte($endDate); $cursor->addDay()) {
            $dates[] = $cursor->copy();
        }

        foreach ($doctorIds as $doctorId) {
            foreach ($dates as $date) {
                if ($date->isWeekend() || $holidays->contains($date->toDateString())) {
                    continue;
                }

                foreach ($timeRanges as [$from, $to]) {
                    $slotStart = Carbon::parse($date->toDateString() . ' ' . $from);
                    $slotEndBoundary = Carbon::parse($date->toDateString() . ' ' . $to);

                    while ($slotStart < $slotEndBoundary) {
                        $slotEnd = $slotStart->copy()->addMinutes(30);

                        if ($slotEnd > $slotEndBoundary) {
                            break;
                        }

                        $allSlots[] = [
                            'doctor_id' => $doctorId,
                            'start' => $slotStart->copy(),
                            'end' => $slotEnd->copy(),
                        ];

                        $slotStart = $slotStart->addMinutes(30);
                    }
                }
            }
        }

        $todaySlots = array_values(array_filter($allSlots, fn($slot) => $slot['start']->isSameDay($today)));
        $pastSlots = array_values(array_filter($allSlots, fn($slot) => $slot['start']->lt($today)));
        $futureSlots = array_values(array_filter($allSlots, fn($slot) => $slot['start']->gt($today->copy()->endOfDay())));
        shuffle($todaySlots);
        shuffle($pastSlots);
        shuffle($futureSlots);

        $selectedSlots = array_merge(
            array_slice($todaySlots, 0, 8),
            array_slice($pastSlots, 0, 30),
            array_slice($futureSlots, 0, 22)
        );

        $cancelReasons = [
            'Paciente solicitó cancelación',
            'Médico indispuesto',
            'Evento institucional',
        ];

        $appointments = [];
        $cutoff = Carbon::now();

        foreach ($selectedSlots as $slot) {
            $isPast = $slot['start']->lt($cutoff);
            // Proporción realista: la mayoría de citas pasadas se atendieron y la mayoría de futuras siguen programadas
            $cancelada = rand(1, 100) <= 15;
            $estado = $cancelada ? Appointment::STATUS_CANCELADA : ($isPast ? Appointment::STATUS_ATENDIDA : Appointment::STATUS_PROGRAMADA);
            $motivoCancelacion = $estado === Appointment::STATUS_CANCELADA ? Arr::random($cancelReasons) : null;
            $cancelUserId = $estado === Appointment::STATUS_CANCELADA ? $secretaryId : null;

            $appointments[] = [
                'id_usuario_paciente' => Arr::random($patientIds),
                'id_usuario_medico' => $slot['doctor_id'],
                'id_servicio' => Arr::random($serviceIds),
                'id_usuario_agenda' => $secretaryId,
                'id_usuario_cancela' => $cancelUserId,
                'fecha_hora_inicio' => $slot['start'],
                'fecha_hora_fin' => $slot['end'],
                'estado' => $estado,
                'notas' => $isPast ? 'Seguimiento realizado en sede principal.' : 'Generada automáticamente para demo.',
                'motivo_cancelacion' => $motivoCancelacion,
                'created_at' => $slot['start']->copy()->subDays(rand(3, 20)),
                'updated_at' => $slot['start']->copy()->subDays(rand(1, 2)),
            ];
        }

        DB::table('appointments')->insert($appointments);
    }
}
