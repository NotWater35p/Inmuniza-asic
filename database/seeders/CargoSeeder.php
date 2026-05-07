<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cargo;

class CargoSeeder extends Seeder
{
    public function run(): void
    {
        $cargos = [
            ['nombre' => 'Administrador', 'nivel_acceso' => 5],
            ['nombre' => 'Asistente Administrativo', 'nivel_acceso' => 3],
            ['nombre' => 'Jefe de Módulo', 'nivel_acceso' => 2],
            ['nombre' => 'Vacunador', 'nivel_acceso' => 1],
        ];

        foreach ($cargos as $cargo) {
            Cargo::create($cargo);
        }
    }
}