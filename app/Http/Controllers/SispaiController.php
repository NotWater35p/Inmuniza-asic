<?php

namespace App\Http\Controllers;

use App\Models\Jornada;
use App\Models\Modulo;
use App\Models\Perdida;
use App\Models\Tratamiento;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SispaIController extends Controller
{
    // ══════════════════════════════════════════════════════════════════════
    //  TABLAS DE CONVERSIÓN — columnas 1-based del Excel SISPAI
    //  Verificadas contra el archivo asic_ilapeca__1_.xlsx (MARZO 2026)
    // ══════════════════════════════════════════════════════════════════════

    /** Meses en español */
    private const MESES = [
        1 => 'Enero',   2 => 'Febrero',   3 => 'Marzo',
        4 => 'Abril',   5 => 'Mayo',      6 => 'Junio',
        7 => 'Julio',   8 => 'Agosto',    9 => 'Septiembre',
        10 => 'Octubre',11 => 'Noviembre',12 => 'Diciembre',
    ];

    /**
     * Nombre exacto de cada hoja en la plantilla Excel.
     * Incluye los espacios finales tal como están en el archivo.
     */
    private const SHEETS = [
        1  => 'ENERO 2026',
        2  => 'FEBRERO 2026 ',
        3  => 'MARZO 2026',
        4  => 'ABRIL ',
        5  => 'MAYO ',
        6  => 'JUNIO',
        7  => 'JULIO ',
        8  => 'AGOSTO',
        9  => 'SEPTIEMBRE ',
        10 => 'OCTUBRE',
        11 => 'NOVIEMBRE ',
        12 => 'DICIEMBRE ',
    ];

    /**
     * tipo_establecimiento → columna Excel "Puestos Activos" (1-based).
     * Notificantes = misma columna + 11 (sección paralela, cols 15-25).
     */
    private const PUESTOS_COL = [
        'CP1'             => 4,
        'CP2'             => 5,
        'CP3'             => 6,
        'HOSPITAL'        => 7,
        'CDI'             => 8,
        'IVSS'            => 9,
        'IPASME'          => 10,
        'SANIDAD MILITAR' => 11,
        'PRIVADO'         => 12,
        'OTROS'           => 13,
    ];

    /**
     * Normaliza el nombre de vacuna (BD) a la clave interna del controlador.
     * Agrega aquí cualquier vacuna nueva que se registre en el sistema.
     */
    private const VACUNA_MAP = [
        'BCG'                          => 'BCG',
        'Hepatitis B'                  => 'HepB',
        'Hepatitis b'                  => 'HepB',
        'Polio inactivada (IPV)'       => 'IPV',
        'Polio inactivada'             => 'IPV',
        'IPV'                          => 'IPV',
        'Polio oral'                   => 'bOPV',
        'Polio Oral'                   => 'bOPV',
        'Polio Oral (bOPV)'            => 'bOPV',
        'bOPV'                         => 'bOPV',
        'Pentavalente'                 => 'Penta',
        'Rotavirus'                    => 'Rota',
        'Neumococo Conjugada'          => 'Neumo',
        'Neumococo conjugada'          => 'Neumo',
        'Neumococo 13V'                => 'Neumo',
        'Influenza Estacional'         => 'Flu',
        'Influenza estacional'         => 'Flu',
        'Fiebre Amarilla'              => 'FA',
        'SRP'                          => 'SRP',
        'Triple Viral'                 => 'SRP',
        'SR'                           => 'SR',
        'Doble Viral'                  => 'SR',
        'Toxoide Tetánico Diftérico'   => 'Td',
        'Td'                           => 'Td',
        'VPH'                          => 'VPH',
        'COVID-19'                     => 'COVID',
        'Covid-19'                     => 'COVID',
        'COVID19'                      => 'COVID',
    ];

    // ── BCG: [col Excel] => [min_dias, max_dias] ─────────────────────────
    private const BCG_AGES = [
        38 => [0,    27],
        39 => [28,   364],
        40 => [365,  729],
        41 => [730,  1094],
        42 => [1095, 1459],
        43 => [1460, 1824],
        44 => [1825, 2189],
        45 => [2190, PHP_INT_MAX],  // 6-7 años
    ];

    // ── Hepatitis B — subtipos no-general → col base (4 cols: D1-D4) ─────
    //    General pediátrica: col 47(≤24h) y 48(1-7D)
    //    General adulto (>7D): cols 69-72 según dosis
    private const HEPB_SUBTYPE_BASE = [
        'personal_salud'    => 49,
        'dialisis'          => 53,
        'privado_libertad'  => 57,
        'trabajador_sexual' => 61,
        'embarazada'        => 65,
    ];

    // ── Rotavirus (<1 año) ─────────────────────────────────────────────────
    private const ROTA_COLS = [1 => 74, 2 => 75, 3 => 76];

    // ── Pentavalente: [edad_años] => [col_base, max_dosis] ────────────────
    //    Verificado contra MARZO 2026 San Ignacio (fila 35)
    private const PENTA = [
        0 => [78,  3],
        1 => [81,  4],
        2 => [85,  4],
        3 => [89,  4],
        4 => [93,  4],
        5 => [97,  4],
        6 => [101, 6],
    ];
    // TOTAL col 107 (fórmula — no escribir)

    // ── IPV: [edad_años] => [col_D1, col_D2|null] ────────────────────────
    //    Verificado contra MARZO 2026 San Ignacio: DD(108)=D1, DE(109)=D2
    private const IPV_COLS = [
        0 => [108, 109],
        1 => [113, 114],
        2 => [118, 119],
        3 => [123, 124],
        4 => [128, 129],
        5 => [133, 134],
        6 => [139, null],
    ];
    // TOTAL IPV col 140 (fórmula)

    // ── bOPV: [edad_años] => [col_D1, col_D2, col_D3|null] ───────────────
    private const bOPV_COLS = [
        0 => [111, 112, null],
        1 => [115, 116, 117],
        2 => [120, 121, 122],
        3 => [125, 126, 127],
        4 => [130, 131, 132],
        5 => [135, 136, 137],
    ];
    // TOTAL bOPV col 141 (fórmula)

    // ── Neumococo conjugada: [edad_años] => [col_D1, col_D2|null] ─────────
    private const NEUMO_COLS = [
        0 => [142, 143],
        1 => [144, null],
    ];
    // TOTAL col 145 (fórmula)

    // ── Influenza — subtipos especiales ───────────────────────────────────
    //    6-11M: 146/147 (D1/D2), 1A: 148/149 (D1/D2)
    private const FLU_SUBTYPE_COL = [
        'personal_salud' => 151,
        'embarazada'     => 152,
    ];

    // ── Dosis Perdidas (columnas JO-KE, índices 275-291) ──────────────────
    private const PERDIDAS_COLS = [
        'BCG'   => 275, 'HepB'  => 276, 'HepBPed' => 277,
        'Rota'  => 278, 'Penta' => 279, 'IPV'     => 280,
        'bOPV'  => 281, 'Neumo' => 282, 'Flu'     => 283,
        'FA'    => 284, 'SRP'   => 285, 'SR'      => 286,
        'Td'    => 287, 'VPH'   => 291,
    ];
    // TOTAL Perdidas col 292 (KF — fórmula)

    // ── Población Indígena (columnas KG-KW, índices 293-309) ──────────────
    private const INDIGENA_COLS = [
        'BCG'   => 293, 'HepB'  => 294, 'HepBPed' => 295,
        'Rota'  => 296, 'Penta' => 297, 'IPV'     => 298,
        'bOPV'  => 299, 'Neumo' => 300, 'Flu'     => 301,
        'FA'    => 302, 'SRP'   => 303, 'SR'      => 304,
        'Td'    => 305, 'VPH'   => 309,
    ];
    // TOTAL Indígenas col 310 (KX — fórmula)

    // ══════════════════════════════════════════════════════════════════════
    //  SECCIONES PARA LA VISTA
    //  Estructura:
    //    'cols'   → [col_excel => 'Etiqueta']      (sección plana)
    //    'grupos' → ['Grupo' => [col => 'Etiqueta']](sección agrupada)
    // ══════════════════════════════════════════════════════════════════════
    private function sections(): array
    {
        return [
            'BCG' => [
                'color' => 'blue',
                'cols'  => [
                    38 => '< 28 días',
                    39 => '28 días – 11 meses',
                    40 => '1 año',
                    41 => '2 años',
                    42 => '3 años',
                    43 => '4 años',
                    44 => '5 años',
                    45 => '6–7 años',
                ],
            ],
            'Hepatitis B' => [
                'color'  => 'green',
                'grupos' => [
                    'Pediátrica ≤ 24 horas' => [47 => 'D.U.'],
                    'Pediátrica 1–7 días'   => [48 => 'D.U.'],
                    'Personal de salud'     => [49 => 'D1', 50 => 'D2', 51 => 'D3', 52 => 'Ref.'],
                    'Pac. en diálisis'      => [53 => 'D1', 54 => 'D2', 55 => 'D3', 56 => 'Ref.'],
                    'Privados de libertad'  => [57 => 'D1', 58 => 'D2', 59 => 'D3', 60 => 'Ref.'],
                    'Trab. sexuales'        => [61 => 'D1', 62 => 'D2', 63 => 'D3', 64 => 'Ref.'],
                    'Embarazadas'           => [65 => 'D1', 66 => 'D2', 67 => 'D3', 68 => 'Ref.'],
                    '6–49 años (general)'  => [69 => 'D1', 70 => 'D2', 71 => 'D3', 72 => 'Ref.'],
                ],
            ],
            'Rotavirus' => [
                'color' => 'amber',
                'cols'  => [
                    74 => 'D1 (< 1 año)',
                    75 => 'D2',
                    76 => 'D3',
                ],
            ],
            'Pentavalente' => [
                'color'  => 'purple',
                'grupos' => [
                    '< 1 año'  => [78 => 'D1', 79 => 'D2', 80 => 'D3'],
                    '1 año'    => [81 => 'D1', 82 => 'D2', 83 => 'D3', 84 => 'D4'],
                    '2 años'   => [85 => 'D1', 86 => 'D2', 87 => 'D3', 88 => 'D4'],
                    '3 años'   => [89 => 'D1', 90 => 'D2', 91 => 'D3', 92 => 'D4'],
                    '4 años'   => [93 => 'D1', 94 => 'D2', 95 => 'D3', 96 => 'D4'],
                    '5 años'   => [97 => 'D1', 98 => 'D2', 99 => 'D3', 100 => 'D4'],
                    '6+ años'  => [101 => 'D1', 102 => 'D2', 103 => 'D3', 104 => 'D4', 105 => 'D5', 106 => 'D6'],
                ],
            ],
            'Polio Inactiva (IPV)' => [
                'color'  => 'red',
                'grupos' => [
                    '< 1 año'  => [108 => 'D1', 109 => 'D2'],
                    '1 año'    => [113 => 'D1', 114 => 'D2'],
                    '2 años'   => [118 => 'D1', 119 => 'D2'],
                    '3 años'   => [123 => 'D1', 124 => 'D2'],
                    '4 años'   => [128 => 'D1', 129 => 'D2'],
                    '5 años'   => [133 => 'D1', 134 => 'D2'],
                    '6–8 años' => [139 => 'D1'],
                ],
            ],
            'Neumococo Conj.' => [
                'color'  => 'orange',
                'grupos' => [
                    '< 1 año' => [142 => 'D1', 143 => 'D2'],
                    '1 año'   => [144 => 'D1'],
                ],
            ],
            'Influenza Estacional' => [
                'color'  => 'teal',
                'grupos' => [
                    '6–11 meses'        => [146 => 'D1', 147 => 'D2'],
                    '1 año'             => [148 => 'D1', 149 => 'D2'],
                    'E. crónicos'       => [150 => ''],
                    'Personal de salud' => [151 => ''],
                    'Embarazadas'       => [152 => ''],
                    'Ad. mayores ≥ 60'  => [153 => ''],
                    'Per. esencial'     => [154 => ''],
                ],
            ],
            'Dosis Perdidas' => [
                'color' => 'rose',
                'cols'  => [
                    275 => 'BCG',       276 => 'Hep.B adult.',
                    277 => 'Hep.B ped.',278 => 'Rotavirus',
                    279 => 'Penta.',    280 => 'Polio IPV',
                    281 => 'Polio OPV', 282 => 'Neumococo 13V',
                    283 => 'Influenza', 284 => 'Fiebre Amarilla',
                    285 => 'SRP',       286 => 'SR',
                    287 => 'Td',        291 => 'VPH',
                ],
            ],
            'Población Indígena' => [
                'color' => 'indigo',
                'cols'  => [
                    293 => 'BCG',       294 => 'Hep.B adult.',
                    295 => 'Hep.B ped.',296 => 'Rotavirus',
                    297 => 'Penta.',    298 => 'Polio IPV',
                    299 => 'Polio OPV', 300 => 'Neumococo 13V',
                    301 => 'Influenza', 302 => 'Fiebre Amarilla',
                    303 => 'SRP',       304 => 'SR',
                    305 => 'Td',        309 => 'VPH',
                ],
            ],
        ];
    }

    // ══════════════════════════════════════════════════════════════════════
    //  ACCIONES PÚBLICAS
    // ══════════════════════════════════════════════════════════════════════

    /** GET /sispai */
    public function index(Request $request)
    {
        $mes  = (int) $request->input('mes',  now()->month);
        $anio = (int) $request->input('anio', now()->year);
        $user = auth()->user();

        $asicId  = $user->personal?->asic_id ?? 1;
        $modulos = Modulo::whereNotNull('sispai_fila')
            ->where('asic_id', $asicId)
            ->orderBy('sispai_fila')
            ->get();

        // Jefe de módulo: sólo ve el suyo
        if ($user->esJefeModulo()) {
            $miModulo = $user->modulo();
            $modulos  = $modulos->filter(fn ($m) => $m->id === $miModulo?->id)->values();
        }

        [$datos, $sinClasificar, $covidTotal] = $this->calcularDesdeDB($mes, $anio, $modulos, $asicId);
        $perdidas = $this->calcularPerdidasDB($mes, $anio, $modulos);

        // Determina qué módulos tuvieron al menos una jornada en el mes
        $tuvoJornadas = Jornada::whereIn('modulo_id', $modulos->pluck('id'))
            ->whereMonth('fecha_jornada', $mes)
            ->whereYear('fecha_jornada',  $anio)
            ->pluck('modulo_id')
            ->unique()
            ->flip()
            ->toArray();

        // Fusionar perdidas calculadas dentro de $datos
        foreach ($perdidas as $mId => $perdCols) {
            foreach ($perdCols as $col => $n) {
                $datos[$mId][$col] = ($datos[$mId][$col] ?? 0) + $n;
            }
        }

        $sections = $this->sections();

        return view('sispai.index', compact(
            'mes', 'anio', 'modulos', 'datos',
            'sinClasificar', 'covidTotal',
            'tuvoJornadas', 'sections'
        ));
    }

    /** POST /sispai/excel — descarga el Excel SISPAI relleno */
    public function excel(Request $request)
    {
        $mes  = (int) $request->input('mes');
        $anio = (int) $request->input('anio');
        $form = $request->input('v', []);   // [modulo_id => [col => valor]]

        $plantilla = storage_path('app/plantillas/sispai.xlsx');
        if (!file_exists($plantilla)) {
            return back()->withErrors([
                'plantilla' => 'Plantilla no encontrada: storage/app/plantillas/sispai.xlsx',
            ]);
        }

        $spreadsheet = IOFactory::load($plantilla);

        // Busca la hoja del mes (tolerando espacios extras en el nombre)
        $hoja = $spreadsheet->getSheetByName(self::SHEETS[$mes] ?? '');
        if (!$hoja) {
            $mesStr = mb_strtoupper(self::MESES[$mes] ?? '');
            foreach ($spreadsheet->getSheetNames() as $sn) {
                if (str_starts_with(trim(mb_strtoupper($sn)), $mesStr)) {
                    $hoja = $spreadsheet->getSheetByName($sn);
                    break;
                }
            }
        }
        if (!$hoja) {
            return back()->withErrors([
                'hoja' => 'Hoja «' . (self::MESES[$mes] ?? $mes) . '» no encontrada en la plantilla.',
            ]);
        }

        $asicId  = auth()->user()->personal?->asic_id ?? 1;
        $modulos = Modulo::whereNotNull('sispai_fila')
            ->where('asic_id', $asicId)
            ->get()
            ->keyBy('id');

        foreach ($form as $moduloId => $colMap) {
            $modulo = $modulos[(int) $moduloId] ?? null;
            if (!$modulo) continue;

            $fila    = (int) $modulo->sispai_fila;
            $tipoCol = self::PUESTOS_COL[$modulo->tipo_establecimiento] ?? null;

            // Puestos activos y notificantes
            if ($tipoCol) {
                $hoja->setCellValue(Coordinate::stringFromColumnIndex($tipoCol)      . $fila, 1);
                $notif = !empty($colMap['notificante']) ? 1 : 0;
                $hoja->setCellValue(Coordinate::stringFromColumnIndex($tipoCol + 11) . $fila, $notif);
            }

            // Datos de vacunas — la clave numérica ES el nro de columna Excel
            foreach ($colMap as $col => $valor) {
                if (!is_numeric($col)) continue;
                $col   = (int) $col;
                $valor = max(0, (int) $valor);
                // Rango seguro: desde puestos activos hasta fin de indígenas
                if ($col >= 4 && $col <= 310) {
                    $hoja->setCellValue(Coordinate::stringFromColumnIndex($col) . $fila, $valor);
                }
            }
        }

        $writer    = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $nombreMes = self::MESES[$mes] ?? $mes;

        return response()->stream(
            fn () => $writer->save('php://output'),
            200,
            [
                'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => "attachment; filename=\"SISPAI_{$nombreMes}_{$anio}.xlsx\"",
                'Cache-Control'       => 'max-age=0',
                'Pragma'              => 'no-cache',
                'Expires'             => '0',
            ]
        );
    }

    /** POST /sispai/pdf — reporte mensual por módulo */
    public function pdf(Request $request)
    {
        $mes      = (int) $request->input('mes',  now()->month);
        $anio     = (int) $request->input('anio', now()->year);
        $moduloId = (int) $request->input('modulo_id');

        $modulo   = Modulo::with('jefe')->findOrFail($moduloId);
        $jornadas = Jornada::with(['tratamientos.vacuna', 'tratamientos.paciente', 'responsable'])
            ->where('modulo_id', $moduloId)
            ->whereMonth('fecha_jornada', $mes)
            ->whereYear('fecha_jornada',  $anio)
            ->get();

        $resumenVacunas = [];
        $totalDosis     = 0;
        $pacientesIds   = collect();

        foreach ($jornadas as $j) {
            foreach ($j->tratamientos as $t) {
                $totalDosis++;
                if ($t->vacuna) {
                    $resumenVacunas[$t->vacuna->nombre] =
                        ($resumenVacunas[$t->vacuna->nombre] ?? 0) + 1;
                }
                if ($t->paciente_id) {
                    $pacientesIds->push($t->paciente_id);
                }
            }
        }

        $totalPacientes = $pacientesIds->unique()->count();
        $nombreMes      = self::MESES[$mes];
        arsort($resumenVacunas);

        $pdf = Pdf::loadView('sispai.pdf', compact(
            'modulo', 'jornadas', 'resumenVacunas',
            'totalDosis', 'totalPacientes', 'nombreMes', 'anio'
        ))->setPaper('letter', 'portrait');

        return $pdf->download("SISPAI_{$modulo->nombre}_{$nombreMes}_{$anio}.pdf");
    }

    // ══════════════════════════════════════════════════════════════════════
    //  CÁLCULO DESDE BD
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Retorna [$datos, $sinClasificar, $covidTotal]
     *
     * $datos[$moduloId][$colExcel]         = n   (listo para escribir en Excel)
     * $sinClasificar[$moduloId][$vacNombre] = n   (descargos sin paciente/edad)
     * $covidTotal[$moduloId]               = n   (COVID: sin sección en SISPAI)
     */
    private function calcularDesdeDB(int $mes, int $anio, $modulos, int $asicId): array
    {
        $datos         = [];
        $sinClasificar = [];
        $covidTotal    = [];

        foreach ($modulos as $m) {
            $datos[$m->id]         = [];
            $sinClasificar[$m->id] = [];
            $covidTotal[$m->id]    = 0;
        }

        $ids = $modulos->pluck('id')->toArray();
        if (empty($ids)) return [$datos, $sinClasificar, $covidTotal];

        $tratamientos = Tratamiento::with(['vacuna', 'paciente.etnia', 'jornada'])
            ->whereMonth('fecha_aplicacion', $mes)
            ->whereYear('fecha_aplicacion',  $anio)
            ->whereHas('jornada', fn ($q) => $q
                ->where('asic_id', $asicId)
                ->whereIn('modulo_id', $ids)
            )
            ->get();

        foreach ($tratamientos as $t) {
            $moduloId  = $t->jornada?->modulo_id;
            if (!$moduloId || !array_key_exists($moduloId, $datos)) continue;

            $vacNombre = $t->vacuna?->nombre ?? '';
            $vacKey    = self::VACUNA_MAP[$vacNombre] ?? null;

            // COVID: contar aparte, no tiene columna propia en SISPAI
            if ($vacKey === 'COVID') {
                $covidTotal[$moduloId]++;
                continue;
            }

            // Sin paciente (descargo rápido) → no se puede calcular edad
            if ($t->es_descargo_rapido || !$t->paciente_id) {
                $sinClasificar[$moduloId][$vacNombre] =
                    ($sinClasificar[$moduloId][$vacNombre] ?? 0) + 1;
                continue;
            }

            if (!$vacKey || !$t->paciente) continue;

            $fechaNac  = Carbon::parse($t->paciente->fecha_nacimiento);
            $fechaVac  = Carbon::parse($t->fecha_aplicacion);
            $edadDias  = (int) $fechaNac->diffInDays($fechaVac);
            $edadAnios = (int) $fechaNac->diffInYears($fechaVac);
            $dosis     = max(1, (int) $t->dosis_aplicada);
            $subtipo   = $t->subtipo_paciente ?? 'general';

            $col = $this->resolverColumna($vacKey, $edadDias, $edadAnios, $dosis, $subtipo);
            if ($col) {
                $datos[$moduloId][$col] = ($datos[$moduloId][$col] ?? 0) + 1;
            }

            // Población indígena (etnia_id != 6 = "No aplica")
            $etniaId = $t->paciente?->etnia_id;
            if ($etniaId && $etniaId !== 6) {
                $colIndi = self::INDIGENA_COLS[$vacKey] ?? null;
                if ($colIndi) {
                    $datos[$moduloId][$colIndi] = ($datos[$moduloId][$colIndi] ?? 0) + 1;
                }
            }
        }

        return [$datos, $sinClasificar, $covidTotal];
    }

    /** Resuelve el número de columna Excel para una combinación dada */
    private function resolverColumna(
        string $vacKey,
        int $edadDias,
        int $edadAnios,
        int $dosis,
        string $subtipo
    ): ?int {
        return match ($vacKey) {
            'BCG'   => $this->colBCG($edadDias),
            'HepB'  => $this->colHepB($edadDias, $edadAnios, $dosis, $subtipo),
            'Rota'  => self::ROTA_COLS[min($dosis, 3)] ?? null,
            'Penta' => $this->colPenta($edadAnios, $dosis),
            'IPV'   => $this->colIPV($edadAnios, $dosis),
            'bOPV'  => $this->colbOPV($edadAnios, $dosis),
            'Neumo' => $this->colNeumo($edadAnios, $dosis),
            'Flu'   => $this->colFlu($edadDias, $edadAnios, $subtipo, $dosis),
            default => null,
        };
    }

    private function colBCG(int $edadDias): int
    {
        foreach (self::BCG_AGES as $col => [$min, $max]) {
            if ($edadDias >= $min && $edadDias <= $max) return $col;
        }
        return 45; // fallback: 6-7 años
    }

    private function colHepB(int $edadDias, int $edadAnios, int $dosis, string $subtipo): ?int
    {
        if ($subtipo === 'general') {
            if ($edadDias === 0) return 47;      // ≤ 24 horas
            if ($edadDias <= 7)  return 48;      // 1-7 días
            return 69 + min($dosis - 1, 3);      // 6-49A: D1-D4 cols 69-72
        }
        $base = self::HEPB_SUBTYPE_BASE[$subtipo] ?? null;
        return $base !== null ? $base + min($dosis - 1, 3) : null;
    }

    private function colPenta(int $edadAnios, int $dosis): int
    {
        $grupo       = min($edadAnios, 6);
        [$base, $md] = self::PENTA[$grupo];
        return $base + min($dosis - 1, $md - 1);
    }

    private function colIPV(int $edadAnios, int $dosis): ?int
    {
        $grupo = min($edadAnios, 6);
        $cols  = array_values(array_filter(self::IPV_COLS[$grupo]));
        return $cols[min($dosis - 1, count($cols) - 1)] ?? null;
    }

    private function colbOPV(int $edadAnios, int $dosis): ?int
    {
        $grupo = min($edadAnios, 5);
        $cols  = array_values(array_filter(self::bOPV_COLS[$grupo] ?? []));
        return $cols[min($dosis - 1, count($cols) - 1)] ?? null;
    }

    private function colNeumo(int $edadAnios, int $dosis): ?int
    {
        $grupo = min($edadAnios, 1);
        $cols  = array_values(array_filter(self::NEUMO_COLS[$grupo] ?? []));
        return $cols[min($dosis - 1, count($cols) - 1)] ?? null;
    }

    private function colFlu(int $edadDias, int $edadAnios, string $subtipo, int $dosis): ?int
    {
        if (isset(self::FLU_SUBTYPE_COL[$subtipo])) return self::FLU_SUBTYPE_COL[$subtipo];
        if ($edadAnios >= 60) return 153;
        if ($edadDias < 365)  return $dosis === 1 ? 146 : 147;  // 6-11 meses
        if ($edadAnios === 1) return $dosis === 1 ? 148 : 149;  // 1 año
        return 150; // e. crónicos (default adulto)
    }

    /** Calcula dosis perdidas del mes por módulo → [modulo_id][col] = n */
    private function calcularPerdidasDB(int $mes, int $anio, $modulos): array
    {
        $result = [];
        foreach ($modulos as $m) $result[$m->id] = [];

        $perdidas = Perdida::with('vacuna')
            ->whereIn('modulo_id', $modulos->pluck('id'))
            ->whereMonth('fecha', $mes)
            ->whereYear('fecha',  $anio)
            ->get();

        foreach ($perdidas as $p) {
            $mId = $p->modulo_id;
            if (!array_key_exists($mId, $result)) continue;
            $key = self::VACUNA_MAP[$p->vacuna?->nombre ?? ''] ?? null;
            $col = $key ? (self::PERDIDAS_COLS[$key] ?? null) : null;
            if (!$col) continue;
            $result[$mId][$col] = ($result[$mId][$col] ?? 0) + $p->cantidad;
        }

        return $result;
    }
}