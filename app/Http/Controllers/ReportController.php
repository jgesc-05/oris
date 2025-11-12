<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Listas base para los selects
        $medicos = Doctor::all();
        $servicios = Service::all();
        $usuariosMedicos = User::where('id_tipo_usuario', 2)->get();

        // Filtros opcionales
        $filtros = [
            'desde' => $request->input('desde'),
            'hasta' => $request->input('hasta'),
            'medico' => $request->input('medico'),
            'servicio' => $request->input('servicio'),
        ];

        // Mapa de servicios por médico (según especialidad)
        $serviciosPorMedico = $medicos->mapWithKeys(function (Doctor $doctor) use ($servicios) {
            $serviciosFiltrados = $servicios
                ->where('id_tipos_especialidad', $doctor->id_tipos_especialidad)
                ->map(fn($service) => [
                    'id' => $service->id_servicio,
                    'nombre' => $service->nombre,
                ])
                ->values();

            return [$doctor->id_usuario => $serviciosFiltrados];
        });

        // Todos los servicios (para opción "todos")
        $serviciosList = $servicios->map(fn($service) => [
            'id' => $service->id_servicio,
            'nombre' => $service->nombre,
        ])->values();

        $serviciosSelect = $servicios;

        if ($filtros['medico']) {
            $doctorSeleccionado = $medicos->firstWhere('id_usuario', (int) $filtros['medico']);
            if ($doctorSeleccionado) {
                $serviciosSelect = $servicios
                    ->where('id_tipos_especialidad', $doctorSeleccionado->id_tipos_especialidad)
                    ->values();
            }
        }

        // ========== GRÁFICO 1: Distribución de citas por servicio ==========
        $queryServicios = DB::table('appointments')
            ->join('services', 'appointments.id_servicio', '=', 'services.id_servicio')
            ->select('services.nombre as servicio', DB::raw('COUNT(*) as total'))
            ->groupBy('services.nombre');

        if ($filtros['desde'])
            $queryServicios->whereDate('fecha_hora_inicio', '>=', $filtros['desde']);
        if ($filtros['hasta'])
            $queryServicios->whereDate('fecha_hora_inicio', '<=', $filtros['hasta']);
        if ($filtros['medico'])
            $queryServicios->where('id_usuario_medico', $filtros['medico']);
        if ($filtros['servicio'])
            $queryServicios->where('appointments.id_servicio', $filtros['servicio']);

        $serviciosChart = $queryServicios->get();

        // ========== GRÁFICO 2: Ocupación por médico ==========
        $queryMedicos = DB::table('appointments')
            ->join('users', 'appointments.id_usuario_medico', '=', 'users.id_usuario')
            ->select(DB::raw("users.nombres as medico"), DB::raw('COUNT(*) as total'))
            ->groupBy('medico');

        if ($filtros['desde'])
            $queryMedicos->whereDate('fecha_hora_inicio', '>=', $filtros['desde']);
        if ($filtros['hasta'])
            $queryMedicos->whereDate('fecha_hora_inicio', '<=', $filtros['hasta']);
        if ($filtros['medico'])
            $queryMedicos->where('id_usuario_medico', $filtros['medico']);
        if ($filtros['servicio'])
            $queryMedicos->where('appointments.id_servicio', $filtros['servicio']);

        $medicosChart = $queryMedicos->get();

        // Retornar todo a la vista
        return view('admin.reportes.index', compact(
            'medicos',
            'serviciosSelect',
            'usuariosMedicos',
            'serviciosChart',
            'medicosChart',
            'filtros',
            'serviciosPorMedico',
            'serviciosList'
        ));
    }
}
