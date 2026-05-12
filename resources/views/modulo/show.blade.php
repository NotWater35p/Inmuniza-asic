@extends('layouts.app')
@section('title', $modulo->nombre . ' | Módulo')

@section('content')
<div class="px-4 py-6 mx-auto max-w-3xl bg-white/90 rounded-lg shadow backdrop-blur-sm">

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <h1 class="text-2xl font-bold text-red-800 flex items-center gap-2">
            <div class="p-2 bg-red-800 rounded text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 7v4"/><path d="M14 21v-3a2 2 0 0 0-4 0v3"/><path d="M14 9h-4"/><path d="M18 11h2a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-9a2 2 0 0 1 2-2h2"/><path d="M18 21V5a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16"/></svg>
            </div>
            Detalles del Módulo
        </h1>
        <a href="{{ route('modulos.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Volver
        </a>
    </div>

    {{-- Banner --}}
    <div class="bg-linear-to-r from-purple-600 to-indigo-600 rounded-xl p-6 mb-5 text-white">
        <div class="flex items-start gap-4">
            <div class="w-14 h-14 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 7v4"/><path d="M14 21v-3a2 2 0 0 0-4 0v3"/><path d="M14 9h-4"/><path d="M18 11h2a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-9a2 2 0 0 1 2-2h2"/><path d="M18 21V5a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16"/></svg>
            </div>
            <div>
                <h2 class="text-xl font-bold">{{ $modulo->nombre }}</h2>
                <span class="text-sm font-mono text-purple-200 mt-0.5 block">{{ $modulo->rif }}</span>
                <span class="text-xs text-purple-300 mt-1 block">{{ $modulo->asic->nombre ?? '—' }}</span>
            </div>
        </div>
    </div>

    {{-- Datos --}}
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-5">
        <div class="p-4 border-b border-gray-100 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
            <h3 class="text-sm font-semibold text-gray-800">Información del Módulo</h3>
        </div>

        <div class="divide-y divide-gray-50">

            {{-- Tipo establecimiento --}}
            <div class="flex items-start gap-4 p-5">
                <div class="p-2 bg-indigo-100 rounded-lg shrink-0 mt-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M12 6h.01"/><path d="M12 10h.01"/><path d="M12 14h.01"/><path d="M16 10h.01"/><path d="M16 14h.01"/><path d="M8 10h.01"/><path d="M8 14h.01"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Tipo de Establecimiento</p>
                    <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $modulo->tipo_establecimiento ?? '—' }}</p>
                </div>
            </div>

            {{-- Municipio --}}
            <div class="flex items-start gap-4 p-5">
                <div class="p-2 bg-blue-100 rounded-lg shrink-0 mt-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Municipio</p>
                    <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $modulo->municipio ?? '—' }}</p>
                </div>
            </div>

            {{-- Parroquia --}}
            <div class="flex items-start gap-4 p-5">
                <div class="p-2 bg-cyan-100 rounded-lg shrink-0 mt-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-cyan-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Parroquia</p>
                    <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $modulo->parroquia ?? '—' }}</p>
                </div>
            </div>

            {{-- Dirección --}}
            <div class="flex items-start gap-4 p-5">
                <div class="p-2 bg-gray-100 rounded-lg shrink-0 mt-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Dirección</p>
                    <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $modulo->direccion ?? '—' }}</p>
                </div>
            </div>

            {{-- Teléfono --}}
            <div class="flex items-start gap-4 p-5">
                <div class="p-2 bg-gray-100 rounded-lg shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.38 2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.72a16 16 0 0 0 6.29 6.29l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Teléfono</p>
                    <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $modulo->telefono ?? '—' }}</p>
                </div>
            </div>
            
            {{-- Fila SISPAI --}}
            <div class="flex items-start gap-4 p-5">
                <div class="p-2 bg-emerald-100 rounded-lg shrink-0 mt-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3h18v18H3z"/><path d="M3 9h18"/><path d="M3 15h18"/><path d="M9 3v18"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Fila SISPAI<i class="font-thin"> (Referencia en el Exel)</i>
                </p>
                    <p class="text-sm font-semibold text-gray-900 mt-0.5">
                        {{ $modulo->sispai_fila ?? '—' }}
                    </p>
                </div>
            </div>

            {{-- Jefe de módulo --}}
            <div class="flex items-start gap-4 p-5">
                <div class="p-2 bg-yellow-100 rounded-lg shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-yellow-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Jefe de Módulo</p>
                    @if($modulo->jefe)
                        <p class="text-sm font-semibold text-gray-900 mt-0.5">
                            {{ $modulo->jefe->nombre }} {{ $modulo->jefe->apellido }}
                        </p>
                        <div class="flex items-center gap-3 mt-1.5">
                            <span class="text-xs font-mono text-gray-500">CI: {{ number_format($modulo->jefe->cedula, 0, ',', '.') }}</span>
                            @if($modulo->jefe->telefono)
                                <span class="text-xs text-gray-400 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.38 2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.72a16 16 0 0 0 6.29 6.29l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                    {{ $modulo->jefe->telefono }}
                                </span>
                            @endif
                        </div>
                        <div class="mt-2">
                            @include('user.components._nivel_badge', ['nivel' => $modulo->jefe->cargo?->nivel_acceso ?? 0])
                        </div>
                    @else
                        <p class="text-sm text-gray-400 italic mt-0.5">Sin jefe asignado</p>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- Acciones --}}
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="flex items-center justify-between px-5 py-4">
            <div class="flex gap-2">
                <a href="{{ route('modulos.pdf', $modulo->id) }}"
                    class="flex items-center gap-2 text-sm font-medium text-emerald-700 bg-emerald-100 hover:bg-emerald-600 hover:text-white rounded-lg px-4 py-2.5 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                    PDF
                </a>
                <a href="{{ route('modulo.inventario', $modulo->id) }}"
                    class="flex items-center gap-2 text-sm font-medium text-blue-700 bg-blue-100 hover:bg-blue-600 hover:text-white rounded-lg px-4 py-2.5 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"/><path d="M12 22V12"/><path d="m3.3 7 7.703 4.734a2 2 0 0 0 1.994 0L20.7 7"/><path d="m7.5 4.27 9 5.15"/></svg>
                    Ver Inventario
                </a>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('modulos.edit', $modulo->id) }}"
                    class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-amber-700 bg-amber-100 hover:bg-amber-500 hover:text-white rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Editar
                </a>
                <button type="button" onclick="abrirEliminar({{ $modulo->id }}, '{{ addslashes($modulo->nombre) }}')"
                    class="flex items-center gap-2 text-sm font-medium text-red-700 bg-red-100 hover:bg-red-600 hover:text-white rounded-lg px-4 py-2.5 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                    Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

@include('modulo.modals.modal-delete')

@push('scripts')
<script>
    function abrirEliminar(id, nombre) {
        document.getElementById('deleteNombre').textContent = nombre;
        document.getElementById('deleteForm').action = '{{ url("modulos") }}/' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }
</script>
@endpush
@endsection