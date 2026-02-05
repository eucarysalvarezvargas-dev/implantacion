<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfiguracionRepartoTableSeeder extends Seeder
{
    public function run(): void
    {
        $medicos = DB::table('medicos')->pluck('id')->toArray();
        $consultorioId = 1;

        if (empty($medicos)) return;

        foreach ($medicos as $medicoId) {
            DB::table('configuracion_reparto')->insert([
                'medico_id' => $medicoId,
                'consultorio_id' => $consultorioId,
                'porcentaje_medico' => 70.00,
                'porcentaje_consultorio' => 20.00,
                'porcentaje_sistema' => 10.00,
                'observaciones' => 'Configuración estándar',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
