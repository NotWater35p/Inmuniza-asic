@extends('layouts.app')
@section('title', 'Ficha de Vacunación')

@section('content')
<div class="px-4 py-6 mx-auto max-w-4xl bg-white/90 rounded-lg shadow-sm backdrop-blur-sm">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <i data-lucide="clipboard-list" class="w-6 h-6 text-teal-600"></i>
                Ficha de Vacunación
            </h1>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('tratamientos.historial.paciente', $tratamiento->paciente->id) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-teal-700 bg-teal-50 border border-teal-200 rounded-lg hover:bg-teal-100">
                <i data-lucide="book-open" class="w-4 h-4"></i>
                Historial completo
            </a>
            <a href="{{ route('tratamientos.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Volver
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="flex items-center gap-3 p-4 mb-5 text-green-800 bg-green-50 border border-green-200 rounded-lg">
        <i data-lucide="check-circle-2" class="w-5 h-5"></i>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @php
        $p        = $tratamiento->paciente;
        $vacuna   = $tratamiento->vacuna;
        $jornada  = $tratamiento->jornada;
        $proxima  = $proximaDosis;
        $diffDias = $proxima ? now()->diffInDays($proxima, false) : null;
        $edad     = $p?->fecha_nacimiento
            ? \Carbon\Carbon::parse($p->fecha_nacimiento)->age
            : null;
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">

        {{-- COLUMNA IZQUIERDA: Paciente --}}
        <div class="lg:col-span-1">

            {{-- Tarjeta paciente --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-4">
                <div class="p-5 bg-linear-to-r from-teal-600 to-teal-800 text-white">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full {{ ($p?->sexo === 'F') ? 'bg-pink-100' : 'bg-teal-100' }} flex items-center justify-center flex-shrink-0">
                            <span class="text-lg font-bold {{ ($p?->sexo === 'F') ? 'text-pink-700' : 'text-teal-700' }}">
                                {{ strtoupper(substr($p?->nombres ?? '?', 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <p class="font-bold text-base">{{ $p?->nombres }} {{ $p?->apellidos }}</p>
                            <p class="text-teal-200 text-xs font-mono mt-0.5">
    {{ $tratamiento->paciente?->cedula ? 'CI: ' . $tratamiento->paciente->cedula : 'Sin cédula' }}
</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 space-y-3 text-sm">
                    @if($edad !== null)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Edad</span>
                        <span class="font-semibold text-gray-900">{{ $edad }} años</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Sexo</span>
                        <span class="font-semibold text-gray-900">{{ $p?->sexo === 'M' ? 'Masculino' : ($p?->sexo === 'F' ? 'Femenino' : '—') }}</span>
                    </div>
                    @if($p?->sector)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Sector</span>
                        <span class="font-semibold text-gray-900">{{ $p->sector->nombre }}</span>
                    </div>
                    @endif
                    @if($p?->etnia)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Etnia</span>
                        <span class="font-semibold text-gray-900">{{ $p->etnia->nombre }}</span>
                    </div>
                    @endif
                    @if($p?->telefono)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Teléfono</span>
                        <span class="font-semibold text-gray-900">{{ $p->telefono }}</span>
                    </div>
                    @endif
                    <div class="pt-2 border-t border-gray-100">
                        <a href="{{ route('pacientes.show', $p?->id) }}"
                            class="flex items-center justify-center gap-1.5 text-xs text-teal-600 hover:text-teal-800 font-medium">
                            <i data-lucide="external-link" class="w-3 h-3"></i>
                            Ver ficha completa del paciente
                        </a>
                    </div>
                </div>
            </div>

            {{-- Jornada --}}
            @if($jornada)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4">
                <p class="text-xs text-gray-400 uppercase tracking-wide font-medium mb-3 flex items-center gap-1.5">
                    <i data-lucide="calendar-check-2" class="w-3.5 h-3.5 text-emerald-600"></i>
                    Jornada
                </p>
                <p class="text-sm font-semibold text-gray-900">
                    {{ \Carbon\Carbon::parse($jornada->fecha_jornada)->locale('es')->isoFormat('D [de] MMMM, YYYY') }}
                </p>
                @if($jornada->responsable)
                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                    <i data-lucide="user-check" class="w-3 h-3"></i>
                    {{ $jornada->responsable->nombre }} {{ $jornada->responsable->apellido }}
                </p>
                @endif
                @if($jornada->descripcion)
                <p class="text-xs text-gray-400 mt-1">{{ $jornada->descripcion }}</p>
                @endif
                <a href="{{ route('jornadas.show', $jornada->id) }}"
                    class="inline-flex items-center gap-1 text-xs text-emerald-600 hover:text-emerald-800 mt-2">
                    <i data-lucide="external-link" class="w-3 h-3"></i> Ver jornada
                </a>
            </div>
            @endif
        </div>

        {{-- COLUMNA DERECHA: Datos de la vacunación --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Vacuna aplicada --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 bg-teal-50 border-b border-teal-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-teal-100 rounded-lg">
                            <i data-lucide="syringe" class="w-5 h-5 text-teal-700"></i>
                        </div>
                        <div>
                            <p class="text-xs text-teal-600 uppercase tracking-wide font-medium">Vacuna aplicada</p>
                            <h2 class="text-lg font-bold text-gray-900">{{ $vacuna?->nombre ?? '—' }}</h2>
                            @if($vacuna?->marca)
                            <p class="text-xs text-gray-500">{{ $vacuna->marca->nombre }}</p>
                            @endif
                        </div>
                    </div>
                    {{-- Indicador dosis --}}
                    <div class="text-center">
                        <div class="w-14 h-14 rounded-full bg-teal-600 flex items-center justify-center">
                            <span class="text-xl font-bold text-white">{{ $tratamiento->dosis_aplicada }}</span>
                        </div>
                        @if($vacuna?->numero_dosis)
                        <p class="text-xs text-teal-600 mt-1">de {{ $vacuna->numero_dosis }} dosis</p>
                        @endif
                    </div>
                </div>

                <div class="p-4 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Fecha de aplicación</p>
                        <p class="text-sm font-semibold text-gray-900 mt-1">
                            {{ $tratamiento->fecha_aplicacion?->locale('es')->isoFormat('D [de] MMMM, YYYY') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Próxima dosis</p>
                        @if($proxima)
                        <p class="text-sm font-semibold mt-1 {{ $diffDias !== null && $diffDias < 0 ? 'text-red-600' : ($diffDias !== null && $diffDias <= 7 ? 'text-orange-600' : 'text-teal-700') }}">
                            {{ $proxima->locale('es')->isoFormat('D [de] MMMM, YYYY') }}
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            @if($diffDias < 0)
                                Vencido hace {{ abs($diffDias) }} días
                            @elseif($diffDias === 0)
                                ¡Hoy!
                            @else
                                En {{ $diffDias }} días
                            @endif
                        </p>
                        @elseif($vacuna?->numero_dosis && $tratamiento->dosis_aplicada >= $vacuna->numero_dosis)
                        <p class="text-sm font-semibold text-green-600 mt-1 flex items-center gap-1">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            Esquema completo
                        </p>
                        @else
                        <p class="text-sm text-gray-400 mt-1">—</p>
                        @endif
                    </div>

                    @if($vacuna?->via_administracion)
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Vía</p>
                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $vacuna->via_administracion }}</p>
                    </div>
                    @endif

                    @if($vacuna?->enfermedad)
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Previene</p>
                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $vacuna->enfermedad }}</p>
                    </div>
                    @endif
                </div>

                @if($tratamiento->observaciones)
                <div class="px-4 pb-4">
                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg">
                        <p class="text-xs text-gray-400 uppercase tracking-wide font-medium mb-1.5 flex items-center gap-1.5">
                            <i data-lucide="message-square" class="w-3 h-3"></i> Observaciones
                        </p>
                        <p class="text-sm text-gray-700">{{ $tratamiento->observaciones }}</p>
                    </div>
                </div>
                @endif
            </div>

            {{-- Historial resumido de esta vacuna para el paciente --}}
            @if($historial->count() > 0)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex items-center gap-2">
                    <i data-lucide="history" class="w-4 h-4 text-teal-600"></i>
                    <h3 class="text-sm font-semibold text-gray-800">Historial de Vacunación del Paciente</h3>
                    <span class="ml-auto text-xs bg-teal-100 text-teal-700 px-2 py-0.5 rounded-full font-medium">
                        {{ $historial->flatten()->count() }} registro(s)
                    </span>
                </div>
                <div class="p-4 space-y-3">
                    @foreach($historial as $vacunaId => $registros)
                    @php $v = $registros->first()->vacuna; @endphp
                    <div class="border border-gray-100 rounded-lg overflow-hidden">
                        <div class="flex items-center gap-2 px-3 py-2 bg-gray-50 border-b border-gray-100">
                            <i data-lucide="syringe" class="w-3.5 h-3.5 text-teal-600"></i>
                            <span class="text-xs font-semibold text-gray-700">{{ $v?->nombre ?? '—' }}</span>
                            @if($v?->numero_dosis)
                            <span class="text-xs text-gray-400">({{ $registros->count() }}/{{ $v->numero_dosis }} dosis)</span>
                            @endif
                            @if($v?->numero_dosis && $registros->count() >= $v->numero_dosis)
                            <span class="ml-auto inline-flex items-center gap-1 text-xs text-green-600 font-medium">
                                <i data-lucide="check-circle" class="w-3 h-3"></i> Completo
                            </span>
                            @endif
                        </div>
                        <div class="flex flex-wrap gap-2 p-3">
                            @foreach($registros->sortBy('dosis_aplicada') as $r)
                            <a href="{{ route('tratamientos.show', $r->id) }}"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 {{ $r->id === $tratamiento->id ? 'bg-teal-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-teal-50 hover:text-teal-700' }} text-xs rounded-lg transition-colors">
                                <span class="font-bold">D{{ $r->dosis_aplicada }}</span>
                                <span class="font-normal opacity-80">{{ $r->fecha_aplicacion?->format('d/m/Y') }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Acciones --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-4">
            <div class="flex items-center gap-2">
                <a href="{{ route('tratamientos.historial.paciente', $tratamiento->paciente->id) }}"
                    class="flex items-center gap-2 text-sm text-teal-600 hover:text-teal-800">
                    <i data-lucide="book-open" class="w-4 h-4"></i>
                    Ver historial completo del paciente
                </a>
            </div>
            <div class="flex gap-2">
                <button type="button" onclick="abrirEliminar({{ $tratamiento->id }})"
                    class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-red-700 bg-white border border-red-200 rounded-lg hover:bg-red-50">
                    <i data-lucide="trash-2" class="w-4 h-4"></i> Eliminar
                </button>
                <a href="{{ route('tratamientos.edit', $tratamiento->id) }}"
                    class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-teal-700 rounded-lg hover:bg-teal-800">
                    <i data-lucide="pencil" class="w-4 h-4"></i> Editar
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Modal eliminar --}}
<div id="deleteModal" class="hidden fixed inset-0 z-50 flex justify-center items-center bg-gray-900/40">
    <div class="p-4 w-full max-w-md">
        <div class="bg-white rounded-xl shadow-xl text-center p-6">
            <button onclick="document.getElementById('deleteModal').classList.add('hidden')"
                class="absolute top-3 right-3 text-gray-400 hover:bg-gray-100 rounded-lg p-1.5">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
            <div class="mx-auto mb-4 w-14 h-14 bg-red-100 rounded-full flex items-center justify-center">
                <i data-lucide="trash-2" class="w-7 h-7 text-red-600"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">¿Eliminar este registro?</h3>
            <p class="text-sm text-gray-500 mb-2">Esto afectará el historial médico del paciente.</p>
            <p class="text-xs text-gray-400 mb-6">Esta acción no se puede deshacer.</p>
            <div class="flex justify-center gap-3">
                <button onclick="document.getElementById('deleteModal').classList.add('hidden')"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancelar
                </button>
                <form id="deleteForm" method="POST">
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
    function abrirEliminar(id) {
        document.getElementById('deleteForm').action = '{{ url("tratamientos") }}/' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }
</script>
@endpush
@endsection