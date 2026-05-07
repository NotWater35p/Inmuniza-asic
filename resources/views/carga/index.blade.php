@extends('layouts.app')
@section('title', 'Gestión de Cargas')

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl bg-white/90 rounded-lg shadow-sm backdrop-blur-sm">

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div>
            <h1 class="text-2xl font-bold text-teal-800 flex items-center gap-2">
                <div class="p-2 bg-teal-800 rounded text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-package-plus-icon lucide-package-plus">
                        <path d="M12 22V12" />
                        <path d="M16 17h6" />
                        <path d="M19 14v6" />
                        <path
                            d="M21 10.535V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.729l7 4a2 2 0 0 0 2 .001l1.675-.955" />
                        <path d="M3.29 7 12 12l8.71-5" />
                        <path d="m7.5 4.27 8.997 5.148" />
                    </svg>
                </div>
                Gestión de Cargas
            </h1>
            <p class="text-sm text-gray-600 font-semibold mt-1">Registro de ingresos de vacunas al ASIC</p>
        </div>
    </div>

    {{-- Alertas --}}
    @if (session('success'))
    <div class="flex items-center gap-3 p-4 mb-5 text-green-800 bg-green-50 border border-green-200 rounded-lg">
        <i data-lucide="check-circle-2" class="w-5 h-5 shrink-0"></i>
        <span class="text-sm font-medium">{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="ml-auto"><i data-lucide="x" class="w-4 h-4"></i></button>
    </div>
    @endif

    {{-- Tarjetas resumen --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Cargas</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $cargas->total() }}</p>
            </div>
            <div class="p-3 bg-blue-100 rounded-lg text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-inbox-icon lucide-inbox">
                    <polyline points="22 12 16 12 14 15 10 15 8 12 2 12" />
                    <path
                        d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z" />
                </svg>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Dosis en esta página</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($cargas->sum('cantidad')) }}</p>
            </div>
            <div class="p-3 bg-green-100 rounded-lg text-green-600">
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
            @php
            $proxVencer = \App\Models\Carga::whereDate('fecha_vencimiento', '>=', now())
            ->whereDate('fecha_vencimiento', '<=', now()->addDays(30))
                ->count();
                @endphp
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Próx. a Vencer (30d)</p>
                    <p class="text-3xl font-bold mt-1 {{ $proxVencer > 0 ? 'text-orange-600' : 'text-gray-900' }}">
                        {{ $proxVencer }}</p>
                </div>
                <div class="p-3 {{ $proxVencer > 0 ? 'bg-orange-100' : 'bg-gray-100' }} rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-clipboard-clock-icon lucide-clipboard-clock {{ $proxVencer > 0 ? 'text-orange-500' : 'text-gray-500' }}">
                        <path d="M16 14v2.2l1.6 1" />
                        <path d="M16 4h2a2 2 0 0 1 2 2v.832" />
                        <path d="M8 4H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h2" />
                        <circle cx="16" cy="16" r="6" />
                        <rect x="8" y="2" width="8" height="4" rx="1" />
                    </svg>
                </div>
        </div>
    </div>



    {{-- Tarjeta principal --}}
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">

        {{-- Toolbar --}}
        <div
            class="flex flex-col md:flex-row items-start md:items-center justify-between gap-2 p-4 border-b border-gray-200 sm:grid-cols-3">

            <form method="GET" action="{{ route('cargas.index') }}" class="flex items-center gap-2 w-full md:w-auto">
                @foreach (request()->except(['vacuna', 'page']) as $k => $v)
                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach
                <div class="relative w-full md:w-72">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    <input type="text" name="vacuna" value="{{ request('vacuna') }}"
                        placeholder="Buscar por nombre de vacuna..."
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2.5">
                </div>
                <button type="submit"
                    class="px-4 py-3 text-sm font-medium text-white bg-blue-400 rounded-lg hover:bg-blue-800 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-search-icon lucide-search">
                        <path d="m21 21-4.34-4.34" />
                        <circle cx="11" cy="11" r="8" />
                    </svg>
                </button>
                @if (request()->hasAny([
                'vacuna',
                'lote',
                'fecha_llegada_desde',
                'fecha_llegada_hasta',
                'fecha_vencimiento_desde',
                'fecha_vencimiento_hasta',
                'cantidad_min',
                'cantidad_max',
                'proximos_vencer',
                ]))
                <a href="{{ route('cargas.index') }}"
                    class="flex items-center gap-1 px-3 py-2.5 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 shrink-0">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </a>
                @endif
            </form>

            {{-- Chips rápidos --}}
            <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                <a href="{{ route('cargas.index', ['fecha_vencimiento_hasta' => now()->format('Y-m-d')]) }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium rounded-lg border transition-colors
                bg-white border-gray-200 text-gray-600 hover:border-red-300 hover:text-red-600">
                    <i data-lucide="alert-circle" class="w-3 h-3"></i> Vencidas
                </a>
                <a href="{{ route('cargas.index', ['fecha_llegada_desde' => now()->startOfMonth()->format('Y-m-d'), 'fecha_llegada_hasta' => now()->endOfMonth()->format('Y-m-d')]) }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium rounded-lg border transition-colors
                bg-white border-gray-200 text-gray-600 hover:border-blue-300 hover:text-blue-600">
                    <i data-lucide="calendar" class="w-3 h-3"></i> Este mes
                </a>
            </div>

            <div class="flex items-center gap-2 shrink-0">

                <button type="button" onclick="toggleFiltros()"
                    class="relative flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    <i data-lucide="sliders-horizontal" class="w-4 h-4"></i>
                    Filtros
                    @if (request()->hasAny([
                    'lote',
                    'fecha_llegada_desde',
                    'fecha_llegada_hasta',
                    'fecha_vencimiento_desde',
                    'fecha_vencimiento_hasta',
                    'cantidad_min',
                    'cantidad_max',
                    'proximos_vencer',
                    ]))
                    <span
                        class="absolute -top-1.5 -right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">✓</span>
                    @endif
                </button>

                {{-- Dropdown reportes --}}
                <div class="relative">
                    <button id="reportesBtn" onclick="toggleReportes()"
                        class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                        Reportes
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </button>
                    <div id="reportesMenu"
                        class="hidden absolute right-0 z-20 mt-1 w-56 bg-white rounded-lg shadow-lg border border-gray-100 py-1">
                        <a href="{{ route('cargas.reporte.general', request()->query()) }}"
                            class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                            <i data-lucide="download" class="w-4 h-4 text-primary-600"></i>
                            Reporte general PDF
                        </a>
                        <a href="{{ route('cargas.reporte.general', array_merge(request()->query(), ['proximos_vencer' => 30])) }}"
                            class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                            <i data-lucide="alert-triangle" class="w-4 h-4 text-orange-500"></i>
                            PDF próximas a vencer
                        </a>
                    </div>
                </div>

                <a href="{{ route('cargas.create') }}"
                    class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-teal-700 rounded-lg hover:text-teal-700 hover:bg-teal-200">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Nueva Carga
                </a>
            </div>
        </div>

        {{-- Tags filtros activos --}}
        @if (request()->hasAny([
        'lote',
        'fecha_llegada_desde',
        'fecha_llegada_hasta',
        'fecha_vencimiento_desde',
        'fecha_vencimiento_hasta',
        'cantidad_min',
        'cantidad_max',
        'proximos_vencer',
        ]))
        <div class="flex flex-wrap items-center gap-2 px-4 py-2.5 bg-gray-50 border-b border-gray-200 text-xs">
            <span class="font-medium text-gray-500 flex items-center gap-1"><i data-lucide="filter" class="w-3 h-3"></i>
                Filtros activos:</span>
            @if (request('lote'))
            <span
                class="inline-flex items-center gap-1 pl-2.5 pr-1 py-1 rounded-full bg-blue-100 text-blue-700 font-medium">
                Lote: {{ request('lote') }}
                <a href="{{ route('cargas.index', request()->except('lote')) }}"
                    class="hover:bg-blue-200 rounded-full p-0.5"><i data-lucide="x" class="w-3 h-3"></i></a>
            </span>
            @endif
            @if (request('proximos_vencer'))
            <span
                class="inline-flex items-center gap-1 pl-2.5 pr-1 py-1 rounded-full bg-orange-100 text-orange-700 font-medium">
                Próx. vencer: {{ request('proximos_vencer') }} días
                <a href="{{ route('cargas.index', request()->except('proximos_vencer')) }}"
                    class="hover:bg-orange-200 rounded-full p-0.5"><i data-lucide="x" class="w-3 h-3"></i></a>
            </span>
            @endif
            @if (request('fecha_llegada_desde') || request('fecha_llegada_hasta'))
            <span
                class="inline-flex items-center gap-1 pl-2.5 pr-1 py-1 rounded-full bg-green-100 text-green-700 font-medium">
                Llegada: {{ request('fecha_llegada_desde', '...') }} →
                {{ request('fecha_llegada_hasta', '...') }}
                <a href="{{ route('cargas.index', request()->except(['fecha_llegada_desde', 'fecha_llegada_hasta'])) }}"
                    class="hover:bg-green-200 rounded-full p-0.5"><i data-lucide="x" class="w-3 h-3"></i></a>
            </span>
            @endif
            @if (request('fecha_vencimiento_desde') || request('fecha_vencimiento_hasta'))
            <span
                class="inline-flex items-center gap-1 pl-2.5 pr-1 py-1 rounded-full bg-red-100 text-red-700 font-medium">
                Vencimiento: {{ request('fecha_vencimiento_desde', '...') }} →
                {{ request('fecha_vencimiento_hasta', '...') }}
                <a href="{{ route('cargas.index', request()->except(['fecha_vencimiento_desde', 'fecha_vencimiento_hasta'])) }}"
                    class="hover:bg-red-200 rounded-full p-0.5"><i data-lucide="x" class="w-3 h-3"></i></a>
            </span>
            @endif
            @if (request('cantidad_min') || request('cantidad_max'))
            <span
                class="inline-flex items-center gap-1 pl-2.5 pr-1 py-1 rounded-full bg-gray-200 text-gray-700 font-medium">
                Cantidad: {{ request('cantidad_min', '0') }} – {{ request('cantidad_max', '∞') }}
                <a href="{{ route('cargas.index', request()->except(['cantidad_min', 'cantidad_max'])) }}"
                    class="hover:bg-gray-300 rounded-full p-0.5"><i data-lucide="x" class="w-3 h-3"></i></a>
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
                        <th class="px-4 py-3">Vacuna</th>
                        <th class="px-4 py-3">Lote</th>
                        <th class="px-4 py-3">F. Llegada</th>
                        <th class="px-4 py-3">F. Vencimiento</th>
                        <th class="px-4 py-3 text-center">Cantidad</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($cargas as $carga)
                    @php
                    $hoy = \Carbon\Carbon::today();
                    $vence = \Carbon\Carbon::parse($carga->fecha_vencimiento);
                    $diasLeft = $hoy->diffInDays($vence, false);
                    if ($diasLeft < 0) { $badge=['bg-red-100', 'text-red-700' , 'alert-circle' , 'Vencida' ]; } elseif
                        ($diasLeft <=30) { $badge=['bg-orange-100', 'text-orange-700' , 'alarm-clock' , 'Próx. vencer'
                        ]; } elseif ($diasLeft <=90) { $badge=['bg-yellow-100', 'text-yellow-700' , 'clock'
                        , 'Por vencer' ]; } else { $badge=['bg-green-100', 'text-green-700' , 'check-circle' , 'Vigente'
                        ]; } @endphp <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="px-4 py-3 text-gray-400 text-xs">{{ $i + $loop->iteration }}</td>

                        <td class="px-4 py-3">
                            <p class="font-semibold text-gray-900">{{ $carga->vacuna?->nombre ?? '—' }}</p>
                            <p class="text-xs text-gray-400">{{ $carga->asic?->nombre ?? '' }}</p>
                        </td>

                        <td class="px-4 py-3">
                            <span class="font-mono text-xs bg-gray-100 text-gray-700 px-2 py-0.5 rounded">{{
                                $carga->lote }}</span>
                        </td>

                        <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="calendar" class="w-3.5 h-3.5 text-gray-400"></i>
                                {{ \Carbon\Carbon::parse($carga->fecha_llegada)->format('d/m/Y') }}
                            </div>
                        </td>

                        <td class="px-4 py-3 whitespace-nowrap">
                            <div
                                class="flex items-center gap-1.5 {{ $diasLeft < 0 ? 'text-red-600' : ($diasLeft <= 30 ? 'text-orange-600' : 'text-gray-600') }}">
                                <i data-lucide="calendar-clock" class="w-3.5 h-3.5"></i>
                                {{ \Carbon\Carbon::parse($carga->fecha_vencimiento)->format('d/m/Y') }}
                            </div>
                            @if ($diasLeft >= 0 && $diasLeft <= 90) <p class="text-xs text-gray-400 mt-0.5 ml-5">{{
                                $diasLeft }} días</p>
                                @endif
                        </td>

                        <td class="px-4 py-3 text-center">
                            <span class="font-bold text-gray-900">{{ number_format($carga->cantidad) }}</span>
                            <span class="text-xs text-gray-400 block">dosis</span>
                        </td>

                        <td class="px-4 py-3">
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $badge[0] }} {{ $badge[1] }}">
                                <i data-lucide="{{ $badge[2] }}" class="w-3 h-3"></i>
                                {{ $badge[3] }}
                            </span>
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex justify-end">
                                <button id="acc-btn-{{ $carga->id }}" data-dropdown-toggle="acc-dd-{{ $carga->id }}"
                                    data-dropdown-placement="left"
                                    class="inline-flex items-center p-1.5 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg">
                                    <i data-lucide="more-horizontal" class="w-5 h-5"></i>
                                </button>
                                <div id="acc-dd-{{ $carga->id }}"
                                    class="hidden z-20 w-52 bg-white rounded-lg shadow-lg border border-gray-100 text-sm text-gray-700">
                                    <ul class="py-1">
                                        <li>
                                            <a href="{{ route('cargas.show', $carga->id) }}"
                                                class="flex items-center gap-2.5 px-4 py-2.5 hover:bg-gray-50">
                                                <i data-lucide="eye" class="w-4 h-4 text-blue-500"></i> Ver
                                                detalle
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('cargas.edit', $carga->id) }}"
                                                class="flex items-center gap-2.5 px-4 py-2.5 hover:bg-gray-50">
                                                <i data-lucide="pencil" class="w-4 h-4 text-yellow-500"></i>
                                                Editar
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('cargas.reporte.individual', $carga->id) }}"
                                                class="flex items-center gap-2.5 px-4 py-2.5 hover:bg-gray-50">
                                                <i data-lucide="file-down" class="w-4 h-4 text-green-500"></i>
                                                Descargar PDF
                                            </a>
                                        </li>
                                        <li class="border-t border-gray-100 mt-1">
                                            <button type="button"
                                                onclick="abrirEliminar({{ $carga->id }}, '{{ addslashes($carga->vacuna?->nombre ?? 'esta carga') }}', '{{ $carga->lote }}')"
                                                class="flex items-center gap-2.5 w-full px-4 py-2.5 hover:bg-danger text-red-600 hover:text-white rounded-lg">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i> Eliminar
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-4 py-20 text-center">
                                <div class="flex flex-col items-center gap-3 text-gray-400">
                                    <i data-lucide="package-x" class="w-12 h-12 text-gray-300"></i>
                                    <div>
                                        <p class="font-semibold text-gray-500">No se encontraron cargas</p>
                                        <p class="text-sm mt-1">
                                            @if (request()->hasAny([
                                            'vacuna',
                                            'lote',
                                            'fecha_llegada_desde',
                                            'fecha_llegada_hasta',
                                            'fecha_vencimiento_desde',
                                            'fecha_vencimiento_hasta',
                                            'cantidad_min',
                                            'cantidad_max',
                                            'proximos_vencer',
                                            ]))
                                            Intenta ajustar los filtros.
                                            @else
                                            <a href="{{ route('cargas.create') }}"
                                                class="text-primary-600 hover:underline font-medium">Registra la
                                                primera carga</a>
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
        @if ($cargas->hasPages())
        <div class="flex flex-col sm:flex-row justify-between items-center gap-3 px-4 py-3 border-t border-gray-200">
            <p class="text-sm text-gray-500">
                Mostrando <span class="font-semibold text-gray-900">{{ $cargas->firstItem() }}</span>–<span
                    class="font-semibold text-gray-900">{{ $cargas->lastItem() }}</span>
                de <span class="font-semibold text-gray-900">{{ $cargas->total() }}</span>
            </p>
            <nav>
                <ul class="inline-flex items-center -space-x-px text-sm h-8">
                    <li>
                        @if ($cargas->onFirstPage())
                        <span
                            class="flex items-center justify-center h-8 px-3 text-gray-300 bg-white border border-gray-300 rounded-l-lg cursor-not-allowed"><i
                                data-lucide="chevron-left" class="w-4 h-4"></i></span>
                        @else
                        <a href="{{ $cargas->withQueryString()->previousPageUrl() }}"
                            class="flex items-center justify-center h-8 px-3 text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100"><i
                                data-lucide="chevron-left" class="w-4 h-4"></i></a>
                        @endif
                    </li>
                    @foreach ($cargas->withQueryString()->getUrlRange(1, $cargas->lastPage()) as $page => $url)
                    <li>
                        @if ($page == $cargas->currentPage())
                        <span
                            class="flex items-center justify-center h-8 px-3 text-primary-600 border border-primary-300 bg-primary-50 font-medium">{{
                            $page }}</span>
                        @elseif(abs($page - $cargas->currentPage()) <= 2 || $page==1 || $page==$cargas->lastPage())
                            <a href="{{ $url }}"
                                class="flex items-center justify-center h-8 px-3 text-gray-500 bg-white border border-gray-300 hover:bg-gray-100">{{
                                $page }}</a>
                            @elseif(abs($page - $cargas->currentPage()) == 3)
                            <span
                                class="flex items-center justify-center h-8 px-3 text-gray-500 bg-white border border-gray-300">…</span>
                            @endif
                    </li>
                    @endforeach
                    <li>
                        @if ($cargas->hasMorePages())
                        <a href="{{ $cargas->withQueryString()->nextPageUrl() }}"
                            class="flex items-center justify-center h-8 px-3 text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100"><i
                                data-lucide="chevron-right" class="w-4 h-4"></i></a>
                        @else
                        <span
                            class="flex items-center justify-center h-8 px-3 text-gray-300 bg-white border border-gray-300 rounded-r-lg cursor-not-allowed"><i
                                data-lucide="chevron-right" class="w-4 h-4"></i></span>
                        @endif
                    </li>
                </ul>
            </nav>
        </div>
        @endif
    </div>
