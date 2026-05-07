<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            CargoSeeder::class,
            AsicSeeder::class,
            MarcaSeeder::class,
            EtniaSeeder::class,
            SectorSeeder::class,
            RepresentanteSeeder::class,
            PersonalSeeder::class,
            UserSeeder::class,
            ModuloSeeder::class,
            VacunaSeeder::class,
            PacienteSeeder::class,
            JornadaSeeder::class,
            CargaSeeder::class,
            DespachoSeeder::class,
            TratamientoSeeder::class,
        ]);
    }
}
