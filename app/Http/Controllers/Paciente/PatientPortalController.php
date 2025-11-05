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

    public function reprogramarIndex()
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

    public function reprogramarSelect(Request $request)
    {
        $data = $request->validate([
            'cita_id' => ['required', 'integer'],
        ]);

        return redirect()->route('paciente.citas.reprogramar.edit', $data['cita_id']);
    }

    public function reprogramarEdit(int $id)
    {
        $patient = Auth::guard('paciente')->user();

        // Mock temporal de la cita (reemplaza luego con consulta a DB)
        $cita = [
            'id'          => $id,
            'especialidad'=> 'Endodoncia',
            'servicio'    => 'Tratamiento de conducto',
            'medico'      => 'Luisa Mantilla',
            'fecha'       => '2025-10-08', // formato ISO (para input date)
            'hora'        => '10:00',
        ];

        // Catálogos de selección (mock)
        $especialidades = ['Endodoncia', 'Ortodoncia', 'Odontología general'];
        $servicios      = ['Tratamiento de conducto', 'Control de ortodoncia', 'Limpieza dental'];
        $medicos        = ['Luisa Mantilla', 'Antonio Londoño', 'Sandra Rodríguez'];
        $horas          = ['08:00', '09:00', '10:00', '11:00', '14:00', '15:00'];

        return view('paciente.citas.reprogramar.edit', compact(
            'patient', 'cita', 'especialidades', 'servicios', 'medicos', 'horas'
        ));
    }

    public function reprogramarUpdate(Request $request, int $id)
    {
        $data = $request->validate([
            'especialidad' => ['required', 'string', 'max:100'],
            'servicio'     => ['required', 'string', 'max:150'],
            'medico'       => ['required', 'string', 'max:150'],
            'fecha'        => ['required', 'date'],
            'hora'         => ['required', 'date_format:H:i'],
        ]);

        // TODO: Guardar cambios de la cita en la BD.
        return redirect()
            ->route('paciente.citas.index')
            ->with('status', 'Tu cita fue reprogramada correctamente.');
    }

    public function citasCancelarIndex()
    {
        $patient = \Auth::guard('paciente')->user();

        // Mock: puedes reutilizar el mismo arreglo que usas en reprogramar
        $appointments = [
            ['id'=>1, 'fecha'=>'2025-11-10', 'hora'=>'09:00', 'doctor'=>'Dra. Laura Hernández', 'servicio'=>'Control', 'estado'=>'Confirmada'],
            ['id'=>2, 'fecha'=>'2025-11-15', 'hora'=>'11:30', 'doctor'=>'Dr. Andrés Salazar',   'servicio'=>'Ortodoncia', 'estado'=>'Confirmada'],
        ];

        return view('paciente.citas.cancelar.index', compact('patient','appointments'));
    }

    public function citasCancelarSubmit(\Illuminate\Http\Request $request)
    {
        $request->validate(['cita_id' => 'required']);
        // TODO: cancelar la cita seleccionada
        return back()->with('status', 'Tu cita ha sido cancelada.');
    }

    public function citasIndex()
    {
        $patient = \Auth::guard('paciente')->user();

        // Mock de citas (reemplaza por query real cuando tengas DB)
        $appointments = [
            ['fecha' => '20 de octubre', 'hora' => '10:00 AM', 'doctor' => 'Antonio Londoño',  'servicio' => 'Limpieza dental',        'estado' => 'Programada'],
            ['fecha' => '30 de octubre', 'hora' => '2:00 PM',  'doctor' => 'Sandra Rodríguez', 'servicio' => 'Control de ortodoncia', 'estado' => 'Programada'],
        ];

        return view('paciente.citas.index', compact('patient','appointments'));
    }


}
