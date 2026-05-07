<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Modulo;
use App\Models\Asic;
use App\Models\Personal;
use App\Models\Cargo;

class ModuloSeeder extends Seeder
{
    public function run(): void
    {
        $asic  = Asic::first();
        $jefe  = Personal::where('cedula', 33333333)->first(); // Carlos García - Jefe de Módulo

        $modulos = [
            [
                'asic_id'     => $asic->id,
                'rif'         => 'J-11111111-1',
                'nombre'      => 'Los Prados',
                'direccion'   => 'Calle 3, Sector La Manga',
                'telefono'    => '0285-1111111',
                'jefe_cedula' => $jefe?->cedula,
            ],
            [
                'asic_id'     => $asic->id,
                'rif'         => 'J-22222222-2',
                'nombre'      => 'Materno Juan Gil',
                'direccion'   => 'Av. Principal, El Cambio',
                'telefono'    => '0285-2222222',
                'jefe_cedula' => null,
            ],
            [
                'asic_id'     => $asic->id,
                'rif'         => 'J-33333333-3',
                'nombre'      => 'Barranquitas',
                'direccion'   => 'Calle 7, Sector 5 de Julio',
                'telefono'    => '0285-3333333',
                'jefe_cedula' => null,
            ],
                        [
                'asic_id'     => $asic->id,
                'rif'         => 'J-33333888-3',
                'nombre'      => 'San Ignacio',
                'direccion'   => 'Calle 7, Sector 5 de Julio',
                'telefono'    => '0285-3333333',
                'jefe_cedula' => null,
            ],
             [
                'asic_id'     => $asic->id,
                'rif'         => 'J-44444444-4',
                'nombre'      => 'CDI Ilapeca',
                'direccion'   => 'Sector El Delirio de la victoria, Calle 26-A',
                'telefono'    => '0285-4444444',
                'jefe_cedula' => null,
            ],
        ];

        foreach ($modulos as $mod) {
            Modulo::create($mod);
        }
    }
}