<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SolicitudesHistorialTableSeeder extends Seeder
{
    public function run(): void
    {
        $cita = DB::table('citas')->first();
        $medicoSolicitante = DB::table('medicos')->where('id', '!=', $cita ? $cita->medico_id : 0)->first() ?? DB::table('medicos')->first();
        
        if (!$cita || !$medicoSolicitante) return;

        DB::table('solicitudes_historial')->insert([
            'cita_id' => $cita->id,
            'paciente_id' => $cita->paciente_id,
            'medico_solicitante_id' => $medicoSolicitante->id, 
            'medico_propietario_id' => $cita->medico_id, 
            'token_validacion' => 'TOKEN123',
            'token_expira_at' => now()->addHours(24),
            'intentos_fallidos' => 0,
            'motivo_solicitud' => 'Interconsulta',
            'estado_permiso' => 'Pendiente',
            'acceso_valido_hasta' => null,
            'observaciones' => 'Solicitud de prueba',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
