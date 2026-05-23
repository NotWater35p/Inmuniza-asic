<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Models\Perdida;
use App\Models\Vacuna;
use App\Models\Tratamiento;
use App\Models\Despacho;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModuloPerdidaController extends Controller
{
    /**
     * Lista las pérdidas de un módulo específico.
     */
    public function index(Modulo $modulo)
    {
        $this->autorizarAcceso($modulo);

        $perdidas = Perdida::with('vacuna')
            ->where('modulo_id', $modulo->id)
            ->orderByDesc('fecha')
            ->paginate(15)
            ->withQueryString();

        $vacunas = Vacuna::orderBy('nombre')->get(); // ← agregar esta línea

        return view('modulo.perdidas', compact('modulo', 'perdidas', 'vacunas')); // ← agregar $vacunas
    }

    /**
     * Registra una nueva pérdida para el módulo.
     */
    public function store(Request $request, Modulo $modulo)
    {
        $this->autorizarAcceso($modulo);

        $validated = $request->validate([
            'vacuna_id'   => 'required|exists:vacuna,id',
            'lote'        => 'nullable|string|max:50',
            'cantidad'    => 'required|integer|min:1',
            'motivo'      => ['required', \Illuminate\Validation\Rule::in(Perdida::MOTIVOS)],
            'observacion' => 'nullable|string|max:500',
            'fecha'       => 'required|date|before_or_equal:today',
        ], [
            'vacuna_id.required'  => 'Debe seleccionar una vacuna.',
            'vacuna_id.exists'    => 'La vacuna seleccionada no existe.',
            'cantidad.required'   => 'La cantidad es obligatoria.',
            'cantidad.min'        => 'La cantidad debe ser al menos 1.',
            'motivo.required'     => 'Debe seleccionar un motivo.',
            'motivo.in'           => 'El motivo seleccionado no es válido.',
            'fecha.required'      => 'La fecha es obligatoria.',
            'fecha.before_or_equal' => 'La fecha no puede ser futura.',
        ]);

        // Verificar que el módulo tiene stock suficiente
        $stock = $modulo->stockVacuna($validated['vacuna_id']);
        if ($validated['cantidad'] > $stock) {
            return back()
                ->withInput()
                ->withErrors(['cantidad' => "Stock insuficiente. Disponible: {$stock} dosis."]);
        }

        Perdida::create([
            ...$validated,
            'modulo_id' => $modulo->id,
        ]);

        return back()->with('success', 'Pérdida registrada correctamente.');
    }

    /**
     * Elimina una pérdida del módulo.
     * Permitido para jefe de módulo (su propio módulo) y admin/asistente.
     */
    public function destroy(Modulo $modulo, Perdida $perdida)
    {
        $this->autorizarAcceso($modulo);

        // Verificar que la pérdida pertenece a este módulo
        abort_if($perdida->modulo_id !== $modulo->id, 403);

        $perdida->delete();

        return back()->with('success', 'Pérdida eliminada correctamente.');
    }

    /**
     * Solo el jefe del módulo o admin/asistente pueden acceder.
     */
    private function autorizarAcceso(Modulo $modulo): void
    {
        $user = Auth::user();

        if ($user->esJefeModulo()) {
            $miModulo = $user->modulo();
            abort_if(!$miModulo || $miModulo->id !== $modulo->id, 403);
        }
    }
    /**
 * AJAX: lotes de una vacuna con stock disponible en el módulo.
 */
public function lotesDisponibles(Modulo $modulo, Vacuna $vacuna)
{
    $this->autorizarAcceso($modulo);

    $lotes = DB::table('carga')
        ->select('lote', DB::raw('SUM(cantidad) as entrado'), DB::raw('MIN(fecha_vencimiento) as fecha_vencimiento'))
        ->where('vacuna_id', $vacuna->id)
        ->whereNotNull('lote')
        ->groupBy('lote')
        ->get()
        ->map(function ($lote) use ($modulo, $vacuna) {
            $lote->despachado = Despacho::where('vacuna_id', $vacuna->id)
                ->where('modulo_id', $modulo->id)
                ->where('lote', $lote->lote)
                ->sum('cantidad');
            $lote->usado = Tratamiento::whereHas('jornada', fn($q) => $q->where('modulo_id', $modulo->id))
                ->where('vacuna_id', $vacuna->id)
                ->sum('dosis_aplicada');
            $lote->perdido = Perdida::where('vacuna_id', $vacuna->id)
                ->where('modulo_id', $modulo->id)
                ->where('lote', $lote->lote)
                ->sum('cantidad');
            $lote->disponible = max(0, $lote->despachado - $lote->usado - $lote->perdido);
            return $lote;
        })
        ->filter(fn($l) => $l->disponible > 0)
        ->values();

    return response()->json(['lotes' => $lotes]);
}
}