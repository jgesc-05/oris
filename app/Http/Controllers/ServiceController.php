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
}
