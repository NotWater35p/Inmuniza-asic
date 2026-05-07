<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Etnia;

class EtniaSeeder extends Seeder
{
    public function run(): void
    {
        $etnias = [
            ['nombre' => 'Warao'],
            ['nombre' => 'Pemón'],
            ['nombre' => 'Yanomami'],
            ['nombre' => 'Kariña'],
            ['nombre' => 'Wayúu'],
            ['nombre' => 'No aplica'], // para pacientes no indígenas
        ];

        foreach ($etnias as $etnia) {
            Etnia::create($etnia);
        }
    }
}