<?php

namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Specialty;
use App\Models\User;
use App\Services\AppointmentAvailabilityService;

class PatientPortalController extends Controller
{
    public function __construct(private AppointmentAvailabilityService $availability)
    {
    }
    protected function patient()
    {
        return Auth::guard('paciente')->user();
    }

    protected function doctorQuery()
    {
        return User::whereHas('userType', fn($query) => $query->where('nombre', 'Médico'));
    }

    protected function patientAppointmentsQuery()
    {
        $patient = $this->patient();

        return Appointment::with(['medico', 'servicio'])
            ->where('id_usuario_paciente', $patient?->id_usuario);
    }

    public function inicio()
    {
        $patient = $this->patient();

        $nextAppointment = $this->patientAppointmentsQuery()
            ->where('estado', '<>', Appointment::STATUS_CANCELADA)
            ->where('fecha_hora_inicio', '>=', now())
            ->orderBy('fecha_hora_inicio')
            ->first();

        $recentAppointments = $this->patientAppointmentsQuery()
            ->orderByDesc('fecha_hora_inicio')
            ->limit(5)
            ->get();

        return view('paciente.dashboard', [
            'patient' => $patient,
            'nextAppointment' => $nextAppointment,
            'recentAppointments' => $recentAppointments,
        ]);
    }

    public function servicios()
    {
        $patient = Auth::guard('paciente')->user();

        $especialidades = [
            ['nombre' => 'Medicina general', 'descripcion' => 'Atención primaria y chequeos preventivos.', 'icono' => '🩺'],
            ['nombre' => 'Pediatría', 'descripcion' => 'Salud y desarrollo infantil.', 'icono' => '👶'],
            ['nombre' => 'Cardiología', 'descripcion' => 'Enfermedades del corazón y circulación.', 'icono' => '❤️'],
            ['nombre' => 'Dermatología', 'descripcion' => 'Cuidado de la piel, cabello y uñas.', 'icono' => '🧴'],
            ['nombre' => 'Ginecología', 'descripcion' => 'Salud reproductiva y atención femenina.', 'icono' => '🌸'],
            ['nombre' => 'Neurología', 'descripcion' => 'Trastornos del sistema nervioso.', 'icono' => '🧠'],
            ['nombre' => 'Oftalmología', 'descripcion' => 'Cuidado de los ojos y la visión.', 'icono' => '👁️'],
            ['nombre' => 'Traumatología', 'descripcion' => 'Lesiones musculares y óseas.', 'icono' => '🦵'],
            ['nombre' => 'Psiquiatría', 'descripcion' => 'Salud mental y emocional.', 'icono' => '🧘'],
            ['nombre' => 'Endocrinología', 'descripcion' => 'Trastornos hormonales y metabólicos.', 'icono' => '🧬'],
            ['nombre' => 'Rehabilitación física', 'descripcion' => 'Recuperación funcional y motora.', 'icono' => '🏃‍♂️'],
        ];

        $especialidades = collect($especialidades)->map(function (array $especialidad) {
            $especialidad['slug'] = Str::slug($especialidad['nombre']);
            return $especialidad;
        })->toArray();

        return view('paciente.servicios.index', [
            'patient' => $patient,
            'especialidades' => $especialidades,
        ]);
    }

    public function serviciosEspecialidad(string $slug)
    {
        $nombre = Str::title(str_replace('-', ' ', $slug));

        $especialidad = [
            'nombre' => $nombre,
            'slug' => $slug,
        ];

        $servicios = [
            ['nombre' => 'Consulta general', 'descripcion' => 'Evaluación médica completa y diagnóstico inicial.', 'icono' => '🩺'],
            ['nombre' => 'Chequeo preventivo', 'descripcion' => 'Revisión periódica para detectar factores de riesgo.', 'icono' => '📋'],
            ['nombre' => 'Atención de urgencias leves', 'descripcion' => 'Atención rápida a emergencias menores.', 'icono' => '🚑'],
            ['nombre' => 'Exámenes especializados', 'descripcion' => 'Pruebas médicas según indicaciones clínicas.', 'icono' => '🧪'],
        ];

        $servicios = collect($servicios)->map(function (array $servicio) use ($slug) {
            $servicio['slug'] = Str::slug($servicio['nombre']);
            $servicio['especialidad_slug'] = $slug;
            return $servicio;
        })->toArray();

        return view('paciente.servicios.especialidad', compact('especialidad', 'servicios'));
    }


