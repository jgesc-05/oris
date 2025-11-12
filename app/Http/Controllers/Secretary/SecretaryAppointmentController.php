<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Specialty;
use App\Models\User;
use App\Models\DocumentType;
use App\Services\AppointmentAvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SecretaryAppointmentController extends Controller
{
    public function __construct(private AppointmentAvailabilityService $availability)
    {
    }

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
        $documentTypes = DocumentType::orderBy('name')->get();

        return view('secretaria.citas.index', [
            'action' => $action,
            'documentTypes' => $documentTypes,
        ]);
    }

    protected function handleLookup(Request $request, string $action)
    {
        $data = $request->validate([
            'id_tipo_documento' => ['required', 'string', 'max:3'],
            'numero_documento' => ['required', 'string', 'max:30'],
            'fecha_nacimiento' => ['required', 'date'],
        ]);

        $patient = User::where('id_tipo_documento', $data['id_tipo_documento'])
            ->where('numero_documento', $data['numero_documento'])
            ->where('fecha_nacimiento', $data['fecha_nacimiento'])
            ->whereHas('userType', fn($query) => $query->where('nombre', 'Paciente'))
            ->first();

        if (!$patient) {
            return back()
                ->withErrors(['error' => 'No se encontró un paciente con los datos proporcionados.'])
                ->withInput();
        }

        return match ($action) {
            'agendar' => redirect()->route('secretaria.citas.create.form', $patient->id_usuario),
            'reprogramar' => redirect()->route('secretaria.citas.reprogramar.seleccion', $patient->id_usuario),
            'cancelar' => redirect()->route('secretaria.citas.cancelar.list', $patient->id_usuario),
            default => back()->withErrors(['error' => 'Acción no válida.']),
        };
    }

    public function showCreateForm(User $patient)
    {
        $this->ensurePatient($patient);

        $specialties = Specialty::where('estado', 'activo')->orderBy('nombre')->get();
        $services = Service::with('tipoEspecialidad')
            ->where('estado', 'activo')
            ->orderBy('nombre')
            ->get();

        $servicesPayload = $services->map(fn($service) => [
            'id' => $service->id_servicio,
            'name' => $service->nombre,
            'specialty_id' => $service->id_tipos_especialidad,
        ])->values()->all();

        $doctors = $this->doctorQuery()
            ->with('doctor.tipoEspecialidad')
            ->orderBy('nombres')
            ->get();

        $doctorsPayload = $doctors->map(fn($doctor) => [
            'id' => $doctor->id_usuario,
            'nombres' => $doctor->nombres,
            'apellidos' => $doctor->apellidos,
            'specialty_id' => $doctor->doctor?->id_tipos_especialidad,
        ])->values()->all();

        $availabilityUrl = route('secretaria.citas.disponibilidad');

        return view('secretaria.citas.create', compact(
            'patient',
            'specialties',
            'services',
            'servicesPayload',
            'doctors',
            'doctorsPayload',
            'availabilityUrl'
        ));
    }

    public function storeAppointment(Request $request, User $patient)
    {
        $this->ensurePatient($patient);

        $validated = $request->validate([
            'id_tipos_especialidad' => ['required', 'exists:specialty_type,id_tipos_especialidad'],
            'id_servicio' => ['required', 'exists:services,id_servicio'],
            'id_usuario_medico' => ['required', 'exists:users,id_usuario'],
            'fecha' => ['required', 'date', 'after_or_equal:today'],
            'hora' => ['required', 'date_format:H:i', Rule::in($this->availability->allowedTimeSlots())],
            'notas' => ['nullable', 'string', 'max:500'],
        ]);

        $service = Service::where('estado', 'activo')
            ->where('id_tipos_especialidad', $validated['id_tipos_especialidad'])
            ->findOrFail($validated['id_servicio']);

        $doctor = $this->doctorQuery()
            ->where('id_usuario', $validated['id_usuario_medico'])
            ->firstOrFail();

        $start = Carbon::parse($validated['fecha'] . ' ' . $validated['hora'])->seconds(0);
        $end = $start->copy()->addMinutes(30);

        $this->validateScheduleWindow($start);
        $this->ensureSlotIsAvailable($doctor->id_usuario, $start, $end);

        $appointment = Appointment::create([
            'id_usuario_paciente' => $patient->id_usuario,
            'id_usuario_medico' => $doctor->id_usuario,
            'id_servicio' => $service->id_servicio,
            'id_usuario_agenda' => Auth::user()?->id_usuario,
            'fecha_hora_inicio' => $start,
            'fecha_hora_fin' => $end,
            'estado' => Appointment::STATUS_PROGRAMADA,
            'notas' => $validated['notas'] ?? null,
        ]);

        return redirect()->route('secretaria.citas.confirmada', $appointment->id_cita);
    }

    public function showAppointmentConfirmation(Appointment $appointment)
    {
        $appointment->loadMissing(['paciente', 'medico', 'servicio']);

        return view('secretaria.citas.confirmada', compact('appointment'));
    }

    public function showReprogramSelection(User $patient)
    {
        $this->ensurePatient($patient);

        $appointments = $this->appointmentsFor($patient)
            ->where('estado', '<>', Appointment::STATUS_CANCELADA)
            ->where('fecha_hora_inicio', '>=', now())
            ->orderBy('fecha_hora_inicio')
            ->get();

        return view('secretaria.citas.reprogramar.seleccion', compact('patient', 'appointments'));
    }

    public function submitReprogramSelection(Request $request, User $patient)
    {
        $this->ensurePatient($patient);

        $data = $request->validate([
            'cita_id' => ['required', 'integer', 'exists:appointments,id_cita'],
        ]);

        $appointmentExists = $this->appointmentsFor($patient)
            ->where('id_cita', $data['cita_id'])
            ->exists();

        if (!$appointmentExists) {
            return back()->withErrors(['error' => 'La cita seleccionada no pertenece al paciente.']);
        }

        return redirect()->route('secretaria.citas.reprogramar.edit', [$patient->id_usuario, $data['cita_id']]);
    }

    public function editReprogram(User $patient, Appointment $appointment)
    {
        $this->ensurePatient($patient);
        $this->ensureAppointmentBelongsTo($appointment, $patient);

        if ($appointment->estado === Appointment::STATUS_CANCELADA || $appointment->fecha_hora_inicio->lt(now())) {
            return redirect()
                ->route('secretaria.citas.reprogramar.seleccion', $patient->id_usuario)
                ->withErrors('La cita seleccionada no puede reprogramarse.');
        }

        $specialties = Specialty::where('estado', 'activo')->orderBy('nombre')->get();
        $services = Service::with('tipoEspecialidad')
            ->where('estado', 'activo')
            ->orderBy('nombre')
            ->get();

        $servicesPayload = $services->map(fn($service) => [
            'id' => $service->id_servicio,
            'name' => $service->nombre,
            'specialty_id' => $service->id_tipos_especialidad,
        ])->values()->all();

        $doctors = $this->doctorQuery()
            ->with('doctor.tipoEspecialidad')
            ->orderBy('nombres')
            ->get();

        $doctorsPayload = $doctors->map(fn($doctor) => [
            'id' => $doctor->id_usuario,
            'nombres' => $doctor->nombres,
            'apellidos' => $doctor->apellidos,
            'specialty_id' => $doctor->doctor?->id_tipos_especialidad,
        ])->values()->all();

        $availabilityUrl = route('secretaria.citas.disponibilidad');
        $initialSlots = $this->availability->slotsForDoctorBetween(
            $appointment->id_usuario_medico,
            now()->startOfDay(),
            now()->copy()->addMonth()->endOfDay(),
            $appointment->id_cita
        );

        return view('secretaria.citas.reprogramar.edit', compact(
            'patient',
            'appointment',
            'specialties',
            'services',
            'servicesPayload',
            'doctors',
            'doctorsPayload',
            'availabilityUrl',
            'initialSlots'
        ));
    }

    public function updateReprogram(Request $request, User $patient, Appointment $appointment)
    {
        $this->ensurePatient($patient);
        $this->ensureAppointmentBelongsTo($appointment, $patient);

        if ($appointment->estado === Appointment::STATUS_CANCELADA) {
            return back()->withErrors(['error' => 'La cita ya fue cancelada.']);
        }

        $validated = $request->validate([
            'id_tipos_especialidad' => ['required', 'exists:specialty_type,id_tipos_especialidad'],
            'id_servicio' => ['required', 'exists:services,id_servicio'],
            'id_usuario_medico' => ['required', 'exists:users,id_usuario'],
            'fecha' => ['required', 'date', 'after_or_equal:today'],
            'hora' => ['required', 'date_format:H:i', Rule::in($this->availability->allowedTimeSlots())],
            'notas' => ['nullable', 'string', 'max:500'],
        ]);

        $service = Service::where('estado', 'activo')
            ->where('id_tipos_especialidad', $validated['id_tipos_especialidad'])
            ->findOrFail($validated['id_servicio']);

        $doctor = $this->doctorQuery()
            ->where('id_usuario', $validated['id_usuario_medico'])
            ->firstOrFail();

        $start = Carbon::parse($validated['fecha'] . ' ' . $validated['hora'])->seconds(0);
        $end = $start->copy()->addMinutes(30);

        $this->validateScheduleWindow($start);
        $this->ensureSlotIsAvailable($doctor->id_usuario, $start, $end, $appointment->id_cita);

        $appointment->update([
            'id_usuario_medico' => $doctor->id_usuario,
            'id_servicio' => $service->id_servicio,
            'fecha_hora_inicio' => $start,
            'fecha_hora_fin' => $end,
            'estado' => Appointment::STATUS_PROGRAMADA,
            'notas' => $validated['notas'] ?? $appointment->notas,
            'id_usuario_agenda' => Auth::user()?->id_usuario,
            'id_usuario_cancela' => null,
            'motivo_cancelacion' => null,
        ]);

        return redirect()->route('secretaria.citas.reprogramar.confirmada', $appointment->id_cita);
    }

    public function showReprogramConfirmation(Appointment $appointment)
    {
        $appointment->loadMissing(['paciente', 'servicio', 'medico']);

        return view('secretaria.citas.reprogramar.confirmada', compact('appointment'));
    }

    public function showCancelList(User $patient)
    {
        $this->ensurePatient($patient);

        $appointments = $this->appointmentsFor($patient)
            ->where('estado', '<>', Appointment::STATUS_CANCELADA)
            ->where('fecha_hora_inicio', '>=', now())
            ->orderBy('fecha_hora_inicio')
            ->get();

        return view('secretaria.citas.cancelar.index', compact('patient', 'appointments'));
    }

    public function cancelAppointment(Request $request, User $patient)
    {
        $this->ensurePatient($patient);

        $data = $request->validate([
            'cita_id' => ['required', 'integer', 'exists:appointments,id_cita'],
            'motivo' => ['nullable', 'string', 'max:255'],
        ]);

        $appointment = $this->appointmentsFor($patient)
            ->where('estado', '<>', Appointment::STATUS_CANCELADA)
            ->where('fecha_hora_inicio', '>', now())
            ->findOrFail($data['cita_id']);

        $appointment->update([
            'estado' => Appointment::STATUS_CANCELADA,
            'id_usuario_cancela' => Auth::user()?->id_usuario,
            'motivo_cancelacion' => $data['motivo'] ?? 'Cancelada por secretaría',
        ]);

        $appointment->loadMissing(['paciente', 'medico', 'servicio']);

        return view('secretaria.citas.cancelar.cancelacion', compact('patient', 'appointment'));
    }

    public function disponibilidad(Request $request)
    {
        $data = $request->validate([
            'id_usuario_medico' => ['required', 'exists:users,id_usuario'],
            'cita_id' => ['nullable', 'integer', 'exists:appointments,id_cita'],
        ]);

        $doctor = $this->doctorQuery()
            ->where('id_usuario', $data['id_usuario_medico'])
            ->firstOrFail();

        $ignoreAppointmentId = $data['cita_id'] ?? null;

        $slots = $this->availability->slotsForDoctorBetween(
            $doctor->id_usuario,
            now()->startOfDay(),
            now()->copy()->addMonth()->endOfDay(),
            $ignoreAppointmentId
        );

        return response()->json(['slots' => $slots]);
    }

    protected function ensurePatient(User $patient): User
    {
        if ($patient->userType?->nombre !== 'Paciente') {
            abort(404);
        }

        return $patient;
    }

    protected function ensureAppointmentBelongsTo(Appointment $appointment, User $patient): void
    {
        if ($appointment->id_usuario_paciente !== $patient->id_usuario) {
            abort(404);
        }
    }

    protected function doctorQuery()
    {
        return User::whereHas('userType', fn($query) => $query->where('nombre', 'Médico'));
    }

    protected function appointmentsFor(User $patient)
    {
        return Appointment::with(['medico', 'servicio'])
            ->where('id_usuario_paciente', $patient->id_usuario);
    }

    protected function validateScheduleWindow(Carbon $start): void
    {
        if ($start->lt(now())) {
            throw ValidationException::withMessages([
                'fecha' => 'No puedes seleccionar una fecha u hora en el pasado.',
            ]);
        }

        if ($start->gt(now()->copy()->addMonth())) {
            throw ValidationException::withMessages([
                'fecha' => 'Solo puedes agendar dentro del próximo mes.',
            ]);
        }
    }

    protected function ensureSlotIsAvailable(int $doctorId, Carbon $start, Carbon $end, ?int $ignoreId = null): void
    {
        if (!$this->availability->slotIsAvailable($doctorId, $start, $end, $ignoreId)) {
            throw ValidationException::withMessages([
                'hora' => 'El horario seleccionado ya no está disponible. Por favor elige otro.',
            ]);
        }
    }
}
