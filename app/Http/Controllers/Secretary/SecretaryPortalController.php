<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SecretaryPortalController extends Controller
{
    public function inicio()
    {
        $secretary = Auth::user();
        $today = now()->toDateString();

        $summary = [
            'citas_programadas' => Appointment::whereDate('fecha_hora_inicio', $today)
                ->where('estado', 'Programada')
                ->count(),
            'citas_canceladas' => Appointment::whereDate('fecha_hora_inicio', $today)
                ->where('estado', 'Cancelada')
                ->count(),
            'pagos_pendientes' => 0,
        ];

        $agenda = Appointment::with(['paciente', 'medico', 'servicio'])
            ->whereDate('fecha_hora_inicio', $today)
            ->orderBy('fecha_hora_inicio')
            ->limit(5)
            ->get();

        return view('secretaria.dashboard', compact('secretary', 'summary', 'agenda'));
    }

    public function agenda(Request $request)
    {
        $filters = [
            'fecha' => $request->input('fecha', now()->toDateString()),
            'estado' => $request->input('estado'),
            'paciente' => $request->input('paciente'),
        ];

        $query = Appointment::with(['paciente', 'medico', 'servicio'])
            ->orderBy('fecha_hora_inicio');

        if ($filters['fecha']) {
            $query->whereDate('fecha_hora_inicio', $filters['fecha']);
        }

        if ($filters['estado']) {
            $query->where('estado', $filters['estado']);
        }

        if ($filters['paciente']) {
            $query->whereHas('paciente', function ($q) use ($filters) {
                $q->where('nombres', 'like', '%'.$filters['paciente'].'%')
                    ->orWhere('apellidos', 'like', '%'.$filters['paciente'].'%')
                    ->orWhere('numero_documento', 'like', '%'.$filters['paciente'].'%');
            });
        }

        $appointments = $query->get();

        return view('secretaria.agenda.index', compact('appointments', 'filters'));
    }

    public function servicios()
    {
        $especialidades = collect([
            ['nombre' => 'Medicina general',      'descripcion' => 'Seguimiento integral del estado de salud.',       'icono' => '🩺'],
            ['nombre' => 'Pediatría',             'descripcion' => 'Atención especializada para niños y niñas.',      'icono' => '👶'],
            ['nombre' => 'Cardiología',           'descripcion' => 'Tratamiento de enfermedades del corazón.',        'icono' => '❤️'],
            ['nombre' => 'Dermatología',          'descripcion' => 'Cuidado de la piel, cabello y uñas.',             'icono' => '🧴'],
            ['nombre' => 'Neurología',            'descripcion' => 'Trastornos del sistema nervioso.',               'icono' => '🧠'],
            ['nombre' => 'Rehabilitación física', 'descripcion' => 'Recuperación de la movilidad y funcionalidad.',  'icono' => '🏃‍♀️'],
        ])->map(function ($item) {
            $item['slug'] = Str::slug($item['nombre']);
            return $item;
        })->toArray();

        return view('secretaria.servicios.index', compact('especialidades'));
    }

    public function serviciosEspecialidad(string $especialidad)
    {
        $especialidadData = [
            'nombre' => Str::title(str_replace('-', ' ', $especialidad)),
            'slug'   => $especialidad,
        ];

        $servicios = collect([
            ['nombre' => 'Consulta general', 'descripcion' => 'Evaluación médica completa y diagnóstico inicial.', 'icono' => '🩺'],
            ['nombre' => 'Chequeo preventivo', 'descripcion' => 'Revisión periódica para detectar factores de riesgo.', 'icono' => '📋'],
            ['nombre' => 'Atención de urgencias leves', 'descripcion' => 'Atención rápida a emergencias menores.', 'icono' => '🚑'],
            ['nombre' => 'Exámenes especializados', 'descripcion' => 'Pruebas médicas según indicaciones clínicas.', 'icono' => '🧪'],
        ])->map(function ($item) use ($especialidad) {
            $item['slug'] = Str::slug($item['nombre']);
            $item['especialidad_slug'] = $especialidad;
            return $item;
        })->toArray();

        return view('secretaria.servicios.especialidad', [
            'especialidad' => $especialidadData,
            'servicios'    => $servicios,
        ]);
    }

    public function serviciosDetalle(string $especialidad, string $servicio)
    {
        $detalle = [
            'nombre'            => Str::title(str_replace('-', ' ', $servicio)),
            'especialidad'      => Str::title(str_replace('-', ' ', $especialidad)),
            'especialidad_slug' => $especialidad,
            'descripcion_corta' => 'Evaluación médica integral y orientación diagnóstica.',
            'descripcion_larga' => 'Este servicio incluye una valoración clínica completa realizada por un profesional de la salud, con enfoque preventivo y diagnóstico. Ideal para chequeos, control de síntomas o derivación a especialistas.',
            'duracion'          => '30 minutos',
            'doctor'            => 'Equipo médico especializado',
            'icono'             => '🩺',
        ];

        return view('secretaria.servicios.detalle', ['servicio' => $detalle]);
    }

    public function medicos()
    {
        $especialidades = collect([
            ['nombre' => 'Medicina general',      'descripcion' => 'Seguimiento integral del estado de salud.',       'icono' => '🩺'],
            ['nombre' => 'Pediatría',             'descripcion' => 'Atención especializada para niños y niñas.',      'icono' => '👶'],
            ['nombre' => 'Cardiología',           'descripcion' => 'Tratamiento de enfermedades del corazón.',        'icono' => '❤️'],
            ['nombre' => 'Dermatología',          'descripcion' => 'Cuidado de la piel, cabello y uñas.',             'icono' => '🧴'],
            ['nombre' => 'Neurología',            'descripcion' => 'Trastornos del sistema nervioso.',               'icono' => '🧠'],
            ['nombre' => 'Rehabilitación física', 'descripcion' => 'Recuperación de la movilidad y funcionalidad.',  'icono' => '🏃‍♀️'],
        ])->map(function ($item) {
            $item['slug'] = Str::slug($item['nombre']);
            return $item;
        })->toArray();

        return view('secretaria.medicos.index', compact('especialidades'));
    }

    public function medicosEspecialidad(string $especialidad)
    {
        $especialidadData = [
            'nombre' => Str::title(str_replace('-', ' ', $especialidad)),
            'slug'   => $especialidad,
        ];

        $medicos = collect([
            [
                'nombre'         => 'Dra. Laura Hernández',
                'descripcion'    => 'Especialista en atención preventiva y control de enfermedades crónicas.',
                'formacion'      => 'Médico cirujano — Universidad Nacional',
                'experiencia'    => '10 años',
                'disponibilidad' => 'Lunes a viernes — 8:00 a.m. - 4:00 p.m.',
            ],
            [
                'nombre'         => 'Dr. Andrés Salazar',
                'descripcion'    => 'Enfoque en diagnóstico temprano y medicina familiar.',
                'formacion'      => 'Especialista en Medicina Familiar — Universidad Javeriana',
                'experiencia'    => '8 años',
                'disponibilidad' => 'Martes y jueves — 10:00 a.m. - 6:00 p.m.',
            ],
            [
                'nombre'         => 'Dra. Catalina Díaz',
                'descripcion'    => 'Atención integral a pacientes con condiciones crónicas.',
                'formacion'      => 'Medicina interna — Universidad de los Andes',
                'experiencia'    => '12 años',
                'disponibilidad' => 'Miércoles y sábado — 9:00 a.m. - 2:00 p.m.',
            ],
        ])->map(function ($item) use ($especialidad) {
            $item['slug'] = Str::slug($item['nombre']);
            $item['especialidad_slug'] = $especialidad;
            return $item;
        })->toArray();

        return view('secretaria.medicos.especialidad', [
            'especialidad' => $especialidadData,
            'medicos'      => $medicos,
        ]);
    }

    public function medicosDetalle(string $especialidad, string $medico)
    {
        $detalle = [
            'nombre'              => Str::title(str_replace('-', ' ', $medico)),
            'especialidad'        => Str::title(str_replace('-', ' ', $especialidad)),
            'especialidad_slug'   => $especialidad,
            'descripcion'         => 'Profesional con enfoque humano y preventivo, acompañando procesos de diagnóstico y tratamiento integral.',
            'formacion'           => 'Médico cirujano — Universidad Nacional, especialización en Medicina interna.',
            'experiencia'         => 'Más de 10 años en consulta externa y hospitalaria.',
            'disponibilidad'      => 'Lunes a viernes — 8:00 a.m. - 4:00 p.m.',
            'icono'               => '👩‍⚕️',
        ];

        return view('secretaria.medicos.detalle', [
            'medico' => $detalle,
        ]);
    }
}
