<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('notifications')->truncate();
        DB::table('appointments')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $referenceYear = 2025;

        $startDate = Carbon::create($referenceYear, 11, 1);
        $endDate = Carbon::create($referenceYear, 12, 15);
        $holidays = collect([
            Carbon::create($referenceYear, 11, 4)->toDateString(), // Traslado Día de Todos los Santos
            Carbon::create($referenceYear, 11, 11)->toDateString(), // Independencia de Cartagena
            Carbon::create($referenceYear, 12, 8)->toDateString(), // Inmaculada Concepción
        ]);

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
                    $slotStart = Carbon::parse($date->toDateString().' '.$from);
                    $slotEndBoundary = Carbon::parse($date->toDateString().' '.$to);

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

        $novemberSlots = array_values(array_filter($allSlots, fn ($slot) => $slot['start']->month === 11));
        $decemberSlots = array_values(array_filter($allSlots, fn ($slot) => $slot['start']->month === 12));
        shuffle($novemberSlots);
        shuffle($decemberSlots);

        $selectedSlots = array_merge(
            array_slice($novemberSlots, 0, 35),
            array_slice($decemberSlots, 0, 15)
        );

        if (count($selectedSlots) < 50) {
            $remaining = array_slice($novemberSlots, 35);
            $selectedSlots = array_merge($selectedSlots, array_slice($remaining, 0, 50 - count($selectedSlots)));
        }

        $statusPast = ['Cumplida', 'Cancelada'];
        $statusFuture = ['Programada', 'Confirmada', 'Cancelada'];
        $cancelReasons = [
            'Paciente solicitó cancelación',
            'Médico indispuesto',
            'Evento institucional',
        ];

        $appointments = [];
        $cutoff = Carbon::create(2024, 11, 6)->startOfDay();

        foreach (array_slice($selectedSlots, 0, 50) as $slot) {
            $isPast = $slot['start']->lt($cutoff);
            $estado = $isPast ? Arr::random($statusPast) : Arr::random($statusFuture);
            $motivoCancelacion = $estado === 'Cancelada' ? Arr::random($cancelReasons) : null;
            $cancelUserId = $estado === 'Cancelada' ? $secretaryId : null;

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
