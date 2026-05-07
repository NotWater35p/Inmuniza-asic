<?php

namespace App\Http\Controllers;

use App\Models\Asic;
use App\Models\Carga;
use App\Models\Despacho;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\AsicRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AsicController extends Controller
{

    public function index(): RedirectResponse
    {
        $asic = Asic::first();

        if (!$asic) {
            return Redirect::route('inicio')
                ->with('error', 'No hay un ASIC registrado en el sistema. Contacta al administrador.');
        }

        return Redirect::route('inicio', $asic->id);
    }

    public function show($id): View
    {
        $asic = Asic::with([
            'modulos',
            'personal.cargo',
            'cargas.vacuna',
            'despachos',
        ])->findOrFail($id);

        $stats = [
            'total_modulos'     => $asic->modulos->count(),
            'total_personal'    => $asic->personal->count(),
            'total_cargas'      => $asic->cargas->count(),
            'total_despachos'   => $asic->despachos->count(),
            'dosis_recibidas'   => $asic->cargas->sum('cantidad'),
            'dosis_despachadas' => $asic->despachos->sum('cantidad'),
        ];

        $inventario = $asic->inventario();

        $proxVencer = Carga::with('vacuna')
            ->where('asic_id', $asic->id)
            ->whereDate('fecha_vencimiento', '>=', now())
            ->whereDate('fecha_vencimiento', '<=', now()->addDays(30))
            ->orderBy('fecha_vencimiento')
            ->get();

        $ultimosDespachos = Despacho::with(['vacuna', 'modulo'])
            ->where('asic_id', $asic->id)
            ->orderBy('fecha_envio', 'desc')
            ->limit(5)
            ->get();

        $ultimasCargas = Carga::with('vacuna')
            ->where('asic_id', $asic->id)
            ->orderBy('fecha_llegada', 'desc')
            ->limit(5)
            ->get();

        return view('inicio', compact(
            'asic', 'stats', 'inventario',
            'proxVencer', 'ultimosDespachos', 'ultimasCargas'
        ));
    }

    /**
     * Formulario de edición — solo nivel 5 (Administrador).
     */
    public function edit($id): View|RedirectResponse
    {
        // Verificación de nivel de acceso
        $nivelUsuario = auth()->user()?->personal?->cargo?->nivel_acceso ?? 0;
        if ($nivelUsuario < 5) {
            return Redirect::route('inicio', $id)
                ->with('error', 'Solo los administradores pueden editar la información del ASIC.');
        }

        $asic = Asic::findOrFail($id);
        return view('asic.edit', compact('asic'));
    }

    /**
     * Actualizar — solo nivel 5.
     */
    public function update(AsicRequest $request, Asic $asic): RedirectResponse
    {
        $nivelUsuario = auth()->user()?->personal?->cargo?->nivel_acceso ?? 0;
        if ($nivelUsuario < 5) {
            return Redirect::route('inicio', $asic->id)
                ->with('error', 'No tienes permiso para realizar esta acción.');
        }

        $asic->update($request->validated());

        return Redirect::route('inicio', $asic->id)
            ->with('success', 'Información del ASIC actualizada exitosamente.');
    }

    // create y destroy eliminados — ASIC es único e inmutable
}