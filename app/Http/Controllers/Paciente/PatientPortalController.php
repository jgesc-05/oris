<?php

namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientPortalController extends Controller
{
    public function inicio()
    {
        $patient = Auth::guard('paciente')->user();
        $nextAppointment = [
            'dia'     => 'Lunes, 30 de septiembre',
            'hora'    => '9:00 AM',
            'doctor'  => 'Dra. Sandra Rodríguez',
            'detalle' => 'Control Ortodoncia',
            'existe'  => false,
        ];

        return view('paciente.dashboard', [
            'patient' => $patient,
            'nextAppointment' => $nextAppointment,
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

    public function citasCreate()
    {
        $patient = Auth::guard('paciente')->user();

        return view('paciente.citas.create', [
            'patient' => $patient,
        ]);
    }

    public function citasStore(Request $request)
    {
        $data = $request->validate([
            'especialidad' => ['required', 'string', 'max:100'],
            'fecha'        => ['required', 'date'],
            'servicio'     => ['required', 'string', 'max:150'],
            'hora'         => ['required'],
            'medico'       => ['required', 'string', 'max:150'],
        ]);

        // TODO: Persistir la cita en base de datos.
        return back()->with('status', 'Tu solicitud de cita fue recibida. Pronto te contactaremos para confirmar.');
    }

    public function citasReprogramarIndex()
    {
        $patient = Auth::guard('paciente')->user();

        // Mock de citas programadas
        $appointments = [
            [
                'id'        => 101,
                'fecha'     => '20 de octubre',
                'hora'      => '10:00 AM',
                'doctor'    => 'Antonio Londoño',
                'servicio'  => 'Limpieza dental',
                'estado'    => 'Programada',
            ],
            [
                'id'        => 102,
                'fecha'     => '30 de octubre',
                'hora'      => '2:00 PM',
                'doctor'    => 'Sandra Rodríguez',
                'servicio'  => 'Control de ortodoncia',
                'estado'    => 'Programada',
            ],
        ];

        return view('paciente.citas.reprogramar.index', compact('patient', 'appointments'));
    }

    public function citasReprogramarSubmit(Request $request)
    {
        $data = $request->validate([
            'cita_id' => ['required'],
        ]);

        // Aquí puedes redirigir a la pantalla de reprogramación (selección nueva fecha/hora)
        // Por ahora, solo confirmamos la selección.
        return back()->with('status', 'Seleccionaste la cita #'.$data['cita_id'].' para reprogramar.');
    }
}
