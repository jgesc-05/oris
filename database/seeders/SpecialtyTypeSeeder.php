<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialtyTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('specialty_type')->insert([
            [
                'nombre' => 'Medicina General',
                'descripcion' => 'Atención primaria en salud, diagnósticos iniciales y remisión a especialistas cuando es necesario.',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Cardiología',
                'descripcion' => 'Diagnóstico y tratamiento de enfermedades del corazón y del sistema cardiovascular.',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Pediatría',
                'descripcion' => 'Atención médica especializada en niños y adolescentes.',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Dermatología',
                'descripcion' => 'Tratamiento de enfermedades y condiciones de la piel, cabello y uñas.',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Ginecología',
                'descripcion' => 'Salud reproductiva femenina, embarazos y control ginecológico.',
                'estado' => 'activo',
            ],
        ]);
    }
}
