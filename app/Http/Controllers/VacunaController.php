<?php

namespace App\Http\Controllers;

use App\Models\Vacuna;
use App\Models\Marca;
use App\Http\Requests\VacunaRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class VacunaController extends Controller
{
    public function index(Request $request): View
    {
        $query = Vacuna::with('marca');

        // Filtro por búsqueda
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nombre', 'like', "%$s%")
                  ->orWhere('enfermedad', 'like', "%$s%")
                  ->orWhere('presentacion', 'like', "%$s%")
                  ->orWhereHas('marca', fn($q) => $q->where('nombre', 'like', "%$s%"));
            });
        }


        // Ordenamiento
        $sortable  = ['nombre', 'enfermedad', 'presentacion', 'numero_dosis', 'created_at'];
        $sort      = in_array($request->sort, $sortable) ? $request->sort : 'nombre';
        $direction = $request->direction === 'desc' ? 'desc' : 'asc';

        if ($sort === 'nombre') {
            $query->orderBy('nombre', $direction);
        } elseif ($sort === 'numero_dosis') {
            $query->orderBy('numero_dosis', $direction);
        } else {
            $query->orderBy($sort, $direction);
        }

        $vacunas = $query->paginate(12)->withQueryString();

        // Sidebar: marcas con conteo de vacunas
        $marcas = Marca::withCount('vacunas')->orderBy('nombre')->get();

        // Marca seleccionada
        $marcaSeleccionada = $request->filled('marca_id')
            ? Marca::find($request->marca_id)
            : null;

        return view('vacuna.index', compact('vacunas', 'marcas', 'marcaSeleccionada', 'sort', 'direction'))
            ->with('i', ($request->input('page', 1) - 1) * $vacunas->perPage());
    }

    public function create(): View
    {
        $vacuna = new Vacuna();
        $marcas = Marca::orderBy('nombre')->get();
        return view('vacuna.create', compact('vacuna', 'marcas'));
    }

    public function store(VacunaRequest $request): RedirectResponse
    {
        Vacuna::create($request->validated());
        return Redirect::route('vacunas.index')
            ->with('success', 'Vacuna creada exitosamente.');
    }

    public function show($id): View
    {
        $vacuna = Vacuna::with('marca')->findOrFail($id);
        return view('vacuna.show', compact('vacuna'));
    }

    public function edit($id): View
    {
        $vacuna = Vacuna::findOrFail($id);
        $marcas = Marca::orderBy('nombre')->get();
        return view('vacuna.edit', compact('vacuna', 'marcas'));
    }

    public function update(VacunaRequest $request, $id): RedirectResponse
    {
        Vacuna::findOrFail($id)->update($request->validated());
        return Redirect::route('vacunas.index')
            ->with('success', 'Vacuna actualizada exitosamente.');
    }

    public function destroy($id): RedirectResponse
    {
        Vacuna::findOrFail($id)->delete();
        return Redirect::route('vacunas.index')
            ->with('success', 'Vacuna eliminada exitosamente.');
    }

    public function generarPDF($id)
    {
        $vacuna = Vacuna::with('marca')->findOrFail($id);
        $pdf = Pdf::loadView('vacuna.pdf', compact('vacuna'));
        return $pdf->download("vacuna-{$vacuna->nombre}.pdf");
    }

    /**
     * Crear marca 
     */
    public function storeMarca(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:100|unique:marca,nombre',
            'descripcion' => 'nullable|string',
        ], [
            'nombre.required' => 'El nombre de la marca es obligatorio.',
            'nombre.unique'   => 'Ya existe una marca con ese nombre.',
        ]);

        $marca = Marca::create([
            'nombre'      => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        return response()->json([
            'success' => true,
            'marca'   => ['id' => $marca->id, 'nombre' => $marca->nombre],
        ]);
    }
}