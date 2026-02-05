<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CitasTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_VE');
        $now = now();
        $citas = [];

        // Obtener IDs existentes para evitar errores de FK
        $pacientesIds = DB::table('pacientes')->pluck('id')->toArray();
        $medicosIds = DB::table('medicos')->pluck('id')->toArray();
        // Intentar obtener especialidades desde la relación medico_especialidad para mayor coherencia, o genéricas
        // Por simplicidad, usamos todas las especialidades disponibles y el medico asignado
        // (En un sistema real, validariamos que el medico tenga esa especialidad)
        $especialidadesIds = DB::table('especialidades')->pluck('id')->toArray();
        $consultoriosIds = DB::table('consultorios')->pluck('id')->toArray();

        // Validar que existan datos
        if (empty($pacientesIds) || empty($medicosIds) || empty($especialidadesIds) || empty($consultoriosIds)) {
            return;
        }

        // Generar 150 citas
        for ($i = 0; $i < 150; $i++) {
            // Fecha aleatoria
            $fecha = $faker->dateTimeBetween('-2 months', '+2 months');
            $horaInicio = $faker->randomElement(['08:00:00', '09:00:00', '10:00:00', '11:00:00', '14:00:00', '15:00:00', '16:00:00']);
            $horaFin = date('H:i:s', strtotime($horaInicio) + 1800);
            
            if ($fecha < $now) {
                $estado = $faker->randomElement(['Completada', 'Cancelada', 'No Asistió']);
            } else {
                $estado = $faker->randomElement(['Programada', 'Confirmada']);
            }

            $citas[] = [
                'paciente_id' => $faker->randomElement($pacientesIds),
                'medico_id' => $faker->randomElement($medicosIds),
                'especialidad_id' => $faker->randomElement($especialidadesIds),
                'consultorio_id' => $faker->randomElement($consultoriosIds),
                'fecha_cita' => $fecha->format('Y-m-d'),
                'hora_inicio' => $horaInicio,
                'hora_fin' => $horaFin,
                'duracion_minutos' => 30,
                'tarifa' => $faker->randomFloat(2, 20, 100),
                'tipo_consulta' => $faker->randomElement(['Presencial', 'Online']),
                'estado_cita' => $estado,
                'observaciones' => $faker->sentence,
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($citas, 50) as $chunk) {
            DB::table('citas')->insert($chunk);
        }
    }
}
