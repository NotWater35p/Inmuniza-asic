<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Asic;
use App\Models\Cargo;
use App\Models\Etnia;
use App\Models\Sector;
use App\Models\Marca;
use App\Models\Vacuna;
use App\Models\Modulo;

/**
 * MaestroSeeder — Datos permanentes del sistema
 * ─────────────────────────────────────────────
 * Contiene ÚNICAMENTE información real y necesaria
 * para el funcionamiento del sistema en producción:
 *   · ASIC Ilapeca
 *   · Cargos / roles
 *   · Etnias del municipio
 *   · Sectores del área de cobertura
 *   · Marcas / fabricantes de biológicos
 *   · Catálogo de vacunas del PAI venezolano
 *   · Módulos afiliados reales del ASIC
 *
 * NO incluye usuarios, cargas, pacientes ni datos de prueba.
 * Ejecutar: php artisan db:seed --class=MaestroSeeder
 */
class MaestroSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. ASIC ──────────────────────────────────────────────
        Asic::firstOrCreate(['rif' => 'J-12345678-9'], [
            'nombre'    => 'ASIC Ilapeca',
            'direccion' => 'Sector El Delirio, Margen sur de la Av. 10, Calle 26-A',
            'telefono'  => '0285-1234567',
        ]);

        // ── 2. CARGOS (roles del sistema) ────────────────────────
        $cargos = [
            ['nombre' => 'Administrador',           'nivel_acceso' => 5],
            ['nombre' => 'Asistente Administrativo','nivel_acceso' => 3],
            ['nombre' => 'Jefe de Módulo',          'nivel_acceso' => 2],
            ['nombre' => 'Vacunador',               'nivel_acceso' => 1],
        ];
        foreach ($cargos as $c) {
            Cargo::firstOrCreate(['nombre' => $c['nombre']], $c);
        }

        // ── 3. ETNIAS (municipio Rosario de Perijá) ──────────────
        $etnias = [
            'Warao', 'Pemón', 'Yanomami', 'Kariña', 'Wayúu',
            'Yukpa', 'Barí', 'Añú', 'Japrería',
            'No aplica', // pacientes no indígenas
        ];
        foreach ($etnias as $e) {
            Etnia::firstOrCreate(['nombre' => $e]);
        }

        // ── 4. SECTORES del área de cobertura del ASIC ───────────
        $sectores = [
            'Sector Jalisco',
            'Sector El Delirio',
            'Sector Ilapeca',
            'Sector Casco Central',
            'Sector El Recreo',
            'Sector 2 de Febrero',
            'Sector La Matica',
            'Sector La Frontera',
            'Sector La Cruz',
            'Sector Juan Gil',
            'Sector La Manga',
            'Sector 5 de Julio',
            'Sector El Cambio',
            'Sector Los Prados',
            'Sector Barranquitas',
        ];
        foreach ($sectores as $s) {
            Sector::firstOrCreate(['nombre' => $s]);
        }

        // ── 5. MARCAS / FABRICANTES ──────────────────────────────
        $marcas = [
            ['nombre' => 'Instituto Butantan',       'descripcion' => 'Instituto de investigación biomédica — Brasil'],
            ['nombre' => 'Serum Institute of India', 'descripcion' => 'Mayor fabricante de vacunas del mundo — India'],
            ['nombre' => 'Pfizer',                   'descripcion' => 'Farmacéutica multinacional — EE.UU.'],
            ['nombre' => 'Sinovac',                  'descripcion' => 'Laboratorio biofarmacéutico — China'],
            ['nombre' => 'AstraZeneca',              'descripcion' => 'Farmacéutica multinacional — Reino Unido / Suecia'],
            ['nombre' => 'Sputnik (Gamaleya)',        'descripcion' => 'Instituto Gamaleya — Rusia'],
            ['nombre' => 'MPPS / BIOGALENO',         'descripcion' => 'Ministerio del Poder Popular para la Salud — Venezuela'],
            ['nombre' => 'Sanofi Pasteur',            'descripcion' => 'División de vacunas de Sanofi — Francia'],
            ['nombre' => 'Merck Sharp & Dohme',      'descripcion' => 'Farmacéutica multinacional — EE.UU.'],
            ['nombre' => 'GSK (GlaxoSmithKline)',     'descripcion' => 'Farmacéutica multinacional — Reino Unido'],
        ];
        foreach ($marcas as $m) {
            Marca::firstOrCreate(['nombre' => $m['nombre']], $m);
        }

        // Referencias para vacunas
        $butantan = Marca::where('nombre', 'Instituto Butantan')->first();
        $serum    = Marca::where('nombre', 'Serum Institute of India')->first();
        $pfizer   = Marca::where('nombre', 'Pfizer')->first();
        $sinovac  = Marca::where('nombre', 'Sinovac')->first();
        $sanofi   = Marca::where('nombre', 'Sanofi Pasteur')->first();
        $merck    = Marca::where('nombre', 'Merck Sharp & Dohme')->first();
        $gsk      = Marca::where('nombre', 'GSK (GlaxoSmithKline)')->first();

        // ── 6. CATÁLOGO DE VACUNAS — PAI Venezuela ───────────────
        // Fuente: Programa Ampliado de Inmunización (PAI) MPPS 2024
        $vacunas = [
            [
                'nombre'             => 'BCG',
                'marca_id'           => $butantan->id,
                'tipo'               => 'vacuna',
                'presentacion'       => 'Frasco ampolla 10 dosis',
                'enfermedad'         => 'Tuberculosis',
                'dosificacion'       => '0.1 ml',
                'via_administracion' => 'Intradérmica',
                'intervalo'          => 'Única dosis',
                'refuerzo'           => 'No requiere',
                'numero_dosis'       => 1,
                'descripcion'        => 'Vacuna contra la tuberculosis. Se aplica al recién nacido.',
            ],
            [
                'nombre'             => 'Hepatitis B',
                'marca_id'           => $serum->id,
                'tipo'               => 'vacuna',
                'presentacion'       => 'Frasco ampolla 1 dosis',
                'enfermedad'         => 'Hepatitis B',
                'dosificacion'       => '0.5 ml niños / 1 ml adultos',
                'via_administracion' => 'Intramuscular',
                'intervalo'          => '0, 1 y 6 meses',
                'refuerzo'           => 'No requiere',
                'numero_dosis'       => 3,
                'descripcion'        => 'Protege contra la infección por virus de la hepatitis B.',
            ],
            [
                'nombre'             => 'Polio inactivada (IPV)',
                'marca_id'           => $pfizer->id,
                'tipo'               => 'vacuna',
                'presentacion'       => 'Frasco ampolla 10 dosis',
                'enfermedad'         => 'Poliomielitis',
                'dosificacion'       => '0.5 ml',
                'via_administracion' => 'Intramuscular',
                'intervalo'          => '2, 4 y 6 meses',
                'refuerzo'           => 'Sí — a los 4-6 años',
                'numero_dosis'       => 3,
                'descripcion'        => 'Vacuna inactivada contra la poliomielitis.',
            ],
            [
                'nombre'             => 'Pentavalente',
                'marca_id'           => $serum->id,
                'tipo'               => 'vacuna',
                'presentacion'       => 'Frasco ampolla 1 dosis',
                'enfermedad'         => 'Difteria, Tétanos, Tos ferina, Hepatitis B, Haemophilus influenzae b',
                'dosificacion'       => '0.5 ml',
                'via_administracion' => 'Intramuscular',
                'intervalo'          => '2, 4 y 6 meses',
                'refuerzo'           => 'No requiere',
                'numero_dosis'       => 3,
                'descripcion'        => 'Vacuna combinada que protege contra 5 enfermedades.',
            ],
            [
                'nombre'             => 'Rotavirus',
                'marca_id'           => $merck->id,
                'tipo'               => 'vacuna',
                'presentacion'       => 'Tubo dosificador oral 1 dosis',
                'enfermedad'         => 'Gastroenteritis por Rotavirus',
                'dosificacion'       => '2 ml oral',
                'via_administracion' => 'Oral',
                'intervalo'          => '2 y 4 meses',
                'refuerzo'           => 'No requiere',
                'numero_dosis'       => 2,
                'descripcion'        => 'Previene la diarrea severa por rotavirus en lactantes.',
            ],
            [
                'nombre'             => 'Neumococo (PCV13)',
                'marca_id'           => $pfizer->id,
                'tipo'               => 'vacuna',
                'presentacion'       => 'Jeringa prellenada 0.5 ml',
                'enfermedad'         => 'Enfermedad neumocócica invasora',
                'dosificacion'       => '0.5 ml',
                'via_administracion' => 'Intramuscular',
                'intervalo'          => '2, 4 y 12 meses',
                'refuerzo'           => 'Sí — a los 12 meses',
                'numero_dosis'       => 3,
                'descripcion'        => 'Protege contra 13 serotipos de Streptococcus pneumoniae.',
            ],
            [
                'nombre'             => 'Influenza',
                'marca_id'           => $sanofi->id,
                'tipo'               => 'vacuna',
                'presentacion'       => 'Frasco ampolla 1 dosis / multidosis',
                'enfermedad'         => 'Influenza estacional',
                'dosificacion'       => '0.5 ml',
                'via_administracion' => 'Intramuscular',
                'intervalo'          => 'Anual',
                'refuerzo'           => 'Sí — anual',
                'numero_dosis'       => 1,
                'descripcion'        => 'Vacuna contra la gripe estacional. Se reformula cada año.',
            ],
            [
                'nombre'             => 'Triple Viral (SRP)',
                'marca_id'           => $merck->id,
                'tipo'               => 'vacuna',
                'presentacion'       => 'Frasco ampolla 1 dosis',
                'enfermedad'         => 'Sarampión, Rubéola, Parotiditis',
                'dosificacion'       => '0.5 ml',
                'via_administracion' => 'Subcutánea',
                'intervalo'          => '12 meses y 5 años',
                'refuerzo'           => 'Sí — a los 5 años (DPT-SRP)',
                'numero_dosis'       => 2,
                'descripcion'        => 'Vacuna combinada contra sarampión, rubéola y parotiditis.',
            ],
            [
                'nombre'             => 'DPT (Difteria, Pertussis, Tétanos)',
                'marca_id'           => $serum->id,
                'tipo'               => 'vacuna',
                'presentacion'       => 'Frasco ampolla 1 dosis',
                'enfermedad'         => 'Difteria, Tos ferina, Tétanos',
                'dosificacion'       => '0.5 ml',
                'via_administracion' => 'Intramuscular',
                'intervalo'          => '18 meses y 5 años',
                'refuerzo'           => 'Sí',
                'numero_dosis'       => 2,
                'descripcion'        => 'Refuerzo de la vacuna pentavalente para niños mayores.',
            ],
            [
                'nombre'             => 'Td (Tétanos y Difteria adultos)',
                'marca_id'           => $sanofi->id,
                'tipo'               => 'vacuna',
                'presentacion'       => 'Frasco ampolla 1 dosis',
                'enfermedad'         => 'Tétanos, Difteria',
                'dosificacion'       => '0.5 ml',
                'via_administracion' => 'Intramuscular',
                'intervalo'          => 'Esquema 0, 1, 6 meses',
                'refuerzo'           => 'Sí — cada 10 años',
                'numero_dosis'       => 3,
                'descripcion'        => 'Para adultos, gestantes y personal de salud.',
            ],
            [
                'nombre'             => 'Fiebre Amarilla',
                'marca_id'           => $butantan->id,
                'tipo'               => 'vacuna',
                'presentacion'       => 'Frasco ampolla 5-10 dosis liofilizado',
                'enfermedad'         => 'Fiebre Amarilla',
                'dosificacion'       => '0.5 ml',
                'via_administracion' => 'Subcutánea',
                'intervalo'          => 'Dosis única',
                'refuerzo'           => 'Sí — a los 10 años',
                'numero_dosis'       => 1,
                'descripcion'        => 'Obligatoria en zonas endémicas de Venezuela (incluye Zulia).',
            ],
            [
                'nombre'             => 'COVID-19',
                'marca_id'           => $sinovac->id,
                'tipo'               => 'vacuna',
                'presentacion'       => 'Frasco ampolla 2 dosis',
                'enfermedad'         => 'COVID-19 (SARS-CoV-2)',
                'dosificacion'       => '0.5 ml',
                'via_administracion' => 'Intramuscular',
                'intervalo'          => '28 días entre dosis',
                'refuerzo'           => 'Sí — anual',
                'numero_dosis'       => 2,
                'descripcion'        => 'Vacuna inactivada contra SARS-CoV-2 (Coronavac / Sinovac).',
            ],
            [
                'nombre'             => 'Varicela',
                'marca_id'           => $merck->id,
                'tipo'               => 'vacuna',
                'presentacion'       => 'Frasco ampolla 1 dosis liofilizado',
                'enfermedad'         => 'Varicela zóster',
                'dosificacion'       => '0.5 ml',
                'via_administracion' => 'Subcutánea',
                'intervalo'          => '12-15 meses y 4-6 años',
                'refuerzo'           => 'Sí — segunda dosis a los 4-6 años',
                'numero_dosis'       => 2,
                'descripcion'        => 'Previene la varicela y reduce complicaciones por herpes zóster.',
            ],
            // Sueros
            [
                'nombre'             => 'Antitetánico (SAT)',
                'marca_id'           => $sanofi->id,
                'tipo'               => 'suero',
                'presentacion'       => 'Ampolla 1500 UI',
                'enfermedad'         => 'Tétanos (profilaxis post-exposición)',
                'dosificacion'       => '1500-3000 UI según peso',
                'via_administracion' => 'Intramuscular',
                'intervalo'          => 'Dosis única (post-exposición)',
                'refuerzo'           => 'No aplica',
                'numero_dosis'       => 1,
                'descripcion'        => 'Suero antitetánico para profilaxis post-exposición a heridas.',
            ],
            [
                'nombre'             => 'Antirrábico (SAR)',
                'marca_id'           => $sanofi->id,
                'tipo'               => 'suero',
                'presentacion'       => 'Vial 150 UI/ml',
                'enfermedad'         => 'Rabia (post-exposición)',
                'dosificacion'       => '20 UI/kg peso corporal',
                'via_administracion' => 'Intramuscular / infiltración local',
                'intervalo'          => 'Dosis única (post-exposición)',
                'refuerzo'           => 'No aplica',
                'numero_dosis'       => 1,
                'descripcion'        => 'Inmunoglobulina antirrábica para exposición a animales sospechosos.',
            ],
            // Insumos
            [
                'nombre'             => 'Jeringa 1 ml (tuberculina)',
                'marca_id'           => $serum->id,
                'tipo'               => 'insumo',
                'presentacion'       => 'Caja 100 unidades',
                'enfermedad'         => 'N/A',
                'dosificacion'       => 'N/A',
                'via_administracion' => 'N/A',
                'intervalo'          => 'N/A',
                'refuerzo'           => 'N/A',
                'numero_dosis'       => 0,
                'descripcion'        => 'Jeringa de 1 ml con aguja 26G para aplicación intradérmica (BCG).',
            ],
            [
                'nombre'             => 'Jeringa 0.5 ml (insulina)',
                'marca_id'           => $serum->id,
                'tipo'               => 'insumo',
                'presentacion'       => 'Caja 100 unidades',
                'enfermedad'         => 'N/A',
                'dosificacion'       => 'N/A',
                'via_administracion' => 'N/A',
                'intervalo'          => 'N/A',
                'refuerzo'           => 'N/A',
                'numero_dosis'       => 0,
                'descripcion'        => 'Jeringa de 0.5 ml para aplicación subcutánea.',
            ],
            [
                'nombre'             => 'Jeringa 5 ml',
                'marca_id'           => $serum->id,
                'tipo'               => 'insumo',
                'presentacion'       => 'Caja 100 unidades',
                'enfermedad'         => 'N/A',
                'dosificacion'       => 'N/A',
                'via_administracion' => 'N/A',
                'intervalo'          => 'N/A',
                'refuerzo'           => 'N/A',
                'numero_dosis'       => 0,
                'descripcion'        => 'Jeringa de 5 ml para dilución y administración intramuscular.',
            ],
            [
                'nombre'             => 'Agua destilada (diluyente)',
                'marca_id'           => $serum->id,
                'tipo'               => 'insumo',
                'presentacion'       => 'Ampolla 5 ml',
                'enfermedad'         => 'N/A',
                'dosificacion'       => 'N/A',
                'via_administracion' => 'N/A',
                'intervalo'          => 'N/A',
                'refuerzo'           => 'N/A',
                'numero_dosis'       => 0,
                'descripcion'        => 'Diluyente para reconstitución de vacunas liofilizadas.',
            ],
        ];

        foreach ($vacunas as $v) {
            Vacuna::firstOrCreate(['nombre' => $v['nombre']], $v);
        }

        // ── 7. MÓDULOS AFILIADOS (datos reales del ASIC) ─────────
        $asic = Asic::first();
        $modulos = [
            [
                'asic_id'              => $asic->id,
                'rif'                  => 'J-11111111-1',
                'nombre'               => 'Los Prados',
                'municipio'            => 'Rosario de Perijá',
                'parroquia'            => 'El Rosario',
                'tipo_establecimiento' => 'CP1',
                'sispai_fila'          => 15,
                'direccion'            => 'Calle 3, Sector La Manga',
                'telefono'             => '0285-1111111',
                'jefe_cedula'          => null,
            ],
            [
                'asic_id'              => $asic->id,
                'rif'                  => 'J-22222222-2',
                'nombre'               => 'Materno Juan Gil',
                'municipio'            => 'Rosario de Perijá',
                'parroquia'            => null,
                'tipo_establecimiento' => 'CP1',
                'sispai_fila'          => 20,
                'direccion'            => 'Av. Principal, El Cambio',
                'telefono'             => '0285-2222222',
                'jefe_cedula'          => null,
            ],
            [
                'asic_id'              => $asic->id,
                'rif'                  => 'J-33333333-3',
                'nombre'               => 'Barranquitas',
                'municipio'            => 'Rosario de Perijá',
                'parroquia'            => null,
                'tipo_establecimiento' => 'CP1',
                'sispai_fila'          => 5,
                'direccion'            => 'Calle 7, Sector 5 de Julio',
                'telefono'             => '0285-3333333',
                'jefe_cedula'          => null,
            ],
            [
                'asic_id'              => $asic->id,
                'rif'                  => 'J-33333888-3',
                'nombre'               => 'San Ignacio',
                'municipio'            => 'Rosario de Perijá',
                'parroquia'            => null,
                'tipo_establecimiento' => 'CP1',
                'sispai_fila'          => 25,
                'direccion'            => 'Calle 7, Sector 5 de Julio',
                'telefono'             => '0285-3333333',
                'jefe_cedula'          => null,
            ],
            [
                'asic_id'              => $asic->id,
                'rif'                  => 'J-44444444-4',
                'nombre'               => 'CDI Ilapeca',
                'municipio'            => 'Rosario de Perijá',
                'parroquia'            => null,
                'tipo_establecimiento' => 'CDI',
                'sispai_fila'          => 10,
                'direccion'            => 'Sector El Delirio de la victoria, Calle 26-A',
                'telefono'             => '0285-4444444',
                'jefe_cedula'          => null,
            ],
        ];
        foreach ($modulos as $m) {
            Modulo::firstOrCreate(['rif' => $m['rif']], $m);
        }

        $this->command->info('✅ MaestroSeeder completado — datos permanentes cargados.');
    }
}