<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class MedicoEspecialidadTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $relaciones = [];

        // Médico 1 (ID 5) - Cardiología (ID 1)
        $relaciones[] = [
            'medico_id' => 5,
            'especialidad_id' => 1,
            'tarifa' => 50.00,
            'anos_experiencia' => 15
        ];

        // Médico 1 (ID 5) - Medicina General (ID 7) - Segunda especialidad
        $relaciones[] = [
            'medico_id' => 5,
            'especialidad_id' => 7,
            'tarifa' => 30.00,
            'anos_experiencia' => 18
        ];

        // Médico 2 (ID 6) - Pediatría (ID 2)
        $relaciones[] = [
            'medico_id' => 6,
            'especialidad_id' => 2,
            'tarifa' => 45.00,
            'anos_experiencia' => 10
        ];

        // Médico 3 (ID 7) - Traumatología (ID 5)
        $relaciones[] = [
            'medico_id' => 7,
            'especialidad_id' => 5,
            'tarifa' => 60.00,
            'anos_experiencia' => 12
        ];

        foreach ($relaciones as $relacion) {
            DB::table('medico_especialidad')->updateOrInsert(
                ['medico_id' => $relacion['medico_id'], 'especialidad_id' => $relacion['especialidad_id']],
                array_merge($relacion, [
                    'status' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }
}
