@extends('layouts.app')
@section('title', 'Usuarios')

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl bg-white/90 backdrop-blur-sm rounded-lg shadow-sm">

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div>
            <h1 class="text-2xl font-bold text-red-800 flex items-center gap-2">
                <div class="p-2 bg-red-800 rounded text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-contact-round-icon lucide-contact-round">
                        <path d="M16 2v2" />
                        <path d="M17.915 22a6 6 0 0 0-12 0" />
                        <path d="M8 2v2" />
                        <circle cx="12" cy="12" r="4" />
                        <rect x="3" y="4" width="18" height="18" rx="2" />
                    </svg>
                </div>
                Gestión de Usuarios
            </h1>
        </div>
        {{-- <a href="{{ route('users.create') }}"
            class="inline-flex items-center gap-2 text-white bg-indigo-500 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-cyan-300 font-medium rounded-base text-sm px-4 py-2.5 text-center leading-5">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-user-round-plus-icon lucide-user-round-plus mr-1">
                <path d="M2 21a8 8 0 0 1 13.292-6" />
                <circle cx="10" cy="8" r="5" />
                <path d="M19 16v6" />
                <path d="M22 19h-6" />
            </svg>
            Crear Usuario
        </a> --}}
    </div>

    {{-- Alertas --}}
    @if(session('success'))
    <div class="flex items-center gap-3 p-4 mb-5 text-green-800 bg-green-50 border border-green-200 rounded-lg">
        <i data-lucide="check-circle-2" class="w-5 h-5 shrink-0"></i>
        <span class="text-sm font-medium">{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="ml-auto">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    </div>
    @endif

    {{-- Tarjetas resumen --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        @php
        $niveles = [
        5 => ['label' => 'Administradores', 'bg' => 'bg-red-100', 'icon' => 'shield', 'text' => 'text-red-600'],
        3 => ['label' => 'Asist. Admin.', 'bg' => 'bg-blue-100', 'icon' => 'briefcase-medical', 'text' =>
        'text-blue-600'],
        2 => ['label' => 'Jefes Módulo', 'bg' => 'bg-yellow-100', 'icon' => 'house-heart', 'text' => 'text-yellow-600'],
        1 => ['label' => 'Vacunadores', 'bg' => 'bg-green-100', 'icon' => 'syringe', 'text' => 'text-green-600'],
        ];
        @endphp
        @foreach($niveles as $nivel => $cfg)
        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500">{{ $cfg['label'] }}</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        {{ \App\Models\User::whereHas('personal.cargo', fn($q) => $q->where('nivel_acceso',
                        $nivel))->count() }}
                    </p>
                </div>
                <div class="p-2.5 {{ $cfg['bg'] }} rounded-lg">
                    <i data-lucide="{{ $cfg['icon'] }}" class="w-5 h-5 {{ $cfg['text'] }}"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>


    {{-- Tabla de usuarios --}}
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">

        {{-- Toolbar --}}
        <div
            class="flex flex-col md:flex-row items-start md:items-center justify-between gap-3 p-4 border-b border-gray-200">
            <form method="GET" action="{{ route('users.index') }}" class="flex items-center gap-2 w-full md:w-auto">
                <div class="relative w-full md:w-72">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    <input type="text" name="buscar" value="{{ request('buscar') }}"
                        placeholder="Buscar por nombre, email o cédula..."
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2.5">
                </div>
                <select name="nivel"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 p-2.5 shrink-0">
                    <option value="">Todos los niveles</option>
                    <option value="5" {{ request('nivel')==5 ? 'selected' : '' }}>Administrador</option>
                    <option value="3" {{ request('nivel')==3 ? 'selected' : '' }}>Asist. Administrativo</option>
                    <option value="2" {{ request('nivel')==2 ? 'selected' : '' }}>Jefe de Módulo</option>
                    <option value="1" {{ request('nivel')==1 ? 'selected' : '' }}>Vacunador</option>
                </select>
                <button type="submit"
                    class="px-2.5 py-2.5 text-sm font-medium text-indigo-600 bg-indigo-200 rounded-lg hover:bg-indigo-600 hover:text-indigo-200 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-user-search-icon lucide-user-search">
                        <circle cx="10" cy="7" r="4" />
                        <path d="M10.3 15H7a4 4 0 0 0-4 4v2" />
                        <circle cx="17" cy="17" r="3" />
                        <path d="m21 21-1.9-1.9" />
                    </svg>
                </button>
                @if(request()->hasAny(['buscar','nivel']))
                <a href="{{ route('users.index') }}"
                    class="flex items-center gap-1 px-3 py-2.5 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 shrink-0">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </a>
                @endif
            </form>

            <p class="text-sm text-gray-500 shrink-0">
                <span class="font-semibold text-gray-900">{{ $users->total() }}</span> usuarios activos
            </p>
        </div>

        {{-- Tabla --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-center">Usuarios</th>
                        <th class="px-4 py-3">Cédula</th>
                        <th class="px-4 py-3">Correo</th>
                        <th class="px-4 py-3">Cargo / Nivel</th>
                        <th class="px-4 py-3">Creado</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50/70 transition-colors">

                        {{-- Avatar + nombre --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0
                                    {{ match($user->nivel_acceso) {
                                        5 => 'bg-red-100',
                                        3 => 'bg-blue-100',
                                        2 => 'bg-yellow-100',
                                        1 => 'bg-green-100',
                                        default => 'bg-gray-100'
                                    } }}">
                                    <span class="text-sm font-bold
                                        {{ match($user->nivel_acceso) {
                                            5 => 'text-red-700',
                                            3 => 'text-blue-700',
                                            2 => 'text-yellow-700',
                                            1 => 'text-green-700',
                                            default => 'text-gray-600'
                                        } }}">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 text-sm">{{ $user->name }}</p>
                                    {{-- <p class="text-xs text-gray-400">
                                        {{ $user->personal?->nombre }} {{ $user->personal?->apellido }}
                                    </p> --}}
                                </div>
                            </div>
                        </td>

                        {{-- Cédula --}}
                        <td class="px-4 py-3">
                            <span class="font-mono text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">
                                {{ $user->personal_cedula }}
                            </span>
                        </td>

                        {{-- Email --}}
                        <td class="px-4 py-3 text-gray-600 text-xs">{{ $user->email }}</td>

                        {{-- Cargo/Nivel --}}
                        <td class="px-4 py-3">
                            @include('user.components._nivel_badge', ['nivel' => $user->nivel_acceso])
                        </td>

                        {{-- Fecha --}}
                        <td class="px-4 py-3 text-xs text-gray-400">
                            {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}
                        </td>

                        {{-- Acciones --}}
                        <td class="px-4 py-3">
                            <div class="flex justify-end">
                                <button id="udd-btn-{{ $user->id }}" data-dropdown-toggle="udd-{{ $user->id }}"
                                    data-dropdown-placement="left"
                                    class="inline-flex items-center p-1.5 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg">
                                    <i data-lucide="more-horizontal" class="w-5 h-5"></i>
                                </button>
                                <div id="udd-{{ $user->id }}"
                                    class="hidden z-20 w-48 bg-white rounded-lg shadow-lg border border-gray-100 text-sm text-gray-700">
                                    <ul class="py-1">
                                        <li>
                                            <a href="{{ route('users.show', $user->id) }}"
                                                class="flex items-center gap-2.5 px-4 py-2.5 hover:bg-gray-50">
                                                <i data-lucide="eye" class="w-4 h-4 text-blue-500"></i>
                                                Ver Detalles
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('users.edit', $user->id) }}"
                                                class="flex items-center gap-2.5 px-4 py-2.5 hover:bg-gray-50">
                                                <i data-lucide="square-pen" class="w-4 h-4 text-yellow-500"></i>
                                                Editar
                                            </a>
                                        </li>
                                        <li class="border-t border-gray-100 mt-1">
                                            <button type="button"
                                                onclick="abrirEliminarUser({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                                class="flex items-center gap-2.5 w-full px-4 py-2.5 hover:bg-danger hover:text-white text-danger rounded">
                                                <i data-lucide="user-x" class="w-4 h-4"></i>
                                                Revocar acceso
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-20 text-center">
                            <div class="flex flex-col items-center gap-3 text-gray-400">
                                <i data-lucide="users" class="w-12 h-12 text-gray-300"></i>
                                <div>
                                    <p class="font-semibold text-gray-500">No se encontraron usuarios</p>
                                    @if(request()->hasAny(['buscar','nivel']))
                                    <p class="text-sm mt-1">Ajusta los filtros de búsqueda.</p>
                                    @else
                                    <p class="text-sm mt-1">
                                        <a href="{{ route('users.create') }}"
                                            class="text-primary-600 hover:underline">Crea el primer usuario</a>
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if($users->hasPages())
        <div class="flex flex-col sm:flex-row justify-between items-center gap-3 px-4 py-3 border-t border-gray-200">
            <p class="text-sm text-gray-500">
                Mostrando <span class="font-semibold text-gray-900">{{ $users->firstItem() }}</span>–<span
                    class="font-semibold text-gray-900">{{ $users->lastItem() }}</span>
                de <span class="font-semibold text-gray-900">{{ $users->total() }}</span>
            </p>
            <nav>
                <ul class="inline-flex items-center -space-x-px text-sm h-8">
                    <li>
                        @if($users->onFirstPage())
                        <span
                            class="flex items-center justify-center h-8 px-3 text-gray-300 bg-white border border-gray-300 rounded-l-lg cursor-not-allowed">
                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                        </span>
                        @else
                        <a href="{{ $users->withQueryString()->previousPageUrl() }}"
                            class="flex items-center justify-center h-8 px-3 text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100">
                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                        </a>
                        @endif
                    </li>
                    @foreach($users->withQueryString()->getUrlRange(1, $users->lastPage()) as $page => $url)
                    <li>
                        @if($page == $users->currentPage())
                        <span
                            class="flex items-center justify-center h-8 px-3 text-primary-600 border border-primary-300 bg-primary-50 font-medium">{{
                            $page }}</span>
                        @elseif(abs($page - $users->currentPage()) <= 2 || $page==1 || $page==$users->lastPage())
                            <a href="{{ $url }}"
                                class="flex items-center justify-center h-8 px-3 text-gray-500 bg-white border border-gray-300 hover:bg-gray-100">{{
                                $page }}</a>
                            @elseif(abs($page - $users->currentPage()) == 3)
                            <span
                                class="flex items-center justify-center h-8 px-3 text-gray-500 bg-white border border-gray-300">…</span>
                            @endif
                    </li>
                    @endforeach
                    <li>
                        @if($users->hasMorePages())
                        <a href="{{ $users->withQueryString()->nextPageUrl() }}"
                            class="flex items-center justify-center h-8 px-3 text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100">
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </a>
                        @else
                        <span
                            class="flex items-center justify-center h-8 px-3 text-gray-300 bg-white border border-gray-300 rounded-r-lg cursor-not-allowed">
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </span>
                        @endif
                    </li>
                </ul>
            </nav>
        </div>
        @endif
    </div>


    {{-- Panel: Personal sin cuenta --}}
    @if($personalSinCuenta->count() > 0)
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 m-4">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <i data-lucide="user-x" class="w-5 h-5 text-amber-600"></i>
                <h3 class="text-sm font-semibold text-amber-800">
                    Personal sin acceso al sistema
                    <span class="ml-2 bg-amber-200 text-amber-800 text-xs font-bold px-2 py-0.5 rounded-full">
                        {{ $personalSinCuenta->count() }}
                    </span>
                </h3>
            </div>
            <button onclick="togglePendientes()" id="btnPendientes"
                class="text-xs text-amber-700 hover:text-amber-900 flex items-center gap-1 font-medium">
                <i data-lucide="chevron-down" class="w-4 h-4" id="iconPendientes"></i>
                Ver todos
            </button>
        </div>

        <div id="listaPendientes" class="hidden">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 mt-2">
                @foreach($personalSinCuenta as $p)
                <div class="flex items-center justify-between bg-white border border-amber-100 rounded-lg px-3 py-2.5">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
                            <span class="text-xs font-bold text-amber-700">
                                {{ strtoupper(substr($p->nombre, 0, 1)) }}
                            </span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">
                                {{ $p->nombre }} {{ $p->apellido }}
                            </p>
                            <p class="text-xs text-gray-400">CI: {{ $p->cedula }}</p>
                        </div>
                    </div>
                    <a href="{{ route('users.create', ['cedula' => $p->cedula]) }}"
                        class="shrink-0 ml-2 flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-warning hover:text-white">
                        <i data-lucide="plus" class="w-3 h-3"></i>
                        Crear
                    </a>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Preview primeros 3 si está colapsado --}}
        <div id="previewPendientes" class="flex flex-wrap gap-2">
            @foreach($personalSinCuenta->take(3) as $p)
            <span
                class="inline-flex items-center gap-1.5 bg-white border border-amber-100 text-amber-700 text-xs font-medium px-2.5 py-1 rounded-full">
                <i data-lucide="user" class="w-3 h-3"></i>
                {{ $p->nombre }} {{ $p->apellido }}
            </span>
            @endforeach
            @if($personalSinCuenta->count() > 3)
            <span class="text-xs text-amber-600 font-medium py-1">
                +{{ $personalSinCuenta->count() - 3 }} más
            </span>
            @endif
        </div>
    </div>
    @endif

</div>
@include('user.modals.delete-modal')

@push('scripts')
<script>
    lucide.createIcons();

    function abrirEliminarUser(id, nombre) {
        document.getElementById('deleteUserName').textContent = nombre;
        document.getElementById('deleteUserForm').action = '{{ url("users") }}/' + id;
        document.getElementById('deleteUserModal').classList.remove('hidden');
    }

    function togglePendientes() {
        const lista   = document.getElementById('listaPendientes');
        const preview = document.getElementById('previewPendientes');
        const icon    = document.getElementById('iconPendientes');
        const btn     = document.getElementById('btnPendientes');
        const visible = !lista.classList.contains('hidden');

        lista.classList.toggle('hidden', visible);
        preview.classList.toggle('hidden', !visible);
        icon.setAttribute('data-lucide', visible ? 'chevron-down' : 'chevron-up');
        btn.querySelector('span') && (btn.querySelector('span').textContent = visible ? 'Ver todos' : 'Ocultar');
        lucide.createIcons();
    }
</script>
@endpush
@endsection