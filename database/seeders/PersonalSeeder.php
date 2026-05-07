<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Personal;
use App\Models\Asic;
use App\Models\Cargo;

class PersonalSeeder extends Seeder
{
    public function run(): void
    {
        $asic = Asic::first(); // ASIC Ilapeca
        $cargoAdmin = Cargo::where('nombre', 'Administrador')->first();
        $cargoAsistente = Cargo::where('nombre', 'Asistente Administrativo')->first();
        $cargoJefe = Cargo::where('nombre', 'Jefe de Módulo')->first();
        $cargoVacunador = Cargo::where('nombre', 'Vacunador')->first();

        $personales = [
            [
                'cedula'    => 30938548,
                'asic_id'   => $asic->id,
                'nombre'    => 'Dany',
                'apellido'  => 'Herazo',
                'cargo_id'  => $cargoAdmin->id,
                'telefono'  => '0412-1111111',
                'correo'    => 'danyherazo548@ilapeca.gob.ve',
            ],
            [
                'cedula'    => 22222222,
                'asic_id'   => $asic->id,
                'nombre'    => 'Carmen',
                'apellido'  => 'Hernández',
                'cargo_id'  => $cargoAsistente->id,
                'telefono'  => '0414-2222222',
                'correo'    => 'carmen.hernandez@ilapeca.gob.ve',
            ],
            [
                'cedula'    => 33333333,
                'asic_id'   => $asic->id,
                'nombre'    => 'Carlos',
                'apellido'  => 'García',
                'cargo_id'  => $cargoJefe->id,
                'telefono'  => '0416-3333333',
                'correo'    => 'carlos.garcia@ilapeca.gob.ve',
            ],
            [
                'cedula'    => 44444444,
                'asic_id'   => $asic->id,
                'nombre'    => 'Elena',
                'apellido'  => 'Suárez',
                'cargo_id'  => $cargoVacunador->id,
                'telefono'  => '0424-4444444',
                'correo'    => 'elena.suarez@ilapeca.gob.ve',
            ],
            [
                'cedula'    => 55555555,
                'asic_id'   => $asic->id,
                'nombre'    => 'Pedro',
                'apellido'  => 'Rivas',
                'cargo_id'  => $cargoVacunador->id,
                'telefono'  => '0412-5555555',
                'correo'    => 'pedro.rivas@ilapeca.gob.ve',
            ],
        ];

        foreach ($personales as $p) {
            Personal::create($p);
        }
    }
}