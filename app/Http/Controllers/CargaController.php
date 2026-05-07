<?php

namespace App\Http\Controllers;

use App\Models\Carga;
use App\Models\Vacuna;
use App\Models\Asic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CargaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class CargaController extends Controller
{
    // -------------------------------------------------------
    // INDEX
    // -------------------------------------------------------
    public function index(Request $request): View
    {
        $query = Carga::with(['vacuna', 'asic']);

        if ($request->filled('vacuna')) {
            $query->whereHas('vacuna', fn($q) =>
                $q->where('nombre', 'like', '%' . $request->vacuna . '%')
            );
        }
        if ($request->filled('lote')) {
            $query->where('lote', 'like', '%' . $request->lote . '%');
        }
        if ($request->filled('fecha_llegada_desde')) {
            $query->whereDate('fecha_llegada', '>=', $request->fecha_llegada_desde);
        }
        if ($request->filled('fecha_llegada_hasta')) {
            $query->whereDate('fecha_llegada', '<=', $request->fecha_llegada_hasta);
        }
        if ($request->filled('fecha_vencimiento_desde')) {
            $query->whereDate('fecha_vencimiento', '>=', $request->fecha_vencimiento_desde);
        }
        if ($request->filled('fecha_vencimiento_hasta')) {
            $query->whereDate('fecha_vencimiento', '<=', $request->fecha_vencimiento_hasta);
        }
        if ($request->filled('cantidad_min')) {
            $query->where('cantidad', '>=', $request->cantidad_min);
        }
        if ($request->filled('cantidad_max')) {
            $query->where('cantidad', '<=', $request->cantidad_max);
        }
        if ($request->filled('proximos_vencer')) {
            $dias = (int) $request->proximos_vencer;
            $query->whereDate('fecha_vencimiento', '>=', Carbon::today())
                  ->whereDate('fecha_vencimiento', '<=', Carbon::today()->addDays($dias));
        }

        $query->orderBy('fecha_llegada', 'desc');

        $cargas  = $query->paginate(15)->withQueryString();
        $vacunas = Vacuna::orderBy('nombre')->get();

        return view('carga.index', compact('cargas', 'vacunas'))
            ->with('i', ($request->input('page', 1) - 1) * $cargas->perPage());
    }

    // -------------------------------------------------------
    // CREATE
    // -------------------------------------------------------
    public function create(Request $request): View
    {
        $carga   = new Carga();
        $vacunas = Vacuna::orderBy('nombre')->get();
        $asic    = Asic::first();

        // Si viene de clonar
        $clonado_de = null;
        if ($request->filled('clonar')) {
            $original = Carga::with('vacuna')->find($request->clonar);
            if ($original) {
                $clonado_de = $original;
                $carga->vacuna_id = $original->vacuna_id;
                $carga->observaciones = $original->observaciones;
            }
        }

        return view('carga.create', compact('carga', 'vacunas', 'asic', 'clonado_de'));
    }

    // -------------------------------------------------------
    // STORE
    // -------------------------------------------------------
    public function store(CargaRequest $request): RedirectResponse
    {
        Carga::create($request->validated());

        return Redirect::route('cargas.index')
            ->with('success', 'Carga registrada exitosamente.');
    }

    // -------------------------------------------------------
    // STORE BULK
    // -------------------------------------------------------
    public function storeBulk(Request $request): RedirectResponse
    {
        $request->validate([
            'cargas'                     => 'required|array|min:1',
            'cargas.*.vacuna_id'         => 'required|exists:vacuna,id',
            'cargas.*.lote'              => 'required|string|max:100',
            'cargas.*.fecha_llegada'     => 'required|date',
            'cargas.*.fecha_vencimiento' => 'required|date|after:cargas.*.fecha_llegada',
            'cargas.*.cantidad'          => 'required|integer|min:1',
            'cargas.*.observaciones'     => 'nullable|string',
        ]);

        $asic = Asic::first();

        foreach ($request->cargas as $item) {
            Carga::create([
                'asic_id'           => $asic->id,
                'vacuna_id'         => $item['vacuna_id'],
                'lote'              => $item['lote'],
                'fecha_llegada'     => $item['fecha_llegada'],
                'fecha_vencimiento' => $item['fecha_vencimiento'],
                'cantidad'          => $item['cantidad'],
                'observaciones'     => $item['observaciones'] ?? null,
            ]);
        }

        return Redirect::route('cargas.index')
            ->with('success', count($request->cargas) . ' carga(s) registrada(s) exitosamente.');
    }

    // -------------------------------------------------------
    // SHOW
    // -------------------------------------------------------
    public function show($id): View
    {
        $carga = Carga::with(['vacuna.marca', 'asic'])->findOrFail($id);
        return view('carga.show', compact('carga'));
    }

    // -------------------------------------------------------
    // EDIT
    // -------------------------------------------------------
    public function edit($id): View
    {
        $carga   = Carga::findOrFail($id);
        $vacunas = Vacuna::orderBy('nombre')->get();
        $asic    = Asic::first();
        return view('carga.edit', compact('carga', 'vacunas', 'asic'));
    }

    // -------------------------------------------------------
    // UPDATE
    // -------------------------------------------------------
    public function update(CargaRequest $request, Carga $carga): RedirectResponse
    {
        $carga->update($request->validated());
        return Redirect::route('cargas.index')
            ->with('success', 'Carga actualizada exitosamente.');
    }

    // -------------------------------------------------------
    // DESTROY
    // -------------------------------------------------------
    public function destroy($id): RedirectResponse
    {
        Carga::findOrFail($id)->delete();
        return Redirect::route('cargas.index')
            ->with('success', 'Carga eliminada exitosamente.');
    }

    // -------------------------------------------------------
    // CLONE
    // -------------------------------------------------------
    public function clone($id): RedirectResponse
    {
        return Redirect::route('cargas.create', ['clonar' => $id]);
    }

    // -------------------------------------------------------
    // PDF GENERAL
    // -------------------------------------------------------
    public function reporteGeneral(Request $request)
    {
        $query = Carga::with(['vacuna', 'asic']);

        if ($request->filled('vacuna')) {
            $query->whereHas('vacuna', fn($q) => $q->where('nombre', 'like', '%'.$request->vacuna.'%'));
        }
        if ($request->filled('lote')) {
            $query->where('lote', 'like', '%'.$request->lote.'%');
        }
        if ($request->filled('fecha_llegada_desde')) {
            $query->whereDate('fecha_llegada', '>=', $request->fecha_llegada_desde);
        }
        if ($request->filled('fecha_llegada_hasta')) {
            $query->whereDate('fecha_llegada', '<=', $request->fecha_llegada_hasta);
        }
        if ($request->filled('fecha_vencimiento_desde')) {
            $query->whereDate('fecha_vencimiento', '>=', $request->fecha_vencimiento_desde);
        }
        if ($request->filled('fecha_vencimiento_hasta')) {
            $query->whereDate('fecha_vencimiento', '<=', $request->fecha_vencimiento_hasta);
        }
        if ($request->filled('cantidad_min')) {
            $query->where('cantidad', '>=', $request->cantidad_min);
        }
        if ($request->filled('cantidad_max')) {
            $query->where('cantidad', '<=', $request->cantidad_max);
        }
        if ($request->filled('proximos_vencer')) {
            $dias = (int) $request->proximos_vencer;
            $query->whereDate('fecha_vencimiento', '>=', Carbon::today())
                  ->whereDate('fecha_vencimiento', '<=', Carbon::today()->addDays($dias));
        }

        $cargas     = $query->orderBy('fecha_llegada', 'desc')->get();
        $asic       = Asic::first();
        $totalDosis = $cargas->sum('cantidad');
        $generadoEn = Carbon::now()->format('d/m/Y H:i');

        $pdf = Pdf::loadView('carga.reportes.general', compact(
            'cargas', 'asic', 'totalDosis', 'generadoEn'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('reporte_cargas_' . Carbon::now()->format('Ymd_His') . '.pdf');
    }

    // -------------------------------------------------------
    // PDF INDIVIDUAL
    // -------------------------------------------------------
    public function reporteIndividual($id)
    {
        $carga      = Carga::with(['vacuna.marca', 'asic'])->findOrFail($id);
        $asic       = Asic::first();
        $generadoEn = Carbon::now()->format('d/m/Y H:i');

        $pdf = Pdf::loadView('carga.reportes.individual', compact(
            'carga', 'asic', 'generadoEn'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('carga_' . $carga->lote . '_' . Carbon::now()->format('Ymd') . '.pdf');
    }
}