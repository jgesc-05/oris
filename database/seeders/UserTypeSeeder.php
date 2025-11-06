<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('user_types')->insert([
            ['nombre' => 'Administrador', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Médico', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Secretaria', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Paciente', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