    public function medicos()
    {
        $patient = Auth::guard('paciente')->user();

        $especialidades = [
            ['nombre' => 'Medicina general', 'descripcion' => 'Seguimiento integral del estado de salud.', 'icono' => '🩺'],
            ['nombre' => 'Pediatría', 'descripcion' => 'Atención especializada para niños y niñas.', 'icono' => '👶'],
            ['nombre' => 'Cardiología', 'descripcion' => 'Tratamiento de enfermedades del corazón.', 'icono' => '❤️'],
            ['nombre' => 'Dermatología', 'descripcion' => 'Cuidado de la piel, cabello y uñas.', 'icono' => '🧴'],
            ['nombre' => 'Neurología', 'descripcion' => 'Trastornos del sistema nervioso.', 'icono' => '🧠'],
            ['nombre' => 'Rehabilitación física', 'descripcion' => 'Recuperación de la movilidad y funcionalidad.', 'icono' => '🏃‍♀️'],
        ];

        $especialidades = collect($especialidades)->map(function (array $especialidad) {
            $especialidad['slug'] = Str::slug($especialidad['nombre']);
            return $especialidad;
        })->toArray();

        return view('paciente.medicos.index', [
            'patient' => $patient,
            'especialidades' => $especialidades,
        ]);
    }

    public function medicosEspecialidad(string $slug)
    {
        $patient = Auth::guard('paciente')->user();

        $especialidad = [
            'nombre' => Str::title(str_replace('-', ' ', $slug)),
            'slug' => $slug,
        ];

        $medicos = [
            [
                'nombre' => 'Dra. Laura Hernández',
                'descripcion' => 'Especialista en atención preventiva y control de enfermedades crónicas.',
                'formacion' => 'Médico cirujano — Universidad Nacional',
                'experiencia' => '10 años',
                'disponibilidad' => 'Lunes a viernes — 8:00 a.m. - 4:00 p.m.',
            ],
            [
                'nombre' => 'Dr. Andrés Salazar',
                'descripcion' => 'Enfoque en diagnóstico temprano y medicina familiar.',
                'formacion' => 'Especialista en Medicina Familiar — Universidad Javeriana',
                'experiencia' => '8 años',
                'disponibilidad' => 'Martes y jueves — 10:00 a.m. - 6:00 p.m.',
            ],
            [
                'nombre' => 'Dra. Catalina Díaz',
                'descripcion' => 'Atención integral a pacientes con condiciones crónicas.',
                'formacion' => 'Medicina interna — Universidad de los Andes',
                'experiencia' => '12 años',
                'disponibilidad' => 'Miércoles y sábado — 9:00 a.m. - 2:00 p.m.',
            ],
        ];

        $medicos = collect($medicos)->map(function (array $medico) use ($slug) {
            $medico['slug'] = Str::slug($medico['nombre']);
            $medico['especialidad_slug'] = $slug;
            return $medico;
        })->toArray();

        return view('paciente.medicos.especialidad', compact('patient', 'especialidad', 'medicos'));
    }

    public function medicosDetalle(string $especialidad, string $medico)
    {
        $patient = Auth::guard('paciente')->user();

        $medicoDetalle = [
            'nombre' => Str::title(str_replace('-', ' ', $medico)),
            'especialidad' => Str::title(str_replace('-', ' ', $especialidad)),
            'especialidad_slug' => $especialidad,
            'descripcion' => 'Profesional con un enfoque humano y preventivo, acompañando procesos de diagnóstico y tratamiento.',
            'formacion' => 'Médico cirujano — Universidad Nacional, especialización en Medicina interna.',
            'experiencia' => 'Más de 10 años en consulta externa y hospitalaria.',
            'disponibilidad' => 'Lunes a viernes — 8:00 a.m. - 4:00 p.m.',
            'icono' => '👨‍⚕️',
        ];

        return view('paciente.medicos.detalle', [
            'patient' => $patient,
            'medico' => $medicoDetalle,
        ]);
    }

