<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Representante;

class RepresentanteSeeder extends Seeder
{
    public function run(): void
    {
        $representantes = [
            [
                'cedula'   => 12345678,
                'telefono' => '0412-1234567',
                'relacion' => 'Madre',
            ],
            [
                'cedula'   => 87654321,
                'telefono' => '0414-9876543',
                'relacion' => 'Padre',
            ],
            [
                'cedula'   => 11223344,
                'telefono' => '0426-5554433',
                'relacion' => 'Abuela',
            ],
        ];

        foreach ($representantes as $rep) {
            Representante::create($rep);
        }
    }
}
