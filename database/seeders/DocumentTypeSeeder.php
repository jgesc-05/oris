<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('document_type')->insert([
            ['name' => 'Cédula de ciudadanía'],
            ['name' => 'Tarjeta de identidad'],
            ['name' => 'Cédula de extranjería'],
            ['name' => 'Pasaporte'],
            ['name' => 'Registro civil'],
            ['name' => 'Permiso especial de permanencia'],
        ]);
    }
}
