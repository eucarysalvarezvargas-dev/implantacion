<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class MedicosTableSeeder extends Seeder
{
    public function run(): void
    {
        $medicos = [];
        $now = now();

        // 1. Médicos (IDs 5-7)
        // Médico 1 (ID 5) - Cardiólogo
        $medicos[] = [
            'id' => 5,
            'user_id' => 5,
            'primer_nombre' => 'Carlos',
            'primer_apellido' => 'Vargas',
            'tipo_documento' => 'V',
            'numero_documento' => '18000005',
            'fecha_nac' => '1980-08-20',
            'estado_id' => 1,
            'ciudad_id' => 1,
            'municipio_id' => 1,
            'parroquia_id' => 1,
            'direccion_detallada' => 'Consultorio Central',
            'prefijo_tlf' => '+58',
            'numero_tlf' => '4140000005',
            'genero' => 'Masculino',
            'nro_colegiatura' => 'MP-12345',
            'formacion_academica' => 'Médico Cirujano - UCV\nEspecialista en Cardiología',
            'experiencia_profesional' => '15 años de experiencia en cardiología clínica.',
            'status' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // Médico 2 (ID 6) - Pediatra
        $medicos[] = [
            'id' => 6,
            'user_id' => 6,
            'primer_nombre' => 'Elena',
            'primer_apellido' => 'Gómez',
            'tipo_documento' => 'V',
            'numero_documento' => '19000006',
            'fecha_nac' => '1985-04-12',
            'estado_id' => 1,
            'ciudad_id' => 1,
            'municipio_id' => 1,
            'parroquia_id' => 1,
            'direccion_detallada' => 'Urb. El Parque',
            'prefijo_tlf' => '+58',
            'numero_tlf' => '4240000006',
            'genero' => 'Femenino',
            'nro_colegiatura' => 'MP-67890',
            'formacion_academica' => 'Médico Cirujano - UCLA\nPediatría y Puericultura',
            'experiencia_profesional' => '10 años cuidando la salud de los niños.',
            'status' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // Médico 3 (ID 7) - Traumatólogo
        $medicos[] = [
            'id' => 7,
            'user_id' => 7,
            'primer_nombre' => 'Roberto',
            'primer_apellido' => 'Díaz',
            'tipo_documento' => 'V',
            'numero_documento' => '20000007',
            'fecha_nac' => '1978-11-05',
            'estado_id' => 1,
            'ciudad_id' => 1,
            'municipio_id' => 1,
            'parroquia_id' => 1,
            'direccion_detallada' => 'Av. Los Próceres',
            'prefijo_tlf' => '+58',
            'numero_tlf' => '4120000007',
            'genero' => 'Masculino',
            'nro_colegiatura' => 'MP-55555',
            'formacion_academica' => 'Médico Cirujano - ULA\nTraumatología y Ortopedia',
            'experiencia_profesional' => 'Especialista en lesiones deportivas.',
            'status' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        DB::table('medicos')->insert($medicos);
    }
}
