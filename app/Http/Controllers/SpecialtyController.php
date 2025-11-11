<?php

namespace App\Http\Controllers;

use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SpecialtyController extends Controller
{

    public function storeSpecialty(Request $request)
    {
        $admin = Auth::user();

        //Verificar rol del usuario autenticado
        if ($admin->userType->id_tipo_usuario != 1) {
            return redirect()->back()->withErrors('No tienes permisos para crear especialidades nuevas.');
        }

        // Validación
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'estado' => 'required|string|in:activo,inactivo',
            'descripcion' => 'required|string|max:255',
        ]);


        //Creación
        Specialty::create([
            'nombre' => $validated['nombre'],
            'estado' => $validated['estado'],
            'descripcion' => $validated['descripcion'],
        ]);


        return redirect()
            ->route('admin.config.especialidad.index')
            ->with('success', 'La especialidad fue creada correctamente.');
    }

    //Mostrar las especialidades reales
    public function index()
    {
        $specialties = Specialty::paginate(10);

        return view('admin.config.especialidad.index', compact('specialties'));
    }

    public function toggleState(int $id)
    {
        $specialty = Specialty::findOrFail($id);

        // Cambiar entre activo/inactivo
        $specialty->estado = ($specialty->estado === 'activo') ? 'inactivo' : 'activo';
        $specialty->save();

        return redirect()->route('admin.config.especialidad.index')
            ->with('success', 'Estado de la especialidad actualizado correctamente.');
    }

    public function showCreate()
    {
        return view('admin.config.especialidad.create');
    }

    public function destroy(int $id)
    {

        $specialty = Specialty::findOrFail($id);
        // 1. Ejecutar la eliminación
        $specialty->delete();

        // 2. Redireccionar con un mensaje de éxito
        return redirect()
            ->route('admin.config.especialidad.index') // Redirige al listado
            ->with([
                'title' => 'Especialidad Eliminada con Éxito', // <-- Nuevo Título
                'success' => "La especialidad '{$specialty->nombre}' ha sido eliminada correctamente." // <-- Mensaje
            ]);
    }

    //Editar especialidad
    public function edit(int $id)
    {

        $specialty = Specialty::findOrFail($id);

        return view('admin.config.especialidad.edit', compact('specialty'));

    }

    //Actualizar especialidad
    public function update(Request $request, int $id)
    {

        $specialty = Specialty::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'estado' => 'required|string|in:activo,inactivo',
            'descripcion' => 'required|string|max:255',
        ]);

        $specialty->update($validated);

        return redirect()
            ->route('admin.config.especialidad.index')
            ->with('success', 'La especialidad se actualizó correctamente.');

    }

    //Visualizar desde la sección de pacientes
    public function patientIndex()
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
        return view('paciente.servicios.index', compact('especialidades')); 
    }

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
