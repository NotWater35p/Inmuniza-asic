<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Representante;
use App\Models\Etnia;
use App\Models\Sector;
use App\Models\User;
use App\Models\Personal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\PacienteRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PacienteController extends Controller
{
    public function index(Request $request): View
    {
        $user   = auth()->user();
        $modulo = $user->esJefeModulo() ? $user->modulo() : null;

        $query = Paciente::with(['etnia', 'sector', 'representante']);

        // Jefe de módulo solo ve pacientes atendidos por su módulo
        if ($modulo) {
            $query->whereHas(
                'tratamientos',
                fn($q) =>
                $q->whereHas(
                    'jornada',
                    fn($q2) =>
                    $q2->where('modulo_id', $modulo->id)
                )
            );
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('cedula',    'like', "%$s%")
                    ->orWhere('nombres',  'like', "%$s%")
                    ->orWhere('apellidos', 'like', "%$s%")
                    ->orWhere('telefono', 'like', "%$s%")
                    ->orWhereHas('etnia',  fn($q) => $q->where('nombre', 'like', "%$s%"))
                    ->orWhereHas('sector', fn($q) => $q->where('nombre', 'like', "%$s%"))
                    ->orWhereHas(
                        'representante',
                        fn($q) =>
                        $q->where('cedula', 'like', "%$s%")
                            ->orWhere('telefono', 'like', "%$s%")
                    );
            });
        }

        if ($request->filled('activo')) {
            $query->where('activo', $request->activo);
        }
        if ($request->filled('etnia_id')) {
            $query->where('etnia_id', $request->etnia_id);
        }
        if ($request->filled('sector_id')) {
            $query->where('sector_id', $request->sector_id);
        }
        if ($request->filled('sexo')) {
            $query->where('sexo', $request->sexo);
        }

        // Ordenamiento
        $sortable  = ['nombres', 'apellidos', 'cedula', 'fecha_nacimiento', 'created_at'];
        $sort      = in_array($request->sort, $sortable) ? $request->sort : 'created_at';
        $direction = $request->direction === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sort, $direction);

        $pacientes = $query->paginate(15)->withQueryString();
        $etnias    = Etnia::orderBy('nombre')->get();
        $sectores  = Sector::orderBy('nombre')->get();

        return view('paciente.index', compact(
            'pacientes',
            'etnias',
            'sectores',
            'sort',
            'direction'
        ))->with('i', ($request->input('page', 1) - 1) * $pacientes->perPage());
    }

    public function create(): View
    {
        $paciente = new Paciente();
        $etnias   = Etnia::orderBy('nombre')->get();
        $sectores = Sector::orderBy('nombre')->get();
        return view('paciente.create', compact('paciente', 'etnias', 'sectores'));
    }

    public function store(PacienteRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request) {
            $representanteId = null;
            if (!empty($validated['representante']['cedula'])) {
                $rep = Representante::updateOrCreate(
                    ['cedula' => $validated['representante']['cedula']],
                    [
                        'telefono' => $validated['representante']['telefono'] ?? null,
                        'relacion' => $validated['representante']['relacion'] ?? null,
                    ]
                );
                $representanteId = $rep->cedula;
            }

            Paciente::create([
                'cedula'           => $validated['cedula'] ?? null,
                'nombres'          => $validated['nombres'],
                'apellidos'        => $validated['apellidos'],
                'fecha_nacimiento' => $validated['fecha_nacimiento'],
                'sexo'             => $validated['sexo'],
                'telefono'         => $validated['telefono'] ?? null,
                'direccion'        => $validated['direccion'] ?? null,
                'etnia_id'         => $validated['etnia_id'] ?? null,
                'sector_id'        => $validated['sector_id'] ?? null,
                'representante_id' => $representanteId,
                'activo'           => $request->boolean('activo', true),
            ]);
        });

        return Redirect::route('pacientes.index')
            ->with('success', 'Paciente registrado exitosamente.');
    }

    public function show($id): View
    {
        $paciente = Paciente::with(['etnia', 'sector', 'representante'])->findOrFail($id);
        return view('paciente.show', compact('paciente'));
    }

    public function edit($id): View
    {
        $paciente = Paciente::with('representante')->findOrFail($id);
        $etnias   = Etnia::orderBy('nombre')->get();
        $sectores = Sector::orderBy('nombre')->get();
        return view('paciente.edit', compact('paciente', 'etnias', 'sectores'));
    }

    public function update(PacienteRequest $request, $id): RedirectResponse
    {
        $paciente  = Paciente::findOrFail($id);
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $paciente, $request) {
            $representanteId = $paciente->representante_id;

            if (!empty($validated['representante']['cedula'])) {
                $rep = Representante::updateOrCreate(
                    ['cedula' => $validated['representante']['cedula']],
                    [
                        'telefono' => $validated['representante']['telefono'] ?? null,
                        'relacion' => $validated['representante']['relacion'] ?? null,
                    ]
                );
                $representanteId = $rep->cedula;
            }

            $paciente->update([
                'cedula'           => $validated['cedula'] ?? null,
                'nombres'          => $validated['nombres'],
                'apellidos'        => $validated['apellidos'],
                'fecha_nacimiento' => $validated['fecha_nacimiento'],
                'sexo'             => $validated['sexo'],
                'telefono'         => $validated['telefono'] ?? null,
                'direccion'        => $validated['direccion'] ?? null,
                'etnia_id'         => $validated['etnia_id'] ?? null,
                'sector_id'        => $validated['sector_id'] ?? null,
                'representante_id' => $representanteId,
                'activo'           => $request->boolean('activo', true),
            ]);
        });

        return Redirect::route('pacientes.index')
            ->with('success', 'Paciente actualizado exitosamente.');
    }

    public function destroy($id): RedirectResponse
    {
        Paciente::findOrFail($id)->delete();
        return Redirect::route('pacientes.index')
            ->with('success', 'Paciente eliminado exitosamente.');
    }

    public function generarPDF($id)
    {
        $paciente = Paciente::with(['etnia', 'sector', 'representante'])->findOrFail($id);
        $pdf = Pdf::loadView('paciente.pdf', compact('paciente'))
            ->setPaper('a4', 'portrait');
        return $pdf->download("paciente-{$paciente->id}.pdf");
    }
}