</div>

{{-- PANEL LATERAL FILTROS --}}
<div id="filtrosOverlay" onclick="toggleFiltros()" class="hidden fixed inset-0 z-40 bg-gray-900/40"></div>
<div id="filtrosPanel"
    class="fixed top-0 right-0 z-50 h-full w-full max-w-sm bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col">
    <div class="flex items-center justify-between p-5 border-b border-gray-200 shrink-0">
        <div class="flex items-center gap-2">
            <i data-lucide="sliders-horizontal" class="w-5 h-5 text-primary-600"></i>
            <h3 class="text-base font-semibold text-gray-900">Filtrar Cargas</h3>
        </div>
        <button onclick="toggleFiltros()" class="text-gray-400 hover:bg-gray-100 rounded-lg p-1.5"><i data-lucide="x"
                class="w-5 h-5"></i></button>
    </div>

    <form method="GET" action="{{ route('cargas.index') }}" class="flex flex-col flex-1 overflow-hidden">
        @if (request('vacuna'))
        <input type="hidden" name="vacuna" value="{{ request('vacuna') }}">
        @endif

        <div class="flex-1 overflow-y-auto p-5 space-y-5">

            <div>
                <label class="block mb-1.5 text-sm font-medium text-gray-700 flex items-center gap-1.5">
                    <i data-lucide="hash" class="w-3.5 h-3.5 text-gray-400"></i> Número de Lote
                </label>
                <input type="text" name="lote" value="{{ request('lote') }}" placeholder="Ej: LOT-2024-001"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
            </div>

            <div>
                <label class="block mb-1.5 text-sm font-medium text-gray-700 flex items-center gap-1.5">
                    <i data-lucide="alarm-clock" class="w-3.5 h-3.5 text-gray-400"></i> Próximos a Vencer
                </label>
                <select name="proximos_vencer"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                    <option value="">-- Todos --</option>
                    <option value="30" {{ request('proximos_vencer')==30 ? 'selected' : '' }}>Próximos 30 días
                    </option>
                    <option value="60" {{ request('proximos_vencer')==60 ? 'selected' : '' }}>Próximos 60 días
                    </option>
                    <option value="90" {{ request('proximos_vencer')==90 ? 'selected' : '' }}>Próximos 90 días
                    </option>
                </select>
            </div>

            <div class="border-t border-gray-100 pt-4">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3 flex items-center gap-1.5">
                    <i data-lucide="calendar" class="w-3 h-3"></i> Fecha de Llegada
                </p>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block mb-1 text-xs text-gray-500">Desde</label>
                        <input type="date" name="fecha_llegada_desde" value="{{ request('fecha_llegada_desde') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2">
                    </div>
                    <div>
                        <label class="block mb-1 text-xs text-gray-500">Hasta</label>
                        <input type="date" name="fecha_llegada_hasta" value="{{ request('fecha_llegada_hasta') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2">
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-4">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3 flex items-center gap-1.5">
                    <i data-lucide="calendar-x-2" class="w-3 h-3"></i> Fecha de Vencimiento
                </p>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block mb-1 text-xs text-gray-500">Desde</label>
                        <input type="date" name="fecha_vencimiento_desde"
                            value="{{ request('fecha_vencimiento_desde') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2">
                    </div>
                    <div>
                        <label class="block mb-1 text-xs text-gray-500">Hasta</label>
                        <input type="date" name="fecha_vencimiento_hasta"
                            value="{{ request('fecha_vencimiento_hasta') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2">
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-4">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3 flex items-center gap-1.5">
                    <i data-lucide="boxes" class="w-3 h-3"></i> Rango de Cantidad
                </p>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block mb-1 text-xs text-gray-500">Mínimo</label>
                        <input type="number" name="cantidad_min" value="{{ request('cantidad_min') }}" min="0"
                            placeholder="0"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2">
                    </div>
                    <div>
                        <label class="block mb-1 text-xs text-gray-500">Máximo</label>
                        <input type="number" name="cantidad_max" value="{{ request('cantidad_max') }}" min="0"
                            placeholder="Sin límite"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2">
                    </div>
                </div>
            </div>
        </div>

        <div class="shrink-0 p-5 border-t border-gray-200 bg-gray-50 flex items-center justify-between gap-3">
            <a href="{{ route('cargas.index') }}"
                class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i> Limpiar
            </a>
            <div class="flex gap-2">
                <button type="button" onclick="toggleFiltros()"
                    class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancelar
                </button>
                <button type="submit"
                    class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-cyan-700 rounded-lg hover:bg-cyan-800">
                    <i data-lucide="search" class="w-4 h-4"></i> Aplicar
                </button>
            </div>
        </div>
    </form>
