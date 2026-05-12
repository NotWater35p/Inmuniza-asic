<?php

namespace App\Http\Controllers;

use App\Models\Despacho;
use App\Models\Vacuna;
use App\Models\Modulo;
use App\Models\Personal;
use App\Models\Asic;
use App\Models\Carga;
use App\Models\Perdida;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\DespachoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class DespachoController extends Controller
{
    // ----------------------------------------------------------------
    // HELPER: Stock ASIC = SUM(cantidad_disponible) - pérdidas sin módulo
    // ----------------------------------------------------------------
    private function calcularStock(int $vacuna_id, int $asic_id, ?int $excludeDespachoId = null): int
    {
        $disponible = Carga::where('vacuna_id', $vacuna_id)
            ->where('asic_id', $asic_id)
            ->sum('cantidad_disponible');

        $perdido = Perdida::where('vacuna_id', $vacuna_id)
            ->whereNull('modulo_id')
            ->sum('cantidad');

        // Si estamos editando, devolvemos temporalmente la cantidad del despacho excluido
        $bonus = 0;
        if ($excludeDespachoId) {
            $d = Despacho::find($excludeDespachoId);
            $bonus = $d ? $d->cantidad : 0;
        }

        return max(0, $disponible - $perdido + $bonus);
    }

    // ----------------------------------------------------------------
    // HELPER: Descuenta de cantidad_disponible en cargas (FIFO por fecha_llegada)
    // ----------------------------------------------------------------
    private function deducirDeCarga(int $vacunaId, int $asicId, ?string $lote, int $cantidad): void
    {
        $query = Carga::where('vacuna_id', $vacunaId)
            ->where('asic_id', $asicId)
            ->where('cantidad_disponible', '>', 0)
            ->orderBy('fecha_llegada'); // FIFO

        if ($lote) {
            $query->where('lote', $lote);
        } else {
            $query->whereNull('lote');
        }

        $restante = $cantidad;
        foreach ($query->get() as $carga) {
            if ($restante <= 0) break;
            $deducir = min($restante, $carga->cantidad_disponible);
            $carga->decrement('cantidad_disponible', $deducir);
            $restante -= $deducir;
        }
    }

    // ----------------------------------------------------------------
    // HELPER: Restaura cantidad_disponible en cargas (anti-FIFO)
    // ----------------------------------------------------------------
    private function restaurarACarga(int $vacunaId, int $asicId, ?string $lote, int $cantidad): void
    {
        $query = Carga::where('vacuna_id', $vacunaId)
            ->where('asic_id', $asicId)
            ->orderByDesc('fecha_llegada'); // más reciente primero

        if ($lote) {
            $query->where('lote', $lote);
        } else {
            $query->whereNull('lote');
        }

        $restante = $cantidad;
        foreach ($query->get() as $carga) {
            if ($restante <= 0) break;
            $espacio    = $carga->cantidad - $carga->cantidad_disponible;
            $restaurar  = min($restante, $espacio);
            if ($restaurar > 0) {
                $carga->increment('cantidad_disponible', $restaurar);
                $restante -= $restaurar;
            }
        }
    }

    // ----------------------------------------------------------------
    // INDEX
    // ----------------------------------------------------------------
    public function index(Request $request): View
    {
        $asic    = Asic::first();
        $modulos = Modulo::where('asic_id', $asic->id)->orderBy('nombre')->get()
            ->map(function ($modulo) {
                $despachos = Despacho::where('modulo_id', $modulo->id);
                $modulo->total_registros = $despachos->count();
                $modulo->total_dosis     = (clone $despachos)->sum('cantidad');
                $modulo->ultimo_despacho = (clone $despachos)->max('fecha_envio');
                return $modulo;
            });

        $query = Despacho::with(['vacuna', 'modulo', 'responsable']);

        if ($request->filled('modulo_id'))  $query->where('modulo_id', $request->modulo_id);
        if ($request->filled('vacuna'))     $query->whereHas('vacuna', fn($q) => $q->where('nombre', 'like', '%'.$request->vacuna.'%'));
        if ($request->filled('responsable')) $query->whereHas('responsable', fn($q) =>
            $q->where('nombre', 'like', '%'.$request->responsable.'%')
              ->orWhere('apellido', 'like', '%'.$request->responsable.'%')
              ->orWhere('cedula', $request->responsable)
        );
        if ($request->filled('fecha_desde'))   $query->whereDate('fecha_envio', '>=', $request->fecha_desde);
        if ($request->filled('fecha_hasta'))   $query->whereDate('fecha_envio', '<=', $request->fecha_hasta);
        if ($request->filled('cantidad_min'))  $query->where('cantidad', '>=', $request->cantidad_min);
        if ($request->filled('cantidad_max'))  $query->where('cantidad', '<=', $request->cantidad_max);

        $despachos          = $query->orderBy('fecha_envio', 'desc')->paginate(15)->withQueryString();
        $moduloSeleccionado = $request->filled('modulo_id') ? Modulo::find($request->modulo_id) : null;
        $vacunas            = Vacuna::orderBy('nombre')->get();

        return view('despacho.index', compact('despachos', 'modulos', 'moduloSeleccionado', 'vacunas', 'asic'))
            ->with('i', ($request->input('page', 1) - 1) * $despachos->perPage());
    }

    // ----------------------------------------------------------------
    // CREATE
    // ----------------------------------------------------------------
    public function create(): View
    {
        $asic     = Asic::first();
        $despacho = new Despacho();
        $vacunas  = Vacuna::orderBy('nombre')->get();
        $modulos  = Modulo::where('asic_id', $asic->id)->orderBy('nombre')->get();
        $personal = Personal::where('asic_id', $asic->id)->orderBy('nombre')->get();

        return view('despacho.create', compact('despacho', 'asic', 'vacunas', 'modulos', 'personal'));
    }

    // ----------------------------------------------------------------
    // STORE
    // ----------------------------------------------------------------
    public function store(DespachoRequest $request): RedirectResponse
    {
        $asic  = Asic::first();
        $datos = $request->validated();

        // Verificar stock del lote específico
        $stockLote = Carga::where('vacuna_id', $datos['vacuna_id'])
            ->where('asic_id', $asic->id)
            ->where('lote', $datos['lote'])
            ->sum('cantidad_disponible');

        if ($datos['cantidad'] > $stockLote) {
            return Redirect::back()->withInput()->withErrors([
                'cantidad' => "El lote {$datos['lote']} solo tiene {$stockLote} unidades disponibles.",
            ]);
        }

        // Descontar de carga
        $this->deducirDeCarga($datos['vacuna_id'], $asic->id, $datos['lote'], $datos['cantidad']);

        Despacho::create(array_merge($datos, ['asic_id' => $asic->id]));

        return Redirect::route('despachos.index')
            ->with('success', 'Despacho registrado y stock descontado del lote ' . $datos['lote'] . '.');
    }

    // ----------------------------------------------------------------
    // STORE BULK
    // ----------------------------------------------------------------
    public function storeBulk(Request $request): RedirectResponse
    {
        $request->validate([
            'despachos'                     => 'required|array|min:1',
            'despachos.*.vacuna_id'         => 'required|exists:vacuna,id',
            'despachos.*.modulo_id'         => 'required|exists:modulo,id',
            'despachos.*.responsable_envio' => 'required|exists:personal,cedula',
            'despachos.*.fecha_envio'       => 'required|date|before_or_equal:today',
            'despachos.*.lote'              => 'required|string|max:50',
            'despachos.*.cantidad'          => 'required|integer|min:1',
        ]);

        $asic    = Asic::first();
        $errores = [];

        foreach ($request->despachos as $i => $item) {
            $stockLote = Carga::where('vacuna_id', $item['vacuna_id'])
                ->where('asic_id', $asic->id)
                ->where('lote', $item['lote'])
                ->sum('cantidad_disponible');

            if ((int) $item['cantidad'] > $stockLote) {
                $vacuna    = Vacuna::find($item['vacuna_id']);
                $errores[] = 'Fila ' . ($i + 1) . ": El lote <strong>{$item['lote']}</strong> de {$vacuna?->nombre} solo tiene {$stockLote} unidades disponibles.";
            }
        }

        if (!empty($errores)) {
            return Redirect::back()->withInput()->with('errores_bulk', $errores);
        }

        foreach ($request->despachos as $item) {
            $this->deducirDeCarga((int) $item['vacuna_id'], $asic->id, $item['lote'], (int) $item['cantidad']);
            Despacho::create([
                'asic_id'           => $asic->id,
                'vacuna_id'         => $item['vacuna_id'],
                'modulo_id'         => $item['modulo_id'],
                'responsable_envio' => $item['responsable_envio'],
                'fecha_envio'       => $item['fecha_envio'],
                'lote'              => $item['lote'],
                'cantidad'          => $item['cantidad'],
            ]);
        }

        return Redirect::route('despachos.index')
            ->with('success', count($request->despachos) . ' despacho(s) registrado(s) exitosamente.');
    }

    // ----------------------------------------------------------------
    // SHOW
    // ----------------------------------------------------------------
    public function show($id): View
    {
        $despacho    = Despacho::with(['vacuna.marca', 'modulo', 'responsable.cargo'])->findOrFail($id);
        $asic        = Asic::first();
        $stockActual = $this->calcularStock($despacho->vacuna_id, $asic->id);

        return view('despacho.show', compact('despacho', 'asic', 'stockActual'));
    }

    // ----------------------------------------------------------------
    // EDIT
    // ----------------------------------------------------------------
    public function edit($id): View
    {
        $despacho        = Despacho::findOrFail($id);
        $asic            = Asic::first();
        $vacunas         = Vacuna::orderBy('nombre')->get();
        $modulos         = Modulo::where('asic_id', $asic->id)->orderBy('nombre')->get();
        $personal        = Personal::where('asic_id', $asic->id)->orderBy('nombre')->get();
        $stockDisponible = $this->calcularStock($despacho->vacuna_id, $asic->id, $despacho->id);

        return view('despacho.edit', compact('despacho', 'asic', 'vacunas', 'modulos', 'personal', 'stockDisponible'));
    }

    // ----------------------------------------------------------------
    // UPDATE
    // ----------------------------------------------------------------
    public function update(DespachoRequest $request, Despacho $despacho): RedirectResponse
    {
        $asic  = Asic::first();
        $datos = $request->validated();

        // Restaurar stock del lote anterior
        $this->restaurarACarga($despacho->vacuna_id, $asic->id, $despacho->lote, $despacho->cantidad);

        // Verificar stock del nuevo lote
        $stockLote = Carga::where('vacuna_id', $datos['vacuna_id'])
            ->where('asic_id', $asic->id)
            ->where('lote', $datos['lote'])
            ->sum('cantidad_disponible');

        if ($datos['cantidad'] > $stockLote) {
            // Revertir la restauración antes de salir
            $this->deducirDeCarga($despacho->vacuna_id, $asic->id, $despacho->lote, $despacho->cantidad);
            return Redirect::back()->withInput()->withErrors([
                'cantidad' => "El lote {$datos['lote']} solo tiene {$stockLote} unidades disponibles.",
            ]);
        }

        // Descontar del nuevo lote
        $this->deducirDeCarga($datos['vacuna_id'], $asic->id, $datos['lote'], $datos['cantidad']);

        $despacho->update($datos);

        return Redirect::route('despachos.index')
            ->with('success', 'Despacho actualizado y stock ajustado correctamente.');
    }

    // ----------------------------------------------------------------
    // DESTROY
    // ----------------------------------------------------------------
    public function destroy($id): RedirectResponse
    {
        $despacho = Despacho::findOrFail($id);

        // Restaurar al lote de carga
        $this->restaurarACarga($despacho->vacuna_id, $despacho->asic_id, $despacho->lote, $despacho->cantidad);

        $despacho->delete();

        return Redirect::route('despachos.index')
            ->with('success', 'Despacho eliminado y stock restaurado al lote de carga.');
    }

    // ----------------------------------------------------------------
    // AJAX: checkStock — usa cantidad_disponible directamente
    // ----------------------------------------------------------------
    public function checkStock(Request $request)
    {
        $vacunaId = (int) $request->vacuna_id;
        $vacuna   = Vacuna::findOrFail($vacunaId);
        $asic     = Asic::first();

        // Stock total = suma de cantidad_disponible - pérdidas ASIC
        $disponibleTotal = Carga::where('vacuna_id', $vacunaId)
            ->where('asic_id', $asic->id)
            ->sum('cantidad_disponible');

        $perdido = Perdida::where('vacuna_id', $vacunaId)->whereNull('modulo_id')->sum('cantidad');
        $stock   = max(0, $disponibleTotal - $perdido);

        // Lotes con cantidad_disponible > 0, agrupados
        $lotes = Carga::where('vacuna_id', $vacunaId)
            ->where('asic_id', $asic->id)
            ->where('cantidad_disponible', '>', 0)
            ->select(
                'lote',
                DB::raw('SUM(cantidad_disponible) as disponible'),
                DB::raw('SUM(cantidad) as cantidad_original'),
                DB::raw('MIN(fecha_vencimiento) as fecha_vencimiento')
            )
            ->groupBy('lote')
            ->orderBy('fecha_vencimiento') // mostrar más próximos a vencer primero
            ->get();

        return response()->json([
            'vacuna' => $vacuna->nombre,
            'stock'  => $stock,
            'lotes'  => $lotes,
        ]);
    }

    // ----------------------------------------------------------------
    // PDFs (sin cambios)
    // ----------------------------------------------------------------
    public function reporteModulo(Request $request, $modulo_id)
    {
        $asic   = Asic::first();
        $modulo = Modulo::findOrFail($modulo_id);
        $query  = Despacho::with(['vacuna', 'responsable'])->where('modulo_id', $modulo_id);

        if ($request->filled('mes') && $request->filled('anio')) {
            $query->whereMonth('fecha_envio', $request->mes)->whereYear('fecha_envio', $request->anio);
        } else {
            if ($request->filled('fecha_desde')) $query->whereDate('fecha_envio', '>=', $request->fecha_desde);
            if ($request->filled('fecha_hasta'))  $query->whereDate('fecha_envio', '<=', $request->fecha_hasta);
        }

        $despachos      = $query->orderBy('fecha_envio', 'desc')->get();
        $totalDosis     = $despachos->sum('cantidad');
        $generadoEn     = Carbon::now()->format('d/m/Y H:i');
        $resumenVacunas = $despachos->groupBy('vacuna_id')->map(fn($g) => [
            'nombre'    => $g->first()->vacuna?->nombre ?? '—',
            'cantidad'  => $g->sum('cantidad'),
            'registros' => $g->count(),
        ])->values();
        $periodo = ($request->filled('mes') && $request->filled('anio'))
            ? Carbon::createFromDate($request->anio, $request->mes, 1)->translatedFormat('F Y')
            : (($request->fecha_desde ?? '...') . ' → ' . ($request->fecha_hasta ?? '...'));

        $pdf = Pdf::loadView('despacho.reportes.modulo', compact(
            'despachos', 'modulo', 'asic', 'totalDosis', 'generadoEn', 'resumenVacunas', 'periodo'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('despacho_' . str_replace(' ', '_', $modulo->nombre) . '_' . Carbon::now()->format('Ymd') . '.pdf');
    }

    public function reporteVacuna(Request $request)
    {
        $asic  = Asic::first();
        $query = Despacho::with(['modulo', 'responsable'])->where('asic_id', $asic->id);
        if ($request->filled('vacuna_id')) $query->where('vacuna_id', $request->vacuna_id);
        if ($request->filled('fecha_desde')) $query->whereDate('fecha_envio', '>=', $request->fecha_desde);
        if ($request->filled('fecha_hasta'))  $query->whereDate('fecha_envio', '<=', $request->fecha_hasta);

        $despachos      = $query->orderBy('fecha_envio', 'desc')->get();
        $vacuna         = Vacuna::find($request->vacuna_id);
        $totalDosis     = $despachos->sum('cantidad');
        $stockActual    = $request->filled('vacuna_id') ? $this->calcularStock($request->vacuna_id, $asic->id) : null;
        $generadoEn     = Carbon::now()->format('d/m/Y H:i');
        $resumenModulos = $despachos->groupBy('modulo_id')->map(fn($g) => [
            'nombre'    => $g->first()->modulo?->nombre ?? '—',
            'cantidad'  => $g->sum('cantidad'),
            'registros' => $g->count(),
        ])->values();

        $pdf = Pdf::loadView('despacho.reportes.vacuna', compact(
            'despachos', 'vacuna', 'asic', 'totalDosis', 'generadoEn', 'stockActual', 'resumenModulos'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('despacho_vacuna_' . Carbon::now()->format('Ymd') . '.pdf');
    }

    public function reportePeriodo(Request $request)
    {
        $asic  = Asic::first();
        $query = Despacho::with(['vacuna', 'modulo', 'responsable'])->where('asic_id', $asic->id);
        if ($request->filled('fecha_desde')) $query->whereDate('fecha_envio', '>=', $request->fecha_desde);
        if ($request->filled('fecha_hasta'))  $query->whereDate('fecha_envio', '<=', $request->fecha_hasta);
        if ($request->filled('modulo_id'))    $query->where('modulo_id', $request->modulo_id);

        $despachos      = $query->orderBy('fecha_envio', 'desc')->get();
        $totalDosis     = $despachos->sum('cantidad');
        $generadoEn     = Carbon::now()->format('d/m/Y H:i');
        $resumenModulos = $despachos->groupBy('modulo_id')->map(fn($g) => ['nombre' => $g->first()->modulo?->nombre ?? '—', 'cantidad' => $g->sum('cantidad'), 'registros' => $g->count()])->values();
        $resumenVacunas = $despachos->groupBy('vacuna_id')->map(fn($g) => ['nombre' => $g->first()->vacuna?->nombre ?? '—', 'cantidad' => $g->sum('cantidad'), 'registros' => $g->count()])->values();

        $pdf = Pdf::loadView('despacho.reportes.periodo', compact(
            'despachos', 'asic', 'totalDosis', 'generadoEn', 'resumenModulos', 'resumenVacunas'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('despacho_periodo_' . Carbon::now()->format('Ymd') . '.pdf');
    }
}