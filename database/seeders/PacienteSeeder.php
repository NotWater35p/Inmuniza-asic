<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Paciente;
use App\Models\Etnia;
use App\Models\Sector;
use App\Models\Representante;

class PacienteSeeder extends Seeder
{
    public function run(): void
    {
        $etniaWarao = Etnia::where('nombre', 'Warao')->first();
        $etniaNoAplica = Etnia::where('nombre', 'No aplica')->first();
        $sectorJ = Sector::where('nombre', 'Sector Jalisco')->first();
        $sectorManga = Sector::where('nombre', 'Sector El Delirio')->first();
        $repMaria = Representante::where('cedula', 12345678)->first();
        $repJose = Representante::where('cedula', 87654321)->first();

        $pacientes = [
            [
                'cedula'            => 20123456,
                'nombres'           => 'Juan Carlos',
                'apellidos'         => 'Márquez',
                'fecha_nacimiento'  => '1985-05-12',
                'sexo'              => 'M',
                'telefono'          => '0412-9998887',
                'direccion'         => 'Calle Bolívar #45, Sector Jalisco',
                'etnia_id'          => $etniaNoAplica->id,
                'representante_id'  => null,
                'sector_id'         => $sectorJ->id,
                'activo'            => true,
            ],
            [
                'cedula'            => 20987654,
                'nombres'           => 'Ana María',
                'apellidos'         => 'López',
                'fecha_nacimiento'  => '1990-08-23',
                'sexo'              => 'F',
                'telefono'          => '0414-5554433',
                'direccion'         => 'Av. Principal, Casa 12, La Manga',
                'etnia_id'          => $etniaWarao->id,
                'representante_id'  => null,
                'sector_id'         => $sectorManga->id,
                'activo'            => true,
            ],
            [
                'cedula'            => 30111222,
                'nombres'           => 'Luisana',
                'apellidos'         => 'González',
                'fecha_nacimiento'  => '2018-11-03',
                'sexo'              => 'F',
                'telefono'          => '0426-1239876',
                'direccion'         => 'Sector El Delirio, Calle 5',
                'etnia_id'          => null,
                'representante_id'  => $repMaria->cedula,
                'sector_id'         => null,
                'activo'            => true,
            ],
            [
                'cedula'            => 30222333,
                'nombres'           => 'Jesús',
                'apellidos'         => 'Martínez',
                'fecha_nacimiento'  => '2020-01-15',
                'sexo'              => 'M',
                'telefono'          => null,
                'direccion'         => 'Av. Las Flores, Casa 5',
                'etnia_id'          => null,
                'representante_id'  => $repJose->cedula,
                'sector_id'         => null,
                'activo'            => true,
            ],
        ];

        foreach ($pacientes as $pac) {
            Paciente::create($pac);
        }
    }
}