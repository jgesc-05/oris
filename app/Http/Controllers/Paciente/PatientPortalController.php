<?php

namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PatientPortalController extends Controller
{
    public function inicio()
    {
        $patient = Auth::guard('paciente')->user();

        return view('paciente.inicio.index', [
            'patient' => $patient,
        ]);
    }

    public function servicios()
    {
        $patient = Auth::guard('paciente')->user();

        $services = [
            [
                'title' => 'Odontología general',
                'description' => 'Controles preventivos, limpiezas y diagnóstico integral.',
            ],
            [
                'title' => 'Ortodoncia',
                'description' => 'Tratamientos para mejorar tu mordida y estética dental.',
            ],
            [
                'title' => 'Rehabilitación oral',
                'description' => 'Soluciones integrales para recuperar la salud de tu sonrisa.',
            ],
        ];

        return view('paciente.servicios.index', [
            'patient' => $patient,
            'services' => $services,
        ]);
    }

    public function medicos()
    {
        $patient = Auth::guard('paciente')->user();

        $doctors = [
            [
                'name' => 'Dra. Laura Hernández',
                'specialty' => 'Odontología general',
                'availability' => 'Lunes a viernes — 8:00 a.m. - 4:00 p.m.',
            ],
            [
                'name' => 'Dr. Andrés Salazar',
                'specialty' => 'Ortodoncia',
                'availability' => 'Martes y jueves — 10:00 a.m. - 6:00 p.m.',
            ],
            [
                'name' => 'Dra. Catalina Díaz',
                'specialty' => 'Rehabilitación oral',
                'availability' => 'Miércoles y sábado — 9:00 a.m. - 2:00 p.m.',
            ],
        ];

        return view('paciente.medicos.index', [
            'patient' => $patient,
            'doctors' => $doctors,
        ]);
    }
}