</div>

{{-- MODAL ELIMINAR --}}
<div id="deleteModal" class="hidden fixed inset-0 z-50 flex justify-center items-center bg-gray-900/40">
    <div class="relative p-4 w-full max-w-md">
        <div class="bg-white rounded-xl shadow-xl text-center p-6">
            <button onclick="document.getElementById('deleteModal').classList.add('hidden')"
                class="absolute top-3 right-3 text-gray-400 hover:bg-gray-100 rounded-lg p-1.5">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
            <div class="mx-auto mb-4 w-14 h-14 bg-red-100 rounded-full flex items-center justify-center">
                <i data-lucide="trash-2" class="w-7 h-7 text-red-600"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">¿Eliminar esta carga?</h3>
            <p id="deleteModalVacuna" class="text-sm font-medium text-gray-700 mb-0.5"></p>
            <p id="deleteModalLote" class="text-xs text-gray-400 font-mono mb-4"></p>
            <p class="text-sm text-gray-500 mb-6">Esta acción es permanente y no se puede deshacer.</p>
            <div class="flex justify-center gap-3">
                <button onclick="document.getElementById('deleteModal').classList.add('hidden')"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancelar
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                        <i data-lucide="trash-2" class="w-4 h-4"></i> Sí, eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    lucide.createIcons();

            function toggleFiltros() {
                const panel = document.getElementById('filtrosPanel');
                const overlay = document.getElementById('filtrosOverlay');
                const abierto = !panel.classList.contains('translate-x-full');
                panel.classList.toggle('translate-x-full', abierto);
                overlay.classList.toggle('hidden', abierto);
            }

            function toggleReportes() {
                document.getElementById('reportesMenu').classList.toggle('hidden');
            }
            document.addEventListener('click', e => {
                const btn = document.getElementById('reportesBtn');
                const menu = document.getElementById('reportesMenu');
                if (btn && !btn.contains(e.target)) menu?.classList.add('hidden');
            });

            function abrirEliminar(id, vacuna, lote) {
                document.getElementById('deleteModalVacuna').textContent = 'Vacuna: ' + vacuna;
                document.getElementById('deleteModalLote').textContent = 'Lote: ' + lote;
                document.getElementById('deleteForm').action = '{{ url('cargas') }}/' + id;
                document.getElementById('deleteModal').classList.remove('hidden');
            }
</script>
@endpush
@endsection