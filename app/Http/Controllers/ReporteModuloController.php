<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Models\Jornada;
use App\Models\Tratamiento;
use App\Models\Vacuna;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReporteModuloController extends Controller
{
    /**
     * Formulario de selección de período
     */
    public function index(Request $request, Modulo $modulo)
    {
        $mes  = $request->input('mes', now()->month);
        $anio = $request->input('anio', now()->year);

        $datos = $this->calcularReporte($modulo, $mes, $anio);

        return view('modulo.reporte.index', array_merge(
            compact('modulo', 'mes', 'anio'),
            $datos
        ));
    }

    /**
     * Generar PDF
     */
    public function pdf(Request $request, Modulo $modulo)
    {
        $this->autorizarAcceso($modulo);

        $mes  = $request->input('mes', Carbon::now()->month);
        $anio = $request->input('anio', Carbon::now()->year);

        $datos = $this->calcularReporte($modulo, $mes, $anio);

        $pdf = Pdf::loadView('modulo.reporte.pdf', array_merge(
            compact('modulo', 'mes', 'anio'),
            $datos
        ))->setPaper('a4', 'landscape');

        $nombreMes = Carbon::createFromDate($anio, $mes, 1)->locale('es')->monthName;
        return $pdf->download("reporte_{$modulo->nombre}_{$nombreMes}_{$anio}.pdf");
    }

    /**
     * Generar Excel
     */
    public function excel(Request $request, Modulo $modulo)
    {
        $this->autorizarAcceso($modulo);

        $mes  = $request->input('mes', Carbon::now()->month);
        $anio = $request->input('anio', Carbon::now()->year);

        $datos     = $this->calcularReporte($modulo, $mes, $anio);
        $nombreMes = Carbon::createFromDate($anio, $mes, 1)->locale('es')->monthName;

        // Generamos el Excel con una vista blade convertida a CSV/Excel simple
        $contenido = $this->generarCSV($modulo, $mes, $anio, $datos, $nombreMes);

        return response($contenido, 200, [
            'Content-Type'        => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"reporte_{$modulo->nombre}_{$nombreMes}_{$anio}.xls\"",
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // Lógica central del reporte (usada por index, pdf, excel)
    // ─────────────────────────────────────────────────────────────
    private function calcularReporte(Modulo $modulo, int $mes, int $anio): array
    {
        // Jornadas del módulo en ese mes
        $jornadas = Jornada::with(['responsable', 'tratamientos.vacuna', 'tratamientos.paciente'])
            ->where('modulo_id', $modulo->id)
            ->whereMonth('fecha_jornada', $mes)
            ->whereYear('fecha_jornada', $anio)
            ->orderBy('fecha_jornada')
            ->get();

        // Resumen de dosis por vacuna en el período
        $resumenVacunas = Vacuna::get()->map(function ($vacuna) use ($modulo, $mes, $anio) {
            $dosisAplicadas = Tratamiento::whereHas('jornada', function ($q) use ($modulo, $mes, $anio) {
                $q->where('modulo_id', $modulo->id)
                    ->whereMonth('fecha_jornada', $mes)
                    ->whereYear('fecha_jornada', $anio);
            })
                ->where('vacuna_id', $vacuna->id)
                ->sum('dosis_aplicada');

            $vacuna->dosis_aplicadas = $dosisAplicadas;
            return $vacuna;
        })->filter(fn($v) => $v->dosis_aplicadas > 0);

        // Total de pacientes únicos atendidos (corregido: se usa $modulo->id)
        $totalPacientes = Tratamiento::whereHas('jornada', function ($q) use ($modulo, $mes, $anio) {
            $q->where('modulo_id', $modulo->id)
                ->whereMonth('fecha_jornada', $mes)
                ->whereYear('fecha_jornada', $anio);
        })->distinct('paciente_id')
            ->count('paciente_id');

        $totalDosis = $resumenVacunas->sum('dosis_aplicadas');

        $nombreMes = Carbon::createFromDate($anio, $mes, 1)
            ->locale('es')->monthName;

        return compact('jornadas', 'resumenVacunas', 'totalPacientes', 'totalDosis', 'nombreMes');
    }

    private function generarCSV(Modulo $modulo, int $mes, int $anio, array $datos, string $nombreMes): string
    {
        $filas   = [];
        $filas[] = ["REPORTE MENSUAL DE VACUNACIÓN"];
        $filas[] = ["Módulo:", $modulo->nombre];
        $filas[] = ["ASIC:", $modulo->asic->nombre ?? ''];
        $filas[] = ["Período:", ucfirst($nombreMes) . " " . $anio];
        $filas[] = ["Generado:", Carbon::now()->format('d/m/Y H:i')];
        $filas[] = [];
        $filas[] = ["RESUMEN DE DOSIS APLICADAS"];
        $filas[] = ["Vacuna", "Dosis Aplicadas"];

        foreach ($datos['resumenVacunas'] as $v) {
            $filas[] = [$v->nombre, $v->dosis_aplicadas];
        }

        $filas[] = ["TOTAL", $datos['totalDosis']];
        $filas[] = [];
        $filas[] = ["DETALLE POR JORNADA"];
        $filas[] = ["Fecha", "Responsable", "Vacuna", "Paciente", "Dosis N°", "Observaciones"];

        foreach ($datos['jornadas'] as $jornada) {
            foreach ($jornada->tratamientos as $t) {
                $filas[] = [
                    $jornada->fecha_jornada->format('d/m/Y'),
                    optional($jornada->responsable)->nombre . ' ' . optional($jornada->responsable)->apellido,
                    optional($t->vacuna)->nombre,
                    optional($t->paciente)->cedula ?? 'Sin CI',
                    $t->dosis_aplicada,
                    $t->observaciones ?? '',
                ];
            }
        }

        // Convertir a string CSV compatible con Excel
        $output = "\xEF\xBB\xBF"; // BOM UTF-8 para que Excel abra bien
        foreach ($filas as $fila) {
            $output .= implode("\t", array_map(fn($c) => '"' . str_replace('"', '""', $c) . '"', $fila)) . "\r\n";
        }

        return $output;
    }

    private function autorizarAcceso(Modulo $modulo): void
    {
        $user = auth()->user();

        // Admin y asistente pasan siempre
        if ($user->esAdmin()) return;

        // Jefe solo puede ver su propio módulo
        if ($user->esJefeModulo() && $user->modulo()?->id === $modulo->id) return;

        abort(403, 'No tienes acceso a este módulo.');
    }
}