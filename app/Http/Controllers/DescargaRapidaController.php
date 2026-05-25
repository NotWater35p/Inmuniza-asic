<?php

namespace App\Http\Controllers;

use App\Models\Jornada;
use App\Models\Tratamiento;
use App\Models\Vacuna;
use App\Models\Modulo;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Carbon\Carbon;

class DescargaRapidaController extends Controller
{
    public function create(Request $request): View
    {
        $user   = auth()->user();
        $modulo = $user->esJefeModulo() ? $user->modulo() : null;

        // Vacunas disponibles para este módulo (que le hayan despachado)
        if ($modulo) {
            $vacunas = Vacuna::whereHas('despachos', fn($q) =>
                $q->where('modulo_id', $modulo->id)
            )->orderBy('nombre')->get();
        } else {
            $vacunas = Vacuna::orderBy('nombre')->get();
            $modulos = Modulo::orderBy('nombre')->get();
        }

        // Jornadas recientes del módulo para asociar el descargo
        $jornadas = Jornada::when($modulo, fn($q) => $q->where('modulo_id', $modulo->id))
            ->orderBy('fecha_jornada', 'desc')
            ->limit(10)
            ->get();

        return view('descargo.create', compact(
            'vacunas', 'jornadas', 'modulo',
            ...($modulo ? [] : ['modulos'])
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vacuna_id'        => 'required|exists:vacuna,id',
            'jornada_id'       => 'nullable|exists:jornada,id',
            'cantidad'         => 'required|integer|min:1',
            'fecha_aplicacion' => 'required|date|before_or_equal:today',
            'subtipo_paciente' => 'nullable|in:general,personal_salud,dialisis,privado_libertad,trabajador_sexual,embarazada',
            'observaciones'    => 'nullable|string|max:500',
        ]);

        // Un solo registro con la cantidad total de dosis
        Tratamiento::create([
            'jornada_id'         => $validated['jornada_id'] ?? null,
            'paciente_id'        => null,   // nullable — descargo sin paciente
            'vacuna_id'          => $validated['vacuna_id'],
            'dosis_aplicada'     => $validated['cantidad'],
            'es_descargo_rapido' => true,
            'subtipo_paciente'   => $validated['subtipo_paciente'] ?? 'general',
            'fecha_aplicacion'   => $validated['fecha_aplicacion'],
            'observaciones'      => $validated['observaciones'] ?? null,
        ]);

        return redirect()->route('descargo.create')
            ->with('success', "Descargo registrado: {$validated['cantidad']} dosis de " .
                Vacuna::find($validated['vacuna_id'])->nombre . ".");
    }

    public function storeBulk(Request $request): RedirectResponse
    {
        $request->validate([
            'descargas'                    => 'required|array|min:1',
            'descargas.*.vacuna_id'        => 'required|exists:vacuna,id',
            'descargas.*.cantidad'         => 'required|integer|min:1',
            'descargas.*.fecha_aplicacion' => 'required|date|before_or_equal:today',
            'descargas.*.subtipo_paciente' => 'nullable|in:general,personal_salud,dialisis,privado_libertad,trabajador_sexual,embarazada',
            'descargas.*.observaciones'    => 'nullable|string|max:500',
        ]);

        $jornadaId = $request->input('jornada_id');
        $total = 0;

        foreach ($request->descargas as $item) {
            Tratamiento::create([
                'jornada_id'         => $jornadaId,
                'paciente_id'        => null,   // nullable — descargo sin paciente
                'vacuna_id'          => $item['vacuna_id'],
                'dosis_aplicada'     => (int) $item['cantidad'],
                'es_descargo_rapido' => true,
                'subtipo_paciente'   => $item['subtipo_paciente'] ?? 'general',
                'fecha_aplicacion'   => $item['fecha_aplicacion'],
                'observaciones'      => $item['observaciones'] ?? null,
            ]);
            $total += (int) $item['cantidad'];
        }

        return redirect()->route('descargo.create')
            ->with('success', "Descargo múltiple registrado: {$total} dosis en total.");
    }
}