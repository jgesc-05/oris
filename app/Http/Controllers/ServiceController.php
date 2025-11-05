<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Specialty;
use Illuminate\Http\Request;


class ServiceController extends Controller
{
    public function create()
    {
        $specialties = Specialty::all();
        return view('admin.config.servicio.create', compact('specialties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_tipos_especialidad' => 'required|exists:specialty_type,id_tipos_especialidad',
            'nombre' => 'required|string|max:255',
            'duracion' => 'nullable|string|max:255',
            'precio_base' => 'nullable|numeric',
            'descripcion' => 'nullable|string',
        ]);
        
        Service::create([
            'id_tipos_especialidad' => $request->id_tipos_especialidad,
            'nombre' => $request->nombre,
            'duracion' => $request->duracion,
            'precio_base' => $request->precio,
            'descripcion' => $request->descripcion,
        ]);
        

        return redirect()
            ->route('admin.config.servicio.index')
            ->with('success', 'Servicio creado correctamente.');
    }

    //ver índice de servicios
    public function index(){
        $services = Service::with('tipoEspecialidad')->get();

        return view('admin.config.servicio.index', compact('services'));
    }

    //Cambiar el estado del servicio
    public function toggleState(int $id)
    {
        $service = Service::findOrFail($id);

        // Cambiar entre activo/inactivo
        $service->estado = ($service->estado === 'activo') ? 'inactivo' : 'activo';
        $service->save();

        return redirect()->route('admin.config.servicio.index')
                        ->with('success', 'Estado del servicio actualizado correctamente.');
    }

    //Eliminar servicio
    public function destroy(int $id)
    {

        $service = Service::findOrFail($id);
        // 1. Ejecutar la eliminación
        $service->delete();

        // 2. Redireccionar con un mensaje de éxito
        return redirect()
               ->route('admin.config.servicio.index') // Redirige al listado
               ->with([
                'title' => 'Servicio Eliminado Con Éxito', // <-- Nuevo Título
                'success' => "El servicio '{$service->nombre}' ha sido eliminado correctamente." // <-- Mensaje
            ]);
    }

    //Editar servicio
    public function edit(int $id){

        $service = Service::findOrFail($id);
        $specialties = Specialty::all();

        return view('admin.config.servicio.edit', compact('service', 'specialties'));

    }

    //Actualizar servicio
    public function update(Request $request, int $id){

        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'id_tipos_especialidad' => 'required|exists:specialty_type,id_tipos_especialidad',
            'nombre' => 'required|string|max:255',
            'duracion' => 'nullable|string|max:255',
            'precio_base' => 'nullable|numeric',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $service->update($validated);

        return redirect()
            ->route('admin.config.servicio.index')
            ->with('success', 'El servicio se actualizó correctamente.');

    }
}
