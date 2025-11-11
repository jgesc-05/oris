<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        $services = Service::with('tipoEspecialidad')->paginate(10);

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

    //Mostrar servicios por especialidad
    public function showBySpecialty($slug)
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

        // Retornar la vista del paciente
        return view('paciente.servicios.especialidad', compact('especialidad', 'servicios'));
    }

    public function showService($especialidadSlug, $servicioSlug)
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

        return view('paciente.servicios.detalle', compact('servicio', 'especialidad'));
    }

}
