<?php

use App\Http\Controllers\AsicController;
use App\Http\Controllers\CargaController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DescargaRapidaController;
use App\Http\Controllers\DespachoController;
use App\Http\Controllers\EtniaController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\JornadaController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ModuloController;
use App\Http\Controllers\ModuloInventarioController;
use App\Http\Controllers\ModuloPerdidaController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\PerdidaController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\ReporteModuloController;
use App\Http\Controllers\RepresentanteController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\SispaIController;
use App\Http\Controllers\TratamientoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VacunaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas Protegidas (autenticación + no-cache)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'no.cache'])->group(function () {

    // ================================================================
    // 1. DASHBOARD Y VISTAS GENERALES (todos los niveles autenticados)
    // ================================================================
    Route::get('/inicio', [DashboardController::class, 'index'])->name('inicio');
    Route::get('/info', fn() => view('info'))->name('info');
    Route::get('modulo/dashboard', [DashboardController::class, 'index'])->name('modulo.dashboard');

    // ================================================================
    // 2. RUTAS PARA ADMINISTRADOR (nivel 5) SOLO
    // ================================================================
    Route::middleware('nivel.acceso:5')->group(function () {
        // Gestión de usuarios del sistema
        Route::get('users/personal/buscar', [UserController::class, 'buscarPersonal'])->name('users.personal.buscar');
        Route::resource('users', UserController::class);
    });

    // ================================================================
    // 3. RUTAS PARA NIVELES 3 y 5 (admin y asistente)
    // ================================================================
    Route::middleware('nivel.acceso:3,5')->group(function () {

        // ----- ASIC (principal) -----
        Route::resource('asic', AsicController::class);

        // ----- Cargas (inventario que llega al ASIC) -----
        Route::get('cargas/reporte/general', [CargaController::class, 'reporteGeneral'])->name('cargas.reporte.general');
        Route::get('cargas/reporte/{id}/individual', [CargaController::class, 'reporteIndividual'])->name('cargas.reporte.individual');
        Route::get('cargas/{id}/clonar', [CargaController::class, 'clone'])->name('cargas.clone');
        Route::post('cargas/bulk', [CargaController::class, 'storeBulk'])->name('cargas.store.bulk');
        Route::resource('cargas', CargaController::class);

        // ----- Cargos (roles) -----
        Route::resource('cargos', CargoController::class);

        // ----- Despachos (envíos a módulos) -----
        Route::get('despachos/stock/check', [DespachoController::class, 'checkStock'])->name('despachos.stock.check');
        Route::get('despachos/reporte/modulo/{id}', [DespachoController::class, 'reporteModulo'])->name('despachos.reporte.modulo');
        Route::get('despachos/reporte/vacuna', [DespachoController::class, 'reporteVacuna'])->name('despachos.reporte.vacuna');
        Route::get('despachos/reporte/periodo', [DespachoController::class, 'reportePeriodo'])->name('despachos.reporte.periodo');
        Route::post('despachos/bulk', [DespachoController::class, 'storeBulk'])->name('despachos.store.bulk');
        Route::resource('despachos', DespachoController::class);

        // ----- Inventario central (ASIC) -----
        Route::get('inventario', [InventarioController::class, 'index'])->name('inventario.index');
        Route::get('inventario/lotes/{vacunaId}', [InventarioController::class, 'lotes'])->name('inventario.lotes');
        Route::post('inventario/perdida', [InventarioController::class, 'storePerdida'])->name('inventario.storePerdida');
        
        // ----- Marcas (fabricantes) -----
        Route::get('marcas/pdf/universal', [MarcaController::class, 'generarPDFUniversal'])->name('marcas.pdf.universal');
        Route::get('marcas/{id}/pdf', [MarcaController::class, 'generarPDF'])->name('marcas.pdf');
        Route::post('marcas', [MarcaController::class, 'store'])->name('marcas.store');
        Route::resource('marcas', MarcaController::class);

        // ----- Módulos (centros afiliados) -----
        Route::get('modulos/pdf/universal', [ModuloController::class, 'generarPDFUniversal'])->name('modulos.pdf.universal');
        Route::get('modulos/{modulo}/pdf', [ModuloController::class, 'generarPDF'])->name('modulos.pdf');
        Route::resource('modulos', ModuloController::class);

        // ----- Personal (empleados) -----
        Route::get('personal/{personal}/pdf', [PersonalController::class, 'generarPDF'])->name('personal.pdf');
        Route::resource('personal', PersonalController::class);

        // ----- Vacunas -----
    Route::resource('vacunas', VacunaController::class)->except(['show', 'index']);
    Route::get('vacunas/{vacuna}/pdf', [VacunaController::class, 'generarPDF'])->name('vacunas.pdf');
    Route::post('vacunas/marca', [VacunaController::class, 'storeMarca'])->name('vacunas.marca.store');
        // Route::resource('vacunas', VacunaController::class);
    });

    // ================================================================
    // 4. RUTAS ACCESIBLES POR TODOS LOS NIVELES AUTENTICADOS
    //    (admin, asistente, jefe de módulo)
    // ================================================================

    // ----- APi / AJAX -----
    Route::get('api/paciente-buscar', function (\Illuminate\Http\Request $request) {
        $q = $request->search ?? $request->cedula;
        $pacientes = \App\Models\Paciente::with(['sector'])
            ->where(function($query) use ($q) {
                $query->where('cedula',   'like', "%$q%")
                      ->orWhere('nombres',   'like', "%$q%")
                      ->orWhere('apellidos', 'like', "%$q%");
            })
            ->limit(8)
            ->get();

        return response()->json($pacientes->map(function($p) {
            return [
                'id'        => $p->id,
                'cedula'    => $p->cedula,
                'nombres'   => $p->nombres,
                'apellidos' => $p->apellidos,
                'edad'      => $p->fecha_nacimiento
                    ? \Carbon\Carbon::parse($p->fecha_nacimiento)->age
                    : null,
                'sexo'      => $p->sexo,
                'activo'    => $p->activo,
                'sector'    => $p->sector?->nombre,
            ];
        }));
    })->middleware('auth')->name('paciente.buscar.ajax');

    // ----- Descargo rápido (salidas de inventario) -----
    Route::get('vacunas', [VacunaController::class, 'index'])->name('vacunas.index');
    Route::get('vacunas/{vacuna}', [VacunaController::class, 'show'])->name('vacunas.show');
    Route::get('descargo', [DescargaRapidaController::class, 'create'])->name('descargo.create');
    Route::post('descargo', [DescargaRapidaController::class, 'store'])->name('descargo.store');
    Route::post('descargo/bulk', [DescargaRapidaController::class, 'storeBulk'])->name('descargo.bulk');

    // ----- Etnias (creación rápida vía modal AJAX) -----
    Route::post('etnias', [EtniaController::class, 'store'])->name('etnias.store');

    // ----- Jornadas -----
    Route::resource('jornadas', JornadaController::class);

    // ----- Módulo: inventario y reportes (jefe de módulo) -----
    Route::get('modulo/{modulo}/lotes/{vacuna}', [ModuloPerdidaController::class, 'lotesDisponibles'])->name('modulo.lotes.vacuna');
    Route::get('modulo/{modulo}/inventario', [ModuloInventarioController::class, 'show'])->name('modulo.inventario');
    Route::get('modulo/{modulo}/reporte', [ReporteModuloController::class, 'index'])->name('modulo.reporte.index');
    Route::get('modulo/{modulo}/reporte/pdf', [ReporteModuloController::class, 'pdf'])->name('modulo.reporte.pdf');
    Route::get('modulo/{modulo}/reporte/excel', [ReporteModuloController::class, 'excel'])->name('modulo.reporte.excel');

    // ----- Módulo: pérdidas -----
    Route::resource('perdida', PerdidaController::class);
    Route::get('modulo/{modulo}/perdidas', [ModuloPerdidaController::class, 'index'])->name('modulo.perdidas.index');
    Route::post('modulo/{modulo}/perdidas', [ModuloPerdidaController::class, 'store'])->name('modulo.perdidas.store');

    // ----- Pacientes (CRUD completo) -----
    Route::get('pacientes/pdf/listado', [PacienteController::class, 'generarPDFListado'])->name('pacientes.pdf.listado');
    Route::get('pacientes/{id}/pdf', [PacienteController::class, 'generarPDF'])->name('pacientes.pdf');
    Route::resource('pacientes', PacienteController::class);

    // ----- Representantes (búsqueda y CRUD) -----
    Route::get('representantes/buscar', function (\Illuminate\Http\Request $request) {
        $rep = \App\Models\Representante::where('cedula', $request->cedula)->first();
        if (!$rep) return response()->json(['found' => false]);
        return response()->json([
            'found'    => true,
            'cedula'   => $rep->cedula,
            'telefono' => $rep->telefono,
            'relacion' => $rep->relacion,
        ]);
    })->name('representantes.buscar');
    Route::resource('representantes', RepresentanteController::class);

    // ----- Sectores (creación rápida vía modal AJAX) -----
    Route::post('sectores', [SectorController::class, 'store'])->name('sectores.store');

    // ----- SISPAI (reporte especial) -----
    Route::get('/sispai', [SispaIController::class, 'index'])->name('sispai.index');
    Route::get('/sispai/excel', [SispaIController::class, 'excel'])->name('sispai.excel');
    Route::get('/sispai/pdf', [SispaIController::class, 'pdf'])->name('sispai.pdf');

    // ----- Tratamientos (vacunaciones) -----
    Route::get('tratamientos/dosis-aplicadas', [TratamientoController::class, 'dosisAplicadas'])->name('tratamientos.dosis');
    Route::get('tratamientos/historial/{cedula}', [TratamientoController::class, 'historialPaciente'])->name('tratamientos.historial');
    Route::get('tratamientos/proxima-fecha', [TratamientoController::class, 'calcularProximaFecha'])->name('tratamientos.proxima-fecha');
    Route::get('pacientes/{paciente}/historial', [TratamientoController::class, 'historialPaciente'])->name('tratamientos.historial.paciente');
    Route::resource('tratamientos', TratamientoController::class);
});