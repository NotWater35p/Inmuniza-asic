<?php

namespace App\Http\Controllers;

use App\Models\Despacho;
use App\Models\Vacuna;
use App\Models\Modulo;
use App\Models\Personal;
use App\Models\Asic;
use App\Models\Carga;
use App\Models\Tratamiento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\DespachoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class DespachoController extends Controller
{
    // ----------------------------------------------------------------
    // HELPER: Stock disponible de una vacuna en el ASIC
    // Stock = total_cargas - total_despachos - total_tratamientos
    // ----------------------------------------------------------------
    private function calcularStock(int $vacuna_id, int $asic_id, ?int $excludeDespachoId = null): int
    {
        $totalCargas = Carga::where('vacuna_id', $vacuna_id)
            ->where('asic_id', $asic_id)
            ->sum('cantidad');

        $query = Despacho::where('vacuna_id', $vacuna_id)
            ->where('asic_id', $asic_id);

        if ($excludeDespachoId) {
            $query->where('id', '!=', $excludeDespachoId);
        }

        $totalDespachos = $query->sum('cantidad');

        $totalTratamientos = 0;
        if (class_exists(\App\Models\Tratamiento::class)) {
            $totalTratamientos = \App\Models\Tratamiento::where('vacuna_id', $vacuna_id)->count();
        }

        return max(0, $totalCargas - $totalDespachos - $totalTratamientos);
    }

    // ----------------------------------------------------------------
    // INDEX
    // ----------------------------------------------------------------
    public function index(Request $request): View
    {
        $asic = Asic::first();

        // Sidebar: módulos con estadísticas relevantes
        $modulos = Modulo::where('asic_id', $asic->id)
            ->orderBy('nombre')
            ->get()
            ->map(function ($modulo) {
                $despachos = Despacho::where('modulo_id', $modulo->id);
                $modulo->total_registros  = $despachos->count();
                $modulo->total_dosis      = (clone $despachos)->sum('cantidad');
                $modulo->ultimo_despacho  = (clone $despachos)->max('fecha_envio');
                return $modulo;
            });

        // Query principal
        $query = Despacho::with(['vacuna', 'modulo', 'responsable']);

        // Filtro por módulo (sidebar)
        if ($request->filled('modulo_id')) {
            $query->where('modulo_id', $request->modulo_id);
        }

        // Filtro por vacuna
        if ($request->filled('vacuna')) {
            $query->whereHas('vacuna', fn($q) => $q->where('nombre', 'like', '%' . $request->vacuna . '%'));
        }

        // Filtro por responsable
        if ($request->filled('responsable')) {
            $query->whereHas(
                'responsable',
                fn($q) =>
                $q->where('nombre', 'like', '%' . $request->responsable . '%')
                    ->orWhere('apellido', 'like', '%' . $request->responsable . '%')
                    ->orWhere('cedula', $request->responsable)
            );
        }

        // Filtro por rango de fecha
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_envio', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_envio', '<=', $request->fecha_hasta);
        }

        // Filtro por cantidad
        if ($request->filled('cantidad_min')) {
            $query->where('cantidad', '>=', $request->cantidad_min);
        }
        if ($request->filled('cantidad_max')) {
            $query->where('cantidad', '<=', $request->cantidad_max);
        }

        $query->orderBy('fecha_envio', 'desc');

        $despachos = $query->paginate(15)->withQueryString();

        // Info del módulo seleccionado
        $moduloSeleccionado = $request->filled('modulo_id')
            ? Modulo::find($request->modulo_id)
            : null;

        $vacunas = Vacuna::orderBy('nombre')->get();

        return view('despacho.index', compact(
            'despachos',
            'modulos',
            'moduloSeleccionado',
            'vacunas',
            'asic'
        ))->with('i', ($request->input('page', 1) - 1) * $despachos->perPage());
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
        $asic = Asic::first();

        $stock = $this->calcularStock($request->vacuna_id, $asic->id);

        if ($request->cantidad > $stock) {
            return Redirect::back()->withInput()->with('error_stock', [
                'disponible' => $stock,
                'solicitado' => $request->cantidad,
                'vacuna'     => Vacuna::find($request->vacuna_id)?->nombre,
            ]);
        }

        Despacho::create(array_merge($request->validated(), ['asic_id' => $asic->id]));

        return Redirect::route('despachos.index')
            ->with('success', 'Despacho registrado exitosamente.');
    }

    public function storeBulk(Request $request): RedirectResponse
    {
        $request->validate([
            'despachos'                          => 'required|array|min:1',
            'despachos.*.vacuna_id'              => 'required|exists:vacuna,id',
            'despachos.*.modulo_id'              => 'required|exists:modulo,id',
            'despachos.*.responsable_envio'      => 'required|exists:personal,cedula',
            'despachos.*.fecha_envio'            => 'required|date|before_or_equal:today',
            'despachos.*.cantidad'               => 'required|integer|min:1',
        ], [
            'despachos.required'                        => 'Debes agregar al menos un despacho.',
            'despachos.*.vacuna_id.required'            => 'Selecciona una vacuna en todas las filas.',
            'despachos.*.modulo_id.required'            => 'Selecciona un módulo en todas las filas.',
            'despachos.*.responsable_envio.required'    => 'Selecciona un responsable en todas las filas.',
            'despachos.*.fecha_envio.required'          => 'Ingresa la fecha en todas las filas.',
            'despachos.*.fecha_envio.before_or_equal'   => 'La fecha no puede ser futura.',
            'despachos.*.cantidad.required'             => 'Ingresa la cantidad en todas las filas.',
            'despachos.*.cantidad.min'                  => 'La cantidad mínima es 1.',
        ]);

        $asic   = Asic::first();
        $errores = [];

        // Validar stock de cada fila antes de guardar
        foreach ($request->despachos as $i => $item) {
            $stock = $this->calcularStock((int)$item['vacuna_id'], $asic->id);
            if ((int)$item['cantidad'] > $stock) {
                $vacuna = Vacuna::find($item['vacuna_id']);
                $errores[] = "Fila " . ($i + 1) . ": Stock insuficiente para <strong>{$vacuna?->nombre}</strong>. Disponible: {$stock}, solicitado: {$item['cantidad']}.";
            }
        }

        if (!empty($errores)) {
            return Redirect::back()->withInput()
                ->with('errores_bulk', $errores);
        }

        // Si todo está bien se guarda
        foreach ($request->despachos as $item) {
            Despacho::create([
                'asic_id'          => $asic->id,
                'vacuna_id'        => $item['vacuna_id'],
                'modulo_id'        => $item['modulo_id'],
                'responsable_envio' => $item['responsable_envio'],
                'fecha_envio'      => $item['fecha_envio'],
                'cantidad'         => $item['cantidad'],
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
        $despacho = Despacho::findOrFail($id);
        $asic     = Asic::first();
        $vacunas  = Vacuna::orderBy('nombre')->get();
        $modulos  = Modulo::where('asic_id', $asic->id)->orderBy('nombre')->get();
        $personal = Personal::where('asic_id', $asic->id)->orderBy('nombre')->get();

        // Stock excluyendo el despacho actual 
        $stockDisponible = $this->calcularStock($despacho->vacuna_id, $asic->id, $despacho->id);

        return view('despacho.edit', compact(
            'despacho',
            'asic',
            'vacunas',
            'modulos',
            'personal',
            'stockDisponible'
        ));
    }

    // ----------------------------------------------------------------
    // UPDATE 
    // ----------------------------------------------------------------
    public function update(DespachoRequest $request, Despacho $despacho): RedirectResponse
    {
        $asic  = Asic::first();
        $stock = $this->calcularStock($request->vacuna_id, $asic->id, $despacho->id);

        if ($request->cantidad > $stock) {
            return Redirect::back()->withInput()->with('error_stock', [
                'disponible' => $stock,
                'solicitado' => $request->cantidad,
                'vacuna'     => Vacuna::find($request->vacuna_id)?->nombre,
            ]);
        }

        $despacho->update($request->validated());

        return Redirect::route('despachos.index')
            ->with('success', 'Despacho actualizado exitosamente.');
    }

    // ----------------------------------------------------------------
    // DESTROY
    // ----------------------------------------------------------------
    public function destroy($id): RedirectResponse
    {
        Despacho::findOrFail($id)->delete();

        return Redirect::route('despachos.index')
            ->with('success', 'Despacho eliminado exitosamente.');
    }

    // ----------------------------------------------------------------
    // AJAX: verificar stock en tiempo real (para el formulario)
    // ----------------------------------------------------------------
    public function checkStock(Request $request)
    {
        $vacunaId = $request->vacuna_id;
        $vacuna   = \App\Models\Vacuna::findOrFail($vacunaId);

        // Calcular stock general
        $entrado    = \App\Models\Carga::where('vacuna_id', $vacunaId)->sum('cantidad');
        $despachado = \App\Models\Despacho::where('vacuna_id', $vacunaId)->sum('cantidad');
        $perdido    = \App\Models\Perdida::where('vacuna_id', $vacunaId)->sum('cantidad');
        $stock      = $entrado - $despachado - $perdido;

        // Lotes disponibles con stock positivo
        $lotes = \Illuminate\Support\Facades\DB::table('carga')
            ->select('lote', \Illuminate\Support\Facades\DB::raw('SUM(cantidad) as entrado'), \Illuminate\Support\Facades\DB::raw('MIN(fecha_vencimiento) as fecha_vencimiento'))
            ->where('vacuna_id', $vacunaId)
            ->whereNotNull('lote')
            ->groupBy('lote')
            ->get()
            ->map(function ($lote) use ($vacunaId) {
                $lote->despachado = \App\Models\Despacho::where('vacuna_id', $vacunaId)->where('lote', $lote->lote)->sum('cantidad');
                $lote->perdido    = \App\Models\Perdida::where('vacuna_id', $vacunaId)->where('lote', $lote->lote)->sum('cantidad');
                $lote->disponible = $lote->entrado - $lote->despachado - $lote->perdido;
                return $lote;
            })
            ->filter(fn($l) => $l->disponible > 0)
            ->values();

        return response()->json([
            'vacuna' => $vacuna->nombre,
            'stock'  => max(0, $stock),
            'lotes'  => $lotes,
        ]);
    }

    // ----------------------------------------------------------------
    // PDF: Reporte por módulo (con opción mensual)
    // ----------------------------------------------------------------
    public function reporteModulo(Request $request, $modulo_id)
    {
        $asic   = Asic::first();
        $modulo = Modulo::findOrFail($modulo_id);

        $query = Despacho::with(['vacuna', 'responsable'])
            ->where('modulo_id', $modulo_id);

        if ($request->filled('mes') && $request->filled('anio')) {
            $query->whereMonth('fecha_envio', $request->mes)
                ->whereYear('fecha_envio', $request->anio);
        } else {
            if ($request->filled('fecha_desde')) {
                $query->whereDate('fecha_envio', '>=', $request->fecha_desde);
            }
            if ($request->filled('fecha_hasta')) {
                $query->whereDate('fecha_envio', '<=', $request->fecha_hasta);
            }
        }

        $despachos = $query->orderBy('fecha_envio', 'desc')->get();
        $totalDosis = $despachos->sum('cantidad');
        $generadoEn = Carbon::now()->format('d/m/Y H:i');

        $resumenVacunas = $despachos->groupBy('vacuna_id')->map(fn($g) => [
            'nombre'    => $g->first()->vacuna?->nombre ?? '—',
            'cantidad'  => $g->sum('cantidad'),
            'registros' => $g->count(),
        ])->values();

        $periodo = ($request->filled('mes') && $request->filled('anio'))
            ? Carbon::createFromDate($request->anio, $request->mes, 1)->translatedFormat('F Y')
            : (($request->fecha_desde ?? '...') . ' → ' . ($request->fecha_hasta ?? '...'));

        $pdf = Pdf::loadView('despacho.reportes.modulo', compact(
            'despachos',
            'modulo',
            'asic',
            'totalDosis',
            'generadoEn',
            'resumenVacunas',
            'periodo'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('despacho_' . str_replace(' ', '_', $modulo->nombre) . '_' . Carbon::now()->format('Ymd') . '.pdf');
    }

    // ----------------------------------------------------------------
    // PDF: Reporte por vacuna
    // ----------------------------------------------------------------
    public function reporteVacuna(Request $request)
    {
        $asic = Asic::first();

        $query = Despacho::with(['modulo', 'responsable'])
            ->where('asic_id', $asic->id);

        if ($request->filled('vacuna_id')) {
            $query->where('vacuna_id', $request->vacuna_id);
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_envio', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_envio', '<=', $request->fecha_hasta);
        }

        $despachos   = $query->orderBy('fecha_envio', 'desc')->get();
        $vacuna      = Vacuna::find($request->vacuna_id);
        $totalDosis  = $despachos->sum('cantidad');
        $stockActual = $request->filled('vacuna_id')
            ? $this->calcularStock($request->vacuna_id, $asic->id) : null;
        $generadoEn  = Carbon::now()->format('d/m/Y H:i');

        $resumenModulos = $despachos->groupBy('modulo_id')->map(fn($g) => [
            'nombre'    => $g->first()->modulo?->nombre ?? '—',
            'cantidad'  => $g->sum('cantidad'),
            'registros' => $g->count(),
        ])->values();

        $pdf = Pdf::loadView('despacho.reportes.vacuna', compact(
            'despachos',
            'vacuna',
            'asic',
            'totalDosis',
            'generadoEn',
            'stockActual',
            'resumenModulos'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('despacho_vacuna_' . Carbon::now()->format('Ymd') . '.pdf');
    }

    // ----------------------------------------------------------------
    // PDF: Reporte por período general
    // ----------------------------------------------------------------
    public function reportePeriodo(Request $request)
    {
        $asic = Asic::first();

        $query = Despacho::with(['vacuna', 'modulo', 'responsable'])
            ->where('asic_id', $asic->id);

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_envio', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_envio', '<=', $request->fecha_hasta);
        }
        if ($request->filled('modulo_id')) {
            $query->where('modulo_id', $request->modulo_id);
        }

        $despachos  = $query->orderBy('fecha_envio', 'desc')->get();
        $totalDosis = $despachos->sum('cantidad');
        $generadoEn = Carbon::now()->format('d/m/Y H:i');

        $resumenModulos = $despachos->groupBy('modulo_id')->map(fn($g) => [
            'nombre'    => $g->first()->modulo?->nombre ?? '—',
            'cantidad'  => $g->sum('cantidad'),
            'registros' => $g->count(),
        ])->values();

        $resumenVacunas = $despachos->groupBy('vacuna_id')->map(fn($g) => [
            'nombre'    => $g->first()->vacuna?->nombre ?? '—',
            'cantidad'  => $g->sum('cantidad'),
            'registros' => $g->count(),
        ])->values();

        $pdf = Pdf::loadView('despacho.reportes.periodo', compact(
            'despachos',
            'asic',
            'totalDosis',
            'generadoEn',
            'resumenModulos',
            'resumenVacunas'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('despacho_periodo_' . Carbon::now()->format('Ymd') . '.pdf');
    }
}
