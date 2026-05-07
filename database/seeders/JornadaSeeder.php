<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jornada;
use App\Models\Asic;
use App\Models\Personal;

class JornadaSeeder extends Seeder
{
    public function run(): void
    {
        $asic = Asic::first();
        $responsable = Personal::where('cargo_id', 1)->first(); // Administrador

        $jornadas = [
            [
                'asic_id'               => $asic->id,
                'fecha_jornada'         => '2025-04-15',
                'descripcion'           => 'Jornada de vacunación en Sector Centro',
                'personal_responsable'  => $responsable->cedula,
            ],
            [
                'asic_id'               => $asic->id,
                'fecha_jornada'         => '2025-04-22',
                'descripcion'           => 'Jornada casa por casa en La Manga',
                'personal_responsable'  => $responsable->cedula,
            ],
        ];

        foreach ($jornadas as $jor) {
            Jornada::create($jor);
        }
    }
}