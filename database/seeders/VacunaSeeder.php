<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vacuna;
use App\Models\Marca;

class VacunaSeeder extends Seeder
{
    public function run(): void
    {
        $pfizer = Marca::where('nombre', 'Pfizer')->first();
        $sinovac = Marca::where('nombre', 'Sinovac')->first();
        $astrazeneca = Marca::where('nombre', 'AstraZeneca')->first();
        $butantan = Marca::where('nombre', 'Instituto Butantan')->first();
        $serum = Marca::where('nombre', 'Serum Institute of India')->first();

        $vacunas = [
            [
                'nombre'             => 'BCG',
                'marca_id'           => $butantan->id,
                'presentacion'       => 'Frasco ampolla 10 dosis',
                'enfermedad'         => 'Tuberculosis',
                'dosificacion'       => '0.1 ml',
                'via_administracion' => 'Intradérmica',
                'intervalo'          => 'Única dosis',
                'refuerzo'           => 'No',
                'numero_dosis'       => 1,
                'descripcion'        => 'Vacuna contra la tuberculosis',
            ],
            [
                'nombre'             => 'Hepatitis B',
                'marca_id'           => $serum->id,
                'presentacion'       => 'Frasco ampolla 1 dosis',
                'enfermedad'         => 'Hepatitis B',
                'dosificacion'       => '0.5 ml (niños), 1 ml (adultos)',
                'via_administracion' => 'Intramuscular',
                'intervalo'          => '0, 1, 6 meses',
                'refuerzo'           => 'No',
                'numero_dosis'       => 3,
                'descripcion'        => 'Protege contra la hepatitis B',
            ],
            [
                'nombre'             => 'Polio inactivada (IPV)',
                'marca_id'           => $pfizer->id,
                'presentacion'       => 'Frasco ampolla 10 dosis',
                'enfermedad'         => 'Poliomielitis',
                'dosificacion'       => '0.5 ml',
                'via_administracion' => 'Intramuscular',
                'intervalo'          => '2, 4, 6-18 meses, refuerzo 4-6 años',
                'refuerzo'           => 'Sí',
                'numero_dosis'       => 4,
                'descripcion'        => 'Vacuna inactivada contra polio',
            ],
            [
                'nombre'             => 'Pentavalente',
                'marca_id'           => $serum->id,
                'presentacion'       => 'Frasco ampolla 1 dosis',
                'enfermedad'         => 'Difteria, Tétanos, Tos ferina, Hepatitis B, Hib',
                'dosificacion'       => '0.5 ml',
                'via_administracion' => 'Intramuscular',
                'intervalo'          => '2, 4, 6 meses',
                'refuerzo'           => 'No',
                'numero_dosis'       => 3,
                'descripcion'        => 'Vacuna combinada pentavalente',
            ],
            [
                'nombre'             => 'COVID-19',
                'marca_id'           => $sinovac->id,
                'presentacion'       => 'Frasco ampolla 2 dosis',
                'enfermedad'         => 'COVID-19',
                'dosificacion'       => '0.5 ml',
                'via_administracion' => 'Intramuscular',
                'intervalo'          => '28 días entre dosis',
                'refuerzo'           => 'Sí (anual)',
                'numero_dosis'       => 2,
                'descripcion'        => 'Vacuna inactivada contra SARS-CoV-2',
            ],
        ];

        foreach ($vacunas as $vac) {
            Vacuna::create($vac);
        }
    }
}