<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
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

    public function serviciosEspecialidad(string $slug)
    {
             // Buscar la especialidad por slug del nombre (convertido a formato URL)
             $specialty = Specialty::whereRaw("LOWER(REPLACE(nombre, ' ', '-')) = ?", [$slug])->first();

             if (!$specialty) {
                 abort(404, 'Especialidad no encontrada');
             }
     
             // Obtener los servicios activos asociados a esa especialidad
             $servicios = Service::where('id_tipos_especialidad', $specialty->id_tipos_especialidad)
                 ->where('estado', 'activo')
                 ->get()
                 ->map(function ($serv) {
                     return [
                         'nombre' => $serv->nombre,
                         'descripcion' => $serv->descripcion ?? 'Sin descripción',
                         'slug' => Str::slug($serv->nombre),
                         'icono' => '🩺',
                     ];
                 });
     
             // Armar los datos de la especialidad para la vista
             $especialidad = [
                 'nombre' => $specialty->nombre,
                 'descripcion' => $specialty->descripcion ?? '',
                 'slug' => Str::slug($specialty->nombre),
             ];
     
             // Retornar la vista
             return view('secretaria.servicios.especialidad', compact('especialidad', 'servicios'));
    }


    public function serviciosDetalle(string $especialidadSlug, string $servicioSlug)
    {
        $especialidad = Specialty::whereRaw("LOWER(REPLACE(nombre, ' ', '-')) = ?", [$especialidadSlug])
        ->firstOrFail();

        $service = Service::where('id_tipos_especialidad', $especialidad->id_tipos_especialidad)
            ->whereRaw("LOWER(REPLACE(nombre, ' ', '-')) = ?", [$servicioSlug])
            ->firstOrFail();

        // Arreglo que se usará en la vista
        $servicio = [
            'nombre' => $service->nombre,
            'especialidad' => $especialidad->nombre,
            'descripcion_corta' => Str::limit($service->descripcion ?? 'Sin descripción', 120),
            'descripcion_larga' => $service->descripcion ?? '',
            'duracion' => $service->duracion ?? '30 minutos',
            'icono' => '🩺',
        ];

        $especialidad = [
            'nombre' => $especialidad->nombre,
            'slug' => Str::slug($especialidad->nombre),
        ];

        return view('secretaria.servicios.detalle', compact('servicio', 'especialidad'));

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

