<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadosTableSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            ['id_estado' => 1, 'estado' => 'Distrito Capital', 'iso_3166_2' => 'CCS'],
            ['id_estado' => 2, 'estado' => 'Amazonas', 'iso_3166_2' => 'AMA'],
            ['id_estado' => 3, 'estado' => 'Anzoátegui', 'iso_3166_2' => 'ANZ'],
            ['id_estado' => 4, 'estado' => 'Apure', 'iso_3166_2' => 'APU'],
            ['id_estado' => 5, 'estado' => 'Aragua', 'iso_3166_2' => 'ARA'],
            ['id_estado' => 6, 'estado' => 'Barinas', 'iso_3166_2' => 'BAR'],
            ['id_estado' => 7, 'estado' => 'Bolívar', 'iso_3166_2' => 'BOL'],
            ['id_estado' => 8, 'estado' => 'Carabobo', 'iso_3166_2' => 'CAR'],
            ['id_estado' => 9, 'estado' => 'Cojedes', 'iso_3166_2' => 'COJ'],
            ['id_estado' => 10, 'estado' => 'Delta Amacuro', 'iso_3166_2' => 'DEL'],
            ['id_estado' => 11, 'estado' => 'Falcón', 'iso_3166_2' => 'FAL'],
            ['id_estado' => 12, 'estado' => 'Guárico', 'iso_3166_2' => 'GUA'],
            ['id_estado' => 13, 'estado' => 'Lara', 'iso_3166_2' => 'LAR'],
            ['id_estado' => 14, 'estado' => 'Mérida', 'iso_3166_2' => 'MER'],
            ['id_estado' => 15, 'estado' => 'Miranda', 'iso_3166_2' => 'MIR'],
            ['id_estado' => 16, 'estado' => 'Monagas', 'iso_3166_2' => 'MON'],
            ['id_estado' => 17, 'estado' => 'Nueva Esparta', 'iso_3166_2' => 'NUE'],
            ['id_estado' => 18, 'estado' => 'Portuguesa', 'iso_3166_2' => 'POR'],
            ['id_estado' => 19, 'estado' => 'Sucre', 'iso_3166_2' => 'SUC'],
            ['id_estado' => 20, 'estado' => 'Táchira', 'iso_3166_2' => 'TAC'],
            ['id_estado' => 21, 'estado' => 'Trujillo', 'iso_3166_2' => 'TRU'],
            ['id_estado' => 22, 'estado' => 'Vargas', 'iso_3166_2' => 'VAR'], // O La Guaira
            ['id_estado' => 23, 'estado' => 'Yaracuy', 'iso_3166_2' => 'YAR'],
            ['id_estado' => 24, 'estado' => 'Zulia', 'iso_3166_2' => 'ZUL'],
        ];

        foreach ($estados as $estado) {
            DB::table('estados')->updateOrInsert(
                ['id_estado' => $estado['id_estado']],
                array_merge($estado, [
                    'status' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
