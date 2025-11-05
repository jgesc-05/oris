<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecuta los seeders
        $this->call([
            DocumentTypeSeeder::class,
            UserTypeSeeder::class,
            UsersTableSeeder::class,
        ]);

        // Puedes dejar el ejemplo del usuario si quieres
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }





}
