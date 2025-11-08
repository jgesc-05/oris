<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use App\Models\ScheduleBlock;
use App\Models\User;
use App\Services\AppointmentAvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SecretaryScheduleController extends Controller
{
    public function __construct(private AppointmentAvailabilityService $availability)
    {
    }

    public function showBlockForm(Request $request)
    {
        $medicos = User::whereHas('userType', function ($query) {
                $query->where('nombre', 'Médico');
            })
            ->orderBy('nombres')
            ->orderBy('apellidos')
            ->get(['id_usuario', 'nombres', 'apellidos']);

        $blocks = ScheduleBlock::with('medico')
            ->orderByDesc('fecha')
            ->orderByDesc('hora_desde')
            ->limit(15)
            ->get();

        $availabilityUrl = route('secretaria.citas.disponibilidad');

        return view('secretaria.horarios.bloquear', compact('medicos', 'blocks', 'availabilityUrl'));
    }

    public function storeBlock(Request $request)
    {
        $startSlots = $this->availability->allowedTimeSlots();
        $endSlots = $this->buildEndSlots($startSlots);

        $data = $request->validate([
            'medico_id'  => ['required', 'integer'],
            'fecha'      => ['required', 'date', 'after_or_equal:today'],
            'hora_desde' => ['required', 'date_format:H:i', Rule::in($startSlots)],
            'hora_hasta' => ['required', 'date_format:H:i', Rule::in($endSlots), 'after:hora_desde'],
            'motivo'     => ['nullable', 'string', 'max:255'],
        ]);

        $medico = User::where('id_usuario', $data['medico_id'])
            ->whereHas('userType', fn ($query) => $query->where('nombre', 'Médico'))
            ->firstOrFail();

        $start = Carbon::createFromFormat('Y-m-d H:i', $data['fecha'].' '.$data['hora_desde'])->seconds(0);
        $end = Carbon::createFromFormat('Y-m-d H:i', $data['fecha'].' '.$data['hora_hasta'])->seconds(0);

        if (!$this->availability->fitsClinicSchedule($start, $end)) {
            throw ValidationException::withMessages([
                'hora_desde' => 'El rango seleccionado está fuera del horario laboral de la clínica.',
            ]);
        }

        if (!$this->availability->doctorIsWorking($medico->id_usuario, $start, $end)) {
            throw ValidationException::withMessages([
                'hora_desde' => 'El médico no tiene disponibilidad activa en ese horario.',
            ]);
        }

        if (!$this->availability->slotIsAvailable($medico->id_usuario, $start, $end)) {
            throw ValidationException::withMessages([
                'hora_desde' => 'Ya existe una cita o bloqueo para ese rango horario.',
            ]);
        }

        ScheduleBlock::create([
            'medico_id'  => $medico->id_usuario,
            'fecha'      => $start->toDateString(),
            'hora_desde' => $start->format('H:i:s'),
            'hora_hasta' => $end->format('H:i:s'),
            'motivo'     => $data['motivo'] ?? null,
            'created_by' => $request->user()?->id_usuario,
        ]);

        return redirect()
            ->route('secretaria.horarios.bloquear')
            ->with('status', 'Horario bloqueado correctamente.');
    }

    protected function buildEndSlots(array $startSlots): array
    {
        return collect($startSlots)
            ->map(fn ($slot) => Carbon::createFromFormat('H:i', $slot)->addMinutes(30)->format('H:i'))
            ->unique()
            ->values()
            ->all();
    }
}
