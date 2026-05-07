<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Models\Tratamiento;
use App\Models\Jornada;
use App\Models\Perdida;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SispaIController extends Controller
{
    // ═══════════════════════════════════════════════════════════════
    // MAPEO COMPLETO DE COLUMNAS SISPAI
    // Clave: 'NombreVacuna_GrupoEdad_Dosis' => columna Excel
    // ═══════════════════════════════════════════════════════════════
    const COLUMNAS = [
        // ── BCG ────────────────────────────────────────────────────
        'BCG_menor28d_DU'      => 38,
        'BCG_28d11m_DU'        => 39,
        'BCG_1a_DU'            => 40,
        'BCG_2a_DU'            => 41,
        'BCG_3a_DU'            => 42,
        'BCG_4a_DU'            => 43,
        'BCG_5a_DU'            => 44,
        'BCG_67a_DU'           => 45,

        // ── HEPATITIS B Pediátrica ──────────────────────────────────
        'HEPATITIS B_menor24h_DU'  => 47,
        'HEPATITIS B_17d_DU'       => 48,

        // ── HEPATITIS B Personal Salud ──────────────────────────────
        'HEPATITIS B_salud_1D'  => 49,
        'HEPATITIS B_salud_2D'  => 50,
        'HEPATITIS B_salud_3D'  => 51,
        'HEPATITIS B_salud_DA'  => 52,

        // ── HEPATITIS B Diálisis ────────────────────────────────────
        'HEPATITIS B_dialisis_1D' => 53,
        'HEPATITIS B_dialisis_2D' => 54,
        'HEPATITIS B_dialisis_3D' => 55,
        'HEPATITIS B_dialisis_DA' => 56,

        // ── HEPATITIS B Privados de libertad ───────────────────────
        'HEPATITIS B_privados_1D' => 57,
        'HEPATITIS B_privados_2D' => 58,
        'HEPATITIS B_privados_3D' => 59,
        'HEPATITIS B_privados_DA' => 60,

        // ── HEPATITIS B Trabajadores sexuales ──────────────────────
        'HEPATITIS B_tsexual_1D' => 61,
        'HEPATITIS B_tsexual_2D' => 62,
        'HEPATITIS B_tsexual_3D' => 63,
        'HEPATITIS B_tsexual_DA' => 64,

        // ── HEPATITIS B Embarazadas ─────────────────────────────────
        'HEPATITIS B_embarazada_1D' => 65,
        'HEPATITIS B_embarazada_2D' => 66,
        'HEPATITIS B_embarazada_3D' => 67,
        'HEPATITIS B_embarazada_DA' => 68,

        // ── HEPATITIS B General (6-49 años) ────────────────────────
        'HEPATITIS B_general_1D' => 69,
        'HEPATITIS B_general_2D' => 70,
        'HEPATITIS B_general_3D' => 71,
        'HEPATITIS B_general_DA' => 72,

        // ── ROTAVIRUS < 1 año ───────────────────────────────────────
        'ROTAVIRUS_menor1a_1D' => 74,
        'ROTAVIRUS_menor1a_2D' => 75,
        'ROTAVIRUS_menor1a_3D' => 76,

        // ── PENTAVALENTE < 1 año ────────────────────────────────────
        'PENTAVALENTE_menor1a_1D' => 78,
        'PENTAVALENTE_menor1a_2D' => 79,
        'PENTAVALENTE_menor1a_3D' => 80,

        // ── PENTAVALENTE 1 año ──────────────────────────────────────
        'PENTAVALENTE_1a_1D'   => 81,
        'PENTAVALENTE_1a_2D'   => 82,
        'PENTAVALENTE_1a_3D'   => 83,
        'PENTAVALENTE_1a_1REF' => 84,

        // ── PENTAVALENTE 2 años ─────────────────────────────────────
        'PENTAVALENTE_2a_1D'   => 85,
        'PENTAVALENTE_2a_2D'   => 86,
        'PENTAVALENTE_2a_3D'   => 87,
        'PENTAVALENTE_2a_1REF' => 88,

        // ── PENTAVALENTE 3 años ─────────────────────────────────────
        'PENTAVALENTE_3a_1D'   => 89,
        'PENTAVALENTE_3a_2D'   => 90,
        'PENTAVALENTE_3a_3D'   => 91,
        'PENTAVALENTE_3a_1REF' => 92,

        // ── PENTAVALENTE 4 años ─────────────────────────────────────
        'PENTAVALENTE_4a_1D'   => 93,
        'PENTAVALENTE_4a_2D'   => 94,
        'PENTAVALENTE_4a_3D'   => 95,
        'PENTAVALENTE_4a_1REF' => 96,

        // ── PENTAVALENTE 5 años ─────────────────────────────────────
        'PENTAVALENTE_5a_1D'    => 97,
        'PENTAVALENTE_5a_2D'    => 98,
        'PENTAVALENTE_5a_3D'    => 99,
        'PENTAVALENTE_5a_1REF'  => 100,
        'PENTAVALENTE_5a_2REF'  => 101,

        // ── PENTAVALENTE 6 años ─────────────────────────────────────
        'PENTAVALENTE_6a_1D'    => 102,
        'PENTAVALENTE_6a_2D'    => 103,
        'PENTAVALENTE_6a_3D'    => 104,
        'PENTAVALENTE_6a_1REF'  => 105,
        'PENTAVALENTE_6a_2REF'  => 106,

        // ── POLIO INACTIVA < 1 año ──────────────────────────────────
        'POLIO IPV_menor1a_1D' => 108,
        'POLIO IPV_menor1a_2D' => 109,

        // ── POLIO ORAL < 1 año ──────────────────────────────────────
        'POLIO bOPV_menor1a_3D' => 111,
        'POLIO bOPV_menor1a_DA' => 112,

        // ── POLIO 1 año (IPV+bOPV) ─────────────────────────────────
        'POLIO IPV_1a_1D'   => 113,
        'POLIO IPV_1a_2D'   => 114,
        'POLIO bOPV_1a_3D'  => 115,
        'POLIO IPV_1a_1REF' => 116,
        'POLIO bOPV_1a_DA'  => 117,

        // ── POLIO 2 años ────────────────────────────────────────────
        'POLIO IPV_2a_1D'   => 118,
        'POLIO IPV_2a_2D'   => 119,
        'POLIO bOPV_2a_3D'  => 120,
        'POLIO IPV_2a_1REF' => 121,
        'POLIO bOPV_2a_DA'  => 122,

        // ── NEUMOCOCO CONJUGADA ─────────────────────────────────────
        'NEUMOCOCO_menor1a_1D'  => 142,
        'NEUMOCOCO_menor1a_2D'  => 143,
        'NEUMOCOCO_1a_1REF'     => 144,

        // ── INFLUENZA ESTACIONAL ────────────────────────────────────
        'INFLUENZA_611m_1D'  => 146,
        'INFLUENZA_611m_2D'  => 147,
        'INFLUENZA_1a_1D'    => 148,
        'INFLUENZA_1a_2D'    => 149,

        // ── FIEBRE AMARILLA ─────────────────────────────────────────
        'FIEBRE AMARILLA_1a_DU'    => 156,
        'FIEBRE AMARILLA_2a_DU'    => 157,
        'FIEBRE AMARILLA_3a_DU'    => 158,
        'FIEBRE AMARILLA_4a_DU'    => 159,
        'FIEBRE AMARILLA_5a_DU'    => 160,
        'FIEBRE AMARILLA_69a_DU'   => 161,
        'FIEBRE AMARILLA_1014a_DU' => 162,
        'FIEBRE AMARILLA_1559a_DU' => 163,

        // ── SRP ─────────────────────────────────────────────────────
        'SRP_1a_1D'  => 165,
        'SRP_1a_2D'  => 166,
        'SRP_2a_1D'  => 167,
        'SRP_2a_2D'  => 168,
        'SRP_3a_1D'  => 169,
        'SRP_3a_2D'  => 170,
        'SRP_4a_1D'  => 171,
        'SRP_4a_2D'  => 172,
        'SRP_5a_1D'  => 173,
        'SRP_5a_2D'  => 174,

        // ── SR ──────────────────────────────────────────────────────
        'SR_611m_DA' => 176,
        'SR_1a_1D'   => 177,
        'SR_1a_2D'   => 178,
        'SR_1a_DA'   => 179,

        // ── TOXOIDE TETÁNICO DIFTÉRICO Escolares ────────────────────
        'TOXOIDE_10a_1D'  => 202,
        'TOXOIDE_10a_2D'  => 203,

        // ── TOXOIDE Mujeres en edad fértil ──────────────────────────
        'TOXOIDE_MEF_1D'  => 206,
        'TOXOIDE_MEF_2D'  => 207,
        'TOXOIDE_MEF_3D'  => 208,
        'TOXOIDE_MEF_4D'  => 209,
        'TOXOIDE_MEF_5D'  => 210,
        'TOXOIDE_MEF_DA'  => 211,

        // ── TOXOIDE Embarazadas ─────────────────────────────────────
        'TOXOIDE_embarazada_DG'  => 212,
        'TOXOIDE_embarazada_DA'  => 213,

        // ── VPH ─────────────────────────────────────────────────────
        'VPH_10a_femenina_1D' => 200,
    ];

    // ═══════════════════════════════════════════════════════════════
    // NORMALIZACIÓN de nombres de vacunas del sistema → clave SISPAI
    // ═══════════════════════════════════════════════════════════════
    const NOMBRES_VACUNA = [
        'bcg'               => 'BCG',
        'hepatitis b'       => 'HEPATITIS B',
        'hepatitis'         => 'HEPATITIS B',
        'rotavirus'         => 'ROTAVIRUS',
        'pentavalente'      => 'PENTAVALENTE',
        'penta'             => 'PENTAVALENTE',
        'polio ipv'         => 'POLIO IPV',
        'polio inactiva'    => 'POLIO IPV',
        'polio oral'        => 'POLIO bOPV',
        'polio bopv'        => 'POLIO bOPV',
        'neumococo'         => 'NEUMOCOCO',
        'influenza'         => 'INFLUENZA',
        'fiebre amarilla'   => 'FIEBRE AMARILLA',
        'srp'               => 'SRP',
        'sr '               => 'SR',
        'toxoide'           => 'TOXOIDE',
        'vph'               => 'VPH',
    ];

    // ─────────────────────────────────────────────────────────────
    // Vista index — selector de período y módulo
    // ─────────────────────────────────────────────────────────────
    public function index(Request $request): View
    {
        $user   = auth()->user();
        $modulo = null;

        if ($user->esJefeModulo()) {
            $modulo = $user->modulo();
            $modulos = collect([$modulo]);
        } else {
            $modulos = Modulo::orderBy('nombre')->get();
        }

        $mes   = $request->input('mes',  Carbon::now()->month);
        $anio  = $request->input('anio', Carbon::now()->year);
        $moduloId = $request->input('modulo_id', $modulo?->id ?? $modulos->first()?->id);

        $moduloSeleccionado = Modulo::find($moduloId);

        // Calcular resumen para la vista previa
        $resumen = null;
        if ($moduloSeleccionado) {
            $resumen = $this->calcularResumen($moduloSeleccionado, $mes, $anio);
        }

        $nombreMes = Carbon::createFromDate($anio, $mes, 1)->locale('es')->monthName;

        return view('sispai.index', compact(
            'modulos', 'moduloSeleccionado', 'mes', 'anio', 'resumen', 'nombreMes'
        ));
    }

    // ─────────────────────────────────────────────────────────────
    // Descargar Excel SISPAI con plantilla original
    // ─────────────────────────────────────────────────────────────
    public function excel(Request $request)
    {
        $mes      = $request->input('mes',  Carbon::now()->month);
        $anio     = $request->input('anio', Carbon::now()->year);
        $moduloId = $request->input('modulo_id');

        $modulo = Modulo::findOrFail($moduloId);
        $this->autorizarAcceso($modulo);

        if (!$modulo->sispai_fila) {
            return back()->with('error', 'Este módulo no tiene fila SISPAI configurada. Edita el módulo y asigna la fila correspondiente.');
        }

        $plantillaPath = storage_path('app/plantillas/sispai.xlsx');
        if (!file_exists($plantillaPath)) {
            return back()->with('error', 'Plantilla SISPAI no encontrada en storage/app/plantillas/sispai.xlsx');
        }

        // Cargar plantilla
        $spreadsheet = IOFactory::load($plantillaPath);

        // El mes corresponde al nombre de la hoja
        $nombreHoja  = strtoupper(
            Carbon::createFromDate($anio, $mes, 1)->locale('es')->monthName
        ) . ' ' . $anio;

        // Intentar encontrar la hoja (pueden variar nombres en el Excel)
        $ws = null;
        foreach ($spreadsheet->getSheetNames() as $nombre) {
            if (stripos($nombre, Carbon::createFromDate($anio, $mes, 1)->locale('es')->monthName) !== false) {
                $ws = $spreadsheet->getSheetByName($nombre);
                break;
            }
        }

        // Si no hay hoja del mes, usar la primera
        if (!$ws) {
            $ws = $spreadsheet->getActiveSheet();
        }

        // Llenar datos
        $datos = $this->calcularDatosSISPAI($modulo, $mes, $anio);
        $fila  = $modulo->sispai_fila;

        // Poner 1 en la columna de tipo de establecimiento (activo/notificante)
        $colTipo = $this->columnaPorTipo($modulo->tipo_establecimiento);
        if ($colTipo) {
            $ws->setCellValueByColumnAndRow($colTipo, $fila, 1);      // activo
            $ws->setCellValueByColumnAndRow($colTipo + 11, $fila, 1); // notificante
        }

        // Llenar las dosis en las columnas correspondientes
        foreach ($datos as $clave => $cantidad) {
            if (isset(self::COLUMNAS[$clave]) && $cantidad > 0) {
                $colActual = $ws->getCellByColumnAndRow(self::COLUMNAS[$clave], $fila)->getValue();
                $ws->setCellValueByColumnAndRow(
                    self::COLUMNAS[$clave],
                    $fila,
                    ($colActual ?? 0) + $cantidad
                );
            }
        }

        // Pérdidas (col 275-291)
        $perdidas = $this->calcularPerdidas($modulo, $mes, $anio);
        foreach ($perdidas as $colPerdida => $cant) {
            if ($cant > 0) {
                $ws->setCellValueByColumnAndRow($colPerdida, $fila, $cant);
            }
        }

        // Descargar
        $writer   = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $tmpPath  = tempnam(sys_get_temp_dir(), 'sispai_') . '.xlsx';
        $writer->save($tmpPath);

        $nombreMes = ucfirst(Carbon::createFromDate($anio, $mes, 1)->locale('es')->monthName);
        $filename  = "SISPAI_{$modulo->nombre}_{$nombreMes}_{$anio}.xlsx";

        return response()->download($tmpPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    // ─────────────────────────────────────────────────────────────
    // Descargar PDF resumen (nuestro formato)
    // ─────────────────────────────────────────────────────────────
    public function pdf(Request $request)
    {
        $mes      = $request->input('mes',  Carbon::now()->month);
        $anio     = $request->input('anio', Carbon::now()->year);
        $moduloId = $request->input('modulo_id');

        $modulo = Modulo::with('asic', 'jefe')->findOrFail($moduloId);
        $this->autorizarAcceso($modulo);

        $datos     = $this->calcularResumen($modulo, $mes, $anio);
        $nombreMes = ucfirst(Carbon::createFromDate($anio, $mes, 1)->locale('es')->monthName);

        $pdf = Pdf::loadView('sispai.pdf', array_merge(
            compact('modulo', 'mes', 'anio', 'nombreMes'),
            $datos
        ))->setPaper('a4', 'landscape');

        return $pdf->download("ReporteVacunacion_{$modulo->nombre}_{$nombreMes}_{$anio}.pdf");
    }

    // ═══════════════════════════════════════════════════════════════
    // LÓGICA CENTRAL
    // ═══════════════════════════════════════════════════════════════

    /**
     * Calcula los datos para llenar el SISPAI — clave => cantidad
     */
    private function calcularDatosSISPAI(Modulo $modulo, int $mes, int $anio): array
    {
        $datos = [];

        $tratamientos = Tratamiento::with(['paciente', 'vacuna'])
            ->whereHas('jornada', fn($q) =>
                $q->where('modulo_id', $modulo->id)
                  ->whereMonth('fecha_jornada', $mes)
                  ->whereYear('fecha_jornada', $anio)
            )
            ->get();

        foreach ($tratamientos as $t) {
            if (!$t->vacuna || !$t->paciente) continue;

            $clave = $this->resolverClave($t);
            if (!$clave) continue;

            $datos[$clave] = ($datos[$clave] ?? 0) + 1;
        }

        return $datos;
    }

    /**
     * Resuelve la clave SISPAI para un tratamiento dado
     * Formato: 'NOMBRE_VACUNA_grupoEdad_dosis'
     */
    private function resolverClave($tratamiento): ?string
    {
        $vacunaNombre = $this->normalizarVacuna($tratamiento->vacuna->nombre);
        if (!$vacunaNombre) return null;

        $paciente = $tratamiento->paciente;
        if (!$paciente->fecha_nacimiento) return null;

        $fechaVacuna = Carbon::parse($tratamiento->fecha_aplicacion);
        $nacimiento  = Carbon::parse($paciente->fecha_nacimiento);
        $edadDias    = $nacimiento->diffInDays($fechaVacuna);
        $edadAnios   = $nacimiento->diffInYears($fechaVacuna);
        $edadMeses   = $nacimiento->diffInMonths($fechaVacuna);

        $dosis    = $tratamiento->dosis_aplicada;
        $subtipo  = $tratamiento->subtipo_paciente ?? 'general';

        // Determinar grupo de edad
        $grupoEdad = $this->resolverGrupoEdad($vacunaNombre, $edadDias, $edadMeses, $edadAnios);
        if (!$grupoEdad) return null;

        // Determinar código de dosis
        $codigoDosis = $this->resolverCodigoDosis($vacunaNombre, $dosis, $edadAnios);

        // Para Hepatitis B usar subtipo de paciente
        if ($vacunaNombre === 'HEPATITIS B') {
            $subtipoKey = match($subtipo) {
                'personal_salud'     => 'salud',
                'dialisis'           => 'dialisis',
                'privado_libertad'   => 'privados',
                'trabajador_sexual'  => 'tsexual',
                'embarazada'         => 'embarazada',
                default              => 'general',
            };
            return "{$vacunaNombre}_{$subtipoKey}_{$codigoDosis}";
        }

        // Para TOXOIDE usar subtipo
        if ($vacunaNombre === 'TOXOIDE') {
            $subtipoKey = match($subtipo) {
                'embarazada' => 'embarazada',
                default      => match(true) {
                    $edadAnios >= 11 && $edadAnios <= 49 => 'MEF',
                    $edadAnios === 10                    => '10a',
                    default                              => 'MEF',
                },
            };
            return "{$vacunaNombre}_{$subtipoKey}_{$codigoDosis}";
        }

        return "{$vacunaNombre}_{$grupoEdad}_{$codigoDosis}";
    }

    private function resolverGrupoEdad(string $vacuna, int $dias, int $meses, int $anios): ?string
    {
        return match($vacuna) {
            'BCG' => match(true) {
                $dias < 28                          => 'menor28d',
                $dias < 365                         => '28d11m',
                $anios === 1                        => '1a',
                $anios === 2                        => '2a',
                $anios === 3                        => '3a',
                $anios === 4                        => '4a',
                $anios === 5                        => '5a',
                $anios >= 6 && $anios <= 7          => '67a',
                default                             => null,
            },
            'HEPATITIS B' => match(true) {
                $dias <= 1                          => 'menor24h',
                $dias <= 7                          => '17d',
                default                             => 'general', // el subtipo lo resuelve resolverClave
            },
            'ROTAVIRUS'   => $meses < 12 ? 'menor1a' : null,
            'PENTAVALENTE' => match(true) {
                $meses < 12  => 'menor1a',
                $anios === 1 => '1a',
                $anios === 2 => '2a',
                $anios === 3 => '3a',
                $anios === 4 => '4a',
                $anios === 5 => '5a',
                $anios === 6 => '6a',
                default      => null,
            },
            'POLIO IPV' => match(true) {
                $meses < 12  => 'menor1a',
                $anios === 1 => '1a',
                $anios === 2 => '2a',
                default      => null,
            },
            'POLIO bOPV' => match(true) {
                $meses < 12  => 'menor1a',
                $anios === 1 => '1a',
                $anios === 2 => '2a',
                default      => null,
            },
            'NEUMOCOCO' => match(true) {
                $meses < 12  => 'menor1a',
                $anios === 1 => '1a',
                default      => null,
            },
            'INFLUENZA' => match(true) {
                $meses >= 6 && $meses < 12 => '611m',
                $anios >= 1                => '1a',
                default                    => null,
            },
            'FIEBRE AMARILLA' => match(true) {
                $anios === 1                          => '1a',
                $anios === 2                          => '2a',
                $anios === 3                          => '3a',
                $anios === 4                          => '4a',
                $anios === 5                          => '5a',
                $anios >= 6  && $anios <= 9           => '69a',
                $anios >= 10 && $anios <= 14          => '1014a',
                $anios >= 15 && $anios <= 59          => '1559a',
                default                               => null,
            },
            'SRP' => match(true) {
                $anios === 1 => '1a',
                $anios === 2 => '2a',
                $anios === 3 => '3a',
                $anios === 4 => '4a',
                $anios === 5 => '5a',
                default      => null,
            },
            'SR' => match(true) {
                $meses >= 6 && $meses < 12 => '611m',
                $anios >= 1                => '1a',
                default                    => null,
            },
            'TOXOIDE', 'VPH' => 'general',
            default           => null,
        };
    }

    private function resolverCodigoDosis(string $vacuna, int $dosis, int $edadAnios): string
    {
        // Vacunas de dosis única
        if (in_array($vacuna, ['BCG', 'FIEBRE AMARILLA'])) return 'DU';

        // Refuerzos
        if (in_array($vacuna, ['PENTAVALENTE', 'POLIO IPV', 'POLIO bOPV', 'NEUMOCOCO'])) {
            return match($dosis) {
                1    => '1D',
                2    => '2D',
                3    => '3D',
                4    => '1REF',
                5    => '2REF',
                6    => 'DA',
                default => 'DA',
            };
        }

        return match($dosis) {
            1 => '1D',
            2 => '2D',
            3 => '3D',
            4 => '4D',
            5 => '5D',
            default => 'DA',
        };
    }

    private function normalizarVacuna(string $nombre): ?string
    {
        $lower = strtolower(trim($nombre));
        foreach (self::NOMBRES_VACUNA as $patron => $clave) {
            if (str_contains($lower, $patron)) {
                return $clave;
            }
        }
        return null;
    }

    private function columnaPorTipo(string $tipo): ?int
    {
        return match($tipo) {
            'CP1'            => 4,
            'CP2'            => 5,
            'CP3'            => 6,
            'HOSPITAL'       => 7,
            'CDI'            => 8,
            'IVSS'           => 9,
            'IPASME'         => 10,
            'SANIDAD MILITAR'=> 11,
            'PRIVADO'        => 12,
            'OTROS'          => 13,
            default          => null,
        };
    }

    private function calcularPerdidas(Modulo $modulo, int $mes, int $anio): array
    {
        // Col 275-291 son dosis perdidas por vacuna
        $perdidas = Perdida::with('vacuna')
            ->whereMonth('fecha', $mes)
            ->whereYear('fecha', $anio)
            ->where('vacuna_id', function($q) use ($modulo) {
                // Perdidas del ASIC relacionadas con vacunas que van al módulo
                $q->select('vacuna_id')->from('despacho')
                  ->where('modulo_id', $modulo->id);
            })
            ->get();

        $mapa = [];
        $colMap = [
            'BCG'          => 275, 'HEPATITIS B' => 276, 'ROTAVIRUS' => 278,
            'PENTAVALENTE' => 279, 'POLIO IPV'   => 280, 'POLIO bOPV' => 281,
            'NEUMOCOCO'    => 282, 'INFLUENZA'    => 283, 'FIEBRE AMARILLA' => 284,
            'SRP'          => 285, 'TOXOIDE'      => 287, 'VPH' => 309,
        ];

        foreach ($perdidas as $p) {
            $clave = $this->normalizarVacuna($p->vacuna->nombre ?? '');
            if ($clave && isset($colMap[$clave])) {
                $mapa[$colMap[$clave]] = ($mapa[$colMap[$clave]] ?? 0) + $p->cantidad;
            }
        }

        return $mapa;
    }

    /**
     * Resumen legible para la vista previa en pantalla
     */
    private function calcularResumen(Modulo $modulo, int $mes, int $anio): array
    {
        $jornadas = Jornada::with(['responsable', 'tratamientos.vacuna', 'tratamientos.paciente'])
            ->where('modulo_id', $modulo->id)
            ->whereMonth('fecha_jornada', $mes)
            ->whereYear('fecha_jornada', $anio)
            ->orderBy('fecha_jornada')
            ->get();

        // Agrupar dosis por vacuna
        $resumenVacunas = [];
        $totalDosis     = 0;
        $pacientesIds   = [];

        foreach ($jornadas as $jornada) {
            foreach ($jornada->tratamientos as $t) {
                if (!$t->vacuna) continue;
                $nombre = $t->vacuna->nombre;
                $resumenVacunas[$nombre] = ($resumenVacunas[$nombre] ?? 0) + $t->dosis_aplicada;
                $totalDosis += $t->dosis_aplicada;
                if ($t->paciente_id) $pacientesIds[$t->paciente_id] = true;
            }
        }

        arsort($resumenVacunas);

        return compact('jornadas', 'resumenVacunas', 'totalDosis') + [
            'totalPacientes' => count($pacientesIds),
        ];
    }

    private function autorizarAcceso(Modulo $modulo): void
    {
        $user = auth()->user();
        if ($user->esAdmin()) return;
        if ($user->esJefeModulo() && $user->modulo()?->id === $modulo->id) return;
        abort(403);
    }
}