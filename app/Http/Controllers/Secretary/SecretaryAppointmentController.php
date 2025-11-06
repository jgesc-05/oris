<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SecretaryAppointmentController extends Controller
{
    public function showAgendarLookup()
    {
        return $this->showLookupForm('agendar');
    }

    public function submitAgendarLookup(Request $request)
    {
        return $this->handleLookup($request, 'agendar');
    }

    public function showReprogramarLookup()
    {
        return $this->showLookupForm('reprogramar');
    }

    public function submitReprogramarLookup(Request $request)
    {
        return $this->handleLookup($request, 'reprogramar');
    }

    public function showCancelarLookup()
    {
        return $this->showLookupForm('cancelar');
    }

    public function submitCancelarLookup(Request $request)
    {
        return $this->handleLookup($request, 'cancelar');
    }

    protected function showLookupForm(string $action)
    {
        return view('secretaria.citas.index', [
            'action' => $action,
        ]);
    }

    protected function handleLookup(Request $request, string $action)
    {
        $data = $request->validate([
            'id_tipo_documento' => ['required', 'string', 'max:3'],
            'numero_documento'  => ['required', 'string', 'max:30'],
            'fecha_nacimiento'  => ['required', 'date'],
        ]);

        $patient = User::where('id_tipo_documento', $data['id_tipo_documento'])
            ->where('numero_documento', $data['numero_documento'])
            ->where('fecha_nacimiento', $data['fecha_nacimiento'])
            ->whereHas('userType', fn ($query) => $query->where('nombre', 'Paciente'))
            ->first();

        if (!$patient) {
            return back()->withErrors(['error' => 'No se encontró un paciente con los datos proporcionados.'])->withInput();
        }

        switch ($action) {
            case 'agendar':
                return redirect()->route('secretaria.citas.create.form', $patient->id_usuario);
            case 'reprogramar':
                return redirect()->route('secretaria.citas.reprogramar.seleccion', $patient->id_usuario);
            case 'cancelar':
                return redirect()->route('secretaria.citas.cancelar.list', $patient->id_usuario);
        }

        return back()->withErrors(['error' => 'Acción no válida.']);
    }

    public function showCreateForm(User $patient)
    {
        $timeSlots = $this->timeSlots();

        return view('secretaria.citas.create', compact('patient', 'timeSlots'));
    }

    public function storeAppointment(Request $request, User $patient)
    {
        $data = $request->validate([
            'especialidad' => ['required', 'string', 'max:100'],
            'servicio'     => ['required', 'string', 'max:150'],
            'fecha'        => ['required', 'date', 'after_or_equal:today'],
            'hora'         => ['required', 'date_format:H:i', 'regex:/^(?:[01]\d|2[0-3]):(?:00|30)$/'],
            'medico'       => ['required', 'string', 'max:150'],
        ]);

        $appointment = [
            'paciente'   => "{$patient->nombres} {$patient->apellidos}",
            'fecha_hora' => Carbon::parse($data['fecha'] . ' ' . $data['hora'])->translatedFormat('l j \\d\\e F, g:i A'),
            'doctor'     => $data['medico'],
            'servicio'   => $data['servicio'],
            'especialidad' => $data['especialidad'],
            'referencia' => 'CITA-' . now()->year . '-' . rand(100000, 999999),
        ];

        return view('secretaria.citas.confirmada', compact('appointment'));
    }

    public function showReprogramSelection(User $patient)
    {
        $appointments = $this->mockAppointments($patient);

        return view('secretaria.citas.reprogramar.seleccion', compact('patient', 'appointments'));
    }

    public function submitReprogramSelection(Request $request, User $patient)
    {
        $data = $request->validate([
            'cita_id' => ['required'],
        ]);

        return redirect()->route('secretaria.citas.reprogramar.edit', [$patient->id_usuario, $data['cita_id']]);
    }

    public function editReprogram(User $patient, string $citaId)
    {
        $appointment = collect($this->mockAppointments($patient))->firstWhere('id', $citaId);

        if (!$appointment) {
            abort(404);
        }

        $especialidades = ['Medicina general', 'Pediatría', 'Cardiología'];
        $servicios = ['Consulta general', 'Chequeo preventivo', 'Exámenes especializados'];
        $medicos = ['Dr. Andrés Salazar', 'Dra. Laura Hernández', 'Dra. Catalina Díaz'];
        $horas = $this->timeSlots();

        return view('secretaria.citas.reprogramar.edit', compact(
            'patient', 'appointment', 'especialidades', 'servicios', 'medicos', 'horas'
        ));
    }

    public function updateReprogram(Request $request, User $patient, string $citaId)
    {
        $data = $request->validate([
            'especialidad' => ['required', 'string', 'max:100'],
            'servicio'     => ['required', 'string', 'max:150'],
            'medico'       => ['required', 'string', 'max:150'],
            'fecha'        => ['required', 'date', 'after_or_equal:today'],
            'hora'         => ['required', 'date_format:H:i', 'regex:/^(?:[01]\d|2[0-3]):(?:00|30)$/'],
        ]);

        $appointment = [
            'paciente'   => "{$patient->nombres} {$patient->apellidos}",
            'fecha_hora' => Carbon::parse($data['fecha'] . ' ' . $data['hora'])->translatedFormat('l j \\d\\e F, g:i A'),
            'doctor'     => $data['medico'],
            'servicio'   => $data['servicio'],
            'referencia' => 'CITA-' . now()->year . '-' . rand(100000, 999999),
        ];

        return view('secretaria.citas.reprogramar.confirmada', compact('appointment'));
    }

    public function showCancelList(User $patient)
    {
        $appointments = $this->mockAppointments($patient);

        return view('secretaria.citas.cancelar.index', compact('patient', 'appointments'));
    }

    public function cancelAppointment(Request $request, User $patient)
    {
        $data = $request->validate([
            'cita_id' => ['required'],
        ]);

        $appointment = collect($this->mockAppointments($patient))->firstWhere('id', $data['cita_id']);

        if (!$appointment) {
            return back()->withErrors(['error' => 'No se encontró la cita seleccionada.']);
        }

        return view('secretaria.citas.cancelar.cancelacion', compact('patient', 'appointment'));
    }

    protected function timeSlots(): array
    {
        $slots = [];
        foreach (range(7, 20) as $hour) {
            foreach ([0, 30] as $minute) {
                $slots[] = sprintf('%02d:%02d', $hour, $minute);
            }
        }

        return $slots;
    }

    protected function mockAppointments(User $patient): array
    {
        return [
            [
                'id'        => 'APT-101',
                'fecha'     => '2025-10-20',
                'hora'      => '08:00',
                'hora_humana' => '08:00 AM',
                'servicio'  => 'Consulta general',
                'medico'    => 'Dr. Andrés Salazar',
                'estado'    => 'Confirmada',
            ],
            [
                'id'        => 'APT-102',
                'fecha'     => '2025-10-23',
                'hora'      => '10:30',
                'hora_humana' => '10:30 AM',
                'servicio'  => 'Chequeo preventivo',
                'medico'    => 'Dra. Laura Hernández',
                'estado'    => 'Confirmada',
            ],
        ];
    }
}
