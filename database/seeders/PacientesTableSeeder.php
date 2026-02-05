<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PacientesTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $pacientes = [];

        // 1. Pacientes (IDs 8-10)
        // Paciente 1 (ID 8)
        $pacientes[] = [
            'user_id' => 8,
            'primer_nombre' => 'José',
            'primer_apellido' => 'Méndez',
            'tipo_documento' => 'V',
            'numero_documento' => '21000008',
            'fecha_nac' => '1995-02-14',
            'estado_id' => 1,
            'ciudad_id' => 1,
            'municipio_id' => 1,
            'parroquia_id' => 1,
            'direccion_detallada' => 'Barrio 5 de Julio',
            'prefijo_tlf' => '+58',
            'numero_tlf' => '4240000008',
            'genero' => 'Masculino',
            'ocupacion' => 'Estudiante',
            'estado_civil' => 'Soltero',
            'status' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // Paciente 2 (ID 9)
        $pacientes[] = [
            'user_id' => 9,
            'primer_nombre' => 'Carmen',
            'primer_apellido' => 'Ortiz',
            'tipo_documento' => 'V',
            'numero_documento' => '22000009',
            'fecha_nac' => '1960-09-30',
            'estado_id' => 1,
            'ciudad_id' => 1,
            'municipio_id' => 1,
            'parroquia_id' => 1,
            'direccion_detallada' => 'Residencias Los Andes',
            'prefijo_tlf' => '+58',
            'numero_tlf' => '4160000009',
            'genero' => 'Femenino',
            'ocupacion' => 'Jubilada',
            'estado_civil' => 'Viuda',
            'status' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // Paciente 3 (ID 10)
        $pacientes[] = [
            'user_id' => 10,
            'primer_nombre' => 'Luis',
            'primer_apellido' => 'Silva',
            'tipo_documento' => 'V',
            'numero_documento' => '23000010',
            'fecha_nac' => '2010-06-01',
            'estado_id' => 1,
            'ciudad_id' => 1,
            'municipio_id' => 1,
            'parroquia_id' => 1,
            'direccion_detallada' => 'Urbanización del Este',
            'prefijo_tlf' => '+58',
            'numero_tlf' => '4120000010',
            'genero' => 'Masculino',
            'ocupacion' => 'Estudiante',
            'estado_civil' => 'Soltero',
            'status' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        DB::table('pacientes')->insert($pacientes);
    }
}
