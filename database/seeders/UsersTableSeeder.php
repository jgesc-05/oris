<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener IDs desde los seeders previos
        $admin = DB::table('user_types')->where('nombre', 'Administrador')->value('id_tipo_usuario');
        $medico = DB::table('user_types')->where('nombre', 'Médico')->value('id_tipo_usuario');
        $secretaria = DB::table('user_types')->where('nombre', 'Secretaria')->value('id_tipo_usuario');
        $paciente = DB::table('user_types')->where('nombre', 'Paciente')->value('id_tipo_usuario');

        $cc = DB::table('document_type')->where('name', 'Cédula de ciudadanía')->value('id_tipo_documento');
        $ti = DB::table('document_type')->where('name', 'Tarjeta de identidad')->value('id_tipo_documento');
        $ce = DB::table('document_type')->where('name', 'Cédula de extranjería')->value('id_tipo_documento');
        $pa = DB::table('document_type')->where('name', 'Pasaporte')->value('id_tipo_documento');

        // Contraseña general
        $password = Hash::make('12345678');

        DB::table('users')->insert([
            // ==== ADMINISTRADORES ====
            [
                'id_tipo_usuario' => $admin,
                'id_tipo_documento' => $cc,
                'numero_documento' => '1001001001',
                'nombres' => 'Laura',
                'apellidos' => 'González',
                'correo_electronico' => 'laura.administradora@vitalcare.com',
                'password' => $password,
            ],
            [
                'id_tipo_usuario' => $admin,
                'id_tipo_documento' => $cc,
                'numero_documento' => '1001001002',
                'nombres' => 'Carlos',
                'apellidos' => 'Ramírez',
                'correo_electronico' => 'carlos.admin@vitalcare.com',
                'password' => $password,
            ],

            // ==== SECRETARIAS ====
            [
                'id_tipo_usuario' => $secretaria,
                'id_tipo_documento' => $cc,
                'numero_documento' => '1012003001',
                'nombres' => 'María',
                'apellidos' => 'López',
                'correo_electronico' => 'maria.secretaria@vitalcare.com',
                'password' => $password,
            ],
            [
                'id_tipo_usuario' => $secretaria,
                'id_tipo_documento' => $cc,
                'numero_documento' => '1012003002',
                'nombres' => 'Sandra',
                'apellidos' => 'Cárdenas',
                'correo_electronico' => 'sandra.secretaria@vitalcare.com',
                'password' => $password,
            ],

            // ==== MÉDICOS ====
            [
                'id_tipo_usuario' => $medico,
                'id_tipo_documento' => $cc,
                'numero_documento' => '1023004001',
                'nombres' => 'Andrés',
                'apellidos' => 'Mejía',
                'correo_electronico' => 'andres.mejia@vitalcare.com',
                'password' => $password,
            ],
            [
                'id_tipo_usuario' => $medico,
                'id_tipo_documento' => $cc,
                'numero_documento' => '1023004002',
                'nombres' => 'Juliana',
                'apellidos' => 'Martínez',
                'correo_electronico' => 'juliana.martinez@vitalcare.com',
                'password' => $password,
            ],
            [
                'id_tipo_usuario' => $medico,
                'id_tipo_documento' => $cc,
                'numero_documento' => '1023004003',
                'nombres' => 'Felipe',
                'apellidos' => 'Rojas',
                'correo_electronico' => 'felipe.rojas@vitalcare.com',
                'password' => $password,
            ],
            [
                'id_tipo_usuario' => $medico,
                'id_tipo_documento' => $cc,
                'numero_documento' => '1023004004',
                'nombres' => 'Diana',
                'apellidos' => 'Moreno',
                'correo_electronico' => 'diana.moreno@vitalcare.com',
                'password' => $password,
            ],
            [
                'id_tipo_usuario' => $medico,
                'id_tipo_documento' => $cc,
                'numero_documento' => '1023004005',
                'nombres' => 'Camilo',
                'apellidos' => 'Ortega',
                'correo_electronico' => 'camilo.ortega@vitalcare.com',
                'password' => $password,
            ],
            [
                'id_tipo_usuario' => $medico,
                'id_tipo_documento' => $cc,
                'numero_documento' => '1023004006',
                'nombres' => 'Paola',
                'apellidos' => 'Fernández',
                'correo_electronico' => 'paola.fernandez@vitalcare.com',
                'password' => $password,
            ],
            [
                'id_tipo_usuario' => $medico,
                'id_tipo_documento' => $cc,
                'numero_documento' => '1023004007',
                'nombres' => 'Santiago',
                'apellidos' => 'Pérez',
                'correo_electronico' => 'santiago.perez@vitalcare.com',
                'password' => $password,
            ],
            [
                'id_tipo_usuario' => $medico,
                'id_tipo_documento' => $ce,
                'numero_documento' => 'E1000456',
                'nombres' => 'Claudia',
                'apellidos' => 'Vargas',
                'correo_electronico' => 'claudia.vargas@vitalcare.com',
                'password' => $password,
            ],
            [
                'id_tipo_usuario' => $medico,
                'id_tipo_documento' => $pa,
                'numero_documento' => 'P1234501',
                'nombres' => 'John',
                'apellidos' => 'Smith',
                'correo_electronico' => 'john.smith@vitalcare.com',
                'password' => $password,
            ],
            [
                'id_tipo_usuario' => $medico,
                'id_tipo_documento' => $cc,
                'numero_documento' => '1023004008',
                'nombres' => 'Natalia',
                'apellidos' => 'Gómez',
                'correo_electronico' => 'natalia.gomez@vitalcare.com',
                'password' => $password,
            ],

            // ==== PACIENTES ====
            [
                'id_tipo_usuario' => $paciente,
                'id_tipo_documento' => $cc,
                'numero_documento' => '1105001001',
                'nombres' => 'Juan',
                'apellidos' => 'Torres',
                'correo_electronico' => 'juan.torres@gmail.com',
                'password' => $password,
            ],
            [
                'id_tipo_usuario' => $paciente,
                'id_tipo_documento' => $cc,
                'numero_documento' => '1105001002',
                'nombres' => 'Camila',
                'apellidos' => 'Mendoza',
                'correo_electronico' => 'camila.mendoza@hotmail.com',
                'password' => $password,
            ],
            [
                'id_tipo_usuario' => $paciente,
                'id_tipo_documento' => $ti,
                'numero_documento' => '980123456',
                'nombres' => 'Mateo',
                'apellidos' => 'Suárez',
                'correo_electronico' => 'mateo.suarez@gmail.com',
                'password' => $password,
            ],
            [
                'id_tipo_usuario' => $paciente,
                'id_tipo_documento' => $cc,
                'numero_documento' => '1105001003',
                'nombres' => 'Valentina',
                'apellidos' => 'Castaño',
                'correo_electronico' => 'valen.castano@gmail.com',
                'password' => $password,
            ],
            [
                'id_tipo_usuario' => $paciente,
                'id_tipo_documento' => $cc,
                'numero_documento' => '1105001004',
                'nombres' => 'Esteban',
                'apellidos' => 'García',
                'correo_electronico' => 'esteban.garcia@gmail.com',
                'password' => $password,
            ],
            [
                'id_tipo_usuario' => $paciente,
                'id_tipo_documento' => $cc,
                'numero_documento' => '1105001005',
                'nombres' => 'Daniela',
                'apellidos' => 'López',
                'correo_electronico' => 'daniela.lopez@gmail.com',
                'password' => $password,
            ],
            [
                'id_tipo_usuario' => $paciente,
                'id_tipo_documento' => $ce,
                'numero_documento' => 'E9087766',
                'nombres' => 'Ana',
                'apellidos' => 'Rojas',
                'correo_electronico' => 'ana.rojas@yahoo.com',
                'password' => $password,
            ],
            [
                'id_tipo_usuario' => $paciente,
                'id_tipo_documento' => $cc,
                'numero_documento' => '1105001006',
                'nombres' => 'Sofía',
                'apellidos' => 'Martínez',
                'correo_electronico' => 'sofia.martinez@gmail.com',
                'password' => $password,
            ],
            [
                'id_tipo_usuario' => $paciente,
                'id_tipo_documento' => $cc,
                'numero_documento' => '1105001007',
                'nombres' => 'Julián',
                'apellidos' => 'Gómez',
                'correo_electronico' => 'julian.gomez@gmail.com',
                'password' => $password,
            ],
            [
                'id_tipo_usuario' => $paciente,
                'id_tipo_documento' => $cc,
                'numero_documento' => '1105001008',
                'nombres' => 'Tatiana',
                'apellidos' => 'Rincón',
                'correo_electronico' => 'tatiana.rincon@gmail.com',
                'password' => $password,
            ],
            [
                'id_tipo_usuario' => $paciente,
                'id_tipo_documento' => $cc,
                'numero_documento' => '1105001009',
                'nombres' => 'Sebastián',
                'apellidos' => 'Vega',
                'correo_electronico' => 'sebastian.vega@hotmail.com',
                'password' => $password,
            ],
            [
                'id_tipo_usuario' => $paciente,
                'id_tipo_documento' => $cc,
                'numero_documento' => '1105001010',
                'nombres' => 'Laura',
                'apellidos' => 'Morales',
                'correo_electronico' => 'laura.morales@gmail.com',
                'password' => $password,
            ],
            [
                'id_tipo_usuario' => $paciente,
                'id_tipo_documento' => $cc,
                'numero_documento' => '1105001011',
                'nombres' => 'Andrés',
                'apellidos' => 'Pardo',
                'correo_electronico' => 'andres.pardo@gmail.com',
                'password' => $password,
            ],
            [
                'id_tipo_usuario' => $paciente,
                'id_tipo_documento' => $ti,
                'numero_documento' => '991234567',
                'nombres' => 'Isabella',
                'apellidos' => 'Jiménez',
                'correo_electronico' => 'isabella.jimenez@gmail.com',
                'password' => $password,
            ],
            [
                'id_tipo_usuario' => $paciente,
                'id_tipo_documento' => $cc,
                'numero_documento' => '1105001012',
                'nombres' => 'Cristian',
                'apellidos' => 'Nieto',
                'correo_electronico' => 'cristian.nieto@gmail.com',
                'password' => $password,
            ],
            [
                'id_tipo_usuario' => $paciente,
                'id_tipo_documento' => $cc,
                'numero_documento' => '1105001013',
                'nombres' => 'Daniel',
                'apellidos' => 'Bermúdez',
                'correo_electronico' => 'daniel.bermudez@gmail.com',
                'password' => $password,
            ],
            [
                'id_tipo_usuario' => $paciente,
                'id_tipo_documento' => $cc,
                'numero_documento' => '1105001014',
                'nombres' => 'Tatiana',
                'apellidos' => 'Rojas',
                'correo_electronico' => 'tatiana.rojas@hotmail.com',
                'password' => $password,
            ],
            [
                'id_tipo_usuario' => $paciente,
                'id_tipo_documento' => $cc,
                'numero_documento' => '1105001015',
                'nombres' => 'Héctor',
                'apellidos' => 'Linares',
                'correo_electronico' => 'hector.linares@gmail.com',
                'password' => $password,
            ],
        ]);
    }
}
