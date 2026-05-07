<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use App\Models\Asic;
use App\Models\Cargo;
use App\Http\Requests\PersonalRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class PersonalController extends Controller
{
    public function index(Request $request): View
    {
        $query = Personal::with(['asic', 'cargo']);

        // Búsqueda
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('cedula',   'like', "%$s%")
                  ->orWhere('nombre',  'like', "%$s%")
                  ->orWhere('apellido','like', "%$s%")
                  ->orWhere('telefono','like', "%$s%")
                  ->orWhere('correo',  'like', "%$s%")
                  ->orWhereHas('cargo', fn($q) => $q->where('nombre', 'like', "%$s%"));
            });
        }

        // Filtro por cargo
        if ($request->filled('cargo_id')) {
            $query->where('cargo_id', $request->cargo_id);
        }

        // Ordenamiento
        $sortable  = ['cedula', 'nombre', 'apellido', 'created_at'];
        $sort      = in_array($request->sort, $sortable) ? $request->sort : 'cedula';
        $direction = $request->direction === 'desc' ? 'desc' : 'asc';
        $query->orderBy($sort, $direction);

        $personals = $query->paginate(12)->withQueryString();
        $cargos    = Cargo::orderBy('nombre')->get();

        return view('personal.index', compact('personals', 'cargos', 'sort', 'direction'))
            ->with('i', ($request->input('page', 1) - 1) * $personals->perPage());
    }

    public function create(): View
    {
        $personal = new Personal();
        $cargos   = Cargo::orderBy('nombre')->get();
        $asic     = Asic::first();

        return view('personal.create', compact('personal', 'cargos', 'asic'));
    }

    public function store(PersonalRequest $request): RedirectResponse
    {
        Personal::create($request->validated());

        return Redirect::route('personal.index')
            ->with('success', 'Personal registrado exitosamente.');
    }

    public function show($cedula): View
    {
        $personal = Personal::with(['asic', 'cargo', 'user'])->findOrFail($cedula);
        return view('personal.show', compact('personal'));
    }

    public function edit($cedula): View
    {
        $personal = Personal::findOrFail($cedula);
        $cargos   = Cargo::orderBy('nombre')->get();
        $asic     = Asic::first();

        return view('personal.edit', compact('personal', 'cargos', 'asic'));
    }

    public function update(PersonalRequest $request, $cedula): RedirectResponse
    {
        Personal::findOrFail($cedula)->update($request->validated());

        return Redirect::route('personal.index')
            ->with('success', 'Personal actualizado exitosamente.');
    }

    public function destroy($cedula): RedirectResponse
    {
        $personal = Personal::with('user')->findOrFail($cedula);

        // Si tiene usuario activo, advertir
        if ($personal->user) {
            return Redirect::route('personal.index')
                ->with('error', 'No se puede eliminar a ' . $personal->nombre . ' porque tiene un usuario activo en el sistema. Revoca su acceso primero.');
        }

        $personal->delete();

        return Redirect::route('personal.index')
            ->with('success', 'Personal eliminado exitosamente.');
    }

    public function generarPDF($cedula)
    {
        $personal = Personal::with(['asic', 'cargo'])->findOrFail($cedula);
        $pdf = Pdf::loadView('personal.pdf', compact('personal'));
        return $pdf->download("personal-{$personal->cedula}.pdf");
    }
}