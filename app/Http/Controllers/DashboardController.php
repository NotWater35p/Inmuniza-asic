<?php

namespace App\Http\Controllers;

use App\Models\Asic;
use App\Models\Carga;
use App\Models\Despacho;
use App\Models\Paciente;
use App\Models\Vacuna;
use App\Models\Personal;
use App\Models\Modulo;
use App\Models\Tratamiento;
use App\Models\Jornada;
use App\Models\Perdida;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        // Jefe de Módulo → su propio dashboard
        if ($user->esJefeModulo()) {
            return $this->dashboardModulo($user);
        }

        // Admin / Asistente → dashboard completo existente
        return $this->dashboardAdmin();
    }

    private function dashboardAdmin(): View
    {
        $asic = Asic::with([
            'modulos',
            'personal.cargo',
            'cargas.vacuna',
            'despachos',
        ])->first();

        $stats = [
            'total_modulos'     => $asic?->modulos->count()            ?? 0,
            'total_personal'    => $asic?->personal->count()           ?? 0,
            'total_cargas'      => $asic?->cargas->count()             ?? 0,
            'total_despachos'   => $asic?->despachos->count()          ?? 0,
            'dosis_recibidas'   => $asic?->cargas->sum('cantidad')     ?? 0,
            'dosis_despachadas' => $asic?->despachos->sum('cantidad')  ?? 0,
        ];

        $totalPacientes   = Paciente::count();
        $pacientesActivos = Paciente::where('activo', true)->count();
        $inventario       = $asic ? $asic->inventario() : collect();
        $totalDosisDisponibles = $inventario->sum(fn($v) => max(0, (int) $v->stock));

        $proxVencer = Carga::with('vacuna')
            ->when($asic, fn($q) => $q->where('asic_id', $asic->id))
            ->whereDate('fecha_vencimiento', '>=', Carbon::today())
            ->whereDate('fecha_vencimiento', '<=', Carbon::today()->addDays(30))
            ->orderBy('fecha_vencimiento')->get();

        $ultimasCargas    = Carga::with('vacuna')->when($asic, fn($q) => $q->where('asic_id', $asic->id))->orderBy('fecha_llegada', 'desc')->limit(5)->get();
        $ultimosDespachos = Despacho::with(['vacuna', 'modulo'])->when($asic, fn($q) => $q->where('asic_id', $asic->id))->orderBy('fecha_envio', 'desc')->limit(5)->get();
        $ultimosPacientes = Paciente::orderBy('created_at', 'desc')->limit(5)->get();

        return view('inicio', compact(
            'asic',
            'stats',
            'inventario',
            'totalPacientes',
            'pacientesActivos',
            'totalDosisDisponibles',
            'proxVencer',
            'ultimasCargas',
            'ultimosDespachos',
            'ultimosPacientes'
        ));
    }

    private function dashboardModulo($user): View
    {
        $modulo = $user->modulo();

        if (!$modulo) {
            return view('modulo.dashboard', ['modulo' => null, 'sinModulo' => true]);
        }

        // Inventario del módulo: lo que le despacharon - lo que usó en tratamientos
        $inventario = Vacuna::with('marca')
            ->get()
            ->map(function ($vacuna) use ($modulo) {
                $despachado = Despacho::where('modulo_id', $modulo->id)
                    ->where('vacuna_id', $vacuna->id)
                    ->sum('cantidad');

                $usado = Tratamiento::whereHas('jornada', fn($q) => $q->where('modulo_id', $modulo->id))
                    ->where('vacuna_id', $vacuna->id)
                    ->sum('dosis_aplicada');

                $perdido = Perdida::where('modulo_id', $modulo->id)
                    ->where('vacuna_id', $vacuna->id)
                    ->sum('cantidad');

                $vacuna->despachado  = $despachado;
                $vacuna->usado       = $usado;
                $vacuna->perdido     = $perdido;
                $vacuna->disponible  = max(0, $despachado - $usado - $perdido);
                return $vacuna;
            })
            ->filter(fn($v) => $v->despachado > 0); // solo vacunas que le han despachado

        // Últimas jornadas del módulo
        $ultimasJornadas = Jornada::with(['responsable', 'tratamientos'])
            ->where('modulo_id', $modulo->id)
            ->orderBy('fecha_jornada', 'desc')
            ->limit(5)
            ->get();

        // Stats del módulo
        $stats = [
            'total_jornadas'    => Jornada::where('modulo_id', $modulo->id)->count(),
            'dosis_recibidas'   => Despacho::where('modulo_id', $modulo->id)->sum('cantidad'),
            'dosis_aplicadas'   => Tratamiento::whereHas('jornada', fn($q) => $q->where('modulo_id', $modulo->id))->sum('dosis_aplicada'),
            'total_pacientes' => Tratamiento::whereHas('jornada', fn($q) =>
            $q->where('modulo_id', $modulo->id))
                ->distinct('paciente_id')->count(),
        ];

        return view('modulo.dashboard', compact('modulo', 'inventario', 'ultimasJornadas', 'stats'));
    }
}