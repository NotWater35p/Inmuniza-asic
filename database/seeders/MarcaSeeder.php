<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Marca;

class MarcaSeeder extends Seeder
{
    public function run(): void
    {
        $marcas = [
            ['nombre' => 'Pfizer', 'descripcion' => 'Fabricante internacional'],
            ['nombre' => 'Sinovac', 'descripcion' => 'Laboratorio chino'],
            ['nombre' => 'AstraZeneca', 'descripcion' => 'Anglo-sueca'],
            ['nombre' => 'Instituto Butantan', 'descripcion' => 'Brasil'],
            ['nombre' => 'Serum Institute of India', 'descripcion' => 'India'],
        ];

        foreach ($marcas as $marca) {
            Marca::create($marca);
        }
    }
}