<?php

namespace App\Http\Controllers;

use App\Models\Vacuna;
use App\Models\Perdida;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class InventarioController extends Controller
{
    public function index(Request $request): View
    {
        // Traemos vacunas con totales calculados en una sola query
        $vacunas = Vacuna::with(['marca'])
            ->withSum('cargas', 'cantidad')           // total entrado
            ->withSum('despachos', 'cantidad')         // total despachado
            ->withSum('perdidas', 'cantidad')          // total perdido
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('nombre', 'like', "%{$request->search}%");
            })
            ->orderBy('nombre')
            ->get()
            ->map(function ($vacuna) {
                $entrado    = $vacuna->cargas_sum_cantidad    ?? 0;
                $despachado = $vacuna->despachos_sum_cantidad ?? 0;
                $perdido    = $vacuna->perdidas_sum_cantidad  ?? 0;
                $vacuna->stock_actual = $entrado - $despachado - $perdido;
                $vacuna->total_entrado    = $entrado;
                $vacuna->total_despachado = $despachado;
                $vacuna->total_perdido    = $perdido;
                return $vacuna;
            });

        return view('inventario.index', compact('vacunas'));
    }

    /**
     * Devuelve el detalle de lotes por vacuna (AJAX)
     */
    public function lotes(int $vacunaId)
    {
        $vacuna = Vacuna::findOrFail($vacunaId);

        // Lotes de cargas agrupados
        $lotes = DB::table('carga')
            ->select(
                'lote',
                DB::raw('SUM(cantidad) as entrado'),
                DB::raw('MIN(fecha_vencimiento) as fecha_vencimiento')
            )
            ->where('vacuna_id', $vacunaId)
            ->whereNotNull('lote')
            ->groupBy('lote')
            ->get()
            ->map(function ($lote) use ($vacunaId) {
                // Despachado de ese lote
                $lote->despachado = DB::table('despacho')
                    ->where('vacuna_id', $vacunaId)
                    ->where('lote', $lote->lote)
                    ->sum('cantidad');

                // Perdido de ese lote
                $lote->perdido = DB::table('perdida')
                    ->where('vacuna_id', $vacunaId)
                    ->where('lote', $lote->lote)
                    ->sum('cantidad');

                $lote->disponible = $lote->entrado - $lote->despachado - $lote->perdido;
                return $lote;
            });

        return response()->json([
            'vacuna' => $vacuna->nombre,
            'lotes'  => $lotes,
        ]);
    }

    /**
     * Registrar pérdida
     */
    public function storePerdida(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vacuna_id'   => 'required|exists:vacuna,id',
            'lote'        => 'nullable|string|max:50',
            'cantidad'    => 'required|integer|min:1',
            'motivo'      => 'required|in:Vencimiento,Rotura,Cadena de frío,Otro',
            'observacion' => 'nullable|string|max:500',
            'fecha'       => 'required|date',
        ]);

        Perdida::create($validated);

        return redirect()->route('inventario.index')
            ->with('success', 'Pérdida registrada correctamente.');
    }
}