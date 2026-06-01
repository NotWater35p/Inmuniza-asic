@extends('layouts.app')
@section('title', 'Pacientes')

@section('content')
    <div class="px-4 py-6 mx-auto max-w-7xl bg-white/90 rounded-lg shadow backdrop-blur-sm">

        {{-- Header --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h1 class="text-2xl font-bold text-warning flex items-center gap-2">
                    <div class="p-2 bg-warning rounded text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-briefcase-medical-icon lucide-briefcase-medical">
                            <path d="M12 11v4" />
                            <path d="M14 13h-4" />
                            <path d="M16 6V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2" />
                            <path d="M18 6v14" />
                            <path d="M6 6v14" />
                            <rect width="20" height="14" x="2" y="6" rx="2" />
                        </svg>
                    </div>
                    Registro de Pacientes
                </h1>
            </div>
            <a href="{{ route('pacientes.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-warning-strong rounded-lg hover:bg-warning-subtle hover:text-warning-strong transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-clipboard-plus-icon lucide-clipboard-plus">
                    <rect width="8" height="4" x="8" y="2" rx="1" ry="1" />
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                    <path d="M9 14h6" />
                    <path d="M12 17v-6" />
                </svg>
                Nuevo Paciente
            </a>
        </div>

        {{-- Alertas --}}
        @if (session('success'))
            <div class="flex items-center gap-3 p-4 mb-5 text-green-800 bg-green-50 border border-green-200 rounded-lg">
                <i data-lucide="check-circle-2" class="w-5 h-5 shrink-0"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="ml-auto"><i data-lucide="x"
                        class="w-4 h-4"></i></button>
            </div>
        @endif

        {{-- Tarjeta principal --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">

            {{-- Toolbar --}}
            <div
                class="flex flex-col md:flex-row items-start md:items-center justify-between gap-3 p-4 border-b border-gray-200">

                <form method="GET" action="{{ route('pacientes.index') }}"
                    class="flex items-center gap-2 w-full md:w-auto">
                    @foreach (request()->except(['search', 'page']) as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <div class="relative w-full md:w-72">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cédula, nombre, sector, etnia..."
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
                    @if (request()->hasAny(['search', 'activo', 'etnia_id', 'sector_id', 'sexo']))
                        <a href="{{ route('pacientes.index') }}"
                            class="flex items-center gap-1 px-3 py-2.5 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 shrink-0">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </a>
                    @endif
                </form>

                <div class="flex items-center gap-2 shrink-0">
                    <span class="flex items-center after:content-['/'] sm:after:hidden after:mx-2 after:text-fg-disabled">
                        <p class="justify-center items-center flex w-5.5 h-5.5 bg-brand-light text-white rounded-full me-2">
                            {{ $pacientes->total() }}
                        </p>
                        Registros
                    </span>
                    <button type="button" onclick="toggleFiltros()"
                        class="relative flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        <i data-lucide="sliders-horizontal" class="w-4 h-4"></i>
                        Filtros
                        @if (request()->hasAny(['activo', 'etnia_id', 'sector_id', 'sexo', 'sort']))
                            <span
                                class="absolute -top-1.5 -right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-blue-600 text-[10px] font-bold text-white">✓</span>
                        @endif
                    </button>
                </div>
            </div>

            {{-- Tags filtros activos --}}
            @if (request()->hasAny(['search', 'activo', 'etnia_id', 'sector_id', 'sexo']))
                <div class="flex flex-wrap items-center gap-2 px-4 py-2.5 bg-gray-50 border-b border-gray-200 text-xs">
                    <span class="font-medium text-gray-500 flex items-center gap-1">
                        <i data-lucide="filter" class="w-3 h-3"></i> Filtros:
                    </span>
                    @if (request('search'))
                        <span
                            class="inline-flex items-center gap-1 pl-2.5 pr-1 py-1 rounded-full bg-blue-100 text-blue-700 font-medium">
                            "{{ request('search') }}"
                            <a href="{{ route('pacientes.index', request()->except('search')) }}"
                                class="hover:bg-blue-200 rounded-full p-0.5"><i data-lucide="x" class="w-3 h-3"></i></a>
                        </span>
                    @endif
                    @if (request('activo') !== null && request('activo') !== '')
                        <span
                            class="inline-flex items-center gap-1 pl-2.5 pr-1 py-1 rounded-full {{ request('activo') === '1' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} font-medium">
                            {{ request('activo') === '1' ? 'Activos' : 'Inactivos' }}
                            <a href="{{ route('pacientes.index', request()->except('activo')) }}"
                                class="hover:bg-green-200 rounded-full p-0.5"><i data-lucide="x" class="w-3 h-3"></i></a>
                        </span>
                    @endif
                    @if (request('etnia_id'))
                        @php $e = $etnias->firstWhere('id', request('etnia_id')); @endphp
                        <span
                            class="inline-flex items-center gap-1 pl-2.5 pr-1 py-1 rounded-full bg-purple-100 text-purple-700 font-medium">
                            Etnia: {{ $e?->nombre }}
                            <a href="{{ route('pacientes.index', request()->except('etnia_id')) }}"
                                class="hover:bg-purple-200 rounded-full p-0.5"><i data-lucide="x"
                                    class="w-3 h-3"></i></a>
                        </span>
                    @endif
                    @if (request('sector_id'))
                        @php $s = $sectores->firstWhere('id', request('sector_id')); @endphp
                        <span
                            class="inline-flex items-center gap-1 pl-2.5 pr-1 py-1 rounded-full bg-teal-100 text-teal-700 font-medium">
                            Sector: {{ $s?->nombre }}
                            <a href="{{ route('pacientes.index', request()->except('sector_id')) }}"
                                class="hover:bg-teal-200 rounded-full p-0.5"><i data-lucide="x" class="w-3 h-3"></i></a>
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

                            {{-- Cédula --}}
                            <th class="px-4 py-3">
                                <a href="{{ route('pacientes.index', array_merge(request()->query(), ['sort' => 'cedula', 'direction' => $sort === 'cedula' && $direction === 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center gap-1.5 group text-gray-700 hover:text-gray-900">
                                    Cédula
                                    <span class="flex flex-col">
                                        <i data-lucide="chevron-up"
                                            class="w-2.5 h-2.5 {{ $sort === 'cedula' && $direction === 'asc' ? 'text-blue-600' : 'text-gray-300 group-hover:text-gray-400' }}"></i>
                                        <i data-lucide="chevron-down"
                                            class="w-2.5 h-2.5 -mt-1 {{ $sort === 'cedula' && $direction === 'desc' ? 'text-blue-600' : 'text-gray-300 group-hover:text-gray-400' }}"></i>
                                    </span>
                                </a>
                            </th>

                            {{-- Nombre --}}
                            <th class="px-4 py-3">
                                <a href="{{ route('pacientes.index', array_merge(request()->query(), ['sort' => 'nombres', 'direction' => $sort === 'nombres' && $direction === 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center gap-1.5 group text-gray-700 hover:text-gray-900">
                                    Paciente
                                    <span class="flex flex-col">
                                        <i data-lucide="chevron-up"
                                            class="w-2.5 h-2.5 {{ $sort === 'nombres' && $direction === 'asc' ? 'text-blue-600' : 'text-gray-300 group-hover:text-gray-400' }}"></i>
                                        <i data-lucide="chevron-down"
                                            class="w-2.5 h-2.5 -mt-1 {{ $sort === 'nombres' && $direction === 'desc' ? 'text-blue-600' : 'text-gray-300 group-hover:text-gray-400' }}"></i>
                                    </span>
                                </a>
                            </th>

                            {{-- Fecha nac --}}
                            <th class="px-4 py-3 hidden md:table-cell">
                                <a href="{{ route('pacientes.index', array_merge(request()->query(), ['sort' => 'fecha_nacimiento', 'direction' => $sort === 'fecha_nacimiento' && $direction === 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center gap-1.5 group text-gray-700 hover:text-gray-900">
                                    F. Nac.
                                    <span class="flex flex-col">
                                        <i data-lucide="chevron-up"
                                            class="w-2.5 h-2.5 {{ $sort === 'fecha_nacimiento' && $direction === 'asc' ? 'text-blue-600' : 'text-gray-300' }}"></i>
                                        <i data-lucide="chevron-down"
                                            class="w-2.5 h-2.5 -mt-1 {{ $sort === 'fecha_nacimiento' && $direction === 'desc' ? 'text-blue-600' : 'text-gray-300' }}"></i>
                                    </span>
                                </a>
                            </th>

                            <th class="px-4 py-3 hidden md:table-cell text-gray-700">Sexo</th>
                            <th class="px-4 py-3 hidden lg:table-cell text-gray-700">Sector</th>
                            <th class="px-4 py-3 hidden lg:table-cell text-gray-700">Etnia</th>
                            <th class="px-4 py-3 hidden xl:table-cell text-gray-700">Representante</th>
                            <th class="px-4 py-3 text-center text-gray-700">Estado</th>
                            <th class="px-4 py-3 text-right text-gray-700">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($pacientes as $paciente)
                            @php
                                $edad = $paciente->fecha_nacimiento
                                    ? \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age
                                    : null;
                            $esMenor = $edad !== null && $edad < 18; @endphp <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="px-4 py-3 text-gray-400 text-xs">{{ $i + $loop->iteration }}</td>

                                {{-- Cédula --}}
                                <td class="px-4 py-3">
                                    @if ($paciente->cedula)
                                        <span
                                            class="font-mono text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">{{ $paciente->cedula }}</span>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Sin cédula</span>
                                    @endif
                                </td>

                                {{-- Paciente --}}
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2.5">
                                        <div
                                            class="w-8 h-8 rounded-full flex items-center justify-center shrink-0
                                    {{ $paciente->sexo === 'F' ? 'bg-pink-100 text-pink-700' : 'bg-blue-100 text-blue-700' }}">
                                            <span
                                                class="text-xs font-bold">{{ strtoupper(substr($paciente->nombres, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 text-sm">{{ $paciente->nombres }}
                                                {{ $paciente->apellidos }}</p>
                                            @if ($esMenor)
                                                <span
                                                    class="text-xs bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded font-medium">Menor
                                                    · {{ $edad }} años</span>
                                            @elseif($edad !== null)
                                                <span class="text-xs text-gray-400">{{ $edad }} años</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Fecha nac --}}
                                <td class="px-4 py-3 hidden md:table-cell text-xs text-gray-500">
                                    {{ $paciente->fecha_nacimiento?->format('d/m/Y') ?? '—' }}
                                </td>

                                {{-- Sexo --}}
                                <td class="px-4 py-3 hidden md:table-cell">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $paciente->sexo === 'F' ? 'bg-pink-100 text-pink-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ $paciente->sexo === 'M' ? 'Masculino' : ($paciente->sexo === 'F' ? 'Femenino' : '—') }}
                                    </span>
                                </td>

                                {{-- Sector --}}
                                <td class="px-4 py-3 hidden lg:table-cell text-xs text-gray-600">
                                    {{ $paciente->sector?->nombre ?? '—' }}
                                </td>

                                {{-- Etnia --}}
                                <td class="px-4 py-3 hidden lg:table-cell text-xs text-gray-600">
                                    {{ $paciente->etnia?->nombre ?? '—' }}
                                </td>

                                {{-- Representante --}}
                                <td class="px-4 py-3 hidden xl:table-cell">
                                    @if ($paciente->representante)
                                        <div class="text-xs">
                                            <p class="font-mono font-medium text-gray-700">CI:
                                                {{ $paciente->representante->cedula }}</p>
                                            @if ($paciente->representante->relacion)
                                                <p class="text-gray-400">{{ $paciente->representante->relacion }}</p>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>

                                {{-- Estado --}}
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $paciente->activo ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        <i data-lucide="{{ $paciente->activo ? 'circle-check' : 'circle-x' }}"
                                            class="w-3 h-3"></i>
                                        {{ $paciente->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>

                                {{-- Acciones --}}
                                <td class="px-4 py-3">
                                    <div class="flex justify-end items-center gap-1.5">
                                        <a href="{{ route('pacientes.show', $paciente->id) }}"
                                            class="p-1.5 text-blue-500 hover:text-blue-700 hover:bg-blue-100 rounded-lg"
                                            title="Ver">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-eye-icon lucide-eye">
                                                <path
                                                    d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('tratamientos.historial.paciente', $paciente->id) }}"
                                            class="p-1.5 text-teal-500 hover:text-teal-700 hover:bg-teal-100 rounded-lg"
                                            title="Historial clínico">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-book-plus-icon lucide-book-plus">
                                                <path d="M12 7v6" />
                                                <path
                                                    d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20" />
                                                <path d="M9 10h6" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('pacientes.edit', $paciente->id) }}"
                                            class="p-1.5 text-yellow-500 hover:text-yellow-700 hover:bg-yellow-100 rounded-lg"
                                            title="Editar">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-square-pen-icon lucide-square-pen">
                                                <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                <path
                                                    d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('pacientes.pdf', $paciente->id) }}"
                                            class="p-1.5 text-green-500 hover:text-green-700 hover:bg-green-100 rounded-lg"
                                            title="Reporte">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-file-down-icon lucide-file-down">
                                                <path
                                                    d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z" />
                                                <path d="M14 2v5a1 1 0 0 0 1 1h5" />
                                                <path d="M12 18v-6" />
                                                <path d="m9 15 3 3 3-3" />
                                            </svg>
                                        </a>
                                        <button type="button"
                                            onclick="abrirEliminar({{ $paciente->id }}, '{{ addslashes($paciente->nombres) }} {{ addslashes($paciente->apellidos) }}')"
                                            class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-100 rounded-lg"
                                            title="Eliminar">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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
                                <td colspan="10" class="px-4 py-20 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <i data-lucide="users" class="w-12 h-12 text-gray-300"></i>
                                        <div>
                                            <p class="font-semibold text-gray-500">No se encontraron pacientes</p>
                                            <p class="text-sm mt-1">
                                                @if (request()->hasAny(['search', 'activo', 'etnia_id', 'sector_id', 'sexo']))
                                                    Ajusta los filtros de búsqueda.
                                                @else
                                                    <a href="{{ route('pacientes.create') }}"
                                                        class="text-blue-600 hover:underline">Registra el primer
                                                        paciente</a>
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
            @if ($pacientes->hasPages())
                <div
                    class="flex flex-col sm:flex-row justify-between items-center gap-3 px-4 py-3 border-t border-gray-200">
                    <p class="text-sm text-gray-500">
                        Mostrando <span class="font-semibold text-gray-900">{{ $pacientes->firstItem() }}</span>–<span
                            class="font-semibold text-gray-900">{{ $pacientes->lastItem() }}</span>
                        de <span class="font-semibold text-gray-900">{{ $pacientes->total() }}</span>
                    </p>
                    <nav>
                        <ul class="inline-flex items-center -space-x-px text-sm h-8">
                            <li>
                                @if ($pacientes->onFirstPage())
                                    <span
                                        class="flex items-center justify-center h-8 px-3 text-gray-300 bg-white border border-gray-300 rounded-l-lg cursor-not-allowed"><i
                                            data-lucide="chevron-left" class="w-4 h-4"></i></span>
                                @else
                                    <a href="{{ $pacientes->withQueryString()->previousPageUrl() }}"
                                        class="flex items-center justify-center h-8 px-3 text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100"><i
                                            data-lucide="chevron-left" class="w-4 h-4"></i></a>
                                @endif
                            </li>
                            @foreach ($pacientes->withQueryString()->getUrlRange(1, $pacientes->lastPage()) as $page => $url)
                                <li>
                                    @if ($page == $pacientes->currentPage())
                                        <span
                                            class="flex items-center justify-center h-8 px-3 text-blue-600 border border-blue-300 bg-blue-50 font-medium">{{ $page }}</span>
                                    @elseif(abs($page - $pacientes->currentPage()) <= 2 || $page == 1 || $page == $pacientes->lastPage())
                                        <a href="{{ $url }}"
                                            class="flex items-center justify-center h-8 px-3 text-gray-500 bg-white border border-gray-300 hover:bg-gray-100">{{ $page }}</a>
                                    @elseif(abs($page - $pacientes->currentPage()) == 3)
                                        <span
                                            class="flex items-center justify-center h-8 px-3 text-gray-500 bg-white border border-gray-300">…</span>
                                    @endif
                                </li>
                            @endforeach
                            <li>
                                @if ($pacientes->hasMorePages())
                                    <a href="{{ $pacientes->withQueryString()->nextPageUrl() }}"
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
                <i data-lucide="sliders-horizontal" class="w-5 h-5 text-blue-600"></i>
                <h3 class="text-base font-semibold text-gray-900">Filtrar Pacientes</h3>
            </div>
            <button onclick="toggleFiltros()" class="text-gray-400 hover:bg-gray-100 rounded-lg p-1.5"><i data-lucide="x"
                    class="w-5 h-5"></i></button>
        </div>

        <form method="GET" action="{{ route('pacientes.index') }}" class="flex flex-col flex-1 overflow-hidden">
            @if (request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif

            <div class="flex-1 overflow-y-auto p-5 space-y-5">

                {{-- Estado activo --}}
                <div>
                    <label class="block mb-1.5 text-sm font-medium text-gray-400 flex items-center gap-1.5">
                        <i data-lucide="circle-check" class="w-3.5 h-3.5 text-gray-400"></i>
                        Estado del paciente
                    </label>
                    <select name="activo"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">Todos</option>
                        <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activos</option>
                        <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivos</option>
                    </select>
                </div>

                {{-- Sexo --}}
                <div>
                    <label class="block mb-1.5 text-sm font-medium text-gray-400 flex items-center gap-1.5">
                        <i data-lucide="user" class="w-3.5 h-3.5 text-gray-400"></i>
                        Sexo
                    </label>
                    <select name="sexo"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">Todos</option>
                        <option value="M" {{ request('sexo') === 'M' ? 'selected' : '' }}>Masculino</option>
                        <option value="F" {{ request('sexo') === 'F' ? 'selected' : '' }}>Femenino</option>
                    </select>
                </div>

                {{-- Etnia --}}
                <div>
                    <label class="block mb-1.5 text-sm font-medium text-gray-400 flex items-center gap-1.5">
                        <i data-lucide="globe" class="w-3.5 h-3.5 text-gray-400"></i>
                        Etnia
                    </label>
                    <select name="etnia_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">Todas</option>
                        @foreach ($etnias as $etnia)
                            <option value="{{ $etnia->id }}" {{ request('etnia_id') == $etnia->id ? 'selected' : '' }}>
                                {{ $etnia->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Sector --}}
                <div>
                    <label class="block mb-1.5 text-sm font-medium text-gray-400 flex items-center gap-1.5">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-gray-400"></i>
                        Sector
                    </label>
                    <select name="sector_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">Todos</option>
                        @foreach ($sectores as $sector)
                            <option value="{{ $sector->id }}"
                                {{ request('sector_id') == $sector->id ? 'selected' : '' }}>{{ $sector->nombre }}</option>
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
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mb-3">
                        <option value="created_at" {{ request('sort', 'created_at') === 'created_at' ? 'selected' : '' }}>
                            Fecha de registro
                        </option>
                        <option value="nombres" {{ request('sort') === 'nombres' ? 'selected' : '' }}>Nombre
                        </option>
                        <option value="apellidos" {{ request('sort') === 'apellidos' ? 'selected' : '' }}>Apellido
                        </option>
                        <option value="cedula" {{ request('sort') === 'cedula' ? 'selected' : '' }}>Cédula
                        </option>
                        <option value="fecha_nacimiento" {{ request('sort') === 'fecha_nacimiento' ? 'selected' : '' }}>
                            Fecha de nacimiento</option>
                    </select>
                    <div class="flex gap-2">
                        <label
                            class="flex-1 flex items-center gap-2 p-2.5 border rounded-lg cursor-pointer {{ request('direction', 'desc') === 'asc' ? 'border-blue-300 bg-blue-50 text-blue-700' : 'border-gray-200 text-gray-600' }}">
                            <input type="radio" name="direction" value="asc" class="sr-only"
                                {{ request('direction', 'desc') === 'asc' ? 'checked' : '' }}>
                            <i data-lucide="arrow-up-az" class="w-4 h-4"></i>
                            <span class="text-sm font-medium">Ascendente</span>
                        </label>
                        <label
                            class="flex-1 flex items-center gap-2 p-2.5 border rounded-lg cursor-pointer {{ request('direction', 'desc') === 'desc' ? 'border-blue-300 bg-blue-50 text-blue-700' : 'border-gray-200 text-gray-600' }}">
                            <input type="radio" name="direction" value="desc" class="sr-only"
                                {{ request('direction', 'desc') === 'desc' ? 'checked' : '' }}>
                            <i data-lucide="arrow-down-az" class="w-4 h-4"></i>
                            <span class="text-sm font-medium">Descendente</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="shrink-0 p-5 border-t border-gray-200 bg-gray-50 flex items-center justify-between gap-3">
                <a href="{{ route('pacientes.index') }}"
                    class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    Limpiar
                </a>
                <div class="flex gap-2">
                    <button type="button" onclick="toggleFiltros()"
                        class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancelar</button>
                    <button type="submit"
                        class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-cyan-400 rounded-lg hover:bg-cyan-600">
                        <i data-lucide="search" class="w-4 h-4"></i>
                        Aplicar
                    </button>
                </div>
            </div>
        </form>
    </div>

    @include('paciente.modals.delete-modal')

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

            function abrirEliminar(id, nombre) {
                document.getElementById('deleteNombre').textContent = nombre;
                document.getElementById('deleteForm').action = '{{ url('pacientes') }}/' + id;
                document.getElementById('deleteModal').classList.remove('hidden');
            }

            document.querySelectorAll('input[name="direction"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    document.querySelectorAll('input[name="direction"]').forEach(r => {
                        const l = r.closest('label');
                        l.classList.remove('border-blue-300', 'bg-blue-50', 'text-blue-700');
                        l.classList.add('border-gray-200', 'text-gray-600');
                    });
                    const l = this.closest('label');
                    l.classList.add('border-blue-300', 'bg-blue-50', 'text-blue-700');
                    l.classList.remove('border-gray-200', 'text-gray-600');
                });
            });
        </script>
    @endpush
@endsection
