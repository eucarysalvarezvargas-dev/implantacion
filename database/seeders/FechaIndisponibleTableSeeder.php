<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FechaIndisponibleTableSeeder extends Seeder
{
    public function run(): void
    {
        $medico = DB::table('medicos')->first();
        if (!$medico) return;

        DB::table('fecha_indisponible')->insert([
            'medico_id' => $medico->id,
            'consultorio_id' => null,
            'fecha' => now()->addDays(10)->format('Y-m-d'),
            'motivo' => 'Indisponibilidad de prueba',
            'todo_el_dia' => true,
            'hora_inicio' => null,
            'hora_fin' => null,
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
