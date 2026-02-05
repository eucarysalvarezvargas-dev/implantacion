<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AdministradoresTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_VE');
        $now = now();
        $admins = [];

        // 1. Administrador Principal (Root) - User ID 1
        $admins[] = [
            'user_id' => 1,
            'primer_nombre' => 'Super',
            'primer_apellido' => 'Admin',
            'tipo_documento' => 'V',
            'numero_documento' => '10000001',
            'fecha_nac' => '1985-01-01',
            'estado_id' => 1,
            'ciudad_id' => 1,
            'municipio_id' => 1,
            'parroquia_id' => 1,
            'direccion_detallada' => 'Sede Central',
            'prefijo_tlf' => '+58',
            'numero_tlf' => '4120000001',
            'genero' => 'Masculino',
            'tipo_admin' => 'Root',
            'status' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // 2. Administradores Adicionales - User IDs 2, 3, 4
        // Admin 1 (ID 2)
        $admins[] = [
            'user_id' => 2,
            'primer_nombre' => 'Ana',
            'primer_apellido' => 'Ramos',
            'tipo_documento' => 'V',
            'numero_documento' => '15000002',
            'fecha_nac' => '1990-05-15',
            'estado_id' => 1,
            'ciudad_id' => 1,
            'municipio_id' => 1,
            'parroquia_id' => 1,
            'direccion_detallada' => $faker->address,
            'prefijo_tlf' => '+58',
            'numero_tlf' => '4140000002',
            'genero' => 'Femenino',
            'tipo_admin' => 'Supervisor',
            'status' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // Admin 2 (ID 3)
        $admins[] = [
            'user_id' => 3,
            'primer_nombre' => 'Pedro',
            'primer_apellido' => 'Castillo',
            'tipo_documento' => 'V',
            'numero_documento' => '16000003',
            'fecha_nac' => '1988-11-20',
            'estado_id' => 1,
            'ciudad_id' => 1,
            'municipio_id' => 1,
            'parroquia_id' => 1,
            'direccion_detallada' => $faker->address,
            'prefijo_tlf' => '+58',
            'numero_tlf' => '4160000003',
            'genero' => 'Masculino',
            'tipo_admin' => 'Recepcionista',
            'status' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // Admin 3 (ID 4)
        $admins[] = [
            'user_id' => 4,
            'primer_nombre' => 'Luisa',
            'primer_apellido' => 'Mendoza',
            'tipo_documento' => 'V',
            'numero_documento' => '17000004',
            'fecha_nac' => '1992-03-10',
            'estado_id' => 1,
            'ciudad_id' => 1,
            'municipio_id' => 1,
            'parroquia_id' => 1,
            'direccion_detallada' => $faker->address,
            'prefijo_tlf' => '+58',
            'numero_tlf' => '4240000004',
            'genero' => 'Femenino',
            'tipo_admin' => 'Recepcionista',
            'status' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        DB::table('administradores')->insert($admins);
    }
}
