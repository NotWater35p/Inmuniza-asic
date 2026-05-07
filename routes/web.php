<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

// RUTAS DE AUTENTICACIÓN...........................................................................................................................
Route::get('/', [AuthController::class, 'showLogin'])->name('login');

// Procesar login (POST)
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Cerrar sesión
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/inicio', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('inicio');

Route::get('/modulo/{modulo}/reporte', function (Modulo $modulo) {
    return redirect()->route('sispai.index', [
        'modulo_id' => $modulo->id,
        'mes'       => now()->month,
        'anio'      => now()->year,
    ]);
})->name('modulo.reporte.index');
