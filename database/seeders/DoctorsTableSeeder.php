<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctorsTableSeeder extends Seeder
{
    public function run(): void
    {
        $specialties = DB::table('specialty_type')
            ->pluck('id_tipos_especialidad', 'nombre');

        $doctors = [
            [
                'email' => 'andres.mejia@vitalcare.com',
                'specialty' => 'Medicina General',
                'universidad' => 'Universidad Nacional de Colombia',
                'numero_licencia' => 'MG-1001',
                'descripcion' => 'Médico general con amplia experiencia en atención primaria y medicina preventiva.',
            ],
            [
                'email' => 'juliana.martinez@vitalcare.com',
                'specialty' => 'Medicina General',
                'universidad' => 'Universidad de Antioquia',
                'numero_licencia' => 'MG-1002',
                'descripcion' => 'Profesional enfocada en atención integral del paciente y promoción de la salud.',
            ],
            [
                'email' => 'felipe.rojas@vitalcare.com',
                'specialty' => 'Cardiología',
                'universidad' => 'Pontificia Universidad Javeriana',
                'numero_licencia' => 'CD-2001',
                'descripcion' => 'Cardiólogo especializado en insuficiencia cardíaca y rehabilitación cardiovascular.',
            ],
            [
                'email' => 'diana.moreno@vitalcare.com',
                'specialty' => 'Cardiología',
                'universidad' => 'Universidad Industrial de Santander',
                'numero_licencia' => 'CD-2002',
                'descripcion' => 'Especialista en diagnóstico y manejo de enfermedades coronarias y arritmias.',
            ],
            [
                'email' => 'camilo.ortega@vitalcare.com',
                'specialty' => 'Pediatría',
                'universidad' => 'Universidad del Valle',
                'numero_licencia' => 'PD-3001',
                'descripcion' => 'Pediatra con experiencia en desarrollo infantil y enfermedades respiratorias.',
            ],
            [
                'email' => 'paola.fernandez@vitalcare.com',
                'specialty' => 'Pediatría',
                'universidad' => 'Universidad de La Sabana',
                'numero_licencia' => 'PD-3002',
                'descripcion' => 'Apasionada por el bienestar integral de los niños y adolescentes.',
            ],
            [
                'email' => 'santiago.perez@vitalcare.com',
                'specialty' => 'Dermatología',
                'universidad' => 'Universidad del Rosario',
                'numero_licencia' => 'DM-4001',
                'descripcion' => 'Dermatólogo experto en tratamientos estéticos y manejo de patologías cutáneas.',
            ],
            [
                'email' => 'claudia.vargas@vitalcare.com',
                'specialty' => 'Dermatología',
                'universidad' => 'Universidad CES',
                'numero_licencia' => 'DM-4002',
                'descripcion' => 'Enfocada en dermatología clínica y prevención del cáncer de piel.',
            ],
            [
                'email' => 'john.smith@vitalcare.com',
                'specialty' => 'Ginecología',
                'universidad' => 'Harvard Medical School',
                'numero_licencia' => 'GN-5001',
                'descripcion' => 'Ginecólogo con experiencia internacional en salud reproductiva y obstetricia.',
            ],
            [
                'email' => 'natalia.gomez@vitalcare.com',
                'specialty' => 'Ginecología',
                'universidad' => 'Universidad de los Andes',
                'numero_licencia' => 'GN-5002',
                'descripcion' => 'Ginecóloga enfocada en acompañamiento prenatal y salud integral de la mujer.',
            ],
        ];

        $payload = [];

        foreach ($doctors as $doctor) {
            $userId = DB::table('users')
                ->where('correo_electronico', $doctor['email'])
                ->value('id_usuario');

            $specialtyId = $specialties[$doctor['specialty']] ?? null;

            if ($userId && $specialtyId) {
                $payload[] = [
                    'id_usuario' => $userId,
                    'id_tipos_especialidad' => $specialtyId,
                    'universidad' => $doctor['universidad'],
                    'numero_licencia' => $doctor['numero_licencia'],
                    'descripcion' => $doctor['descripcion'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('doctors')->insert($payload);
    }
}
