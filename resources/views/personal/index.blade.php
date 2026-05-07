@extends('layouts.app')
@section('title', 'Personal')

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl bg-white/90 rounded-lg shadow backdrop:blur-sm">

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div>
            <h1 class="text-2xl font-bold text-brand-strong flex items-center gap-2">
                <div class="p-2 bg-brand-strong rounded text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 2v2" />
                        <path d="M5 2v2" />
                        <path d="M5 3H4a2 2 0 0 0-2 2v4a6 6 0 0 0 12 0V5a2 2 0 0 0-2-2h-1" />
                        <path d="M8 15a6 6 0 0 0 12 0v-3" />
                        <circle cx="20" cy="10" r="2" />
                    </svg>
                </div>
                Personal Registrado
            </h1>
        </div>
        <a href="{{ route('personal.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-indigo-100 bg-indigo-900 rounded-lg hover:bg-indigo-600 hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-user-plus-icon lucide-user-plus">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                <circle cx="9" cy="7" r="4" />
                <line x1="19" x2="19" y1="8" y2="14" />
                <line x1="22" x2="16" y1="11" y2="11" />
            </svg>
            Nuevo Personal
        </a>
    </div>

    {{-- Alertas --}}
    @if(session('success'))
    <div class="flex items-center gap-3 p-4 mb-5 text-green-800 bg-green-50 border border-green-200 rounded-lg">
        <i data-lucide="check-circle-2" class="w-5 h-5 shrink-0"></i>
        <span class="text-sm font-medium">{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="ml-auto"><i data-lucide="x" class="w-4 h-4"></i></button>
    </div>
    @endif

    @if(session('error'))
    <div class="flex items-center gap-3 p-4 mb-5 text-red-800 bg-red-50 border border-red-200 rounded-lg">
        <i data-lucide="alert-circle" class="w-5 h-5 shrink-0"></i>
        <span class="text-sm font-medium">{{ session('error') }}</span>
    </div>
    @endif

    {{-- Contenido principal --}}
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">

        {{-- Toolbar --}}
        <div
            class="flex flex-col md:flex-row items-start md:items-center justify-between gap-3 p-4 border-b border-gray-200">

            <form method="GET" action="{{ route('personal.index') }}" class="flex items-center gap-2 w-full md:w-auto">
                @foreach(request()->except(['search', 'page']) as $k => $v)
                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach
                <div class="relative w-full md:w-72">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cédula, nombre, apellido..."
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2.5">
                </div>
                <button type="submit"
                    class="px-3 py-3 text-sm font-medium text-white bg-cyan-500 rounded-lg hover:bg-cyan-700 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-search-icon lucide-search">
                        <path d="m21 21-4.34-4.34" />
                        <circle cx="11" cy="11" r="8" />
                    </svg>
                </button>
                @if(request()->hasAny(['search', 'cargo_id']))
                <a href="{{ route('personal.index') }}"
                    class="flex items-center gap-1 px-3 py-2.5 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 shrink-0">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </a>
                @endif
            </form>

            <div class="flex items-center gap-2 shrink-0">
                <span class="flex items-center after:content-['/'] sm:after:hidden after:mx-2 after:text-fg-disabled">
                    <p class="justify-center items-center flex w-5.5 h-5.5 bg-brand-light text-white rounded-full me-2">
                        {{ $personals->total() }}
                    </p>
                    Registros
                </span>
                <button type="button" onclick="toggleFiltros()"
                    class="relative flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-sliders-horizontal-icon lucide-sliders-horizontal">
                        <path d="M10 5H3" />
                        <path d="M12 19H3" />
                        <path d="M14 3v4" />
                        <path d="M16 17v4" />
                        <path d="M21 12h-9" />
                        <path d="M21 19h-5" />
                        <path d="M21 5h-7" />
                        <path d="M8 10v4" />
                        <path d="M8 12H3" />
                    </svg>
                    Filtros
                    @if(request()->hasAny(['cargo_id', 'sort']))
                    <span
                        class="absolute -top-1.5 -right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-primary-600 text-2xs font-bold text-white">✓</span>
                    @endif
                </button>

            </div>
        </div>

        {{-- Tags filtros activos --}}
        @if(request()->hasAny(['search', 'cargo_id']))
        <div class="flex flex-wrap items-center gap-2 px-4 py-2.5 bg-gray-50 border-b border-gray-200 text-xs">
            <span class="font-medium text-gray-500 flex items-center gap-1">
                <i data-lucide="filter" class="w-3 h-3"></i> Filtros activos:
            </span>
            @if(request('search'))
            <span
                class="inline-flex items-center gap-1 pl-2.5 pr-1 py-1 rounded-full bg-blue-100 text-blue-700 font-medium">
                Búsqueda: "{{ request('search') }}"
                <a href="{{ route('personal.index', request()->except('search')) }}"
                    class="hover:bg-blue-200 rounded-full p-0.5">
                    <i data-lucide="x" class="w-3 h-3"></i>
                </a>
            </span>
            @endif
            @if(request('cargo_id'))
            @php $cargoActivo = $cargos->firstWhere('id', request('cargo_id')); @endphp
            <span
                class="inline-flex items-center gap-1 pl-2.5 pr-1 py-1 rounded-full bg-purple-100 text-purple-700 font-medium">
                Cargo: {{ $cargoActivo?->nombre ?? request('cargo_id') }}
                <a href="{{ route('personal.index', request()->except('cargo_id')) }}"
                    class="hover:bg-purple-200 rounded-full p-0.5">
                    <i data-lucide="x" class="w-3 h-3"></i>
                </a>
            </span>
            @endif
        </div>
        @endif

        {{-- Tabla --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 w-10">#</th>

                        {{-- Cédula  --}}
                        <th class="px-4 py-3">
                            <a href="{{ route('personal.index', array_merge(request()->query(), ['sort' => 'cedula', 'direction' => ($sort === 'cedula' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                                class="flex items-center gap-1.5 group text-gray-700 hover:text-gray-900">
                                Cédula
                                <span class="flex flex-col">
                                    <i data-lucide="chevron-up"
                                        class="w-2.5 h-2.5 {{ $sort === 'cedula' && $direction === 'asc' ? 'text-primary-600' : 'text-gray-300 group-hover:text-gray-400' }}"></i>
                                    <i data-lucide="chevron-down"
                                        class="w-2.5 h-2.5 -mt-1 {{ $sort === 'cedula' && $direction === 'desc' ? 'text-primary-600' : 'text-gray-300 group-hover:text-gray-400' }}"></i>
                                </span>
                            </a>
                        </th>

                        {{-- Nombre  --}}
                        <th class="px-4 py-3">
                            <a href="{{ route('personal.index', array_merge(request()->query(), ['sort' => 'nombre', 'direction' => ($sort === 'nombre' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                                class="flex items-center gap-1.5 group text-gray-700 hover:text-gray-900">
                                Nombre
                                <span class="flex flex-col">
                                    <i data-lucide="chevron-up"
                                        class="w-2.5 h-2.5 {{ $sort === 'nombre' && $direction === 'asc' ? 'text-primary-600' : 'text-gray-300 group-hover:text-gray-400' }}"></i>
                                    <i data-lucide="chevron-down"
                                        class="w-2.5 h-2.5 -mt-1 {{ $sort === 'nombre' && $direction === 'desc' ? 'text-primary-600' : 'text-gray-300 group-hover:text-gray-400' }}"></i>
                                </span>
                            </a>
                        </th>

                        {{-- Cargo --}}
                        <th class="px-4 py-3 text-gray-700">Cargo</th>

                        {{-- Teléfono --}}
                        <th class="px-4 py-3 hidden md:table-cell text-gray-700">Teléfono</th>

                        {{-- Correo --}}
                        <th class="px-4 py-3 hidden lg:table-cell text-gray-700">Correo</th>

                        {{-- Usuario --}}
                        <th class="px-4 py-3 hidden lg:table-cell text-gray-700 text-center">Acceso</th>

                        <th class="px-4 py-3 text-right text-gray-700">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($personals as $personal)
                    @php
                    $nivel = $personal->cargo?->nivel_acceso ?? 0;
                    $badgeCfg = match($nivel) {
                    5 => ['bg' => 'bg-red-100', 'text' => 'text-red-700'],
                    3 => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
                    2 => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'],
                    1 => ['bg' => 'bg-green-100', 'text' => 'text-green-700'],
                    default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'],
                    };
                    $avatarBg = match($nivel) {
                    5 => 'bg-red-100 text-red-700',
                    3 => 'bg-blue-100 text-blue-700',
                    2 => 'bg-yellow-100 text-yellow-700',
                    1 => 'bg-green-100 text-green-700',
                    default => 'bg-gray-100 text-gray-600',
                    };
                    @endphp
                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="px-4 py-3 text-gray-400 text-xs">{{ $i + $loop->iteration }}</td>

                        {{-- Cédula --}}
                        <td class="px-4 py-3">
                            <span class="font-mono text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">
                                {{ $personal->cedula }}
                            </span>
                        </td>

                        {{-- Nombre con avatar --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2.5">
                                <div
                                    class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 {{ $avatarBg }}">
                                    <span class="text-xs font-bold">{{ strtoupper(substr($personal->nombre, 0, 1))
                                        }}</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 text-sm">{{ $personal->nombre }} {{
                                        $personal->apellido }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Cargo --}}
                        <td class="px-4 py-3">
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold {{ $badgeCfg['bg'] }} {{ $badgeCfg['text'] }}">
                                {{ $personal->cargo?->nombre ?? '—' }}
                            </span>
                        </td>

                        {{-- Teléfono --}}
                        <td class="px-4 py-3 hidden md:table-cell text-gray-500 text-xs">
                            {{ $personal->telefono ?? '—' }}
                        </td>

                        {{-- Correo --}}
                        <td class="px-4 py-3 hidden lg:table-cell text-gray-500 text-xs">
                            {{ $personal->correo ?? '—' }}
                        </td>

                        {{-- Tiene usuario en sistema --}}
                        <td class="px-4 py-3 hidden lg:table-cell text-center">
                            @if($personal->user)
                            <span
                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 font-medium">
                                <i data-lucide="check" class="w-3 h-3"></i>
                                Activo
                            </span>
                            @else
                            <span
                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs bg-gray-100 text-gray-500 font-medium">
                                <i data-lucide="minus" class="w-3 h-3"></i>
                                Sin acceso
                            </span>
                            @endif
                        </td>

                        {{-- Acciones --}}
                        <td class="px-4 py-3">
                            <div class="flex justify-end items-center gap-1.5">
                                <a href="{{ route('personal.show', $personal->cedula) }}"
                                            class="p-1.5 text-blue-500 hover:text-blue-700 hover:bg-blue-100 rounded-lg"
                                            title="Ver">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-eye-icon lucide-eye">
                                                <path
                                                    d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </a>
                                </a>
                                <a href="{{ route('personal.edit', $personal->cedula) }}"
                                            class="p-1.5 text-yellow-500 hover:text-yellow-700 hover:bg-yellow-100 rounded-lg"
                                            title="Editar">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-square-pen-icon lucide-square-pen">
                                                <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                <path
                                                    d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z" />
                                            </svg>
                                        </a>
                                <a href="{{ route('personal.pdf', $personal->cedula) }}"
                                            class="p-1.5 text-green-500 hover:text-green-700 hover:bg-green-100 rounded-lg"
                                            title="Reporte">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-file-down-icon lucide-file-down">
                                                <path
                                                    d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z" />
                                                <path d="M14 2v5a1 1 0 0 0 1 1h5" />
                                                <path d="M12 18v-6" />
                                                <path d="m9 15 3 3 3-3" />
                                            </svg>
                                        </a>
                                </a>
                                <button type="button"
                                    onclick="abrirEliminar('{{ $personal->cedula }}', '{{ addslashes($personal->nombre) }} {{ addslashes($personal->apellido) }}', {{ $personal->user ? 'true' : 'false' }})"
                                            class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-100 rounded-lg"
                                            title="Eliminar">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-trash2-icon lucide-trash-2">
                                                <path d="M10 11v6" />
                                                <path d="M14 11v6" />
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                                <path d="M3 6h18" />
                                                <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                            </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-20 text-center">
                            <div class="flex flex-col items-center gap-3 text-gray-400">
                                <i data-lucide="users" class="w-12 h-12 text-gray-300"></i>
                                <div>
                                    <p class="font-semibold text-gray-500">No se encontró personal</p>
                                    <p class="text-sm mt-1">
                                        @if(request()->hasAny(['search', 'cargo_id']))
                                        Ajusta los filtros de búsqueda.
                                        @else
                                        <a href="{{ route('personal.create') }}"
                                            class="text-primary-600 hover:underline">Registra el primer miembro</a>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if($personals->hasPages())
        <div class="flex flex-col sm:flex-row justify-between items-center gap-3 px-4 py-3 border-t border-gray-200">
            <p class="text-sm text-gray-500">
                Mostrando <span class="font-semibold text-gray-900">{{ $personals->firstItem() }}</span>–<span
                    class="font-semibold text-gray-900">{{ $personals->lastItem() }}</span>
                de <span class="font-semibold text-gray-900">{{ $personals->total() }}</span>
            </p>
            <nav>
                <ul class="inline-flex items-center -space-x-px text-sm h-8">
                    <li>
                        @if($personals->onFirstPage())
                        <span
                            class="flex items-center justify-center h-8 px-3 text-gray-300 bg-white border border-gray-300 rounded-l-lg cursor-not-allowed">
                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                        </span>
                        @else
                        <a href="{{ $personals->withQueryString()->previousPageUrl() }}"
                            class="flex items-center justify-center h-8 px-3 text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100">
                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                        </a>
                        @endif
                    </li>
                    @foreach($personals->withQueryString()->getUrlRange(1, $personals->lastPage()) as $page => $url)
                    <li>
                        @if($page == $personals->currentPage())
                        <span
                            class="flex items-center justify-center h-8 px-3 text-primary-600 border border-primary-300 bg-primary-50 font-medium">{{
                            $page }}</span>
                        @elseif(abs($page - $personals->currentPage()) <= 2 || $page==1 || $page==$personals->
                            lastPage())
                            <a href="{{ $url }}"
                                class="flex items-center justify-center h-8 px-3 text-gray-500 bg-white border border-gray-300 hover:bg-gray-100">{{
                                $page }}</a>
                            @elseif(abs($page - $personals->currentPage()) == 3)
                            <span
                                class="flex items-center justify-center h-8 px-3 text-gray-500 bg-white border border-gray-300">…</span>
                            @endif
                    </li>
                    @endforeach
                    <li>
                        @if($personals->hasMorePages())
                        <a href="{{ $personals->withQueryString()->nextPageUrl() }}"
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
</div>

{{-- ===== PANEL LATERAL FILTROS ===== --}}
<div id="filtrosOverlay" onclick="toggleFiltros()" class="hidden fixed inset-0 z-40 bg-gray-900/40"></div>

<div id="filtrosPanel"
    class="fixed top-0 right-0 z-50 h-full w-full max-w-sm bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col">

    <div class="flex items-center justify-between p-5 border-b border-gray-200 shrink-0">
        <div class="flex items-center gap-2">
            <i data-lucide="sliders-horizontal" class="w-5 h-5 text-primary-600"></i>
            <h3 class="text-base font-semibold text-gray-900">Filtrar Personal</h3>
        </div>
        <button onclick="toggleFiltros()" class="text-gray-400 hover:bg-gray-100 rounded-lg p-1.5">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
    </div>

    <form method="GET" action="{{ route('personal.index') }}" class="flex flex-col flex-1 overflow-hidden">
        @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif

        <div class="flex-1 overflow-y-auto p-5 space-y-5">

            {{-- Cargo --}}
            <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3 flex items-center gap-1.5">
                    <i data-lucide="briefcase" class="w-3.5 h-3.5 text-gray-400"></i>
                    Cargo
                </label>
                <select name="cargo_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                    <option value="">-- Todos los cargos --</option>
                    @foreach($cargos as $cargo)
                    <option value="{{ $cargo->id }}" {{ request('cargo_id')==$cargo->id ? 'selected' : '' }}>
                        {{ $cargo->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Ordenamiento --}}
            <div class="border-t border-gray-100 pt-4">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3 flex items-center gap-1.5">
                    <i data-lucide="arrow-up-down" class="w-3 h-3"></i>
                    Ordenar por
                </p>
                <select name="sort"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 mb-3">
                    <option value="cedula" {{ request('sort','cedula')==='cedula' ? 'selected' :'' }}>Cédula</option>
                    <option value="nombre" {{ request('sort')==='nombre' ? 'selected' :'' }}>Nombre</option>
                    <option value="apellido" {{ request('sort')==='apellido' ? 'selected' :'' }}>Apellido</option>
                    <option value="created_at" {{ request('sort')==='created_at' ? 'selected' :'' }}>Fecha de registro
                    </option>
                </select>
                <div class="flex gap-2">
                    <label
                        class="flex-1 flex items-center gap-2 p-2.5 border rounded-lg cursor-pointer
                        {{ request('direction','asc') === 'asc' ? 'border-e-brand-light bg-blue-50 text-brand' : 'border-gray-200 text-gray-600' }}">
                        <input type="radio" name="direction" value="asc" class="sr-only" {{
                            request('direction','asc')==='asc' ? 'checked' : '' }}>
                        <i data-lucide="arrow-up-az" class="w-4 h-4"></i>
                        <span class="text-sm font-medium">Ascendente</span>
                    </label>
                    <label
                        class="flex-1 flex items-center gap-2 p-2.5 border rounded-lg cursor-pointer
                        {{ request('direction') === 'desc' ? 'border-e-brand-light bg-blue-50 text-brand' : 'border-gray-200 text-gray-600' }}">
                        <input type="radio" name="direction" value="desc" class="sr-only" {{
                            request('direction')==='desc' ? 'checked' : '' }}>
                        <i data-lucide="arrow-down-az" class="w-4 h-4"></i>
                        <span class="text-sm font-medium">Descendente</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="shrink-0 p-5 border-t border-gray-200 bg-gray-50 flex items-center justify-between gap-3">
            <a href="{{ route('personal.index') }}"
                class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                Limpiar
            </a>
            <div class="flex gap-2">
                <button type="button" onclick="toggleFiltros()"
                    class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancelar
                </button>
                <button type="submit"
                    class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-cyan-500 rounded-lg hover:bg-cyan-700">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    Aplicar
                </button>
            </div>
        </div>
    </form>
</div>

@include('personal.modals.delete-modal')

@push('scripts')
<script>
    lucide.createIcons();

    function toggleFiltros() {
        const panel   = document.getElementById('filtrosPanel');
        const overlay = document.getElementById('filtrosOverlay');
        const abierto = !panel.classList.contains('translate-x-full');
        panel.classList.toggle('translate-x-full', abierto);
        overlay.classList.toggle('hidden', abierto);
    }

    function abrirEliminar(cedula, nombre, tieneUsuario) {
        document.getElementById('deleteNombre').textContent = nombre;
        document.getElementById('deleteCedula').textContent = 'CI: ' + cedula;
        document.getElementById('deleteForm').action = '{{ url("personal") }}/' + cedula;

        const warning = document.getElementById('deleteWarning');
        const btn     = document.getElementById('deleteBtn');

        if (tieneUsuario) {
            warning.classList.remove('hidden');
            btn.disabled = true;
        } else {
            warning.classList.add('hidden');
            btn.disabled = false;
        }

        document.getElementById('deleteModal').classList.remove('hidden');
        lucide.createIcons();
    }

    document.querySelectorAll('input[name="direction"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('input[name="direction"]').forEach(r => {
                const label = r.closest('label');
                label.classList.remove('border-primary-300','bg-primary-50','text-primary-700');
                label.classList.add('border-gray-200','text-gray-600');
            });
            const label = this.closest('label');
            label.classList.add('border-primary-300','bg-primary-50','text-primary-700');
            label.classList.remove('border-gray-200','text-gray-600');
        });
    });
</script>
@endpush
@endsection