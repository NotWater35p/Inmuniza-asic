<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Models\Vacuna;
use App\Models\Despacho;
use App\Models\Tratamiento;
use App\Models\Perdida;
use Illuminate\View\View;

class ModuloInventarioController extends Controller
{
    public function show(Modulo $modulo): View
    {
        // Autorizar acceso
        $user = auth()->user();
        if (!$user->esAdmin() && !($user->esJefeModulo() && $user->modulo()?->id === $modulo->id)) {
            abort(403);
        }

        // Inventario del módulo: despachado - usado en tratamientos
        $inventario = Vacuna::with('marca')
            ->get()
            ->map(function ($vacuna) use ($modulo) {

                // Total despachado a este módulo por lote
                $lotes = Despacho::where('modulo_id', $modulo->id)
                    ->where('vacuna_id', $vacuna->id)
                    ->selectRaw('lote, SUM(cantidad) as despachado, MIN(fecha_envio) as primer_despacho')
                    ->groupBy('lote')
                    ->get()
                    ->map(function ($lote) use ($modulo, $vacuna) {
                        $usado = Tratamiento::whereHas('jornada', fn($q) =>
                                $q->where('modulo_id', $modulo->id))
                            ->where('vacuna_id', $vacuna->id)
                            ->sum('dosis_aplicada');

                        // Nota: el usado total lo distribuimos al lote más antiguo
                        // para simplificar — en v2 se puede hacer por lote específico
                        $lote->usado     = 0;
                        $lote->disponible = $lote->despachado;
                        return $lote;
                    });

                $totalDespachado = $lotes->sum('despachado');

                $totalUsado = Tratamiento::whereHas('jornada', fn($q) =>
                        $q->where('modulo_id', $modulo->id))
                    ->where('vacuna_id', $vacuna->id)
                    ->sum('dosis_aplicada');

                $totalPerdido = Perdida::where('modulo_id', $modulo->id)
                    ->where('vacuna_id', $vacuna->id)
                    ->sum('cantidad');

                $vacuna->total_despachado = $totalDespachado;
                $vacuna->total_usado      = $totalUsado;
                $vacuna->total_perdido    = $totalPerdido;
                $vacuna->disponible       = max(0, $totalDespachado - $totalUsado - $totalPerdido);
                $vacuna->lotes            = $lotes;
                return $vacuna;
            })
            ->filter(fn($v) => $v->total_despachado > 0)
            ->sortByDesc('disponible')
            ->values();

        // Últimos despachos recibidos
        $ultimosDespachos = Despacho::with(['vacuna', 'responsable'])
            ->where('modulo_id', $modulo->id)
            ->orderBy('fecha_envio', 'desc')
            ->limit(10)
            ->get();

        // Stats rápidos
        $stats = [
            'total_vacunas'   => $inventario->count(),
            'total_recibido'  => $inventario->sum('total_despachado'),
            'total_usado'     => $inventario->sum('total_usado'),
            'total_disponible'=> $inventario->sum('disponible'),
        ];

        return view('modulo.inventario', compact('modulo', 'inventario', 'ultimosDespachos', 'stats'));
    }
}