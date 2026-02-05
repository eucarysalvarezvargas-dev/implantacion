<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacturaCabeceraTableSeeder extends Seeder
{
    public function run(): void
    {
        $cita = DB::table('citas')->first();
        if (!$cita) return;

        DB::table('factura_cabecera')->insert([
            'cita_id' => $cita->id,
            'nro_control' => 'CTL-'.now()->format('Ymd').'-001',
            'paciente_id' => $cita->paciente_id,
            'medico_id' => $cita->medico_id,
            'tasa_id' => 1,
            'fecha_emision' => now(),
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
