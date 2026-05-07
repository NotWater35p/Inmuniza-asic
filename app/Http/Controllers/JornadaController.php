<?php

namespace App\Http\Controllers;

use App\Models\Jornada;
use App\Models\Asic;
use App\Models\Modulo;
use App\Models\Personal;
use App\Http\Requests\JornadaRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class JornadaController extends Controller
{
    public function index(Request $request): View
    {
        $user   = auth()->user();
        $modulo = $user->esJefeModulo() ? $user->modulo() : null;

        $query = Jornada::with(['responsable.cargo', 'modulo'])
            ->withCount('tratamientos');

        // Jefe de módulo solo ve sus jornadas
        if ($modulo) {
            $query->where('modulo_id', $modulo->id);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('descripcion', 'like', "%$s%")
                    ->orWhereHas(
                        'responsable',
                        fn($q) =>
                        $q->where('nombre', 'like', "%$s%")
                            ->orWhere('apellido', 'like', "%$s%")
                    );
            });
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_jornada', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_jornada', '<=', $request->fecha_hasta);
        }

        $sort      = in_array($request->sort, ['fecha_jornada', 'created_at']) ? $request->sort : 'fecha_jornada';
        $direction = $request->direction === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sort, $direction);

        $jornadas = $query->paginate(12)->withQueryString();
        $personal = Personal::with('cargo')->orderBy('apellido')->get();

        return view('jornada.index', compact('jornadas', 'personal', 'sort', 'direction'))
            ->with('i', ($request->input('page', 1) - 1) * $jornadas->perPage());
    }

    public function create(): View
    {
        $jornada  = new Jornada();
        $asic     = Asic::first();
        $personal = Personal::with('cargo')->orderBy('apellido')->get();
        $modulos  = Modulo::orderBy('nombre')->get();  // ← agregado

        return view('jornada.create', compact('jornada', 'asic', 'personal', 'modulos'));
    }

    public function store(JornadaRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Asignar ASIC automáticamente si no viene (siempre hay uno solo)
        if (empty($data['asic_id'])) {
            $data['asic_id'] = Asic::first()->id;
        }

        // Si es Jefe de Módulo, asignar su módulo automáticamente
        $user = auth()->user();
        if ($user->esJefeModulo()) {
            $modulo = $user->modulo();
            $data['modulo_id'] = $modulo?->id;
        }

        Jornada::create($data);

        return Redirect::route('jornadas.index')
            ->with('success', 'Jornada registrada exitosamente.');
    }

    public function show($id): View
    {
        $jornada = Jornada::with([
            'responsable.cargo',
            'asic',
            'modulo',
            'tratamientos.paciente',
            'tratamientos.vacuna',
        ])->findOrFail($id);

        return view('jornada.show', compact('jornada'));
    }

    public function edit($id): View
    {
        $jornada  = Jornada::findOrFail($id);
        $asic     = Asic::first();          // ← agregado
        $personal = Personal::with('cargo')->orderBy('apellido')->get();
        $modulos  = Modulo::orderBy('nombre')->get();  // ← agregado

        return view('jornada.edit', compact('jornada', 'asic', 'personal', 'modulos'));
    }

    public function update(JornadaRequest $request, Jornada $jornada): RedirectResponse
    {
        $data = $request->validated();

        // El jefe no puede cambiar el módulo
        $user = auth()->user();
        if ($user->esJefeModulo()) {
            unset($data['modulo_id']);
        }

        $jornada->update($data);

        return Redirect::route('jornadas.show', $jornada->id)
            ->with('success', 'Jornada actualizada exitosamente.');
    }

    public function destroy($id): RedirectResponse
    {
        $jornada = Jornada::withCount('tratamientos')->findOrFail($id);

        if ($jornada->tratamientos_count > 0) {
            return Redirect::route('jornadas.index')
                ->with('error', 'No se puede eliminar: tiene ' . $jornada->tratamientos_count . ' tratamiento(s) registrado(s).');
        }

        $jornada->delete();

        return Redirect::route('jornadas.index')
            ->with('success', 'Jornada eliminada exitosamente.');
    }
}
