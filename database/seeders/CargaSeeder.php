<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Carga;
use App\Models\Asic;
use App\Models\Vacuna;

class CargaSeeder extends Seeder
{
    public function run(): void
    {
        $asic = Asic::first();
        $vacunaBCG = Vacuna::where('nombre', 'BCG')->first();
        $vacunaHepB = Vacuna::where('nombre', 'Hepatitis B')->first();
        $vacunaPolio = Vacuna::where('nombre', 'Polio inactivada (IPV)')->first();
        $vacunaPenta = Vacuna::where('nombre', 'Pentavalente')->first();
        $vacunaCovid = Vacuna::where('nombre', 'COVID-19')->first();

        $cargas = [
            [
                'asic_id'           => $asic->id,
                'vacuna_id'         => $vacunaBCG->id,
                'lote'              => 'BCG-2025-01',
                'fecha_llegada'     => '2025-04-01',
                'fecha_vencimiento' => '2026-04-01',
                'cantidad'          => 200,
            ],
            [
                'asic_id'           => $asic->id,
                'vacuna_id'         => $vacunaHepB->id,
                'lote'              => 'HEPB-2025-03',
                'fecha_llegada'     => '2025-04-05',
                'fecha_vencimiento' => '2026-04-05',
                'cantidad'          => 150,
            ],
            [
                'asic_id'           => $asic->id,
                'vacuna_id'         => $vacunaPolio->id,
                'lote'              => 'IPV-2025-02',
                'fecha_llegada'     => '2025-04-10',
                'fecha_vencimiento' => '2026-04-10',
                'cantidad'          => 300,
            ],
            [
                'asic_id'           => $asic->id,
                'vacuna_id'         => $vacunaPenta->id,
                'lote'              => 'PENTA-2025-01',
                'fecha_llegada'     => '2025-04-12',
                'fecha_vencimiento' => '2026-04-12',
                'cantidad'          => 250,
            ],
            [
                'asic_id'           => $asic->id,
                'vacuna_id'         => $vacunaCovid->id,
                'lote'              => 'COVID-2025-04',
                'fecha_llegada'     => '2025-04-14',
                'fecha_vencimiento' => '2025-10-14',
                'cantidad'          => 500,
            ],
        ];

        foreach ($cargas as $carga) {
            Carga::create($carga);
        }
    }
}