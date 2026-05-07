@extends('layouts.app')
@section('title', 'Jornadas de Vacunación')

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl bg-white/90 rounded-lg shadow-sm backdrop-blur-sm">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <div class="p-2 bg-emerald-700 rounded text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                        <line x1="16" x2="16" y1="2" y2="6" />
                        <line x1="8" x2="8" y1="2" y2="6" />
                        <line x1="3" x2="21" y1="10" y2="10" />
                        <path d="m9 16 2 2 4-4" />
                    </svg>
                </div>
                Jornadas de Vacunación
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                @if(auth()->user()->esJefeModulo())
                Jornadas de tu módulo
                @else
                Registro de sesiones de vacunación del ASIC
                @endif
            </p>
        </div>
        <a href="{{ route('jornadas.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-emerald-700 rounded-lg hover:bg-emerald-800">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Nueva Jornada
        </a>
    </div>

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

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">

        {{-- Toolbar --}}
        <div
            class="flex flex-col md:flex-row items-start md:items-center justify-between gap-3 p-4 border-b border-gray-200">
            <form method="GET" action="{{ route('jornadas.index') }}" class="flex items-center gap-2 w-full md:w-auto">
                @foreach(request()->except(['search','page']) as $k => $v)
                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach
                <div class="relative w-full md:w-72">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Buscar por descripción o responsable..."
                        class="bg-gray-50 border border-gray-300 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full pl-10 p-2.5">
                </div>
                <button type="submit"
                    class="px-4 py-2.5 text-sm font-medium text-white bg-emerald-700 rounded-lg hover:bg-emerald-800 shrink-0">
                    Buscar
                </button>
            </form>
            <div class="flex items-center gap-2">
                <button onclick="toggleFiltros()"
                    class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    <i data-lucide="sliders-horizontal" class="w-4 h-4"></i> Filtros
                </button>
            </div>
        </div>

        {{-- Tabla --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 w-10">#</th>
                        <th class="px-4 py-3">
                            <a href="{{ route('jornadas.index', array_merge(request()->query(), ['sort'=>'fecha_jornada','direction'=>($sort==='fecha_jornada'&&$direction==='desc')?'asc':'desc'])) }}"
                                class="flex items-center gap-1.5 group text-gray-700 hover:text-gray-900">
                                Fecha
                                <span class="flex flex-col">
                                    <i data-lucide="chevron-up"
                                        class="w-2.5 h-2.5 {{ $sort==='fecha_jornada'&&$direction==='asc' ? 'text-emerald-600':'text-gray-300' }}"></i>
                                    <i data-lucide="chevron-down"
                                        class="w-2.5 h-2.5 -mt-1 {{ $sort==='fecha_jornada'&&$direction==='desc' ? 'text-emerald-600':'text-gray-300' }}"></i>
                                </span>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-gray-700">Responsable</th>
                        <th class="px-4 py-3 hidden md:table-cell text-gray-700">Descripción</th>
                        <th class="px-4 py-3 text-center text-gray-700">Tratamientos</th>
                        <th class="px-4 py-3 text-right text-gray-700">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($jornadas as $jornada)
                    @php
                    $hoy = now()->startOfDay();
                    $fechaJ = \Carbon\Carbon::parse($jornada->fecha_jornada)->startOfDay();
                    $esHoy = $fechaJ->eq($hoy);
                    $esFutura = $fechaJ->gt($hoy);
                    @endphp
                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="px-4 py-3 text-gray-400 text-xs">{{ $i + $loop->iteration }}</td>

                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                @if($esHoy)
                                <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0 animate-pulse"></span>
                                @elseif($esFutura)
                                <span class="w-2 h-2 rounded-full bg-blue-400 shrink-0"></span>
                                @else
                                <span class="w-2 h-2 rounded-full bg-gray-300 shrink-0"></span>
                                @endif
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $fechaJ->locale('es')->isoFormat('D [de] MMMM, YYYY') }}
                                    </p>
                                    @if($esHoy)
                                    <span class="text-xs text-emerald-600 font-medium">Hoy</span>
                                    @elseif($esFutura)
                                    <span class="text-xs text-blue-500">En {{ $hoy->diffInDays($fechaJ) }} días</span>
                                    @else
                                    <span class="text-xs text-gray-400">Hace {{ $fechaJ->diffInDays($hoy) }} días</span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-7 h-7 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 text-xs font-bold text-emerald-700">
                                    {{ strtoupper(substr($jornada->responsable?->nombre ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $jornada->responsable?->nombre }} {{ $jornada->responsable?->apellido }}
                                    </p>
                                    <p class="text-xs text-gray-400">{{ $jornada->responsable?->cargo?->nombre ?? '' }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="px-4 py-3 hidden md:table-cell text-sm text-gray-600">
                            {{ $jornada->descripcion ? \Illuminate\Support\Str::limit($jornada->descripcion, 50) : '—'
                            }}
                        </td>

                        <td class="px-4 py-3 text-center">
                            <span
                                class="inline-flex items-center justify-center w-7 h-7 text-xs font-bold text-emerald-700 bg-emerald-100 rounded-full">
                                {{ $jornada->tratamientos_count }}
                            </span>
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex justify-end items-center gap-1.5">
                                <a href="{{ route('jornadas.show', $jornada->id) }}"
                                    class="p-1.5 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg"
                                    title="Ver">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('tratamientos.create', ['jornada_id' => $jornada->id]) }}"
                                    class="p-1.5 text-emerald-600 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg"
                                    title="Registrar tratamiento">
                                    <i data-lucide="syringe" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('jornadas.edit', $jornada->id) }}"
                                    class="p-1.5 text-yellow-500 hover:text-yellow-700 hover:bg-yellow-50 rounded-lg"
                                    title="Editar">
                                    <i data-lucide="square-pen" class="w-4 h-4"></i>
                                </a>
                                <button type="button"
                                    onclick="abrirEliminar({{ $jornada->id }}, '{{ $fechaJ->format('d/m/Y') }}', {{ $jornada->tratamientos_count }})"
                                    class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg"
                                    title="Eliminar">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-20 text-center">
                            <i data-lucide="calendar-x-2" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                            <p class="font-semibold text-gray-500">Sin jornadas registradas</p>
                            <a href="{{ route('jornadas.create') }}"
                                class="text-sm text-emerald-600 hover:underline mt-1 inline-block">Registrar primera
                                jornada</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($jornadas->hasPages())
        <div class="flex flex-col sm:flex-row justify-between items-center gap-3 px-4 py-3 border-t border-gray-200">
            <p class="text-sm text-gray-500">
                Mostrando <span class="font-semibold text-gray-900">{{ $jornadas->firstItem() }}</span>–<span
                    class="font-semibold text-gray-900">{{ $jornadas->lastItem() }}</span>
                de <span class="font-semibold text-gray-900">{{ $jornadas->total() }}</span>
            </p>
            {{ $jornadas->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Panel filtros --}}
