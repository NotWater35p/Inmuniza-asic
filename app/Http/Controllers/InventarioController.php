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
        $hoy = now()->toDateString();

        $vacunas = Vacuna::with(['marca'])
            ->when($request->filled('buscar'), fn($q) =>
                $q->where('nombre', 'like', "%{$request->buscar}%"))
            ->orderBy('nombre')
            ->paginate(20)
            ->through(function ($vacuna) use ($hoy) {

                // Stock vigente (excluye lotes vencidos)
                $stockDisponible = (int) DB::table('carga')
                    ->where('vacuna_id', $vacuna->id)
                    ->where(fn($q) => $q->whereNull('fecha_vencimiento')
                                        ->orWhere('fecha_vencimiento', '>=', $hoy))
                    ->sum('cantidad_disponible');

                // Stock bruto en lotes vencidos con unidades aún en carga
                $stockVencidoBruto = (int) DB::table('carga')
                    ->where('vacuna_id', $vacuna->id)
                    ->whereNotNull('fecha_vencimiento')
                    ->where('fecha_vencimiento', '<', $hoy)
                    ->where('cantidad_disponible', '>', 0)
                    ->sum('cantidad_disponible');

                // Pérdidas registradas del ASIC para esos lotes vencidos
                // (lotes sin especificar también se descuentan por seguridad)
                $perdidoEnVencidos = (int) DB::table('perdida')
                    ->where('vacuna_id', $vacuna->id)
                    ->whereNull('modulo_id')
                    ->where(function ($q) use ($vacuna, $hoy) {
                        $q->whereIn('lote', function ($sub) use ($vacuna, $hoy) {
                            $sub->select('lote')
                                ->from('carga')
                                ->where('vacuna_id', $vacuna->id)
                                ->whereNotNull('lote')
                                ->whereNotNull('fecha_vencimiento')
                                ->where('fecha_vencimiento', '<', $hoy);
                        })->orWhereNull('lote'); // pérdidas sin lote también cuentan
                    })
                    ->sum('cantidad');

                $stockVencido = max(0, $stockVencidoBruto - $perdidoEnVencidos);

                $despachado = (int) DB::table('despacho')->where('vacuna_id', $vacuna->id)->sum('cantidad');
                $perdido    = (int) DB::table('perdida')
                    ->where('vacuna_id', $vacuna->id)
                    ->whereNull('modulo_id')
                    ->sum('cantidad');

                $vacuna->stock_actual     = max(0, $stockDisponible - $perdido);
                $vacuna->stock_vencido    = $stockVencido;
                $vacuna->has_vencidos     = $stockVencido > 0;  // flag campana naranja
                $vacuna->total_despachado = $despachado;
                $vacuna->total_perdido    = $perdido;

                return $vacuna;
            });

        // Todas las vacunas para el select del modal (sin paginar)
        $todasLasVacunas = Vacuna::orderBy('nombre')->get(['id', 'nombre']);

        return view('inventario.index', compact('vacunas', 'todasLasVacunas'));
    }

    // AJAX: detalle de lotes por vacuna
    public function lotes(int $vacunaId)
    {
        $hoy    = now()->toDateString();
        $vacuna = Vacuna::findOrFail($vacunaId);

        $lotes = DB::table('carga')
            ->select(
                'lote',
                DB::raw('SUM(cantidad) as entrado'),
                DB::raw('SUM(cantidad_disponible) as disponible_bruto'),
                DB::raw('MIN(fecha_vencimiento) as fecha_vencimiento')
            )
            ->where('vacuna_id', $vacunaId)
            ->whereNotNull('lote')
            ->groupBy('lote')
            ->orderBy('fecha_vencimiento')
            ->get()
            ->map(function ($lote) use ($vacunaId, $hoy) {
                $despachado = (int) DB::table('despacho')
                    ->where('vacuna_id', $vacunaId)->where('lote', $lote->lote)->sum('cantidad');
                $perdido = (int) DB::table('perdida')
                    ->where('vacuna_id', $vacunaId)->where('lote', $lote->lote)->sum('cantidad');

                $lote->despachado = $despachado;
                $lote->perdido    = $perdido;
                $lote->disponible = max(0, (int) $lote->disponible_bruto - $perdido);
                $lote->vencido    = $lote->fecha_vencimiento && $lote->fecha_vencimiento < $hoy;
                return $lote;
            });

        return response()->json(['vacuna' => $vacuna->nombre, 'lotes' => $lotes]);
    }

    // Registrar pérdida del ASIC desde inventario
    public function storePerdida(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vacuna_id'   => 'required|exists:vacuna,id',
            'lote'        => 'nullable|string|max:50',
            'cantidad'    => 'required|integer|min:1',
            'motivo'      => 'required|in:Vencimiento,Rotura,Cadena de frío,Otro',
            'observacion' => 'nullable|string|max:500',
            'fecha'       => 'required|date|before_or_equal:today',
        ], [
            'vacuna_id.required'    => 'Selecciona una vacuna.',
            'cantidad.required'     => 'La cantidad es obligatoria.',
            'cantidad.min'          => 'La cantidad debe ser al menos 1.',
            'motivo.required'       => 'Selecciona un motivo.',
            'fecha.required'        => 'La fecha es obligatoria.',
            'fecha.before_or_equal' => 'La fecha no puede ser futura.',
        ]);

        Perdida::create($validated); // modulo_id = null → pérdida del ASIC

        return redirect()->route('inventario.index')
            ->with('success', 'Pérdida del ASIC registrada correctamente.');
    }
}