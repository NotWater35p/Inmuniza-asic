<?php

namespace App\Http\Controllers;

use App\Models\Representante;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\RepresentanteRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class RepresentanteController extends Controller
{
    public function index(Request $request): View
    {
        $query = Representante::withCount('pacientes');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('cedula',   'like', "%$s%")
                  ->orWhere('telefono', 'like', "%$s%")
                  ->orWhere('relacion', 'like', "%$s%");
            });
        }

        $representantes = $query->paginate(15)->withQueryString();

        return view('representante.index', compact('representantes'))
            ->with('i', ($request->input('page', 1) - 1) * $representantes->perPage());
    }

    public function create(): View
    {
        $representante = new Representante();
        return view('representante.create', compact('representante'));
    }

    public function store(RepresentanteRequest $request): RedirectResponse
    {
        Representante::create($request->validated());

        return Redirect::route('representantes.index')
            ->with('success', 'Representante creado exitosamente.');
    }

    public function show($cedula): View
    {
        $representante = Representante::with('pacientes.etnia', 'pacientes.sector')
            ->findOrFail($cedula);

        return view('representante.show', compact('representante'));
    }

    public function edit($cedula): View
    {
        $representante = Representante::findOrFail($cedula);
        return view('representante.edit', compact('representante'));
    }

    public function update(RepresentanteRequest $request, Representante $representante): RedirectResponse
    {
        $representante->update($request->validated());

        return Redirect::route('representantes.index')
            ->with('success', 'Representante actualizado exitosamente.');
    }

    public function destroy($cedula): RedirectResponse
    {
        $representante = Representante::withCount('pacientes')->findOrFail($cedula);

        if ($representante->pacientes_count > 0) {
            return Redirect::route('representantes.index')
                ->with('error', 'No se puede eliminar: tiene ' . $representante->pacientes_count . ' paciente(s) asociado(s).');
        }

        $representante->delete();

        return Redirect::route('representantes.index')
            ->with('success', 'Representante eliminado exitosamente.');
    }
}