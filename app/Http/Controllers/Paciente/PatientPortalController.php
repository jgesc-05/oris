<?php

namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

        $especialidades = [
            ['nombre' => 'Medicina general',       'descripcion' => 'Atención primaria y chequeos preventivos.',     'icono' => '🩺'],
            ['nombre' => 'Pediatría',              'descripcion' => 'Salud y desarrollo infantil.',                 'icono' => '👶'],
            ['nombre' => 'Cardiología',            'descripcion' => 'Enfermedades del corazón y circulación.',      'icono' => '❤️'],
            ['nombre' => 'Dermatología',           'descripcion' => 'Cuidado de la piel, cabello y uñas.',           'icono' => '🧴'],
            ['nombre' => 'Ginecología',            'descripcion' => 'Salud reproductiva y atención femenina.',       'icono' => '🌸'],
            ['nombre' => 'Neurología',             'descripcion' => 'Trastornos del sistema nervioso.',              'icono' => '🧠'],
            ['nombre' => 'Oftalmología',           'descripcion' => 'Cuidado de los ojos y la visión.',              'icono' => '👁️'],
            ['nombre' => 'Traumatología',          'descripcion' => 'Lesiones musculares y óseas.',                  'icono' => '🦵'],
            ['nombre' => 'Psiquiatría',            'descripcion' => 'Salud mental y emocional.',                     'icono' => '🧘'],
            ['nombre' => 'Endocrinología',         'descripcion' => 'Trastornos hormonales y metabólicos.',          'icono' => '🧬'],
            ['nombre' => 'Rehabilitación física',  'descripcion' => 'Recuperación funcional y motora.',              'icono' => '🏃‍♂️'],
        ];

        $especialidades = collect($especialidades)->map(function (array $especialidad) {
            $especialidad['slug'] = Str::slug($especialidad['nombre']);
            return $especialidad;
        })->toArray();

        return view('paciente.servicios.index', [
            'patient' => $patient,
            'especialidades' => $especialidades,
        ]);
    }

    public function serviciosEspecialidad(string $slug)
    {
        $nombre = Str::title(str_replace('-', ' ', $slug));

        $especialidad = [
            'nombre' => $nombre,
            'slug'   => $slug,
        ];

        $servicios = [
            ['nombre' => 'Consulta general', 'descripcion' => 'Evaluación médica completa y diagnóstico inicial.', 'icono' => '🩺'],
            ['nombre' => 'Chequeo preventivo', 'descripcion' => 'Revisión periódica para detectar factores de riesgo.', 'icono' => '📋'],
            ['nombre' => 'Atención de urgencias leves', 'descripcion' => 'Atención rápida a emergencias menores.', 'icono' => '🚑'],
            ['nombre' => 'Exámenes especializados', 'descripcion' => 'Pruebas médicas según indicaciones clínicas.', 'icono' => '🧪'],
        ];

        $servicios = collect($servicios)->map(function (array $servicio) use ($slug) {
            $servicio['slug'] = Str::slug($servicio['nombre']);
            $servicio['especialidad_slug'] = $slug;
            return $servicio;
        })->toArray();

        return view('paciente.servicios.especialidad', compact('especialidad', 'servicios'));
    }


    public function medicos()
    {
        $patient = Auth::guard('paciente')->user();

        $especialidades = [
            ['nombre' => 'Medicina general',      'descripcion' => 'Seguimiento integral del estado de salud.',       'icono' => '🩺'],
            ['nombre' => 'Pediatría',             'descripcion' => 'Atención especializada para niños y niñas.',      'icono' => '👶'],
            ['nombre' => 'Cardiología',           'descripcion' => 'Tratamiento de enfermedades del corazón.',        'icono' => '❤️'],
            ['nombre' => 'Dermatología',          'descripcion' => 'Cuidado de la piel, cabello y uñas.',             'icono' => '🧴'],
            ['nombre' => 'Neurología',            'descripcion' => 'Trastornos del sistema nervioso.',               'icono' => '🧠'],
            ['nombre' => 'Rehabilitación física', 'descripcion' => 'Recuperación de la movilidad y funcionalidad.', 'icono' => '🏃‍♀️'],
        ];

        $especialidades = collect($especialidades)->map(function (array $especialidad) {
            $especialidad['slug'] = Str::slug($especialidad['nombre']);
            return $especialidad;
        })->toArray();

        return view('paciente.medicos.index', [
            'patient'        => $patient,
            'especialidades' => $especialidades,
        ]);
    }

    public function medicosEspecialidad(string $slug)
    {
        $patient = Auth::guard('paciente')->user();

        $especialidad = [
            'nombre' => Str::title(str_replace('-', ' ', $slug)),
            'slug'   => $slug,
        ];

        $medicos = [
            [
                'nombre'       => 'Dra. Laura Hernández',
                'descripcion'  => 'Especialista en atención preventiva y control de enfermedades crónicas.',
                'formacion'    => 'Médico cirujano — Universidad Nacional',
                'experiencia'  => '10 años',
                'disponibilidad' => 'Lunes a viernes — 8:00 a.m. - 4:00 p.m.',
            ],
            [
                'nombre'       => 'Dr. Andrés Salazar',
                'descripcion'  => 'Enfoque en diagnóstico temprano y medicina familiar.',
                'formacion'    => 'Especialista en Medicina Familiar — Universidad Javeriana',
                'experiencia'  => '8 años',
                'disponibilidad' => 'Martes y jueves — 10:00 a.m. - 6:00 p.m.',
            ],
            [
                'nombre'       => 'Dra. Catalina Díaz',
                'descripcion'  => 'Atención integral a pacientes con condiciones crónicas.',
                'formacion'    => 'Medicina interna — Universidad de los Andes',
                'experiencia'  => '12 años',
                'disponibilidad' => 'Miércoles y sábado — 9:00 a.m. - 2:00 p.m.',
            ],
        ];

        $medicos = collect($medicos)->map(function (array $medico) use ($slug) {
            $medico['slug'] = Str::slug($medico['nombre']);
            $medico['especialidad_slug'] = $slug;
            return $medico;
        })->toArray();

        return view('paciente.medicos.especialidad', compact('patient', 'especialidad', 'medicos'));
    }

    public function medicosDetalle(string $especialidad, string $medico)
    {
        $patient = Auth::guard('paciente')->user();

        $medicoDetalle = [
            'nombre'              => Str::title(str_replace('-', ' ', $medico)),
            'especialidad'        => Str::title(str_replace('-', ' ', $especialidad)),
            'especialidad_slug'   => $especialidad,
            'descripcion'         => 'Profesional con un enfoque humano y preventivo, acompañando procesos de diagnóstico y tratamiento.',
            'formacion'           => 'Médico cirujano — Universidad Nacional, especialización en Medicina interna.',
            'experiencia'         => 'Más de 10 años en consulta externa y hospitalaria.',
            'disponibilidad'      => 'Lunes a viernes — 8:00 a.m. - 4:00 p.m.',
            'icono'               => '👨‍⚕️',
        ];

        return view('paciente.medicos.detalle', [
            'patient' => $patient,
            'medico'  => $medicoDetalle,
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
            'fecha'        => ['required', 'date', 'after_or_equal:today'],
            'servicio'     => ['required', 'string', 'max:150'],
            'hora'         => ['required', 'date_format:H:i', 'regex:/^(?:[01]\d|2[0-3]):(?:00|30)$/'],
            'medico'       => ['required', 'string', 'max:150'],
        ]);

        // Simulación de creación de cita (reemplazar por DB cuando esté lista)
        $appointment = [
            'fecha_hora' => Carbon::parse($data['fecha'] . ' ' . $data['hora'])->translatedFormat('l j \\d\\e F, g:i A'),
            'doctor'     => $data['medico'],
            'servicio'   => $data['servicio'],
            'referencia' => 'CITA-' . now()->year . '-' . rand(100000, 999999),
        ];

        // Redirige a la vista de confirmación
        return view('paciente.citas.confirmada', compact('appointment'));
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
        $especialidades = ['Endodoncia', 'Ortodoncia', 'Medicina general'];
        $servicios      = ['Tratamiento de conducto', 'Control de ortodoncia', 'Limpieza dental'];
        $medicos        = ['Luisa Mantilla', 'Antonio Londoño', 'Sandra Rodríguez'];
        $horas          = ['08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30'];

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
            'fecha'        => ['required', 'date', 'after_or_equal:today'],
            'hora'         => ['required', 'date_format:H:i', 'regex:/^(?:[01]\d|2[0-3]):(?:00|30)$/'],
        ]);

        $appointment = [
            'fecha_hora' => Carbon::parse($data['fecha'] . ' ' . $data['hora'])->translatedFormat('l j \\d\\e F, g:i A'),
            'doctor'     => $data['medico'],
            'servicio'   => $data['servicio'],
            'referencia' => 'CITA-' . now()->year . '-' . rand(100000, 999999),
        ];

        // TODO: Guardar cambios de la cita en la BD.
        return redirect()
            ->route('paciente.citas.reprogramar.confirmada')
            ->with('appointment', $appointment);
    }

    public function reprogramarConfirmada()
    {
        $appointment = session('appointment');

        if (!$appointment) {
            return redirect()->route('paciente.citas.reprogramar.index');
        }

        return view('paciente.citas.reprogramar.confirmada', compact('appointment'));
    }



    public function citasReprogramarSubmit(Request $request)
    {
        $data = $request->validate([
            'cita_id' => ['required'],
            'fecha'   => ['nullable', 'date', 'after_or_equal:today'],
            'hora'    => ['nullable', 'date_format:H:i', 'regex:/^(?:[01]\d|2[0-3]):(?:00|30)$/'],
            'medico'  => ['nullable', 'string'],
            'servicio'=> ['nullable', 'string'],
        ]);

        // Simulación de actualización (luego conectar a la DB real)
        $appointment = [
            'fecha_hora' => isset($data['fecha'])
                ? Carbon::parse(($data['fecha'] ?? now()->toDateString()) . ' ' . ($data['hora'] ?? '08:00'))->translatedFormat('l j \\d\\e F, g:i A')
                : 'Miércoles 8 de Octubre, 10:00 AM',
            'doctor'     => $data['medico'] ?? 'Luisa Mantilla',
            'servicio'   => $data['servicio'] ?? 'Tratamiento de conducto',
            'referencia' => 'CITA-' . now()->year . '-' . rand(100000, 999999),
        ];

        // Retornar la pantalla de confirmación
        return redirect()
            ->route('paciente.citas.reprogramar.confirmada')
            ->with('appointment', $appointment);
    }


    public function citasReprogramarUpdate(Request $request, $id)
    {
        $data = $request->validate([
            'especialidad' => ['required', 'string', 'max:100'],
            'fecha'        => ['required', 'date', 'after_or_equal:today'],
            'servicio'     => ['required', 'string', 'max:150'],
            'hora'         => ['required', 'date_format:H:i', 'regex:/^(?:[01]\d|2[0-3]):(?:00|30)$/'],
            'medico'       => ['required', 'string', 'max:150'],
        ]);

        // 🔹 Simulación de actualización
        // En el futuro aquí actualizarías en la base de datos usando el modelo Cita::find($id)->update($data)
        $appointment = [
            'fecha_hora' => Carbon::parse($data['fecha'] . ' ' . $data['hora'])->translatedFormat('l j \\d\\e F, g:i A'),
            'doctor'     => $data['medico'],
            'servicio'   => $data['servicio'],
            'referencia' => 'CITA-' . now()->year . '-' . rand(100000, 999999),
        ];

        // 🔹 Mostrar pantalla de confirmación
        return redirect()
            ->route('paciente.citas.reprogramar.confirmada')
            ->with('appointment', $appointment);
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


    public function servicioDetalle(string $especialidad, string $servicio)
    {
        $especialidadNombre = Str::title(str_replace('-', ' ', $especialidad));
        $servicioNombre     = Str::title(str_replace('-', ' ', $servicio));

        $servicio = [
            'nombre'            => $servicioNombre,
            'especialidad'      => $especialidadNombre,
            'especialidad_slug' => $especialidad,
            'descripcion_corta' => 'Evaluación médica integral y orientación diagnóstica.',
            'descripcion_larga' => 'Este servicio incluye una valoración clínica completa realizada por un médico general, con enfoque preventivo y diagnóstico. Ideal para chequeos, control de síntomas o derivación a especialistas.',
            'duracion'          => '30 minutos',
            'doctor'            => 'Dr. Andrés Gutiérrez',
            'icono'             => '🩺',
        ];

        return view('paciente.servicios.detalle', compact('servicio'));
    }



}
