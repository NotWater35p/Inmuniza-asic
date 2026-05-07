<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sector;

class SectorSeeder extends Seeder
{
    public function run(): void
    {
        $sectores = [
            ['nombre' => 'Sector Jalisco'],
            ['nombre' => 'Sector El Delirio'],
            ['nombre' => 'Sector Ilapeca'],
            ['nombre' => 'Sector Casco Central'],
            ['nombre' => 'Sector El Recreo'],
            ['nombre' => 'Sector 2 de febrero'],
            ['nombre' => 'Sector La Matica'],
            ['nombre' => 'Sector La Frontera'],
            ['nombre' => 'Sector La Cruz'],
            ['nombre' => 'Sector Juan Gil'],
        ];

        foreach ($sectores as $sector) {
            Sector::create($sector);
        }
    }
}