<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicesTableSeeder extends Seeder
{
    public function run(): void
    {
        $specialties = DB::table('specialty_type')
            ->pluck('id_tipos_especialidad', 'nombre');

        $servicesBySpecialty = [
            'Medicina General' => [
                [
                    'nombre' => 'Consulta general integral',
                    'descripcion' => 'Valoración inicial, revisión de antecedentes y plan preventivo.',
                    'duracion' => 30,
                    'precio_base' => 80000,
                ],
                [
                    'nombre' => 'Control de seguimiento',
                    'descripcion' => 'Seguimiento de tratamientos crónicos y ajustes de medicación.',
                    'duracion' => 20,
                    'precio_base' => 60000,
                ],
            ],
            'Cardiología' => [
                [
                    'nombre' => 'Consulta cardiológica',
                    'descripcion' => 'Evaluación del sistema cardiovascular y plan diagnóstico.',
                    'duracion' => 40,
                    'precio_base' => 150000,
                ],
                [
                    'nombre' => 'Prueba de esfuerzo',
                    'descripcion' => 'Valoración del ritmo cardiaco y capacidad física controlada.',
                    'duracion' => 45,
                    'precio_base' => 220000,
                ],
            ],
            'Pediatría' => [
                [
                    'nombre' => 'Consulta pediátrica',
                    'descripcion' => 'Revisión de crecimiento, vacunas y hábitos saludables.',
                    'duracion' => 30,
                    'precio_base' => 90000,
                ],
                [
                    'nombre' => 'Control de desarrollo',
                    'descripcion' => 'Evaluación de hitos del desarrollo y orientación familiar.',
                    'duracion' => 25,
                    'precio_base' => 85000,
                ],
            ],
            'Dermatología' => [
                [
                    'nombre' => 'Consulta dermatológica',
                    'descripcion' => 'Diagnóstico de afecciones cutáneas y plan de tratamiento.',
                    'duracion' => 30,
                    'precio_base' => 120000,
                ],
                [
                    'nombre' => 'Procedimientos dérmicos menores',
                    'descripcion' => 'Extracción de lunares, queratosis o lesiones superficiales.',
                    'duracion' => 35,
                    'precio_base' => 180000,
                ],
            ],
            'Ginecología' => [
                [
                    'nombre' => 'Consulta ginecológica',
                    'descripcion' => 'Valoración integral de la salud reproductiva femenina.',
                    'duracion' => 35,
                    'precio_base' => 130000,
                ],
                [
                    'nombre' => 'Control prenatal',
                    'descripcion' => 'Seguimiento de embarazo, educación y planificación del parto.',
                    'duracion' => 40,
                    'precio_base' => 160000,
                ],
            ],
        ];

        $rows = [];

        foreach ($servicesBySpecialty as $specialtyName => $services) {
            $specialtyId = $specialties[$specialtyName] ?? null;

            if (!$specialtyId) {
                continue;
            }

            foreach ($services as $service) {
                $rows[] = [
                    'id_tipos_especialidad' => $specialtyId,
                    'nombre' => $service['nombre'],
                    'duracion' => $service['duracion'],
                    'precio_base' => $service['precio_base'],
                    'estado' => 'activo',
                    'descripcion' => $service['descripcion'],
                ];
            }
        }

        DB::table('services')->insert($rows);
    }
}
