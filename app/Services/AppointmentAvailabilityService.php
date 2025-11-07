<?php

namespace App\Services;

use App\Models\AgendaAvailability;
use App\Models\Appointment;
use App\Models\ScheduleBlock;
use Carbon\Carbon;

class AppointmentAvailabilityService
{
    /**
     * Retorna los horarios válidos (cada 30 min) para los formularios.
     */
    public function allowedTimeSlots(): array
    {
        return collect([
            ['start' => '08:00', 'end' => '12:00'],
            ['start' => '14:00', 'end' => '18:00'],
        ])->flatMap(function (array $range) {
            $slots = [];
            $cursor = Carbon::createFromFormat('H:i', $range['start']);
            $end = Carbon::createFromFormat('H:i', $range['end']);

            while ($cursor < $end) {
                $slots[] = $cursor->format('H:i');
                $cursor->addMinutes(30);
            }

            return $slots;
        })->toArray();
    }

    public function slotIsAvailable(int $doctorId, Carbon $start, Carbon $end, ?int $ignoreAppointmentId = null): bool
    {
        if (!$this->matchesAvailability($doctorId, $start, $end)) {
            return false;
        }

        if ($this->hasAppointmentOverlap($doctorId, $start, $end, $ignoreAppointmentId)) {
            return false;
        }

        if ($this->hasScheduleBlockOverlap($doctorId, $start, $end)) {
            return false;
        }

        return true;
    }

    protected function matchesAvailability(int $doctorId, Carbon $start, Carbon $end): bool
    {
        $dayName = $this->mapDayName($start->dayName);

        if (!$dayName) {
            return false;
        }

        return AgendaAvailability::where('id_usuario_odontologo', $doctorId)
            ->where('dia_semana', $dayName)
            ->where('vigencia_desde', '<=', $start->toDateString())
            ->where('vigencia_hasta', '>=', $start->toDateString())
            ->where('hora_inicio', '<=', $start->format('H:i:s'))
            ->where('hora_fin', '>=', $end->format('H:i:s'))
            ->exists();
    }

    protected function hasAppointmentOverlap(int $doctorId, Carbon $start, Carbon $end, ?int $ignoreAppointmentId = null): bool
    {
        return Appointment::where('id_usuario_medico', $doctorId)
            ->when($ignoreAppointmentId, fn ($query) => $query->where('id_cita', '<>', $ignoreAppointmentId))
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('fecha_hora_inicio', [$start, $end->copy()->subMinutes(1)])
                    ->orWhereBetween('fecha_hora_fin', [$start->copy()->addMinutes(1), $end])
                    ->orWhere(function ($query) use ($start, $end) {
                        $query->where('fecha_hora_inicio', '<=', $start)
                            ->where('fecha_hora_fin', '>=', $end);
                    });
            })
            ->exists();
    }

    protected function hasScheduleBlockOverlap(int $doctorId, Carbon $start, Carbon $end): bool
    {
        return ScheduleBlock::where('medico_id', $doctorId)
            ->where('fecha', $start->toDateString())
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('hora_desde', [$start->format('H:i:s'), $end->format('H:i:s')])
                    ->orWhereBetween('hora_hasta', [$start->format('H:i:s'), $end->format('H:i:s')])
                    ->orWhere(function ($sub) use ($start, $end) {
                        $sub->where('hora_desde', '<=', $start->format('H:i:s'))
                            ->where('hora_hasta', '>=', $end->format('H:i:s'));
                    });
            })
            ->exists();
    }

    public function slotsForDoctorBetween(int $doctorId, Carbon $startDate, Carbon $endDate, ?int $ignoreAppointmentId = null): array
    {
        $startDate = $startDate->copy()->startOfDay();
        $endDate = $endDate->copy()->endOfDay();

        $slotsPerDate = [];
        $timeSlots = $this->allowedTimeSlots();

        for ($cursor = $startDate->copy(); $cursor->lte($endDate); $cursor->addDay()) {
            $times = [];

            foreach ($timeSlots as $time) {
                $slotStart = Carbon::parse($cursor->toDateString().' '.$time)->seconds(0);
                $slotEnd = $slotStart->copy()->addMinutes(30);

                if ($slotStart->lt($startDate) || $slotEnd->gt($endDate)) {
                    continue;
                }

                if ($this->slotIsAvailable($doctorId, $slotStart, $slotEnd, $ignoreAppointmentId)) {
                    $times[] = [
                        'value' => $slotStart->format('H:i'),
                        'label' => $slotStart->format('h:i A'),
                    ];
                }
            }

            if (!empty($times)) {
                $slotsPerDate[] = [
                    'date' => $cursor->toDateString(),
                    'label' => $cursor->locale('es')->translatedFormat('l j \\d\\e F'),
                    'times' => $times,
                ];
            }
        }

        return $slotsPerDate;
    }

    protected function mapDayName(string $dayName): ?string
    {
        return [
            'Monday' => 'Lunes',
            'Tuesday' => 'Martes',
            'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves',
            'Friday' => 'Viernes',
        ][$dayName] ?? null;
    }
}
