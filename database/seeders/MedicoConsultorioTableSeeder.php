<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class MedicoConsultorioTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_VE');
        $now = now();
        $horarios = [];
        $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

        // Médicos IDs 5, 6, 7
        $medicosIds = [5, 6, 7];

        foreach ($medicosIds as $medicoId) {
            // Un médico trabaja en 1 o 2 consultorios
            $numConsultorios = $faker->numberBetween(1, 2);
            $consultoriosIds = $faker->randomElements(range(1, 8), $numConsultorios);
            
            // Días disponibles para asignar (para que no choquen entre consultorios)
            $diasDisponibles = $diasSemana;
            shuffle($diasDisponibles); // Mezclar para aleatoriedad

            foreach ($consultoriosIds as $consultorioId) {
                // Si no hay días disponibles, saltar este consultorio
                if (empty($diasDisponibles)) break;

                // Asignar 1 o 2 días por consultorio, tomados de los disponibles
                $numDias = $faker->numberBetween(1, min(2, count($diasDisponibles)));
                // Extraer los días de la lista de disponibles
                $diasTrabajo = array_splice($diasDisponibles, 0, $numDias);
                
                foreach ($diasTrabajo as $dia) {
                    $turno = $faker->randomElement(['mañana', 'tarde', 'completo']);
                    
                    if ($turno === 'mañana') {
                        $inicio = '08:00:00';
                        $fin = '12:00:00';
                    } elseif ($turno === 'tarde') {
                        $inicio = '13:00:00';
                        $fin = '17:00:00';
                    } else {
                        $inicio = '08:00:00';
                        $fin = '16:00:00';
                    }

                    $horarios[] = [
                        'medico_id' => $medicoId,
                        'consultorio_id' => $consultorioId,
                        'dia_semana' => $dia,
                        'turno' => $turno,
                        'horario_inicio' => $inicio,
                        'horario_fin' => $fin,
                        'status' => true,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }
        
        // Insertar en chunks
        foreach (array_chunk($horarios, 100) as $chunk) {
            DB::table('medico_consultorio')->insert($chunk);
        }
    }
}
