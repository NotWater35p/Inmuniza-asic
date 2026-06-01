<?php

namespace App\Http\Controllers;

use App\Models\Tratamiento;
use App\Models\Paciente;
use App\Models\Vacuna;
use App\Models\Jornada;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\TratamientoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Carbon\Carbon;

class TratamientoController extends Controller
{
    public function index(Request $request): View
    {
        $user   = auth()->user();
        $modulo = $user->esJefeModulo() ? $user->modulo() : null;

        $query = Tratamiento::with(['jornada.modulo', 'paciente', 'vacuna', 'jornada.responsable']);

        if ($modulo) {
            $query->whereHas('jornada', fn($q) => $q->where('modulo_id', $modulo->id));
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('paciente_cedula', 'like', "%$s%")
                    ->orWhereHas(
                        'paciente',
                        fn($q) =>
                        $q->where('nombres',   'like', "%$s%")
                            ->orWhere('apellidos', 'like', "%$s%")
                    )
                    ->orWhereHas('vacuna', fn($q) => $q->where('nombre', 'like', "%$s%"));
            });
        }

        if ($request->filled('vacuna_id')) {
            $query->where('vacuna_id', $request->vacuna_id);
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_aplicacion', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_aplicacion', '<=', $request->fecha_hasta);
        }

        $tratamientos = $query->orderBy('fecha_aplicacion', 'desc')
            ->paginate(15)
            ->withQueryString();

        $vacunas = Vacuna::orderBy('nombre')->get();

        return view('tratamiento.index', compact('tratamientos', 'vacunas'))
            ->with('i', ($request->input('page', 1) - 1) * $tratamientos->perPage());
    }

    public function create(Request $request): View
    {
        $tratamiento = new Tratamiento();
        $vacunas     = Vacuna::orderBy('nombre')->get();
        $jornadas    = Jornada::with('responsable')
            ->orderBy('fecha_jornada', 'desc')
            ->limit(30)
            ->get();

        // Precarga de paciente si viene de ?cedula=
        $pacientePreload = null;
        if ($request->filled('cedula')) {
            $pacientePreload = Paciente::where('cedula', $request->cedula)->first();
        }

        // Precarga de jornada si viene de una jornada específica
        $jornadaPreload = null;
        if ($request->filled('jornada_id')) {
            $jornadaPreload = Jornada::find($request->jornada_id);
        }

        return view('tratamiento.create', compact(
            'tratamiento',
            'vacunas',
            'jornadas',
            'pacientePreload',
            'jornadaPreload'
        ));
    }

    public function store(TratamientoRequest $request): RedirectResponse
    {
        Tratamiento::create($request->validated());

        return Redirect::route('tratamientos.index')
            ->with('success', 'Tratamiento registrado exitosamente.');
    }

    /**
     * Ficha médica completa del tratamiento individual.
     */
    public function show($id): View
    {
        $tratamiento = Tratamiento::with([
            'paciente.etnia',
            'paciente.sector',
            'vacuna.marca',
            'jornada.responsable.cargo',
        ])->findOrFail($id);

        // Historial completo de vacunación de este paciente
        $historial = Tratamiento::with(['vacuna', 'jornada'])
            ->where('paciente_id', $tratamiento->paciente_id)
            ->orderBy('fecha_aplicacion', 'desc')
            ->get()
            ->groupBy('vacuna_id');

        // Próxima dosis calculada para este tratamiento
        $proximaDosis = $tratamiento->fechaProximaDosis();

        return view('tratamiento.show', compact('tratamiento', 'historial', 'proximaDosis'));
    }

    /**
     * Vista de historial completo de un paciente específico.
     */
    public function historialPaciente($id): View
    {
        $paciente = Paciente::with(['etnia', 'sector'])->findOrFail($id);

        $historial = Tratamiento::with(['vacuna', 'jornada.responsable'])
            ->where('paciente_id', $paciente->id)
            ->orderBy('fecha_aplicacion', 'desc')
            ->get()
            ->groupBy('vacuna_id');

        return view('tratamiento.historial', compact('paciente', 'historial'));
    }

    public function edit($id): View
    {
        $tratamiento = Tratamiento::findOrFail($id);
        $vacunas     = Vacuna::orderBy('nombre')->get();
        $jornadas    = Jornada::with('responsable')
            ->orderBy('fecha_jornada', 'desc')
            ->limit(30)
            ->get();

        return view('tratamiento.edit', compact('tratamiento', 'vacunas', 'jornadas'));
    }

    public function update(TratamientoRequest $request, Tratamiento $tratamiento): RedirectResponse
    {
        $tratamiento->update($request->validated());

        return Redirect::route('tratamientos.show', $tratamiento->id)
            ->with('success', 'Tratamiento actualizado exitosamente.');
    }

    public function destroy($id): RedirectResponse
    {
        Tratamiento::findOrFail($id)->delete();

        return Redirect::route('tratamientos.index')
            ->with('success', 'Tratamiento eliminado exitosamente.');
    }

    /**
     * AJAX: Devuelve las dosis que ya tiene un paciente para una vacuna específica.
     */
    public function dosisAplicadas(Request $request)
    {
        $pacienteId = $request->paciente_id; // ← antes era cedula
        $vacunaId   = $request->vacuna_id;

        if (!$pacienteId || !$vacunaId) {
            return response()->json(['dosis_siguiente' => 1, 'historial' => []]);
        }

        $tratamientos = Tratamiento::where('paciente_id', $pacienteId)
            ->where('vacuna_id', $vacunaId)
            ->orderBy('dosis_aplicada')
            ->get(['dosis_aplicada', 'fecha_aplicacion']);

        $dosisMax  = $tratamientos->max('dosis_aplicada') ?? 0;
        $vacuna    = Vacuna::find($vacunaId);
        $numDosis  = $vacuna?->numero_dosis ?? 99;
        $dosisSig  = min($dosisMax + 1, $numDosis);

        return response()->json([
            'dosis_siguiente'  => $dosisSig,
            'total_recibidas'  => $tratamientos->count(),
            'num_dosis_vacuna' => $numDosis,
            'completado'       => $dosisMax >= $numDosis,
            'historial'        => $tratamientos->map(fn($t) => [
                'dosis' => $t->dosis_aplicada,
                'fecha' => \Carbon\Carbon::parse($t->fecha_aplicacion)->format('d/m/Y'),
            ]),
        ]);
    }

    /**
     * AJAX: Calcula la próxima fecha de dosis basada en intervalo de la vacuna.
     */
    public function calcularProximaFecha(Request $request)
    {
        $vacunaId       = $request->vacuna_id;
        $fechaAplicacion = $request->fecha_aplicacion;
        $dosisAplicada   = (int) $request->dosis_aplicada;

        if (!$vacunaId || !$fechaAplicacion) {
            return response()->json(['proxima_fecha' => null]);
        }

        $vacuna = Vacuna::find($vacunaId);
        if (!$vacuna) return response()->json(['proxima_fecha' => null]);

        $t = new Tratamiento([
            'vacuna_id'       => $vacunaId,
            'fecha_aplicacion' => $fechaAplicacion,
            'dosis_aplicada'   => $dosisAplicada,
        ]);
        $t->setRelation('vacuna', $vacuna);

        $fecha = $t->fechaProximaDosis();

        return response()->json([
            'proxima_fecha' => $fecha?->format('Y-m-d'),
            'proxima_fecha_legible' => $fecha?->locale('es')->isoFormat('D [de] MMMM, YYYY'),
            'intervalo_texto' => ($dosisAplicada >= $vacuna->numero_dosis ? $vacuna->refuerzo : $vacuna->intervalo) ?? null,
        ]);
    }
}