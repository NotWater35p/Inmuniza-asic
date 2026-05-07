<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tratamiento;
use App\Models\Jornada;
use App\Models\Paciente;
use App\Models\Vacuna;

class TratamientoSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener jornadas existentes
        $jornada1 = Jornada::where('descripcion', 'Jornada de vacunación en Sector Julio Cesar')->first();
        $jornada2 = Jornada::where('descripcion', 'Jornada casa por casa en El Delirio')->first();

        // Obtener pacientes por nombres y apellidos (ya que ahora tienen id autoincremental)
        $pacienteJuan = Paciente::where('nombres', 'Juan Carlos')->where('apellidos', 'Márquez')->first();
        $pacienteAna  = Paciente::where('nombres', 'Ana María')->where('apellidos', 'López')->first();
        $pacienteLuisana = Paciente::where('nombres', 'Luisana')->where('apellidos', 'González')->first();

        // Vacunas necesarias
        $vacunaCovid = Vacuna::where('nombre', 'COVID-19')->first();
        $vacunaPolio = Vacuna::where('nombre', 'Polio inactivada (IPV)')->first();
        $vacunaBCG   = Vacuna::where('nombre', 'BCG')->first();

        // Verificar que existan todos los elementos
        if (!$jornada1 || !$jornada2 || !$pacienteJuan || !$pacienteAna || !$pacienteLuisana || !$vacunaCovid || !$vacunaPolio || !$vacunaBCG) {
            $this->command->error('Faltan datos de jornadas, pacientes o vacunas. Ejecuta primero los seeders correspondientes.');
            return;
        }

        $tratamientos = [
            [
                'jornada_id'        => $jornada1->id,
                'paciente_id'       => $pacienteJuan->id,
                'vacuna_id'         => $vacunaCovid->id,
                'dosis_aplicada'    => 1,
                'fecha_aplicacion'  => '2025-04-15',
                'observaciones'     => 'Primera dosis COVID-19',
            ],
            [
                'jornada_id'        => $jornada1->id,
                'paciente_id'       => $pacienteAna->id,
                'vacuna_id'         => $vacunaPolio->id,
                'dosis_aplicada'    => 3,
                'fecha_aplicacion'  => '2025-04-15',
                'observaciones'     => 'Tercera dosis IPV',
            ],
            [
                'jornada_id'        => $jornada2->id,
                'paciente_id'       => $pacienteLuisana->id,
                'vacuna_id'         => $vacunaBCG->id,
                'dosis_aplicada'    => 1,
                'fecha_aplicacion'  => '2025-04-22',
                'observaciones'     => 'Recién nacido, aplicación BCG',
            ],
        ];

        foreach ($tratamientos as $trat) {
            Tratamiento::create($trat);
        }
    }
}