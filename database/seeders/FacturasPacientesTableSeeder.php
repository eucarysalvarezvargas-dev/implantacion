<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacturasPacientesTableSeeder extends Seeder
{
    public function run(): void
    {
        $cita = DB::table('citas')->first();
        if (!$cita) return;

        DB::table('facturas_pacientes')->insert([
            'cita_id' => $cita->id,
            'paciente_id' => $cita->paciente_id,
            'medico_id' => $cita->medico_id,
            'monto_usd' => 40.00,
            'tasa_id' => 1, 
            'monto_bs' => 1420.00, 
            'fecha_emision' => now()->format('Y-m-d'),
            'fecha_vencimiento' => now()->addDays(15)->format('Y-m-d'),
            'numero_factura' => 'FAC-'.now()->format('Ymd').'-001',
            'status_factura' => 'Emitida',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
