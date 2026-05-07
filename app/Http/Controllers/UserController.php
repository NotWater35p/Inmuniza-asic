<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Personal;
use App\Models\Asic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    // -------------------------------------------------------
    // INDEX
    // -------------------------------------------------------
    public function index(Request $request): View
    {
        $query = User::with(['personal.cargo']);

        // Filtro búsqueda
        if ($request->filled('buscar')) {
            $q = $request->buscar;
            $query->where(function($qb) use ($q) {
                $qb->where('name', 'like', "%$q%")
                   ->orWhere('email', 'like', "%$q%")
                   ->orWhereHas('personal', fn($p) =>
                       $p->where('nombre', 'like', "%$q%")
                         ->orWhere('apellido', 'like', "%$q%")
                         ->orWhere('cedula', $q)
                   );
            });
        }

        // Filtro por nivel
        if ($request->filled('nivel')) {
            $query->whereHas('personal.cargo', fn($q) =>
                $q->where('nivel_acceso', $request->nivel)
            );
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        // Personal sin cuenta (para el panel de pendientes)
        $personalSinCuenta = Personal::with('cargo')
            ->whereDoesntHave('user')
            ->where('asic_id', Asic::first()->id)
            ->orderBy('nombre')
            ->get();

        return view('user.index', compact('users', 'personalSinCuenta'))
            ->with('i', ($request->input('page', 1) - 1) * $users->perPage());
    }

    // -------------------------------------------------------
    // CREATE
    // -------------------------------------------------------
    public function create(Request $request): View
    {
        $user     = new User();
        $asic     = Asic::first();

        // Personal sin cuenta — puede venir preseleccionado desde el index
        $personalSinCuenta = Personal::with('cargo')
            ->whereDoesntHave('user')
            ->where('asic_id', $asic->id)
            ->orderBy('nombre')
            ->get();

        // Si viene con cedula preseleccionada desde el index
        $personalPresel = $request->filled('cedula')
            ? Personal::with('cargo')->find($request->cedula)
            : null;

        return view('user.create', compact('user', 'personalSinCuenta', 'personalPresel'));
    }

    // -------------------------------------------------------
    // STORE
    // -------------------------------------------------------
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'personal_cedula' => [
                'required',
                'integer',
                'exists:personal,cedula',
                'unique:users,personal_cedula',
            ],
            'email'    => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(4)],
        ], [
            'personal_cedula.unique' => 'Este personal ya tiene un usuario asignado.',
            'personal_cedula.exists' => 'El personal seleccionado no existe.',
            'email.unique'           => 'Este correo ya está registrado.',
            'password.confirmed'     => 'Las contraseñas no coinciden.',
            'password.min'           => 'La contraseña debe tener al menos 4 caracteres.',
        ]);

        $personal = Personal::find($request->personal_cedula);

        User::create([
            'personal_cedula' => $request->personal_cedula,
            'name'            => $personal->nombre . ' ' . $personal->apellido,
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
        ]);

        return Redirect::route('users.index')
            ->with('success', 'Usuario creado exitosamente para ' . $personal->nombre . ' ' . $personal->apellido . '.');
    }

    // -------------------------------------------------------
    // SHOW
    // -------------------------------------------------------
    public function show($id): View
    {
        $user = User::with(['personal.cargo', 'personal.asic'])->findOrFail($id);
        return view('user.show', compact('user'));
    }

    // -------------------------------------------------------
    // EDIT
    // -------------------------------------------------------
    public function edit($id): View
    {
        $user = User::with(['personal.cargo'])->findOrFail($id);
        return view('user.edit', compact('user'));
    }

    // -------------------------------------------------------
    // UPDATE
    // -------------------------------------------------------
    public function update(Request $request, User $user): RedirectResponse
    {
        $rules = [
            'email' => 'required|email|unique:users,email,' . $user->id,
            'name'  => 'required|string|max:255',
        ];

        // Contraseña solo si la envían
        if ($request->filled('password')) {
            $rules['password']              = ['required', 'confirmed', Password::min(4)];
            $rules['password_confirmation'] = 'required';
        }

        $request->validate($rules, [
            'email.unique'       => 'Este correo ya está en uso por otro usuario.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return Redirect::route('users.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    // -------------------------------------------------------
    // DESTROY
    // -------------------------------------------------------
    public function destroy($id): RedirectResponse
    {
        User::findOrFail($id)->delete();

        return Redirect::route('users.index')
            ->with('success', 'Usuario eliminado. El personal conserva sus datos.');
    }

    // -------------------------------------------------------
    // AJAX: buscar personal por cédula (para autocompletar)
    // -------------------------------------------------------
    public function buscarPersonal(Request $request)
    {
        $cedula   = $request->cedula;
        $personal = Personal::with('cargo')
                    ->where('cedula', $cedula)
                    ->whereDoesntHave('user')
                    ->first();

        if (!$personal) {
            return response()->json(['found' => false]);
        }

        return response()->json([
            'found'    => true,
            'cedula'   => $personal->cedula,
            'nombre'   => $personal->nombre . ' ' . $personal->apellido,
            'correo'   => $personal->correo ?? '',
            'cargo'    => $personal->cargo?->nombre ?? '—',
            'nivel'    => $personal->cargo?->nivel_acceso ?? 0,
        ]);
    }
}