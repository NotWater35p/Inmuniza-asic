<?php

namespace App\Http\Controllers;

use App\Models\Perdida;
use App\Models\Vacuna;
use App\Models\Modulo;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PerdidaController extends Controller
{
    public function index(Request $request): View
    {
        $perdidas = Perdida::with(['vacuna', 'modulo'])
            ->when($request->filled('p_vacuna'), fn($q) =>
                $q->where('vacuna_id', $request->p_vacuna))
            ->when($request->filled('p_modulo'), function ($q) use ($request) {
                if ($request->p_modulo === 'asic') {
                    $q->whereNull('modulo_id');
                } else {
                    $q->where('modulo_id', $request->p_modulo);
                }
            })
            ->when($request->filled('p_motivo'), fn($q) =>
                $q->where('motivo', $request->p_motivo))
            ->when($request->filled('p_desde'), fn($q) =>
                $q->whereDate('fecha', '>=', $request->p_desde))
            ->when($request->filled('p_hasta'), fn($q) =>
                $q->whereDate('fecha', '<=', $request->p_hasta))
            ->orderByDesc('fecha')
            ->paginate(20)
            ->withQueryString();

        $vacunas = Vacuna::orderBy('nombre')->get();
        $modulos = Modulo::orderBy('nombre')->get();

        return view('perdida.index', compact('perdidas', 'vacunas', 'modulos'));
    }

    public function destroy(Perdida $perdida)
    {
        $perdida->delete();

        return redirect()->route('perdida.index')
            ->with('success', 'Pérdida eliminada correctamente.');
    }
}