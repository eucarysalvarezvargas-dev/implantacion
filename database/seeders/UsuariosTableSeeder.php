<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UsuariosTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_VE');

        // Función para aplicar MD5 dos veces (según lógica del sistema actual)
        $doubleMd5 = function($password) {
            return md5(md5($password));
        };

        $passwordComun = $doubleMd5('12345678'); // Contraseña genérica para pruebas
        $now = now();

        $usuarios = [];

        // =========================================================================
        // 1. ROOT ADMINISTRATOR (ID 1)
        // =========================================================================
        $usuarios[] = [
            'id' => 1,
            'rol_id' => 1, // Administrador
            'correo' => 'admin@clinica.com',
            'password' => $passwordComun,
            'status' => true,
            'email_verified_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // =========================================================================
        // 2. ADDITIONAL ADMINISTRATORS (IDs 2-4)
        // =========================================================================
        // Admin 1
        $usuarios[] = [
            'id' => 2,
            'rol_id' => 1,
            'correo' => 'admin1@clinica.com',
            'password' => $passwordComun,
            'status' => true,
            'email_verified_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ];
        // Admin 2
        $usuarios[] = [
            'id' => 3,
            'rol_id' => 1,
            'correo' => 'admin2@clinica.com',
            'password' => $passwordComun,
            'status' => true,
            'email_verified_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ];
        // Admin 3
        $usuarios[] = [
            'id' => 4,
            'rol_id' => 1,
            'correo' => 'admin3@clinica.com',
            'password' => $passwordComun,
            'status' => true,
            'email_verified_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // =========================================================================
        // 3. DOCTORS (IDs 5-7)
        // =========================================================================
        // Doctor 1
        $usuarios[] = [
            'id' => 5,
            'rol_id' => 2, // Médico
            'correo' => 'medico1@clinica.com',
            'password' => $passwordComun,
            'status' => true,
            'email_verified_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ];
        // Doctor 2
        $usuarios[] = [
            'id' => 6,
            'rol_id' => 2, // Médico
            'correo' => 'medico2@clinica.com',
            'password' => $passwordComun,
            'status' => true,
            'email_verified_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ];
        // Doctor 3
        $usuarios[] = [
            'id' => 7,
            'rol_id' => 2, // Médico
            'correo' => 'medico3@clinica.com',
            'password' => $passwordComun,
            'status' => true,
            'email_verified_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // =========================================================================
        // 4. PATIENTS (IDs 8-10)
        // =========================================================================
        // Paciente 1
        $usuarios[] = [
            'id' => 8,
            'rol_id' => 3, // Paciente
            'correo' => 'paciente1@clinica.com',
            'password' => $passwordComun,
            'status' => true,
            'email_verified_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ];
        // Paciente 2
        $usuarios[] = [
            'id' => 9,
            'rol_id' => 3, // Paciente
            'correo' => 'paciente2@clinica.com',
            'password' => $passwordComun,
            'status' => true,
            'email_verified_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ];
        // Paciente 3
        $usuarios[] = [
            'id' => 10,
            'rol_id' => 3, // Paciente
            'correo' => 'paciente3@clinica.com',
            'password' => $passwordComun,
            'status' => true,
            'email_verified_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // Insertar usuarios
        DB::table('usuarios')->insert($usuarios);
    }
}
