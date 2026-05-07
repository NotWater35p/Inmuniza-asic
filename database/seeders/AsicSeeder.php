<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asic;

class AsicSeeder extends Seeder
{
    public function run(): void
    {
        Asic::create([
            'rif'       => 'J-12345678-9',
            'nombre'    => 'ASIC Ilapeca',
            'direccion' => 'Sector El Delirio, Margen sur de la Av. 10 , Calle 26-A',
            'telefono'  => '0285-1234567',
        ]);

    }
}