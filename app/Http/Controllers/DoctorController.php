<?php

namespace App\Http\Controllers;

use App\Models\Specialty;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function indexDoctors()
    {
        // 1. Obtener todas las especialidades que están 'activo'
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

        // 3. Devolver la vista del paciente
        return view('paciente.medicos.index', compact('especialidades')); 
    }

    //Devolver médicos por especialidad
    public function doctorsBySpecialty(string $especialidadSlug)
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
        return view('paciente.medicos.especialidad', compact('doctors', 'especialidadSlug'));
    }

    //Detalle del médico
    public function doctorDetail(string $especialidadSlug, string $medicoSlug)
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
        return view('paciente.medicos.detalle', compact('medico'));
    }

}
