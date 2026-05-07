@extends('layouts.app')
@section('title', 'Gestión de Despachos')

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl rounded-lg bg-white/90 backdrop-blur-sm ">

    {{-- HEADER --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div>
            <h1 class="text-2xl font-bold text-purple-800 flex items-center gap-2">
                <div class="p-2 bg-purple-800 rounded text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-ambulance-icon lucide-ambulance">
                        <path d="M10 10H6" />
                        <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2" />
                        <path
                            d="M19 18h2a1 1 0 0 0 1-1v-3.28a1 1 0 0 0-.684-.948l-1.923-.641a1 1 0 0 1-.578-.502l-1.539-3.076A1 1 0 0 0 16.382 8H14" />
                        <path d="M8 8v4" />
                        <path d="M9 18h6" />
                        <circle cx="17" cy="18" r="2" />
                        <circle cx="7" cy="18" r="2" />
                    </svg>
                </div>
                Gestión de Despachos
            </h1>
            <p class="text-sm text-gray-600 font-semibold mt-1">Control de envíos de vacunas a módulos afiliados
            </p>
        </div>
    </div>

    {{-- ALERTAS --}}
    @if(session('success'))
    <div class="flex items-center gap-3 p-4 mb-5 text-green-800 bg-green-50 border border-green-200 rounded-lg">
        <i data-lucide="check-circle-2" class="w-5 h-5 shrink-0"></i>
        <span class="text-sm font-medium">{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="ml-auto"><i data-lucide="x" class="w-4 h-4"></i></button>
    </div>
    @endif

    @if(session('error_stock'))
    @php $es = session('error_stock'); @endphp
    <div class="flex items-start gap-3 p-4 mb-5 text-red-800 bg-red-50 border border-red-200 rounded-lg">
        <i data-lucide="alert-triangle" class="w-5 h-5 shrink-0 mt-0.5"></i>
        <div>
            <p class="font-semibold text-sm">Stock insuficiente — Operación cancelada</p>
            <p class="text-sm mt-0.5">
                <strong>{{ $es['vacuna'] }}</strong>:
                solicitaste <strong>{{ number_format($es['solicitado']) }}</strong> dosis,
                pero solo hay <strong>{{ number_format($es['disponible']) }}</strong> disponibles.
            </p>
        </div>
    </div>
    @endif

    {{-- TARJETAS RESUMEN --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Despachos</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $despachos->total() }}</p>
            </div>
            <div class="p-2.5 bg-blue-100 rounded-lg text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-package-check-icon lucide-package-check">
                    <path d="M12 22V12" />
                    <path d="m16 17 2 2 4-4" />
                    <path
                        d="M21 11.127V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.729l7 4a2 2 0 0 0 2 .001l1.32-.753" />
                    <path d="M3.29 7 12 12l8.71-5" />
                    <path d="m7.5 4.27 8.997 5.148" />
                </svg>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Dosis Despachadas</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($despachos->sum('cantidad')) }}</p>
            </div>
            <div class="p-2.5 bg-green-100 rounded-lg text-green-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-syringe-icon lucide-syringe">
                    <path d="m18 2 4 4" />
                    <path d="m17 7 3-3" />
                    <path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5" />
                    <path d="m9 11 4 4" />
                    <path d="m5 19-3 3" />
                    <path d="m14 4 6 6" />
                </svg>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Módulos Activos</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $modulos->where('total_registros', '>', 0)->count()
                    }}</p>
            </div>
            <div class="p-2.5 bg-purple-100 rounded-lg text-purple-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-hospital-icon lucide-hospital">
                    <path d="M12 7v4" />
                    <path d="M14 21v-3a2 2 0 0 0-4 0v3" />
                    <path d="M14 9h-4" />
                    <path d="M18 11h2a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-9a2 2 0 0 1 2-2h2" />
                    <path d="M18 21V5a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16" />
                </svg>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Este Mes</p>
                @php
                $esteMes = \App\Models\Despacho::whereMonth('fecha_envio', now()->month)
                ->whereYear('fecha_envio', now()->year)->sum('cantidad');
                @endphp
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($esteMes) }}</p>
            </div>
            <div class="p-2.5 bg-orange-100 rounded-lg text-orange-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-calendar-icon lucide-calendar">
                    <path d="M8 2v4" />
                    <path d="M16 2v4" />
                    <rect width="18" height="18" x="3" y="4" rx="2" />
                    <path d="M3 10h18" />
                </svg>
            </div>
        </div>
    </div>

    {{-- LAYOUT: Sidebar + Contenido --}}
    <div class="flex flex-col lg:flex-row gap-5">

        {{-- ===== SIDEBAR DE MÓDULOS ===== --}}
        <aside class="w-full lg:w-72 shrink-0">
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm sticky top-4">
                <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i data-lucide="building-2" class="w-4 h-4 text-primary-600"></i>
                        <h3 class="text-sm font-semibold text-gray-800">Módulos Afiliados</h3>
                    </div>
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full font-medium">{{
                        $modulos->count() }}</span>
                </div>

                <div class="p-2">
                    {{-- Opción "Todos" --}}
                    <a href="{{ route('despachos.index', request()->except('modulo_id')) }}"
                        class="flex items-center justify-between px-3 py-2.5 rounded-lg mb-1 text-sm transition-colors
                            {{ !request('modulo_id') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                        <div class="flex items-center gap-2">
                            <i data-lucide="layers" class="w-4 h-4"></i>
                            <span>Todos los módulos</span>
                        </div>
                        <span
                            class="text-xs {{ !request('modulo_id') ? 'bg-primary-100 text-primary-700' : 'bg-gray-100 text-gray-500' }} px-2 py-0.5 rounded-full font-medium">
                            {{ \App\Models\Despacho::count() }}
                        </span>
                    </a>

                    {{-- Lista de módulos --}}
                    @forelse($modulos as $modulo)
                    <a href="{{ route('despachos.index', array_merge(request()->except('modulo_id', 'page'), ['modulo_id' => $modulo->id])) }}"
                        class="flex flex-col px-3 py-3 rounded-lg mb-1 text-sm transition-colors
                            {{ request('modulo_id') == $modulo->id ? 'bg-primary-50 bg-gray-200 text-purple-800' : 'hover:bg-gray-50' }}">
                        <div class="flex items-center justify-between">
                            <span
                                class="font-medium {{ request('modulo_id') == $modulo->id ? 'text-primary-700' : 'text-gray-800' }} truncate">
                                {{ $modulo->nombre }}
                            </span>
                            <span
                                class="text-xs {{ request('modulo_id') == $modulo->id ? 'bg-primary-100 text-primary-700' : 'bg-gray-100 text-gray-500' }} px-2 py-0.5 rounded-full font-medium shrink-0 ml-2">
                                {{ $modulo->total_registros }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between mt-1.5 text-xs text-gray-400">
                            <span class="flex items-center gap-1">
                                <i data-lucide="syringe" class="w-3 h-3"></i>
                                {{ number_format($modulo->total_dosis) }} dosis
                            </span>
                            @if($modulo->ultimo_despacho)
                            <span class="flex items-center gap-1">
                                <i data-lucide="clock" class="w-3 h-3"></i>
                                {{ \Carbon\Carbon::parse($modulo->ultimo_despacho)->format('d/m/Y') }}
                            </span>
                            @else
                            <span class="italic text-gray-300">Sin despachos</span>
                            @endif
                        </div>
                    </a>
                    @empty
                    <div class="px-3 py-6 text-center text-xs text-gray-400">
                        <i data-lucide="building-x" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                        No hay módulos registrados
                    </div>
                    @endforelse
                </div>

                {{-- Reportes rápidos desde sidebar --}}
                <div class="p-3 border-t border-gray-100">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2 px-1">Reportes</p>
                    <button onclick="toggleReportesModal()"
                        class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-success hover:text-green-200 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-printer-icon lucide-printer">
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                            <path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6" />
                            <rect x="6" y="14" width="12" height="8" rx="1" />
                        </svg>
                        Generar reporte PDF
                    </button>
                </div>
            </div>
        </aside>

        {{-- ===== CONTENIDO PRINCIPAL ===== --}}
        <main class="flex-1 min-w-0">

            {{-- INFO DEL MÓDULO SELECCIONADO --}}
            @if($moduloSeleccionado)
            <div
                class="bg-linear-to-r from-purple-700 via-purple-600 to-purple-500 rounded-lg p-4 mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <i data-lucide="building-2" class="w-4 h-4 text-white"></i>
                        <span class="text-xs font-semibold text-white uppercase tracking-wide">Módulo
                            seleccionado</span>
                    </div>
                    <h2 class="text-lg font-bold text-purple-100">{{ $moduloSeleccionado->nombre }}</h2>
                    <p class="text-xs text-white mt-0.5">{{ $moduloSeleccionado->direccion }}</p>
                </div>
                <div class="flex items-center gap-3">
                    @if($moduloSeleccionado->telefono)
                    <span class="flex items-center gap-1 text-xs text-white">
                        <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                        {{ $moduloSeleccionado->telefono }}
                    </span>
                    @endif
                    <a href="{{ route('despachos.reporte.modulo', $moduloSeleccionado->id) }}"
                        class="flex items-center gap-1.5 p-2 bg-purple-900 rounded text-white text-xs hover:bg-purple-300 hover:text-purple-900 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-printer-icon lucide-printer">
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                            <path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6" />
                            <rect x="6" y="14" width="12" height="8" rx="1" />
                        </svg>
                    </a>
                </div>
            </div>
            @endif

            {{-- TARJETA CON TABLA --}}
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">

                {{-- TOOLBAR --}}
                <div
                    class="flex flex-col md:flex-row items-start md:items-center justify-between gap-3 p-4 border-b border-gray-200">

                    {{-- Búsqueda por vacuna --}}
                    <form method="GET" action="{{ route('despachos.index') }}"
                        class="flex items-center gap-2 w-full md:w-auto">
                        @foreach(request()->except(['vacuna', 'page']) as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        <div class="relative w-full md:w-64">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <input type="text" name="vacuna" value="{{ request('vacuna') }}"
                                placeholder="Buscar por vacuna..."
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2.5">
                        </div>
                        <button type="submit"
                            class="px-4 py-3 text-sm font-medium text-white bg-blue-400 rounded-lg hover:bg-blue-800 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-search-icon lucide-search">
                                <path d="m21 21-4.34-4.34" />
                                <circle cx="11" cy="11" r="8" />
                            </svg>
                        </button>
                        @if(request()->hasAny(['vacuna','responsable','fecha_desde','fecha_hasta','cantidad_min','cantidad_max']))
                        <a href="{{ route('despachos.index', request()->only('modulo_id')) }}"
                            class="flex items-center gap-1 px-3 py-2.5 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 shrink-0">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </a>
                        @endif
                    </form>

                    <div class="flex items-center gap-2 shrink-0">
                        {{-- Filtros --}}
                        <button type="button" onclick="toggleFiltrosDespacho()"
                            class="relative flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            <i data-lucide="sliders-horizontal" class="w-4 h-4"></i>
                            Filtros
                            @if(request()->hasAny(['responsable','fecha_desde','fecha_hasta','cantidad_min','cantidad_max']))
                            <span
                                class="absolute -top-1.5 -right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-primary-600 text-2xs font-bold text-white">✓</span>
                            @endif
                        </button>
                        {{-- Nuevo despacho --}}
                        <a href="{{ route('despachos.create') }}"
                            class="flex items-center gap-2 text-white bg-linear-to-r from-purple-500 via-purple-600 to-purple-700 hover:bg-linear-to-br focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-base text-sm px-4 py-2 text-center leading-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-circle-plus-icon lucide-circle-plus">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M8 12h8" />
                                <path d="M12 8v8" />
                            </svg>
                            Nuevo Registro
                        </a>
                    </div>
                </div>

                {{-- TAGS FILTROS ACTIVOS --}}
                @if(request()->hasAny(['responsable','fecha_desde','fecha_hasta','cantidad_min','cantidad_max']))
                <div class="flex flex-wrap items-center gap-2 px-4 py-2.5 bg-gray-50 border-b border-gray-200 text-xs">
                    <span class="font-medium text-gray-500 flex items-center gap-1">
                        <i data-lucide="filter" class="w-3 h-3"></i> Filtros:
                    </span>
                    @if(request('responsable'))
                    <span
                        class="inline-flex items-center gap-1 pl-2.5 pr-1 py-1 rounded-full bg-blue-100 text-blue-700 font-medium">
                        Responsable: {{ request('responsable') }}
                        <a href="{{ route('despachos.index', request()->except('responsable')) }}"
                            class="hover:bg-blue-200 rounded-full p-0.5"><i data-lucide="x" class="w-3 h-3"></i></a>
                    </span>
                    @endif
                    @if(request('fecha_desde') || request('fecha_hasta'))
                    <span
                        class="inline-flex items-center gap-1 pl-2.5 pr-1 py-1 rounded-full bg-green-100 text-green-700 font-medium">
                        Fecha: {{ request('fecha_desde','...') }} → {{ request('fecha_hasta','...') }}
                        <a href="{{ route('despachos.index', request()->except(['fecha_desde','fecha_hasta'])) }}"
                            class="hover:bg-green-200 rounded-full p-0.5"><i data-lucide="x" class="w-3 h-3"></i></a>
                    </span>
                    @endif
                </div>
                @endif

                {{-- TABLA --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 w-10">#</th>
                                <th class="px-4 py-3">Vacuna</th>
                                <th class="px-4 py-3">Módulo Destino</th>
                                <th class="px-4 py-3">Fecha Envío</th>
                                <th class="px-4 py-3">Responsable</th>
                                <th class="px-4 py-3 text-center">Cantidad</th>
                                <th class="px-4 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($despachos as $despacho)
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="px-4 py-3 text-gray-400 text-xs">{{ $i + $loop->iteration }}</td>

                                <td class="px-4 py-3">
                                    <p class="font-semibold text-gray-900">{{ $despacho->vacuna?->nombre ?? '—' }}</p>
                                </td>

                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium bg-purple-50 text-purple-700 rounded-full">
                                        <i data-lucide="building" class="w-3 h-3"></i>
                                        {{ $despacho->modulo?->nombre ?? '—' }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5">
                                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-gray-400"></i>
                                        {{ \Carbon\Carbon::parse($despacho->fecha_envio)->format('d/m/Y') }}
                                    </div>
                                </td>

                                <td class="px-4 py-3">
                                    @if($despacho->responsable)
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-7 h-7 rounded-full bg-purple-100 flex items-center justify-center shrink-0">
                                            <span class="text-xs font-bold text-purple-700">
                                                {{ strtoupper(substr($despacho->responsable->nombre, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium text-gray-900">
                                                {{ $despacho->responsable->nombre }} {{ $despacho->responsable->apellido
                                                }}
                                            </p>
                                            <p class="text-xs text-gray-400">CI: {{ $despacho->responsable_envio }}</p>
                                        </div>
                                    </div>
                                    @else
                                    <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <span class="font-bold text-gray-900">{{ number_format($despacho->cantidad)
                                        }}</span>
                                    <span class="text-xs text-gray-400 block">unidades</span>
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex justify-end">
                                        <button id="dd-btn-{{ $despacho->id }}"
                                            data-dropdown-toggle="dd-{{ $despacho->id }}" data-dropdown-placement="left"
                                            class="inline-flex items-center p-1.5 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg">
                                            <i data-lucide="more-horizontal" class="w-5 h-5"></i>
                                        </button>
                                        <div id="dd-{{ $despacho->id }}"
                                            class="hidden z-20 w-48 bg-white rounded-lg shadow-lg border border-gray-100 text-sm text-gray-700">
                                            <ul class="py-1">
                                                <li>
                                                    <a href="{{ route('despachos.show', $despacho->id) }}"
                                                        class="flex items-center gap-2.5 px-4 py-2.5 hover:bg-gray-50">
                                                        <i data-lucide="eye" class="w-4 h-4 text-blue-500"></i>
                                                        Detalles
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('despachos.edit', $despacho->id) }}"
                                                        class="flex items-center gap-2.5 px-4 py-2.5 hover:bg-gray-50">
                                                        <i data-lucide="pencil" class="w-4 h-4 text-yellow-500"></i>
                                                        Editar
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('despachos.reporte.modulo', $despacho->modulo_id) }}"
                                                        class="flex items-center gap-2.5 px-4 py-2.5 hover:bg-gray-50">
                                                        <i data-lucide="file-down" class="w-4 h-4 text-green-500"></i>
                                                        Reporte (PDF)
                                                    </a>
                                                </li>
                                                <li class="border-t border-gray-100 mt-1">
                                                    <button type="button"
                                                        onclick="abrirEliminarDespacho({{ $despacho->id }}, '{{ addslashes($despacho->vacuna?->nombre ?? '') }}', '{{ addslashes($despacho->modulo?->nombre ?? '') }}')"
                                                        class="flex items-center gap-2.5 w-full px-4 py-2.5 hover:bg-danger text-danger hover:text-white rounded">
                                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                        Eliminar
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-4 py-20 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <i data-lucide="send" class="w-12 h-12 text-gray-300"></i>
                                        <div>
                                            <p class="font-semibold text-gray-500">No se encontraron despachos</p>
                                            <p class="text-sm mt-1">
                                                @if(request()->hasAny(['vacuna','modulo_id','responsable','fecha_desde','fecha_hasta']))
                                                Intenta ajustar los filtros.
                                                @else
                                                <a href="{{ route('despachos.create') }}"
                                                    class="text-primary-600 hover:underline">Registra el primer
                                                    despacho</a>
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

                {{-- PAGINACIÓN --}}
                @if($despachos->hasPages())
                <div
                    class="flex flex-col sm:flex-row justify-between items-center gap-3 px-4 py-3 border-t border-gray-200">
                    <p class="text-sm text-gray-500">
                        Mostrando <span class="font-semibold text-gray-900">{{ $despachos->firstItem() }}</span>–<span
                            class="font-semibold text-gray-900">{{ $despachos->lastItem() }}</span>
                        de <span class="font-semibold text-gray-900">{{ $despachos->total() }}</span>
                    </p>
                    <nav>
                        <ul class="inline-flex items-center -space-x-px text-sm h-8">
                            <li>
                                @if($despachos->onFirstPage())
                                <span
                                    class="flex items-center justify-center h-8 px-3 text-gray-300 bg-white border border-gray-300 rounded-l-lg cursor-not-allowed">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </span>
                                @else
                                <a href="{{ $despachos->withQueryString()->previousPageUrl() }}"
                                    class="flex items-center justify-center h-8 px-3 text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </a>
                                @endif
                            </li>
                            @foreach($despachos->withQueryString()->getUrlRange(1, $despachos->lastPage()) as $page =>
                            $url)
                            <li>
                                @if($page == $despachos->currentPage())
                                <span
                                    class="flex items-center justify-center h-8 px-3 text-primary-600 border border-primary-300 bg-primary-50 font-medium">{{
                                    $page }}</span>
                                @elseif(abs($page - $despachos->currentPage()) <= 2 || $page==1 || $page==$despachos->
                                    lastPage())
                                    <a href="{{ $url }}"
                                        class="flex items-center justify-center h-8 px-3 text-gray-500 bg-white border border-gray-300 hover:bg-gray-100">{{
                                        $page }}</a>
                                    @elseif(abs($page - $despachos->currentPage()) == 3)
                                    <span
                                        class="flex items-center justify-center h-8 px-3 text-gray-500 bg-white border border-gray-300">…</span>
                                    @endif
                            </li>
                            @endforeach
                            <li>
                                @if($despachos->hasMorePages())
                                <a href="{{ $despachos->withQueryString()->nextPageUrl() }}"
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
        </main>{{-- /contenido principal --}}
    </div>
</div>

{{-- ===== PANEL FILTROS ===== --}}
<div id="filtrosDespachoOverlay" onclick="toggleFiltrosDespacho()" class="hidden fixed inset-0 z-40 bg-gray-900/40">
</div>

<div id="filtrosDespachoPanel"
    class="fixed top-0 right-0 z-50 h-full w-full max-w-sm bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col">
    <div class="flex items-center justify-between p-5 border-b border-gray-200 shrink-0">
        <div class="flex items-center gap-2">
            <i data-lucide="sliders-horizontal" class="w-5 h-5 text-primary-600"></i>
            <h3 class="text-base font-semibold text-gray-900">Filtrar Despachos</h3>
        </div>
        <button onclick="toggleFiltrosDespacho()" class="text-gray-400 hover:bg-gray-100 rounded-lg p-1.5">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
    </div>

    <form method="GET" action="{{ route('despachos.index') }}" class="flex flex-col flex-1 overflow-hidden">
        @if(request('modulo_id'))<input type="hidden" name="modulo_id" value="{{ request('modulo_id') }}">@endif
        @if(request('vacuna'))<input type="hidden" name="vacuna" value="{{ request('vacuna') }}">@endif

        <div class="flex-1 overflow-y-auto p-5 space-y-5">

            <div>
                <label class="block mb-1.5 text-sm font-medium text-gray-700 flex items-center gap-1.5">
                    <i data-lucide="user" class="w-3.5 h-3.5 text-gray-400"></i>
                    Responsable
                </label>
                <input type="text" name="responsable" value="{{ request('responsable') }}"
                    placeholder="Nombre, apellido o cédula..."
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
            </div>

            <div class="border-t border-gray-100 pt-1">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3 flex items-center gap-1.5">
                    <i data-lucide="calendar" class="w-3 h-3"></i> Fecha de Envío
                </p>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block mb-1 text-xs text-gray-500">Desde</label>
                        <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2">
                    </div>
                    <div>
                        <label class="block mb-1 text-xs text-gray-500">Hasta</label>
                        <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2">
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-1">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3 flex items-center gap-1.5">
                    <i data-lucide="boxes" class="w-3 h-3"></i> Rango de Cantidad
                </p>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block mb-1 text-xs text-gray-500">Mínimo</label>
                        <input type="number" name="cantidad_min" value="{{ request('cantidad_min') }}" min="0"
                            placeholder="0"
                            class="bg-gray-50 border border-gray-300 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2">
                    </div>
                    <div>
                        <label class="block mb-1 text-xs text-gray-500">Máximo</label>
                        <input type="number" name="cantidad_max" value="{{ request('cantidad_max') }}" min="0"
                            placeholder="..."
                            class="bg-gray-50 border border-gray-300 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2">
                    </div>
                </div>
            </div>
        </div>

        <div class="shrink-0 p-5 border-t border-gray-200 bg-gray-50 flex items-center justify-between gap-3">
            <a href="{{ route('despachos.index', request()->only('modulo_id')) }}"
                class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                Limpiar
            </a>
            <div class="flex gap-2">
                <button type="button" onclick="toggleFiltrosDespacho()"
                    class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50">
                    Cancelar
                </button>
                <button type="submit"
                    class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-blue-400 rounded hover:bg-blue-800">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    Aplicar
                </button>
            </div>
        </div>
    </form>
</div>

@include('despacho.modals.report-modal')
@include('despacho.modals.delete-modal')

@push('scripts')
<script src="{{ asset('js/despacho-scripts.js') }}"></script>
@endpush
@endsection