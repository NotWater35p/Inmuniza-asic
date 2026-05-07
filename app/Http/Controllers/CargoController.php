<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CargoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CargoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $cargos = Cargo::paginate();

        return view('cargo.index', compact('cargos'))
            ->with('i', ($request->input('page', 1) - 1) * $cargos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cargo = new Cargo();

        return view('cargo.create', compact('cargo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CargoRequest $request): RedirectResponse
    {
        Cargo::create($request->validated());

        return Redirect::route('cargos.index')
            ->with('success', 'Cargo created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $cargo = Cargo::find($id);

        return view('cargo.show', compact('cargo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cargo = Cargo::find($id);

        return view('cargo.edit', compact('cargo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CargoRequest $request, Cargo $cargo): RedirectResponse
    {
        $cargo->update($request->validated());

        return Redirect::route('cargos.index')
            ->with('success', 'Cargo updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Cargo::find($id)->delete();

        return Redirect::route('cargos.index')
            ->with('success', 'Cargo deleted successfully');
    }
}
