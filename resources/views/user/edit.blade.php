@extends('layouts.app')
@section('title')
Editar | {{ $user->name }}
@endsection

@section('content')
<div class="px-4 py-6 mx-auto max-w-2xl bg-white/90 rounded-lg shadow">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-3xl font-semibold text-warning flex items-center gap-2">
                <div class="p-2 bg-warning rounded text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-user-round-pen-icon lucide-user-round-pen">
                        <path d="M2 21a8 8 0 0 1 10.821-7.487" />
                        <path
                            d="M21.378 16.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z" />
                        <circle cx="10" cy="8" r="5" />
                    </svg>
                </div>
                Editar Usuario
            </h1>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('users.show', $user->id) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm text-brand bg-blue-200 hover:bg-brand hover:text-white focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded-lg focus:outline-none">
                <i data-lucide="eye" class="w-4 h-4"></i>
                Ver
            </a>
            <a href="{{ route('users.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Volver
            </a>
        </div>
    </div>

    @if($errors->any())
    <div class="flex items-start gap-3 p-4 mb-5 text-red-800 bg-red-50 border border-red-200 rounded-lg">
        <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 mt-0.5"></i>
        <ul class="text-sm list-disc list-inside">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('users.update', $user->id) }}">
        @csrf
        @method('PATCH')

        {{-- Personal vinculado (solo lectura) --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-4">
            <div class="p-5 border-b border-gray-100 flex items-center gap-2">
                <i data-lucide="user" class="w-4 h-4 text-gray-400"></i>
                <h2 class="text-base font-semibold text-gray-800">Personal Vinculado</h2>
                <span class="ml-auto text-xs text-gray-400 italic">No editable</span>
            </div>
            <div class="p-5">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-full flex items-center justify-center shrink-0
                        {{ match($user->nivel_acceso) { 5=>'bg-red-100', 3=>'bg-blue-100', 2=>'bg-yellow-100', 1=>'bg-green-100', default=>'bg-gray-100' } }}">
                        <span
                            class="text-base font-bold
                            {{ match($user->nivel_acceso) { 5=>'text-red-700', 3=>'text-blue-700', 2=>'text-yellow-700', 1=>'text-green-700', default=>'text-gray-600' } }}">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">
                            {{ $user->personal?->nombre }} {{ $user->personal?->apellido }}
                        </p>
                        <div class="flex items-center gap-3 mt-1">
                            {{-- <span class="text-xs text-gray-500 flex items-center gap-1">
                                <i data-lucide="hash" class="w-3 h-3"></i>
                                CI: <span class="font-mono font-semibold text-gray-700 ml-0.5">{{ $user->personal_cedula
                                    }}</span>
                            </span> --}}
                            @include('user.components._nivel_badge', ['nivel' => $user->nivel_acceso])
                        </div>
                    </div>
                </div>

                <div class="mt-3 p-2.5 bg-gray-50 rounded">
                    <p class="text-xs text-gray-600 flex items-center gap-1.5">
                        <i data-lucide="key-round" class="w-3.5 h-3.5"></i>
                        CI: <strong class="font-xs">{{ $user->personal_cedula }}</strong>
                    </p>
                </div>
            </div>
        </div>

        {{-- Datos editables --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-4">
            <div class="p-5 border-b border-gray-100 flex items-center gap-2">
                <i data-lucide="pencil" class="w-4 h-4 text-gray-400"></i>
                <h2 class="text-base font-semibold text-gray-800">Información de Cuenta</h2>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Nombre --}}
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-1.5 text-sm font-medium text-gray-700">
                        Nombre de usuario <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i data-lucide="user" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                            class="pointer-events-none pl-9 bg-gray-50 border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                    </div>
                    @error('name')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- Email --}}
                <div class="sm:col-span-2">
                    <label for="email" class="block mb-1.5 text-sm font-medium text-gray-700">
                        Correo Electrónico <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i data-lucide="mail" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                            class="pl-9 bg-gray-50 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                    </div>
                    @error('email')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Cambio de contraseña --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-5">
            <button type="button" onclick="togglePassword()"
                class="w-full flex items-center justify-between p-5 text-left">
                <div class="flex items-center gap-2">
                    <i data-lucide="lock" class="w-4 h-4 text-gray-400"></i>
                    <h2 class="text-base font-semibold text-gray-800">Cambiar Contraseña</h2>
                </div>
                <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 transition-transform" id="passChevron"></i>
            </button>

            <div id="passwordSection" class="hidden border-t border-gray-100">
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="password" class="block mb-1.5 text-sm font-medium text-gray-700">
                            Nueva Contraseña
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="lock" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <input type="password" name="password" id="password" placeholder="Mínimo 8 caracteres"
                                oninput="checkStrength(this.value)"
                                class="pl-9 bg-gray-50 border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        </div>
                        <div class="mt-2 space-y-1">
                            <div class="flex gap-1">
                                <div id="bar1" class="h-1 flex-1 rounded-full bg-gray-200 transition-colors"></div>
                                <div id="bar2" class="h-1 flex-1 rounded-full bg-gray-200 transition-colors"></div>
                                <div id="bar3" class="h-1 flex-1 rounded-full bg-gray-200 transition-colors"></div>
                                <div id="bar4" class="h-1 flex-1 rounded-full bg-gray-200 transition-colors"></div>
                            </div>
                            <p id="strengthLabel" class="text-xs text-gray-400"></p>
                        </div>
                        @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block mb-1.5 text-sm font-medium text-gray-700">
                            Confirmar Contraseña
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="lock-keyhole" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                placeholder="Repite la contraseña" oninput="checkMatch()"
                                class="pl-9 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        </div>
                        <p id="matchLabel" class="mt-1.5 text-xs hidden"></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('users.index') }}"
                class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                Cancelar
            </a>
            <button type="submit"
                class="flex items-center gap-2 px-5 py-2.5 text-sm text-warning bg-yellow-300 hover:bg-warning hover:text-white focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded-base focus:outline-none">
                <i data-lucide="save" class="w-4 h-4"></i>
                Guardar Cambios
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    lucide.createIcons();

    // Abrir sección contraseña si hay error en ella
    @if($errors->hasAny(['password','password_confirmation']))
        document.getElementById('passwordSection').classList.remove('hidden');
        document.getElementById('passChevron').style.transform = 'rotate(180deg)';
    @endif

    function togglePassword() {
        const sec     = document.getElementById('passwordSection');
        const chevron = document.getElementById('passChevron');
        const hidden  = sec.classList.contains('hidden');
        sec.classList.toggle('hidden', !hidden);
        chevron.style.transform = hidden ? 'rotate(180deg)' : '';
    }

    function checkStrength(val) {
        let score = 0;
        if (val.length >= 8)          score++;
        if (/[A-Z]/.test(val))        score++;
        if (/[0-9]/.test(val))        score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;
        const colors = ['','bg-red-400','bg-orange-400','bg-yellow-400','bg-green-500'];
        const labels = ['','Muy débil','Débil','Moderada','Fuerte'];
        for (let i = 1; i <= 4; i++) {
            document.getElementById('bar'+i).className =
                'h-1 flex-1 rounded-full transition-colors ' + (i <= score ? colors[score] : 'bg-gray-200');
        }
        const lbl = document.getElementById('strengthLabel');
        lbl.textContent = val.length > 0 ? 'Contraseña ' + labels[score] : '';
        lbl.className   = 'text-xs ' + (score >= 3 ? 'text-green-600' : score >= 2 ? 'text-yellow-600' : 'text-red-500');
    }

    function checkMatch() {
        const p1  = document.getElementById('password').value;
        const p2  = document.getElementById('password_confirmation').value;
        const lbl = document.getElementById('matchLabel');
        if (!p2) { lbl.classList.add('hidden'); return; }
        lbl.classList.remove('hidden');
        lbl.textContent = p1 === p2 ? '✓ Las contraseñas coinciden' : '✗ No coinciden';
        lbl.className   = 'mt-1.5 text-xs ' + (p1 === p2 ? 'text-green-600' : 'text-red-600');
    }
</script>
@endpush
@endsection