    public function citasCreate()
    {
        $patient = $this->patient();

        $specialties = Specialty::where('estado', 'activo')->orderBy('nombre')->get();

        $services = Service::with('tipoEspecialidad')
            ->where('estado', 'activo')
            ->orderBy('nombre')
            ->get();

        $servicesPayload = $services->map(function ($service) {
            return [
                'id' => $service->id_servicio,
                'name' => $service->nombre,
                'specialty_id' => $service->id_tipos_especialidad,
            ];
        })->values()->all();

        // Obtener doctores CON su especialidad desde la tabla doctors
        $doctors = User::whereHas('userType', fn($query) => $query->where('nombre', 'Médico'))
            ->with('doctor.tipoEspecialidad') // Asumiendo que tienes esta relación
            ->orderBy('nombres')
            ->get();

        // Crear payload de doctores con su especialidad
        $doctorsPayload = $doctors->map(function ($doctor) {
            return [
                'id' => $doctor->id_usuario,
                'nombres' => $doctor->nombres,
                'apellidos' => $doctor->apellidos,
                'specialty_id' => $doctor->doctor?->id_tipos_especialidad, // Desde la tabla doctors
            ];
        })->values()->all();

        $availabilityUrl = route('paciente.citas.disponibilidad');

        return view('paciente.citas.create', compact(
            'patient',
            'specialties',
            'services',
            'servicesPayload',
            'doctors',
            'doctorsPayload', // ← IMPORTANTE: Agregar esto
            'availabilityUrl'
        ));
    }
    public function citasStore(Request $request)
    {
        $patient = $this->patient();

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
            'id_usuario_agenda' => $patient->id_usuario,
            'fecha_hora_inicio' => $start,
            'fecha_hora_fin' => $end,
            'estado' => Appointment::STATUS_PROGRAMADA,
            'notas' => $validated['notas'] ?? null,
        ]);

        return redirect()->route('paciente.citas.confirmada', $appointment->id_cita);
    }

    public function citaConfirmada(int $id)
    {
        $appointment = $this->patientAppointmentsQuery()
            ->findOrFail($id);

        return view('paciente.citas.confirmada', compact('appointment'));
    }

    public function reprogramarIndex()
    {
        $patient = $this->patient();
        $appointments = $this->patientAppointmentsQuery()
            ->where('estado', '<>', Appointment::STATUS_CANCELADA)
            ->where('fecha_hora_inicio', '>=', now())
            ->orderBy('fecha_hora_inicio')
            ->get();

        return view('paciente.citas.reprogramar.index', compact('patient', 'appointments'));
    }

    public function reprogramarSelect(Request $request)
    {
        $data = $request->validate([
            'cita_id' => ['required', 'exists:appointments,id_cita'],
        ]);

        return redirect()->route('paciente.citas.reprogramar.edit', $data['cita_id']);
    }

    public function reprogramarEdit(int $id)
    {
        $patient = $this->patient();
        $appointment = $this->patientAppointmentsQuery()
            ->where('estado', '<>', Appointment::STATUS_CANCELADA)
            ->findOrFail($id);

        if ($appointment->fecha_hora_inicio->lt(now())) {
            return redirect()->route('paciente.citas.reprogramar.index')
                ->withErrors('La cita ya pasó y no puede reprogramarse.');
        }

        $specialties = Specialty::where('estado', 'activo')->orderBy('nombre')->get();

        $services = Service::with('tipoEspecialidad')
            ->where('estado', 'activo')
            ->orderBy('nombre')
            ->get();

        $servicesPayload = $services->map(function ($service) {
            return [
                'id' => $service->id_servicio,
                'name' => $service->nombre,
                'specialty_id' => $service->id_tipos_especialidad,
            ];
        })->values()->all();

        $doctors = User::whereHas('userType', fn($query) => $query->where('nombre', 'Médico'))
            ->with('doctor.tipoEspecialidad')
            ->orderBy('nombres')
            ->get();

        $doctorsPayload = $doctors->map(function ($doctor) {
            return [
                'id' => $doctor->id_usuario,
                'nombres' => $doctor->nombres,
                'apellidos' => $doctor->apellidos,
                'specialty_id' => $doctor->doctor?->id_tipos_especialidad,
            ];
        })->values()->all();

        $availabilityUrl = route('paciente.citas.disponibilidad');

        return view('paciente.citas.reprogramar.edit', compact(
            'patient',
            'appointment',
            'specialties',
            'services',
            'servicesPayload',
            'doctors',
            'doctorsPayload', // ← IMPORTANTE
            'availabilityUrl'
        ));
    }

    public function reprogramarUpdate(Request $request, int $id)
    {
        $patient = $this->patient();
        $appointment = $this->patientAppointmentsQuery()
            ->where('estado', '<>', Appointment::STATUS_CANCELADA)
            ->findOrFail($id);

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
            'id_usuario_agenda' => $patient->id_usuario,
            'id_usuario_cancela' => null,
            'motivo_cancelacion' => null,
        ]);

        return redirect()->route('paciente.citas.reprogramar.confirmada', $appointment->id_cita);
    }

    public function reprogramarConfirmada(int $id)
    {
        $appointment = $this->patientAppointmentsQuery()
            ->findOrFail($id);

        return view('paciente.citas.reprogramar.confirmada', compact('appointment'));
    }

    public function citasCancelarIndex()
    {
        $patient = $this->patient();

        $appointments = $this->patientAppointmentsQuery()
            ->where('estado', '<>', Appointment::STATUS_CANCELADA)
            ->where('fecha_hora_inicio', '>', now())
            ->orderBy('fecha_hora_inicio')
            ->get();

        return view('paciente.citas.cancelar.index', compact('patient', 'appointments'));
    }

    public function citasCancelarSubmit(Request $request)
    {
        $patient = $this->patient();

        $validated = $request->validate([
            'cita_id' => ['required', 'exists:appointments,id_cita'],
            'motivo' => ['nullable', 'string', 'max:255'],
        ]);

        $appointment = $this->patientAppointmentsQuery()
            ->where('estado', '<>', Appointment::STATUS_CANCELADA)
            ->where('fecha_hora_inicio', '>', now())
            ->findOrFail($validated['cita_id']);

        $appointment->update([
            'estado' => Appointment::STATUS_CANCELADA,
            'id_usuario_cancela' => $patient->id_usuario,
            'motivo_cancelacion' => $validated['motivo'] ?? 'Cancelada por el paciente',
        ]);

        return redirect()->route('paciente.citas.index')
            ->with('status', 'Tu cita ha sido cancelada.');
    }

    public function citasIndex()
    {
        $patient = $this->patient();

        $appointments = $this->patientAppointmentsQuery()
            ->orderByDesc('fecha_hora_inicio')
            ->paginate(10);

        return view('paciente.citas.index', compact('patient', 'appointments'));
    }

    public function citasDisponibilidad(Request $request)
    {
        $data = $request->validate([
            'id_usuario_medico' => ['required', 'exists:users,id_usuario'],
            'cita_id' => ['nullable', 'integer', 'exists:appointments,id_cita'],
        ]);

        $doctor = $this->doctorQuery()
            ->where('id_usuario', $data['id_usuario_medico'])
            ->firstOrFail();

        $ignoreAppointmentId = null;
        if (!empty($data['cita_id'])) {
            $appointment = $this->patientAppointmentsQuery()
                ->findOrFail($data['cita_id']);
            $ignoreAppointmentId = $appointment->id_cita;
        }

        $slots = $this->availability->slotsForDoctorBetween(
            $doctor->id_usuario,
            now()->startOfDay(),
            now()->copy()->addMonth()->endOfDay(),
            $ignoreAppointmentId
        );

        return response()->json(['slots' => $slots]);
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


    public function servicioDetalle(string $especialidad, string $servicio)
    {
        $especialidadNombre = Str::title(str_replace('-', ' ', $especialidad));
        $servicioNombre = Str::title(str_replace('-', ' ', $servicio));

        $servicio = [
            'nombre' => $servicioNombre,
            'especialidad' => $especialidadNombre,
            'especialidad_slug' => $especialidad,
            'descripcion_corta' => 'Evaluación médica integral y orientación diagnóstica.',
            'descripcion_larga' => 'Este servicio incluye una valoración clínica completa realizada por un médico general, con enfoque preventivo y diagnóstico. Ideal para chequeos, control de síntomas o derivación a especialistas.',
            'duracion' => '30 minutos',
            'doctor' => 'Dr. Andrés Gutiérrez',
            'icono' => '🩺',
        ];

        return view('paciente.servicios.detalle', compact('servicio'));
    }



}
