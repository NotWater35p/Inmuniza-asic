@extends('layouts.app')
@section('title', 'Vacunas')

@section('content')
@php
    $nivelUsuario = auth()->user()?->personal?->cargo?->nivel_acceso ?? 0;
    $esAdmin      = $nivelUsuario >= 5;
    $puedeEditar  = $nivelUsuario >= 3; // nivel 3 (asistente) y 5 (admin)
@endphp
<div class="px-4 py-6 mx-auto max-w-7xl bg-white/90 backdrop-blur-sm rounded-lg shadow-sm">

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div>
            <h1 class="text-2xl font-bold text-success flex items-center gap-2">
                <div class="p-2 bg-success rounded text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m18 2 4 4" />
                        <path d="m17 7 3-3" />
                        <path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5" />
                        <path d="m9 11 4 4" />
                        <path d="m5 19-3 3" />
                        <path d="m14 4 6 6" />
                    </svg>
                </div>
                Vacunas Registradas
            </h1>
        </div>
        @if($puedeEditar)
        <a href="{{ route('vacunas.create') }}"
            class="inline-flex items-center gap-2 text-white bg-linear-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-linear-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-base text-sm px-4 py-2.5 text-center leading-5">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-circle-plus-icon lucide-circle-plus">
                <circle cx="12" cy="12" r="10" />
                <path d="M8 12h8" />
                <path d="M12 8v8" />
            </svg>
            Nueva Vacuna
        </a>
        @endif
    </div>

    {{-- Alertas --}}
    @if(session('success'))
    <div class="flex items-center gap-3 p-4 mb-5 text-green-800 bg-green-50 border border-green-200 rounded-lg">
        <i data-lucide="check-circle-2" class="w-5 h-5 shrink-0"></i>
        <span class="text-sm font-medium">{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="ml-auto"><i data-lucide="x" class="w-4 h-4"></i></button>
    </div>
    @endif

    {{-- Layout Sidebar + Tabla --}}
    <div class="flex flex-col lg:flex-row gap-5">

        {{-- ===== CONTENIDO PRINCIPAL ===== --}}
        <div class="flex-1 min-w-0">
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">

                {{-- Toolbar --}}
                <div
                    class="flex flex-col md:flex-row items-start md:items-center justify-between gap-3 p-4 border-b border-gray-200">

                    {{-- Búsqueda --}}
                    <form method="GET" action="{{ route('vacunas.index') }}"
                        class="flex items-center gap-2 w-full md:w-auto">
                        @foreach(request()->except(['search', 'page']) as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        <div class="relative w-full md:w-64">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Buscar vacuna, enfermedad..."
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5">
                        </div>
                        <button type="submit"
                            class="px-3 py-3 text-sm font-medium text-white bg-cyan-500 rounded-lg hover:bg-cyan-700 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-search-icon lucide-search">
                                <path d="m21 21-4.34-4.34" />
                                <circle cx="11" cy="11" r="8" />
                            </svg>
                        </button>
                        @if(request()->hasAny(['search', 'marca_id']))
                        <a href="{{ route('vacunas.index') }}"
                            class="flex items-center gap-1 px-3 py-2.5 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 shrink-0">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </a>
                        @endif
                    </form>

                    <div class="flex items-center gap-2 shrink-0">
                        {{-- Botón filtros --}}
                        <button type="button" onclick="toggleFiltros()"
                            class="relative flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-funnel-icon lucide-funnel">
                                <path
                                    d="M10 20a1 1 0 0 0 .553.895l2 1A1 1 0 0 0 14 21v-7a2 2 0 0 1 .517-1.341L21.74 4.67A1 1 0 0 0 21 3H3a1 1 0 0 0-.742 1.67l7.225 7.989A2 2 0 0 1 10 14z" />
                            </svg>
                            Filtros
                            @if(request()->hasAny(['search', 'marca_id']))
                            <span
                                class="absolute -top-1.5 -right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-blue-600 text-2xs font-bold text-white">✓</span>
                            @endif
                        </button>
                    </div>
                </div>

                {{-- Tag de filtro activo --}}
                @if(request('search'))
                <div class="flex flex-wrap items-center gap-2 px-4 py-2.5 bg-gray-50 border-b border-gray-200 text-xs">
                    <span class="font-medium text-gray-500 flex items-center gap-1">
                        <i data-lucide="filter" class="w-3 h-3"></i> Filtros activos:
                    </span>
                    <span
                        class="inline-flex items-center gap-1 pl-2.5 pr-1 py-1 rounded-full bg-blue-100 text-blue-700 font-medium">
                        Búsqueda: "{{ request('search') }}"
                        <a href="{{ route('vacunas.index', request()->except('search')) }}"
                            class="hover:bg-blue-200 rounded-full p-0.5">
                            <i data-lucide="x" class="w-3 h-3"></i>
                        </a>
                    </span>
                </div>
                @endif

                {{-- Tabla con headers ordenables --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 w-10">#</th>

                                {{-- Columna ordenable: Nombre --}}
                                <th class="px-4 py-3">
                                    <a href="{{ route('vacunas.index', array_merge(request()->query(), ['sort' => 'nombre', 'direction' => ($sort === 'nombre' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                                        class="flex items-center gap-1.5 group text-gray-700 hover:text-gray-900">
                                        Nombre
                                        <span class="flex flex-col">
                                            <i data-lucide="chevron-up"
                                                class="w-2.5 h-2.5 {{ $sort === 'nombre' && $direction === 'asc' ? 'text-blue-600' : 'text-gray-300 group-hover:text-gray-400' }}"></i>
                                            <i data-lucide="chevron-down"
                                                class="w-2.5 h-2.5 -mt-1 {{ $sort === 'nombre' && $direction === 'desc' ? 'text-blue-600' : 'text-gray-300 group-hover:text-gray-400' }}"></i>
                                        </span>
                                    </a>
                                </th>

                                {{-- Marca --}}
                                <th class="px-4 py-3 text-gray-700">Marca</th>
                                {{-- Tipo --}}
                                <th class="px-4 py-3 hidden sm:table-cell text-gray-700">Tipo</th>

                                {{-- Enfermedad ordenable --}}
                                <th class="px-4 py-3 hidden md:table-cell">
                                    <a href="{{ route('vacunas.index', array_merge(request()->query(), ['sort' => 'enfermedad', 'direction' => ($sort === 'enfermedad' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                                        class="flex items-center gap-1.5 group text-gray-700 hover:text-gray-900">
                                        Enfermedad
                                        <span class="flex flex-col">
                                            <i data-lucide="chevron-up"
                                                class="w-2.5 h-2.5 {{ $sort === 'enfermedad' && $direction === 'asc' ? 'text-blue-600' : 'text-gray-300 group-hover:text-gray-400' }}"></i>
                                            <i data-lucide="chevron-down"
                                                class="w-2.5 h-2.5 -mt-1 {{ $sort === 'enfermedad' && $direction === 'desc' ? 'text-blue-600' : 'text-gray-300 group-hover:text-gray-400' }}"></i>
                                        </span>
                                    </a>
                                </th>

                                {{-- Presentación --}}
                                <th class="px-4 py-3 hidden lg:table-cell">
                                    <a href="{{ route('vacunas.index', array_merge(request()->query(), ['sort' => 'presentacion', 'direction' => ($sort === 'presentacion' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                                        class="flex items-center gap-1.5 group text-gray-700 hover:text-gray-900">
                                        Presentación
                                        <span class="flex flex-col">
                                            <i data-lucide="chevron-up"
                                                class="w-2.5 h-2.5 {{ $sort === 'presentacion' && $direction === 'asc' ? 'text-blue-600' : 'text-gray-300 group-hover:text-gray-400' }}"></i>
                                            <i data-lucide="chevron-down"
                                                class="w-2.5 h-2.5 -mt-1 {{ $sort === 'presentacion' && $direction === 'desc' ? 'text-blue-600' : 'text-gray-300 group-hover:text-gray-400' }}"></i>
                                        </span>
                                    </a>
                                </th>

                                {{-- Dosis ordenable --}}
                                <th class="px-4 py-3 hidden lg:table-cell">
                                    <a href="{{ route('vacunas.index', array_merge(request()->query(), ['sort' => 'numero_dosis', 'direction' => ($sort === 'numero_dosis' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                                        class="flex items-center gap-1.5 group text-gray-700 hover:text-gray-900">
                                        Dosis
                                        <span class="flex flex-col">
                                            <i data-lucide="chevron-up"
                                                class="w-2.5 h-2.5 {{ $sort === 'numero_dosis' && $direction === 'asc' ? 'text-blue-600' : 'text-gray-300 group-hover:text-gray-400' }}"></i>
                                            <i data-lucide="chevron-down"
                                                class="w-2.5 h-2.5 -mt-1 {{ $sort === 'numero_dosis' && $direction === 'desc' ? 'text-blue-600' : 'text-gray-300 group-hover:text-gray-400' }}"></i>
                                        </span>
                                    </a>
                                </th>

                                <th class="px-4 py-3 text-right text-gray-700">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($vacunas as $vacuna)
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="px-4 py-3 text-gray-400 text-xs">{{ $i + $loop->iteration }}</td>

                                <td class="px-4 py-3">
                                    <p class="font-semibold text-gray-900">{{ $vacuna->nombre }}</p>
                                </td>

                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-green-100 text-fg-success-strong">
                                        {{ $vacuna->marca?->nombre ?? '—' }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 hidden sm:table-cell">
                                    @php
                                    $tipoCfg = match($vacuna->tipo ?? 'vacuna') {
                                    'suero' => ['bg-amber-100 text-amber-700', 'test-tube-2', 'Suero'],
                                    'insumo' => ['bg-gray-100 text-gray-600', 'package', 'Insumo'],
                                    default => ['bg-blue-100 text-blue-700', 'syringe', 'Vacuna'],
                                    };
                                    @endphp
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium {{ $tipoCfg[0] }}">
                                        <i data-lucide="{{ $tipoCfg[1] }}" class="w-3 h-3"></i>
                                        {{ $tipoCfg[2] }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 hidden md:table-cell text-gray-600 text-xs">
                                    {{ $vacuna->enfermedad ?? '—' }}
                                </td>

                                <td class="px-4 py-3 hidden lg:table-cell text-gray-600 text-xs">
                                    {{ $vacuna->presentacion ?? '—' }}
                                </td>

                                <td class="px-4 py-3 hidden lg:table-cell text-center">
                                    @if($vacuna->numero_dosis)
                                    <span
                                        class="inline-flex items-center justify-center w-7 h-7 text-xs font-bold text-green-700 bg-gray-100 rounded-full">
                                        {{ $vacuna->numero_dosis }}
                                    </span>
                                    @else
                                    <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex justify-end items-center gap-1.5">
                                        <a href="{{ route('vacunas.show', $vacuna->id) }}"
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
                                        @if($puedeEditar)
                                        <a href="{{ route('vacunas.edit', $vacuna->id) }}"
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
                                        @endif
                                        <a href="{{ route('vacunas.pdf', $vacuna->id) }}"
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
                                        @if($esAdmin)
                                        <button type="button"
                                            onclick="abrirEliminar({{ $vacuna->id }}, '{{ addslashes($vacuna->nombre) }}')"
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
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-4 py-20 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-300"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path d="m18 2 4 4" />
                                            <path d="m17 7 3-3" />
                                            <path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5" />
                                            <path d="m9 11 4 4" />
                                            <path d="m5 19-3 3" />
                                            <path d="m14 4 6 6" />
                                        </svg>
                                        <div>
                                            <p class="font-semibold text-gray-500">No se encontraron vacunas</p>
                                            <p class="text-sm mt-1">
                                                @if(request()->hasAny(['search', 'marca_id']))
                                                Ajusta los filtros de búsqueda.
                                                @else
                                                <a href="{{ route('vacunas.create') }}"
                                                    class="text-blue-600 hover:underline">Registra la primera vacuna</a>
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
                @if($vacunas->hasPages())
                <div
                    class="flex flex-col sm:flex-row justify-between items-center gap-3 px-4 py-3 border-t border-gray-200">
                    <p class="text-sm text-gray-500">
                        Mostrando <span class="font-semibold text-gray-900">{{ $vacunas->firstItem() }}</span>–<span
                            class="font-semibold text-gray-900">{{ $vacunas->lastItem() }}</span>
                        de <span class="font-semibold text-gray-900">{{ $vacunas->total() }}</span>
                    </p>
                    <nav>
                        <ul class="inline-flex items-center -space-x-px text-sm h-8">
                            <li>
                                @if($vacunas->onFirstPage())
                                <span
                                    class="flex items-center justify-center h-8 px-3 text-gray-300 bg-white border border-gray-300 rounded-l-lg cursor-not-allowed">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </span>
                                @else
                                <a href="{{ $vacunas->withQueryString()->previousPageUrl() }}"
                                    class="flex items-center justify-center h-8 px-3 text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </a>
                                @endif
                            </li>
                            @foreach($vacunas->withQueryString()->getUrlRange(1, $vacunas->lastPage()) as $page => $url)
                            <li>
                                @if($page == $vacunas->currentPage())
                                <span
                                    class="flex items-center justify-center h-8 px-3 text-blue-600 border border-blue-300 bg-blue-50 font-medium">{{
                                    $page }}</span>
                                @elseif(abs($page - $vacunas->currentPage()) <= 2 || $page==1 || $page==$vacunas->
                                    lastPage())
                                    <a href="{{ $url }}"
                                        class="flex items-center justify-center h-8 px-3 text-gray-500 bg-white border border-gray-300 hover:bg-gray-100">{{
                                        $page }}</a>
                                    @elseif(abs($page - $vacunas->currentPage()) == 3)
                                    <span
                                        class="flex items-center justify-center h-8 px-3 text-gray-500 bg-white border border-gray-300">…</span>
                                    @endif
                            </li>
                            @endforeach
                            <li>
                                @if($vacunas->hasMorePages())
                                <a href="{{ $vacunas->withQueryString()->nextPageUrl() }}"
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
        </div>{{-- /contenido --}}
    </div>
</div>

{{-- ===== PANEL LATERAL FILTROS ===== --}}
<div id="filtrosOverlay" onclick="toggleFiltros()" class="hidden fixed inset-0 z-40 bg-gray-900/40"></div>

<div id="filtrosPanel"
    class="fixed top-0 right-0 z-50 h-full w-full max-w-sm bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col">

    <div class="flex items-center justify-between p-5 border-b border-gray-200 shrink-0">
        <div class="flex items-center gap-2">
            <i data-lucide="sliders-horizontal" class="w-5 h-5 text-blue-600"></i>
            <h3 class="text-base font-semibold text-gray-900">Filtrar Vacunas</h3>
        </div>
        <button onclick="toggleFiltros()" class="text-gray-400 hover:bg-gray-100 rounded-lg p-1.5">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
    </div>

    <form method="GET" action="{{ route('vacunas.index') }}" class="flex flex-col flex-1 overflow-hidden">
        @if(request('marca_id'))<input type="hidden" name="marca_id" value="{{ request('marca_id') }}">@endif
        @if(request('sort'))<input type="hidden" name="sort" value="{{ request('sort') }}">@endif
        @if(request('direction'))<input type="hidden" name="direction" value="{{ request('direction') }}">@endif

        <div class="flex-1 overflow-y-auto p-5 space-y-5">

            <div>
                <label class="block mb-1.5 text-sm font-medium text-gray-700 flex items-center gap-1.5">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-gray-400"></i>
                    Búsqueda general
                </label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Nombre, enfermedad, presentación..."
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>

            <div class="border-t border-gray-100 pt-4">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3 flex items-center gap-1.5">
                    <i data-lucide="arrow-up-down" class="w-3 h-3"></i>
                    Ordenar por
                </p>
                <select name="sort"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mb-3">
                    <option value="nombre" {{ request('sort','nombre')==='nombre' ? 'selected' :'' }}>Nombre</option>
                    <option value="enfermedad" {{ request('sort')==='enfermedad' ? 'selected' :'' }}>Enfermedad</option>
                    <option value="presentacion" {{ request('sort')==='presentacion' ? 'selected' :'' }}>Presentación
                    </option>
                    <option value="numero_dosis" {{ request('sort')==='numero_dosis' ? 'selected' :'' }}>Nº de dosis
                    </option>
                    <option value="created_at" {{ request('sort')==='created_at' ? 'selected' :'' }}>Fecha registro
                    </option>
                </select>
                <div class="flex gap-2">
                    <label
                        class="flex-1 flex items-center gap-2 p-2.5 border rounded-lg cursor-pointer
                        {{ request('direction','asc') === 'asc' ? 'border-blue-300 bg-blue-50 text-blue-700' : 'border-gray-200 text-gray-600' }}">
                        <input type="radio" name="direction" value="asc" class="sr-only" {{
                            request('direction','asc')==='asc' ? 'checked' : '' }}>
                        <i data-lucide="arrow-up-az" class="w-4 h-4"></i>
                        <span class="text-sm font-medium">Ascendente</span>
                    </label>
                    <label
                        class="flex-1 flex items-center gap-2 p-2.5 border rounded-lg cursor-pointer
                        {{ request('direction') === 'desc' ? 'border-blue-300 bg-blue-50 text-blue-700' : 'border-gray-200 text-gray-600' }}">
                        <input type="radio" name="direction" value="desc" class="sr-only" {{
                            request('direction')==='desc' ? 'checked' : '' }}>
                        <i data-lucide="arrow-down-az" class="w-4 h-4"></i>
                        <span class="text-sm font-medium">Descendente</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="shrink-0 p-5 border-t border-gray-200 bg-gray-50 flex items-center justify-between gap-3">
            <a href="{{ route('vacunas.index') }}"
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
                    class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    Aplicar
                </button>
            </div>
        </div>
    </form>
</div>

@include('vacuna.modals.delete-modals')

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

    function abrirEliminar(id, nombre) {
        document.getElementById('deleteNombre').textContent = nombre;
        document.getElementById('deleteForm').action = '{{ url("vacunas") }}/' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    // Radio buttons del panel de filtros — highlight visual
    document.querySelectorAll('input[name="direction"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('input[name="direction"]').forEach(r => {
                const label = r.closest('label');
                label.classList.remove('border-blue-300','bg-blue-50','text-blue-700');
                label.classList.add('border-gray-200','text-gray-600');
            });
            const label = this.closest('label');
            label.classList.add('border-blue-300','bg-blue-50','text-blue-700');
            label.classList.remove('border-gray-200','text-gray-600');
        });
    });
</script>
@endpush
@endsection