<div id="filtrosOverlay" onclick="toggleFiltros()" class="hidden fixed inset-0 z-40 bg-gray-900/40"></div>
<div id="filtrosPanel"
    class="fixed top-0 right-0 z-50 h-full w-full max-w-sm bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col">
    <div class="flex items-center justify-between p-5 border-b border-gray-200 shrink-0">
        <div class="flex items-center gap-2">
            <i data-lucide="sliders-horizontal" class="w-5 h-5 text-emerald-600"></i>
            <h3 class="text-base font-semibold text-gray-900">Filtrar Jornadas</h3>
        </div>
        <button onclick="toggleFiltros()" class="text-gray-400 hover:bg-gray-100 rounded-lg p-1.5"><i data-lucide="x"
                class="w-5 h-5"></i></button>
    </div>
    <form method="GET" action="{{ route('jornadas.index') }}" class="flex flex-col flex-1 overflow-hidden">
        @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
        <div class="flex-1 overflow-y-auto p-5 space-y-5">
            <div>
                <label class="block mb-1.5 text-sm font-medium text-gray-700">Fecha desde</label>
                <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}"
                    class="bg-gray-50 border border-gray-300 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2.5">
            </div>
            <div>
                <label class="block mb-1.5 text-sm font-medium text-gray-700">Fecha hasta</label>
                <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}"
                    class="bg-gray-50 border border-gray-300 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2.5">
            </div>
        </div>
        <div class="shrink-0 p-5 border-t border-gray-200 bg-gray-50 flex items-center justify-between gap-3">
            <a href="{{ route('jornadas.index') }}"
                class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i> Limpiar
            </a>
            <div class="flex gap-2">
                <button type="button" onclick="toggleFiltros()"
                    class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancelar</button>
                <button type="submit"
                    class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-emerald-700 rounded-lg hover:bg-emerald-800">
                    <i data-lucide="search" class="w-4 h-4"></i> Aplicar
                </button>
            </div>
        </div>
    </form>
</div>

{{-- Modal eliminar --}}
<div id="deleteModal" class="hidden fixed inset-0 z-50 flex justify-center items-center bg-gray-900/40">
    <div class="p-4 w-full max-w-md">
        <div class="bg-white rounded-xl shadow-xl text-center p-6">
            <button onclick="document.getElementById('deleteModal').classList.add('hidden')"
                class="absolute top-3 right-3 text-gray-400 hover:bg-gray-100 rounded-lg p-1.5"><i data-lucide="x"
                    class="w-5 h-5"></i></button>
            <div class="mx-auto mb-4 w-14 h-14 bg-red-100 rounded-full flex items-center justify-center">
                <i data-lucide="trash-2" class="w-7 h-7 text-red-600"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">¿Eliminar esta jornada?</h3>
            <p id="delFecha" class="text-sm font-medium text-gray-700 mb-3"></p>
            <div id="delWarning" class="hidden mb-4 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                <p class="text-xs text-orange-700 flex items-center justify-center gap-1.5">
                    <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i>
                    Tiene tratamientos registrados. Elimínalos primero.
                </p>
            </div>
            <p class="text-sm text-gray-500 mb-6">Esta acción es permanente.</p>
            <div class="flex justify-center gap-3">
                <button onclick="document.getElementById('deleteModal').classList.add('hidden')"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancelar</button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" id="deleteBtn"
                        class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed">
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
        const p = document.getElementById('filtrosPanel');
        const o = document.getElementById('filtrosOverlay');
        const a = !p.classList.contains('translate-x-full');
        p.classList.toggle('translate-x-full', a);
        o.classList.toggle('hidden', a);
    }
    function abrirEliminar(id, fecha, numTrat) {
        document.getElementById('delFecha').textContent = 'Jornada del ' + fecha;
        document.getElementById('deleteForm').action = '{{ url("jornadas") }}/' + id;
        const w = document.getElementById('delWarning');
        const b = document.getElementById('deleteBtn');
        w.classList.toggle('hidden', numTrat === 0);
        b.disabled = numTrat > 0;
        document.getElementById('deleteModal').classList.remove('hidden');
        lucide.createIcons();
    }
</script>
@endpush
@endsection