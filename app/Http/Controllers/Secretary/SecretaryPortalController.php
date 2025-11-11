<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Specialty;
use App\Models\User;
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
        $especialidadesActivas = Specialty::where('estado', 'activo')->get();

        // 2. Formatear los datos para la vista: solo nombre, descripción y slug.
        $especialidades = $especialidadesActivas->map(function ($specialty) {
            
            // Generar el slug para la URL
            $slug = Str::slug($specialty->nombre);

            return [
                // Solo incluimos lo que la vista necesita:
                'nombre' => $specialty->nombre,
                'descripcion' => $specialty->descripcion,
                'slug' => $slug, 
                'icono' => '👨‍⚕️',
            ];
        });

        // 3. Devolver la vista 
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
        $especialidadesActivas = Specialty::where('estado', 'activo')->get();

        // 2. Formatear los datos para la vista: solo nombre, descripción y slug.
        $especialidades = $especialidadesActivas->map(function ($specialty) {
            
            // Generar el slug para la URL
            $slug = Str::slug($specialty->nombre);

            return [
                // Solo incluimos lo que la vista necesita:
                'nombre' => $specialty->nombre,
                'descripcion' => $specialty->descripcion,
                'slug' => $slug, 
                'icono' => '👨‍⚕️',
            ];
        });

        // 3. Devolver la vista 
        return view('secretaria.medicos.index', compact('especialidades')); 
    
    }

    public function medicosEspecialidad(string $especialidadSlug)
    {
        // 1. Buscar la especialidad (cambiar en el futuro)
        $specialty = Specialty::whereRaw('LOWER(REPLACE(nombre, " ", "-")) = ?', [$especialidadSlug])->firstOrFail();

        // 2. Obtener médicos activos que pertenezcan a esa especialidad
        $activeDoctors = User::with('doctor')
            ->where('id_tipo_usuario', 2)
            ->where('estado', 'activo')
            ->whereHas('doctor', function ($q) use ($specialty) {
                $q->where('id_tipos_especialidad', $specialty->id_tipos_especialidad);
            })
            ->get();

        // 3. Preparar los datos para la vista
        $doctors = $activeDoctors->map(function ($user) {
            $doctorData = $user->doctor;

            return [
                'nombre' => "{$user->nombres} {$user->apellidos}",
                'descripcion' => optional($doctorData)->descripcion,
                'universidad' => optional($doctorData)->universidad,
                'experiencia' => optional($doctorData)->experiencia,
                'slug' => Str::slug("{$user->nombres}-{$user->apellidos}"),
            ];
        });

        // 4. Retornar vista correcta (nota: era 'paciente', no 'pacientes')
        return view('secretaria.medicos.especialidad', compact('doctors', 'especialidadSlug', 'specialty'));
    }

    public function medicosDetalle(string $especialidadSlug, string $medicoSlug)
    {
        // 1. Buscar la especialidad por el slug de la URL (pero usando el nombre real en BD)
        $specialty = Specialty::whereRaw('LOWER(REPLACE(nombre, " ", "-")) = ?', [$especialidadSlug])
        ->firstOrFail();

        // 2. Buscar el médico según el slug de la URL
        $user = User::with('doctor')
        ->where('id_tipo_usuario', 2)
        ->where('estado', 'activo')
        ->get()
        ->first(function ($u) use ($medicoSlug) {
            $slug = Str::slug("{$u->nombres}-{$u->apellidos}");
            return $slug === $medicoSlug;
        });

        if (!$user) {
        abort(404, 'Médico no encontrado');
        }

        $doctorData = $user->doctor;

        // 3. Preparar los datos con tildes originales desde la BD
        $medico = [
            'nombre' => "{$user->nombres} {$user->apellidos}",
            'descripcion' => optional($doctorData)->descripcion,
            'formacion' => optional($doctorData)->universidad,
            'experiencia' => optional($doctorData)->experiencia,
            'especialidad' => $specialty->nombre, // nombre con tildes
            'especialidad_slug' => $especialidadSlug,
        ];

        // 4. Retornar la vista del perfil médico
        return view('secretaria.medicos.detalle', compact('medico'));
        }
    }

