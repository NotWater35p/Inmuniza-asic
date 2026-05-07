@extends('layouts.app')
@section('title', 'Tratamientos')

@section('content')
<div class="px-4 py-6 mx-auto max-w-screen-xl bg-white/90 rounded-lg shadow-sm backdrop-blur-sm">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <div class="p-2 bg-teal-700 rounded text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m18 2 4 4"/><path d="m17 7 3-3"/><path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5"/><path d="m9 11 4 4"/><path d="m5 19-3 3"/><path d="m14 4 6 6"/></svg>
                </div>
                Historial de Vacunaciones
            </h1>
            <p class="text-sm text-gray-500 mt-1">Registro de vacunas aplicadas a pacientes</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('jornadas.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100">
                <i data-lucide="calendar-check-2" class="w-4 h-4"></i>
                Jornadas
            </a>
            <a href="{{ route('tratamientos.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-teal-700 rounded-lg hover:bg-teal-800">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Nueva Vacunación
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="flex items-center gap-3 p-4 mb-5 text-green-800 bg-green-50 border border-green-200 rounded-lg">
        <i data-lucide="check-circle-2" class="w-5 h-5 flex-shrink-0"></i>
        <span class="text-sm font-medium">{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="ml-auto"><i data-lucide="x" class="w-4 h-4"></i></button>
    </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">

        {{-- Toolbar --}}
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-3 p-4 border-b border-gray-200">
            <form method="GET" action="{{ route('tratamientos.index') }}" class="flex items-center gap-2 w-full md:w-auto">
                @foreach(request()->except(['search','page']) as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach
                <div class="relative w-full md:w-72">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Buscar paciente, vacuna..."
                        class="bg-gray-50 border border-gray-300 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block w-full pl-10 p-2.5">
                </div>
                <button type="submit" class="px-4 py-2.5 text-sm font-medium text-white bg-teal-700 rounded-lg hover:bg-teal-800 flex-shrink-0">
                    Buscar
                </button>
                @if(request()->hasAny(['search','vacuna_id','fecha_desde','fecha_hasta']))
                <a href="{{ route('tratamientos.index') }}" class="flex items-center gap-1 px-3 py-2.5 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 flex-shrink-0">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </a>
                @endif
            </form>

            <button onclick="toggleFiltros()"
                class="relative flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <i data-lucide="sliders-horizontal" class="w-4 h-4"></i> Filtros
                @if(request()->hasAny(['vacuna_id','fecha_desde','fecha_hasta']))
                <span class="absolute -top-1.5 -right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-teal-600 text-[10px] font-bold text-white">✓</span>
                @endif
            </button>
        </div>

        {{-- Tabla --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 w-10">#</th>
                        <th class="px-4 py-3">Paciente</th>
                        <th class="px-4 py-3">Vacuna</th>
                        <th class="px-4 py-3 text-center">Dosis</th>
                        <th class="px-4 py-3">F. Aplicación</th>
                        <th class="px-4 py-3 hidden md:table-cell">Próxima Dosis</th>
                        <th class="px-4 py-3 hidden lg:table-cell">Jornada</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tratamientos as $tratamiento)
                    @php
                        $proxima  = $tratamiento->fechaProximaDosis();
                        $diffDias = $proxima ? now()->diffInDays($proxima, false) : null;
                        $vacuna   = $tratamiento->vacuna;
                        $totalDosis = $vacuna?->numero_dosis;
                    @endphp
                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="px-4 py-3 text-gray-400 text-xs">{{ $i + $loop->iteration }}</td>

                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                @php $p = $tratamiento->paciente; @endphp
                                <div class="w-8 h-8 rounded-full {{ ($p?->sexo === 'F') ? 'bg-pink-100 text-pink-700' : 'bg-blue-100 text-blue-700' }} flex items-center justify-center flex-shrink-0 text-xs font-bold">
                                    {{ strtoupper(substr($p?->nombres ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $p?->nombres }} {{ $p?->apellidos }}</p>
                                    <p class="text-xs text-gray-400 font-mono">
    {{ $tratamiento->paciente?->cedula ? 'CI: ' . $tratamiento->paciente->cedula : 'Sin cédula' }}
</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-700">
                                <i data-lucide="syringe" class="w-3 h-3"></i>
                                {{ $tratamiento->vacuna?->nombre ?? '—' }}
                            </span>
                        </td>

                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-7 h-7 text-xs font-bold text-white bg-teal-600 rounded-full">
                                {{ $tratamiento->dosis_aplicada }}
                            </span>
                            @if($totalDosis)
                            <span class="text-xs text-gray-400">/{{ $totalDosis }}</span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-gray-600 text-sm">
                            {{ $tratamiento->fecha_aplicacion?->format('d/m/Y') ?? '—' }}
                        </td>

                        <td class="px-4 py-3 hidden md:table-cell">
                            @if($proxima)
                            <p class="text-sm font-medium {{ $diffDias < 0 ? 'text-red-600' : ($diffDias <= 7 ? 'text-orange-600' : 'text-gray-700') }}">
                                {{ $proxima->format('d/m/Y') }}
                            </p>
                            <p class="text-xs text-gray-400">
                                @if($diffDias < 0)
                                    <span class="text-red-500">Vencido hace {{ abs($diffDias) }}d</span>
                                @elseif($diffDias === 0)
                                    <span class="text-orange-500 font-medium">¡Hoy!</span>
                                @else
                                    En {{ $diffDias }} días
                                @endif
                            </p>
                            @elseif($totalDosis && $tratamiento->dosis_aplicada >= $totalDosis)
                            <span class="inline-flex items-center gap-1 text-xs text-green-700 bg-green-100 px-2 py-0.5 rounded-full font-medium">
                                <i data-lucide="check-circle" class="w-3 h-3"></i> Esquema completo
                            </span>
                            @else
                            <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>

                        <td class="px-4 py-3 hidden lg:table-cell text-xs text-gray-500">
                            @if($tratamiento->jornada)
                            <p>{{ \Carbon\Carbon::parse($tratamiento->jornada->fecha_jornada)->format('d/m/Y') }}</p>
                            <p class="text-gray-400">{{ $tratamiento->jornada->responsable?->apellido }}</p>
                            @else
                            —
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex justify-end items-center gap-1.5">
                                <a href="{{ route('tratamientos.show', $tratamiento->id) }}"
                                    class="p-1.5 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg" title="Ver ficha">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('tratamientos.edit', $tratamiento->id) }}"
                                    class="p-1.5 text-yellow-500 hover:text-yellow-700 hover:bg-yellow-50 rounded-lg" title="Editar">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('tratamientos.historial.paciente', $tratamiento->paciente->id) }}"
                                    class="p-1.5 text-teal-500 hover:text-teal-700 hover:bg-teal-50 rounded-lg" title="Historial del paciente">
                                    <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                                </a>
                                <button type="button"
                                    onclick="abrirEliminar({{ $tratamiento->id }})"
                                    class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg" title="Eliminar">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-20 text-center">
                            <i data-lucide="syringe" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                            <p class="font-semibold text-gray-500">Sin vacunaciones registradas</p>
                            <a href="{{ route('tratamientos.create') }}" class="text-teal-600 hover:underline text-sm mt-1 inline-block">Registrar primera vacunación</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tratamientos->hasPages())
        <div class="flex flex-col sm:flex-row justify-between items-center gap-3 px-4 py-3 border-t border-gray-200">
            <p class="text-sm text-gray-500">
                Mostrando <span class="font-semibold text-gray-900">{{ $tratamientos->firstItem() }}</span>–<span class="font-semibold text-gray-900">{{ $tratamientos->lastItem() }}</span>
                de <span class="font-semibold text-gray-900">{{ $tratamientos->total() }}</span>
            </p>
            {{ $tratamientos->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Panel filtros --}}
<div id="filtrosOverlay" onclick="toggleFiltros()" class="hidden fixed inset-0 z-40 bg-gray-900/40"></div>
<div id="filtrosPanel" class="fixed top-0 right-0 z-50 h-full w-full max-w-sm bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col">
    <div class="flex items-center justify-between p-5 border-b border-gray-200 flex-shrink-0">
        <div class="flex items-center gap-2">
            <i data-lucide="sliders-horizontal" class="w-5 h-5 text-teal-600"></i>
            <h3 class="text-base font-semibold text-gray-900">Filtrar Vacunaciones</h3>
        </div>
        <button onclick="toggleFiltros()" class="text-gray-400 hover:bg-gray-100 rounded-lg p-1.5"><i data-lucide="x" class="w-5 h-5"></i></button>
    </div>
    <form method="GET" action="{{ route('tratamientos.index') }}" class="flex flex-col flex-1 overflow-hidden">
        @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
        <div class="flex-1 overflow-y-auto p-5 space-y-5">
            <div>
                <label class="block mb-1.5 text-sm font-medium text-gray-700">Vacuna</label>
                <select name="vacuna_id" class="bg-gray-50 border border-gray-300 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block w-full p-2.5">
                    <option value="">Todas</option>
                    @foreach($vacunas as $v)
                    <option value="{{ $v->id }}" {{ request('vacuna_id') == $v->id ? 'selected':'' }}>{{ $v->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block mb-1.5 text-sm font-medium text-gray-700">F. Aplicación desde</label>
                <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}"
                    class="bg-gray-50 border border-gray-300 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block w-full p-2.5">
            </div>
            <div>
                <label class="block mb-1.5 text-sm font-medium text-gray-700">F. Aplicación hasta</label>
                <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}"
                    class="bg-gray-50 border border-gray-300 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block w-full p-2.5">
            </div>
        </div>
        <div class="flex-shrink-0 p-5 border-t border-gray-200 bg-gray-50 flex items-center justify-between gap-3">
            <a href="{{ route('tratamientos.index') }}" class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i> Limpiar
            </a>
            <div class="flex gap-2">
                <button type="button" onclick="toggleFiltros()" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancelar</button>
                <button type="submit" class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-teal-700 rounded-lg hover:bg-teal-800">
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
            <button onclick="document.getElementById('deleteModal').classList.add('hidden')" class="absolute top-3 right-3 text-gray-400 hover:bg-gray-100 rounded-lg p-1.5"><i data-lucide="x" class="w-5 h-5"></i></button>
            <div class="mx-auto mb-4 w-14 h-14 bg-red-100 rounded-full flex items-center justify-center">
                <i data-lucide="trash-2" class="w-7 h-7 text-red-600"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">¿Eliminar esta vacunación?</h3>
            <p class="text-sm text-gray-500 mb-6">Esto afectará el historial médico del paciente. Esta acción no se puede deshacer.</p>
            <div class="flex justify-center gap-3">
                <button onclick="document.getElementById('deleteModal').classList.add('hidden')" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancelar</button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
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
    function abrirEliminar(id) {
        document.getElementById('deleteForm').action = '{{ url("tratamientos") }}/' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }
</script>
@endpush
@endsection