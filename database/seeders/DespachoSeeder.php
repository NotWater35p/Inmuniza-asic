<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Despacho;
use App\Models\Asic;
use App\Models\Modulo;
use App\Models\Vacuna;
use App\Models\Personal;

class DespachoSeeder extends Seeder
{
    public function run(): void
    {
        $asic = Asic::first();
        $moduloManga = Modulo::where('nombre', 'Módulo La Manga')->first();
        $moduloCambio = Modulo::where('nombre', 'Módulo El Cambio')->first();
        $vacunaCovid = Vacuna::where('nombre', 'COVID-19')->first();
        $vacunaPolio = Vacuna::where('nombre', 'Polio inactivada (IPV)')->first();
        $responsable = Personal::where('cedula', 30938548)->first(); 

        $despachos = [
            [
                'asic_id'           => $asic->id,
                'modulo_id'         => $moduloManga->id,
                'vacuna_id'         => $vacunaCovid->id,
                'fecha_envio'       => '2025-04-16',
                'responsable_envio' => $responsable->cedula,
                'cantidad'          => 50,
            ],
            [
                'asic_id'           => $asic->id,
                'modulo_id'         => $moduloCambio->id,
                'vacuna_id'         => $vacunaPolio->id,
                'fecha_envio'       => '2025-04-18',
                'responsable_envio' => $responsable->cedula,
                'cantidad'          => 30,
            ],
        ];

        foreach ($despachos as $des) {
            Despacho::create($des);
        }
    }
}