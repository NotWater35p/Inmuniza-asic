<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Personal;

class AuthController extends Controller
{

    // Mostrar el formulario de login 
    public function showLogin()
    {
        return view('Auth.login');
    }

    // Procesar el login
    public function login(Request $request)
    {
        $request->validate([
            'cedula'   => 'required|string',
            'password' => 'required|string',
        ]);

        $personal = Personal::where('cedula', $request->cedula)->first();

        if (!$personal) {
            return back()->withErrors(['cedula' => 'La cédula no está registrada.']);
        }

        $user = $personal->user;

        if (!$user) {
            return back()->withErrors(['cedula' => 'Este empleado no tiene credenciales de acceso.']);
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Contraseña incorrecta.']);
        }

        Auth::login($user, $request->has('remember'));

        // Redirigir según nivel de acceso
        if ($user->esJefeModulo()) {
            return redirect()->route('modulo.dashboard');
        }

        return redirect()->route('inicio');
    }
    // Cerrar sesión
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
