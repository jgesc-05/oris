<?php

namespace App\Http\Controllers;

use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function index(){
        $specialties = Specialty::all();

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
}
