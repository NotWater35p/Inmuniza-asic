<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Http\Requests\MarcaRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class MarcaController extends Controller
{
    public function index(Request $request): View
    {
        $query = Marca::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nombre', 'like', "%$search%")
                  ->orWhere('descripcion', 'like', "%$search%");
        }

        $marcas = $query->orderBy('nombre')->paginate(10)->withQueryString();

        return view('marca.index', compact('marcas'))
            ->with('i', ($request->input('page', 1) - 1) * $marcas->perPage());
    }

    public function create(): View
    {
        $marca = new Marca();
        return view('marca.create', compact('marca'));
    }

    public function store(MarcaRequest $request): RedirectResponse
    {
        Marca::create($request->validated());
        return Redirect::route('marcas.index')->with('success', 'Marca creada exitosamente.');
    }

    public function show($id): View
    {
        $marca = Marca::with('vacunas')->findOrFail($id);
        return view('marca.show', compact('marca'));
    }

    public function edit($id): View
    {
        $marca = Marca::findOrFail($id);
        return view('marca.edit', compact('marca'));
    }

    public function update(MarcaRequest $request, $id): RedirectResponse
    {
        $marca = Marca::findOrFail($id);
        $marca->update($request->validated());
        return Redirect::route('marcas.index')->with('success', 'Marca actualizada exitosamente.');
    }

    public function destroy($id): RedirectResponse
    {
        Marca::destroy($id);
        return Redirect::route('marcas.index')->with('success', 'Marca eliminada exitosamente.');
    }

    // PDF individual 
    public function generarPDF($id)
    {
        $marca = Marca::with('vacunas')->findOrFail($id);
        $pdf = Pdf::loadView('marca.pdf-individual', compact('marca'));
        return $pdf->download("marca-{$marca->id}.pdf");
    }

    // PDF universal 
    public function generarPDFUniversal()
    {
        $marcas = Marca::with('vacunas')->orderBy('nombre')->get();
        $pdf = Pdf::loadView('marca.pdf-universal', compact('marcas'));
        return $pdf->download('marcas-universal.pdf');
    }
}