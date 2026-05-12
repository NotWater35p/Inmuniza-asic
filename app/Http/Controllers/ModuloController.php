<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Models\Asic;
use App\Models\Personal;
use App\Http\Requests\ModuloRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class ModuloController extends Controller
{
    public function index(Request $request): View
    {
        $query = Modulo::with(['asic', 'jefe.cargo']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('rif', 'like', "%{$search}%")
                    ->orWhere('nombre', 'like', "%{$search}%")
                    ->orWhereHas('jefe', function ($subq) use ($search) {
                        $subq->where('nombre', 'like', "%{$search}%")
                            ->orWhere('apellido', 'like', "%{$search}%")
                            ->orWhere('cedula', 'like', "%{$search}%");
                    });
            });
        }

        $modulos = $query->orderBy('nombre')->paginate(12)->withQueryString();

        return view('modulo.index', compact('modulos'));
    }

    public function create(): View
    {
        $modulo = new Modulo();
        $asics  = Asic::orderBy('nombre')->get();
        $jefes  = Personal::with('cargo')
            ->whereHas('cargo', fn($q) => $q->where('nivel_acceso', 2))
            ->orderBy('apellido')
            ->get();
        $tipos  = Modulo::TIPOS_ESTABLECIMIENTO;

        return view('modulo.create', compact('modulo', 'asics', 'jefes', 'tipos'));
    }

    public function store(ModuloRequest $request): RedirectResponse
    {
        Modulo::create($request->validated());

        return Redirect::route('modulos.index')
            ->with('success', 'Módulo creado exitosamente.');
    }

    public function show($id): View
    {
        $modulo = Modulo::with(['asic', 'jefe.cargo'])->findOrFail($id);
        return view('modulo.show', compact('modulo'));
    }

    public function edit($id): View
    {
        $modulo = Modulo::findOrFail($id);
        $asics  = Asic::orderBy('nombre')->get();
        $jefes  = Personal::with('cargo')
            ->whereHas('cargo', fn($q) => $q->where('nivel_acceso', 2))
            ->orderBy('apellido')
            ->get();
        $tipos  = Modulo::TIPOS_ESTABLECIMIENTO;

        return view('modulo.edit', compact('modulo', 'asics', 'jefes', 'tipos'));
    }

    public function update(ModuloRequest $request, $id): RedirectResponse
    {
        $modulo = Modulo::findOrFail($id);
        $modulo->update($request->validated());

        return Redirect::route('modulos.index')
            ->with('success', 'Módulo actualizado exitosamente.');
    }

    public function destroy($id): RedirectResponse
    {
        Modulo::findOrFail($id)->delete();

        return Redirect::route('modulos.index')
            ->with('success', 'Módulo eliminado exitosamente.');
    }

    public function generarPDF($id)
    {
        $modulo = Modulo::with('asic')->findOrFail($id);
        $pdf = Pdf::loadView('modulo.pdf-individual', compact('modulo'));
        return $pdf->download("modulo-{$modulo->id}.pdf");
    }

    public function generarPDFUniversal()
    {
        $modulos = Modulo::with('asic')->orderBy('nombre')->get();
        $pdf = Pdf::loadView('modulo.pdf-universal', compact('modulos'));
        return $pdf->download('modulos-listado.pdf');
    }
}