<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NivelAcceso
{
    public function handle(Request $request, Closure $next, int ...$niveles): mixed
    {
        $user = $request->user();

        if (!$user || !in_array($user->nivel_acceso, $niveles)) {
            // Si es Jefe de Módulo intentando acceder a rutas de admin
            if ($user?->esJefeModulo()) {
                return redirect()->route('modulo.dashboard')
                    ->with('error', 'No tienes permiso para acceder a esa sección.');
            }
            abort(403, 'Acceso no autorizado.');
        }

        return $next($request);
    }
}