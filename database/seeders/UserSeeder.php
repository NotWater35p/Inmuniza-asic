<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'personal_cedula' => 30938548,
                'name'            => 'Luis Pérez',
                'email'           => 'admin@ilapeca.gob.ve',
                'password'        => Hash::make('admin123'),
            ],
            [
                'personal_cedula' => 22222222,
                'name'            => 'Carmen Hernández',
                'email'           => 'asistente@ilapeca.gob.ve',
                'password'        => Hash::make('mortadela'),
            ],
            [
                'personal_cedula' => 33333333,
                'name'            => 'Carlos García',
                'email'           => 'jefe@ilapeca.gob.ve',
                'password'        => Hash::make('jefe123'),
            ],
            [
                'personal_cedula' => 44444444,
                'name'            => 'Elena Suárez',
                'email'           => 'vacunador1@ilapeca.gob.ve',
                'password'        => Hash::make('ampolla'),
            ],
            [
                'personal_cedula' => 55555555,
                'name'            => 'Pedro Rivas',
                'email'           => 'vacunador2@ilapeca.gob.ve',
                'password'        => Hash::make('ringo'),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}