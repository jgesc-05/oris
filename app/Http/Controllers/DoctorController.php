<?php

namespace App\Http\Controllers;

use App\Models\Specialty;
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
}
