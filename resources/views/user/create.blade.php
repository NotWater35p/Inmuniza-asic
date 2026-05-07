@extends('layouts.app')
@section('title', 'Crear Usuario')

@section('content')
<div class="px-4 py-6 mx-auto max-w-2xl bg-white/90 rounded-lg shadow">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-3xl font-semibold text-gray-800 flex items-center gap-2">
                <div class="p-2 bg-gray-800 rounded text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-user-round-plus-icon lucide-user-round-plus">
                        <path d="M2 21a8 8 0 0 1 13.292-6" />
                        <circle cx="10" cy="8" r="5" />
                        <path d="M19 16v6" />
                        <path d="M22 19h-6" />
                    </svg>
                </div>
                Registrar Usuario
            </h1>
        </div>
        <a href="{{ route('users.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Volver
        </a>
    </div>

    @if($errors->any())
    <div class="flex items-start gap-3 p-4 mb-5 text-red-800 bg-red-50 border border-red-200 rounded-lg">
        <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 mt-0.5"></i>
        <ul class="text-sm list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    {{-- Info de cómo funciona el login --}}
    {{-- <div class="flex items-start gap-3 p-4 mb-5 bg-blue-50 border border-blue-100 rounded-lg text-sm text-blue-700">
        <i data-lucide="info" class="w-5 h-5 shrink-0 mt-0.5"></i>
        <div>
            <p class="font-semibold mb-0.5">¿Cómo accede el usuario al sistema?</p>
            <p>El personal iniciará sesión usando su <strong>número de cédula</strong> como usuario y la contraseña que
                asignes aquí.</p>
        </div>
    </div> --}}

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="p-5 border-b border-gray-100 flex items-center gap-2">
            <i data-lucide="user-check" class="w-4 h-4 text-primary-600"></i>
            <h2 class="text-base font-semibold text-gray-800">Datos del Usuario</h2>
        </div>

        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="p-5 space-y-5">

                {{-- Seleccionar personal --}}
                <div>
                    <label class="block mb-1.5 text-sm font-medium text-gray-700">
                        Personal <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="hidden" name="personal_cedula" id="personal_cedula_hidden"
                            value="{{ old('personal_cedula', $personalPresel?->cedula) }}">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="user" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <input type="text" id="personal_search"
                                value="{{ old('personal_cedula') ? '' : ($personalPresel ? $personalPresel->nombre . ' ' . $personalPresel->apellido : '') }}"
                                placeholder="Buscar personal por nombre o cédula..." autocomplete="off"
                                class="pl-9 pr-4 bg-gray-50 border {{ $errors->has('personal_cedula') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        </div>
                        <div id="personal_dropdown"
                            class="hidden absolute z-30 w-full bottom-full mb-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-52 overflow-y-auto">
                            @foreach($personalSinCuenta as $p)
                            <div data-id="{{ $p->cedula }}" data-name="{{ $p->nombre }} {{ $p->apellido }}"
                                data-correo="{{ $p->correo ?? '' }}" data-cargo="{{ $p->cargo?->nombre ?? '' }}"
                                data-nivel="{{ $p->cargo?->nivel_acceso ?? 0 }}"
                                class="px-4 py-2.5 cursor-pointer hover:bg-primary-50 text-gray-700 text-sm {{ ($personalPresel?->cedula == $p->cedula) ? 'bg-primary-50 text-primary-700 font-medium' : '' }}">
                                <div class="flex items-center justify-between">
                                    <p class="font-medium">{{ $p->nombre }} {{ $p->apellido }}</p>
                                    <span class="text-xs font-mono text-gray-400">{{ $p->cedula }}</span>
                                </div>
                                @if($p->cargo)
                                <p class="text-xs text-gray-400 mt-0.5">{{ $p->cargo->nombre }}</p>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @error('personal_cedula')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- Tarjeta info del personal seleccionado --}}
                <div id="personalCard"
                    class="{{ $personalPresel ? '' : 'hidden' }} bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Personal seleccionado</p>
                        <div id="nivelBadge"></div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div id="avatarCircle"
                            class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center shrink-0">
                            <span id="avatarLetra" class="text-sm font-bold text-white">
                                {{ $personalPresel ? strtoupper(substr($personalPresel->nombre, 0, 1)) : '' }}
                            </span>
                        </div>
                        <div>
                            <p id="personalNombre" class="text-sm font-semibold text-gray-900">
                                {{ $personalPresel ? $personalPresel->nombre . ' ' . $personalPresel->apellido : '' }}
                            </p>
                            <p class="text-xs text-gray-400 flex items-center gap-3 mt-0.5">
                                <span class="flex items-center gap-1">
                                    <i data-lucide="hash" class="w-3 h-3"></i>
                                    <span id="personalCedula">{{ $personalPresel?->cedula }}</span>
                                </span>
                                <span class="flex items-center gap-1">
                                    <i data-lucide="briefcase" class="w-3 h-3"></i>
                                    <span id="personalCargo">{{ $personalPresel?->cargo?->nombre }}</span>
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <p class="text-xs text-gray-500 flex items-center gap-1.5">
                            <i data-lucide="key-round" class="w-3.5 h-3.5 text-primary-500"></i>
                            Iniciará sesión con cédula:
                            <strong id="loginCedula" class="font-mono text-primary-700">
                                {{ $personalPresel?->cedula }}
                            </strong>
                        </p>
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block mb-1.5 text-sm font-medium text-gray-700">
                        Correo Electrónico <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i data-lucide="mail" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email', $personalPresel?->correo) }}"
                            placeholder="correo@ejemplo.com"
                            class="pl-9 bg-gray-50 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                    </div>
                    @error('email')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- Contraseña --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block mb-1.5 text-sm font-medium text-gray-700">
                            Contraseña <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="lock" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <input type="password" name="password" id="password" placeholder="Mínimo 8 caracteres"
                                oninput="checkStrength(this.value)"
                                class="pl-9 bg-gray-50 border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        </div>
                        {{-- Barra de fortaleza --}}
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
                            Confirmar Contraseña <span class="text-red-500">*</span>
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

            {{-- Footer --}}
            <div class="flex items-center justify-end gap-3 px-5 py-4 border-t border-gray-100 bg-gray-50 rounded-b-lg">
                <a href="{{ route('users.index') }}"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-danger hover:text-white focus:ring-4 focus:ring-gray-200">
                    Cancelar
                </a>
                <button type="submit"
                    class="flex items-center gap-2 px-5 py-2.5 text-sm text-success bg-green-300 hover:bg-success hover:text-white focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded-base focus:outline-none">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    Crear Usuario
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    lucide.createIcons();

    const nivelLabels = {
        5: { label: 'Administrador',        bg: 'bg-red-100',    text: 'text-red-700',    icon: 'shield' },
        3: { label: 'Asist. Administrativo',bg: 'bg-blue-100',   text: 'text-blue-700',   icon: 'briefcase' },
        2: { label: 'Jefe de Módulo',       bg: 'bg-yellow-100', text: 'text-yellow-700', icon: 'building' },
        1: { label: 'Vacunador',            bg: 'bg-green-100',  text: 'text-green-700',  icon: 'syringe' },
    };

    // ---- Searchable personal ----
    const input    = document.getElementById('personal_search');
    const hidden   = document.getElementById('personal_cedula_hidden');
    const dropdown = document.getElementById('personal_dropdown');

    input.addEventListener('input', function() {
        const q = this.value.toLowerCase();
        let vis = 0;
        dropdown.querySelectorAll('[data-id]').forEach(item => {
            const m = item.dataset.name.toLowerCase().includes(q) ||
                      item.dataset.id.toString().includes(q);
            item.style.display = m ? '' : 'none';
            if (m) vis++;
        });
        dropdown.classList.toggle('hidden', vis === 0);
        if (!this.value) { hidden.value = ''; ocultarCard(); }
    });

    input.addEventListener('focus', () => {
        dropdown.querySelectorAll('[data-id]').forEach(i => i.style.display = '');
        dropdown.classList.remove('hidden');
    });

    dropdown.querySelectorAll('[data-id]').forEach(item => {
        item.addEventListener('mousedown', function(e) {
            e.preventDefault();
            input.value  = this.dataset.name;
            hidden.value = this.dataset.id;
            dropdown.classList.add('hidden');
            mostrarCard(this.dataset);
            // Autocompletar email
            if (this.dataset.correo) {
                document.getElementById('email').value = this.dataset.correo;
            }
        });
    });

    document.addEventListener('click', e => {
        if (!input.contains(e.target) && !dropdown.contains(e.target))
            dropdown.classList.add('hidden');
    });

    function mostrarCard(data) {
        const card = document.getElementById('personalCard');
        card.classList.remove('hidden');
        document.getElementById('personalNombre').textContent = data.name;
        document.getElementById('personalCedula').textContent = data.id;
        document.getElementById('personalCargo').textContent  = data.cargo;
        document.getElementById('loginCedula').textContent    = data.id;
        document.getElementById('avatarLetra').textContent    = data.name.charAt(0).toUpperCase();

        // Badge nivel
        const cfg = nivelLabels[parseInt(data.nivel)] || { label:'Sin cargo', bg:'bg-gray-100', text:'text-gray-600', icon:'user' };
        document.getElementById('nivelBadge').innerHTML = `
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold ${cfg.bg} ${cfg.text}">
                <i data-lucide="${cfg.icon}" class="w-3 h-3"></i>
                ${cfg.label}
            </span>`;
        lucide.createIcons();
    }

    function ocultarCard() {
        document.getElementById('personalCard').classList.add('hidden');
    }

    // Si hay personal preseleccionado, mostrar card
    @if($personalPresel)
    mostrarCard({
        name:  '{{ $personalPresel->nombre }} {{ $personalPresel->apellido }}',
        id:    '{{ $personalPresel->cedula }}',
        cargo: '{{ $personalPresel->cargo?->nombre }}',
        nivel: '{{ $personalPresel->cargo?->nivel_acceso ?? 0 }}',
    });
    @endif

    // ---- Fortaleza de contraseña ----
    function checkStrength(val) {
        let score = 0;
        if (val.length >= 8)         score++;
        if (/[A-Z]/.test(val))       score++;
        if (/[0-9]/.test(val))       score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const colors = ['', 'bg-red-400', 'bg-orange-400', 'bg-yellow-400', 'bg-green-500'];
        const labels = ['', 'Muy débil', 'Débil', 'Moderada', 'Fuerte'];

        for (let i = 1; i <= 4; i++) {
            const bar = document.getElementById('bar' + i);
            bar.className = 'h-1 flex-1 rounded-full transition-colors ' +
                (i <= score ? colors[score] : 'bg-gray-200');
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
        if (p1 === p2) {
            lbl.textContent  = '✓ Las contraseñas coinciden';
            lbl.className    = 'mt-1.5 text-xs text-green-600';
        } else {
            lbl.textContent  = '✗ Las contraseñas no coinciden';
            lbl.className    = 'mt-1.5 text-xs text-red-600';
        }
    }
</script>
@endpush
@endsection