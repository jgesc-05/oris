<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SecretaryPortalController extends Controller
{
    public function inicio()
    {
        $secretary = Auth::user();

        $summary = [
            'agendadas_hoy' => 12,
            'pendientes'    => 5,
            'pacientes_hoy' => 18,
        ];

        $agenda = [
            ['hora' => '08:00', 'paciente' => 'Laura Sánchez', 'servicio' => 'Chequeo general', 'medico' => 'Dr. Andrés Salazar'],
            ['hora' => '09:30', 'paciente' => 'Juan Martínez', 'servicio' => 'Control ortodoncia', 'medico' => 'Dra. Catalina Díaz'],
            ['hora' => '11:00', 'paciente' => 'Camila Torres', 'servicio' => 'Exámenes especializados', 'medico' => 'Dra. Laura Hernández'],
        ];

        return view('secretaria.dashboard', compact('secretary', 'summary', 'agenda'));
    }

    public function agenda()
    {
        $entries = [
            ['fecha' => '2025-09-21', 'hora' => '08:00', 'paciente' => 'Laura Sánchez', 'servicio' => 'Chequeo general', 'medico' => 'Dr. Andrés Salazar', 'estado' => 'Confirmada'],
            ['fecha' => '2025-09-21', 'hora' => '09:30', 'paciente' => 'Juan Martínez', 'servicio' => 'Control ortodoncia', 'medico' => 'Dra. Catalina Díaz', 'estado' => 'Pendiente'],
            ['fecha' => '2025-09-21', 'hora' => '10:00', 'paciente' => 'Felipe Márquez', 'servicio' => 'Valoración inicial', 'medico' => 'Dra. Laura Hernández', 'estado' => 'Confirmada'],
            ['fecha' => '2025-09-21', 'hora' => '11:30', 'paciente' => 'Ana Restrepo', 'servicio' => 'Exámenes especializados', 'medico' => 'Dr. Mario Pineda', 'estado' => 'Reprogramada'],
        ];

        return view('secretaria.agenda.index', compact('entries'));
    }

    public function servicios()
    {
        $especialidades = collect([
            ['nombre' => 'Medicina general',      'descripcion' => 'Seguimiento integral del estado de salud.',       'icono' => '🩺'],
            ['nombre' => 'Pediatría',             'descripcion' => 'Atención especializada para niños y niñas.',      'icono' => '👶'],
            ['nombre' => 'Cardiología',           'descripcion' => 'Tratamiento de enfermedades del corazón.',        'icono' => '❤️'],
            ['nombre' => 'Dermatología',          'descripcion' => 'Cuidado de la piel, cabello y uñas.',             'icono' => '🧴'],
            ['nombre' => 'Neurología',            'descripcion' => 'Trastornos del sistema nervioso.',               'icono' => '🧠'],
            ['nombre' => 'Rehabilitación física', 'descripcion' => 'Recuperación de la movilidad y funcionalidad.',  'icono' => '🏃‍♀️'],
        ])->map(function ($item) {
            $item['slug'] = Str::slug($item['nombre']);
            return $item;
        })->toArray();

        return view('secretaria.servicios.index', compact('especialidades'));
    }

    public function serviciosEspecialidad(string $especialidad)
    {
        $especialidadData = [
            'nombre' => Str::title(str_replace('-', ' ', $especialidad)),
            'slug'   => $especialidad,
        ];

        $servicios = collect([
            ['nombre' => 'Consulta general', 'descripcion' => 'Evaluación médica completa y diagnóstico inicial.', 'icono' => '🩺'],
            ['nombre' => 'Chequeo preventivo', 'descripcion' => 'Revisión periódica para detectar factores de riesgo.', 'icono' => '📋'],
            ['nombre' => 'Atención de urgencias leves', 'descripcion' => 'Atención rápida a emergencias menores.', 'icono' => '🚑'],
            ['nombre' => 'Exámenes especializados', 'descripcion' => 'Pruebas médicas según indicaciones clínicas.', 'icono' => '🧪'],
        ])->map(function ($item) use ($especialidad) {
            $item['slug'] = Str::slug($item['nombre']);
            $item['especialidad_slug'] = $especialidad;
            return $item;
        })->toArray();

        return view('secretaria.servicios.especialidad', [
            'especialidad' => $especialidadData,
            'servicios'    => $servicios,
        ]);
    }

    public function serviciosDetalle(string $especialidad, string $servicio)
    {
        $detalle = [
            'nombre'            => Str::title(str_replace('-', ' ', $servicio)),
            'especialidad'      => Str::title(str_replace('-', ' ', $especialidad)),
            'especialidad_slug' => $especialidad,
            'descripcion_corta' => 'Evaluación médica integral y orientación diagnóstica.',
            'descripcion_larga' => 'Este servicio incluye una valoración clínica completa realizada por un profesional de la salud, con enfoque preventivo y diagnóstico. Ideal para chequeos, control de síntomas o derivación a especialistas.',
            'duracion'          => '30 minutos',
            'doctor'            => 'Equipo médico especializado',
            'icono'             => '🩺',
        ];

        return view('secretaria.servicios.detalle', ['servicio' => $detalle]);
    }

    public function medicos()
    {
        $especialidades = collect([
            ['nombre' => 'Medicina general',      'descripcion' => 'Seguimiento integral del estado de salud.',       'icono' => '🩺'],
            ['nombre' => 'Pediatría',             'descripcion' => 'Atención especializada para niños y niñas.',      'icono' => '👶'],
            ['nombre' => 'Cardiología',           'descripcion' => 'Tratamiento de enfermedades del corazón.',        'icono' => '❤️'],
            ['nombre' => 'Dermatología',          'descripcion' => 'Cuidado de la piel, cabello y uñas.',             'icono' => '🧴'],
            ['nombre' => 'Neurología',            'descripcion' => 'Trastornos del sistema nervioso.',               'icono' => '🧠'],
            ['nombre' => 'Rehabilitación física', 'descripcion' => 'Recuperación de la movilidad y funcionalidad.',  'icono' => '🏃‍♀️'],
        ])->map(function ($item) {
            $item['slug'] = Str::slug($item['nombre']);
            return $item;
        })->toArray();

        return view('secretaria.medicos.index', compact('especialidades'));
    }

    public function medicosEspecialidad(string $especialidad)
    {
        $especialidadData = [
            'nombre' => Str::title(str_replace('-', ' ', $especialidad)),
            'slug'   => $especialidad,
        ];

        $medicos = collect([
            [
                'nombre'         => 'Dra. Laura Hernández',
                'descripcion'    => 'Especialista en atención preventiva y control de enfermedades crónicas.',
                'formacion'      => 'Médico cirujano — Universidad Nacional',
                'experiencia'    => '10 años',
                'disponibilidad' => 'Lunes a viernes — 8:00 a.m. - 4:00 p.m.',
            ],
            [
                'nombre'         => 'Dr. Andrés Salazar',
                'descripcion'    => 'Enfoque en diagnóstico temprano y medicina familiar.',
                'formacion'      => 'Especialista en Medicina Familiar — Universidad Javeriana',
                'experiencia'    => '8 años',
                'disponibilidad' => 'Martes y jueves — 10:00 a.m. - 6:00 p.m.',
            ],
            [
                'nombre'         => 'Dra. Catalina Díaz',
                'descripcion'    => 'Atención integral a pacientes con condiciones crónicas.',
                'formacion'      => 'Medicina interna — Universidad de los Andes',
                'experiencia'    => '12 años',
                'disponibilidad' => 'Miércoles y sábado — 9:00 a.m. - 2:00 p.m.',
            ],
        ])->map(function ($item) use ($especialidad) {
            $item['slug'] = Str::slug($item['nombre']);
            $item['especialidad_slug'] = $especialidad;
            return $item;
        })->toArray();

        return view('secretaria.medicos.especialidad', [
            'especialidad' => $especialidadData,
            'medicos'      => $medicos,
        ]);
    }

    public function medicosDetalle(string $especialidad, string $medico)
    {
        $detalle = [
            'nombre'              => Str::title(str_replace('-', ' ', $medico)),
            'especialidad'        => Str::title(str_replace('-', ' ', $especialidad)),
            'especialidad_slug'   => $especialidad,
            'descripcion'         => 'Profesional con enfoque humano y preventivo, acompañando procesos de diagnóstico y tratamiento integral.',
            'formacion'           => 'Médico cirujano — Universidad Nacional, especialización en Medicina interna.',
            'experiencia'         => 'Más de 10 años en consulta externa y hospitalaria.',
            'disponibilidad'      => 'Lunes a viernes — 8:00 a.m. - 4:00 p.m.',
            'icono'               => '👩‍⚕️',
        ];

        return view('secretaria.medicos.detalle', [
            'medico' => $detalle,
        ]);
    }
}
