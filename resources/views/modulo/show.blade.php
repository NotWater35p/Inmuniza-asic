@extends('layouts.app')
@section('title', $modulo->nombre . ' | Módulo')

@section('content')
<div class="px-4 py-6 mx-auto max-w-3xl bg-white/90 rounded-lg shadow backdrop-blur-sm">

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-red-800 flex items-center gap-2">
                <div class="p-2 bg-red-800 rounded text-white">
                    <i data-lucide="hospital" class="w-6 h-6"></i>
                </div>
                Detalles del Módulo
            </h1>
        </div>
        <a href="{{ route('modulos.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Volver
        </a>
    </div>

    {{-- Banner --}}
    <div class="bg-linear-to-r from-purple-600 to-indigo-600 rounded-xl p-6 mb-5 text-white">
        <div class="flex items-start justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                    <i data-lucide="hospital" class="w-7 h-7 text-white"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold">{{ $modulo->nombre }}</h2>
                    <span class="text-sm font-mono text-purple-200 mt-0.5 block">{{ $modulo->rif }}</span>
                    <span class="text-xs text-purple-300 mt-1 block">{{ $modulo->asic->nombre ?? '—' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Datos --}}
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-5">
        <div class="p-4 border-b border-gray-100 flex items-center gap-2">
            <i data-lucide="info" class="w-4 h-4 text-purple-600"></i>
            <h3 class="text-sm font-semibold text-gray-800">Información del Módulo</h3>
        </div>

        <div class="divide-y divide-gray-50">

            {{-- Dirección --}}
            <div class="flex items-start gap-4 p-5">
                <div class="p-2 bg-gray-100 rounded-lg shrink-0 mt-0.5">
                    <i data-lucide="map-pin" class="w-4 h-4 text-gray-500"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Dirección</p>
                    <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $modulo->direccion ?? '—' }}</p>
                </div>
            </div>


            {{-- Tipo de Establecimiento --}}
            <div class="flex items-start gap-4 p-5">
                <div class="p-2 bg-indigo-100 rounded-lg shrink-0 mt-0.5">
                    <i data-lucide="building-2" class="w-4 h-4 text-indigo-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Tipo de Establecimiento</p>
                    <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $modulo->tipo_establecimiento ?? '—' }}</p>
                </div>
            </div>

            {{-- Municipio --}}
            <div class="flex items-start gap-4 p-5">
                <div class="p-2 bg-blue-100 rounded-lg shrink-0 mt-0.5">
                    <i data-lucide="map" class="w-4 h-4 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Municipio</p>
                    <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $modulo->municipio ?? '—' }}</p>
                </div>
            </div>

            {{-- Parroquia --}}
            <div class="flex items-start gap-4 p-5">
                <div class="p-2 bg-cyan-100 rounded-lg shrink-0 mt-0.5">
                    <i data-lucide="map-pinned" class="w-4 h-4 text-cyan-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Parroquia</p>
                    <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $modulo->parroquia ?? '—' }}</p>
                </div>
            </div>

            {{-- Teléfono --}}
            <div class="flex items-start gap-4 p-5">
                <div class="p-2 bg-gray-100 rounded-lg shrink-0">
                    <i data-lucide="phone" class="w-4 h-4 text-gray-500"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Teléfono</p>
                    <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $modulo->telefono ?? '—' }}</p>
                </div>
            </div>

            {{-- Jefe de módulo --}}
            <div class="flex items-start gap-4 p-5">
                <div class="p-2 bg-yellow-100 rounded-lg shrink-0">
                    <i data-lucide="user-check" class="w-4 h-4 text-yellow-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Jefe de Módulo</p>
                    @if($modulo->jefe)
                    <p class="text-sm font-semibold text-gray-900 mt-0.5">
                        {{ $modulo->jefe->nombre }} {{ $modulo->jefe->apellido }}
                    </p>
                    <div class="flex items-center gap-3 mt-1.5">
                        <span class="text-xs font-mono text-gray-500">CI: {{ $modulo->jefe->cedula }}</span>
                        @if($modulo->jefe->telefono)
                        <span class="text-xs text-gray-400 flex items-center gap-1">
                            <i data-lucide="phone" class="w-3 h-3"></i>
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
                    <i data-lucide="file-down" class="w-4 h-4"></i>
                    PDF
                </a>
                <a href="{{ route('modulo.inventario', $modulo->id) }}"
                    class="flex items-center gap-2 text-sm font-medium text-blue-700 bg-blue-100 hover:bg-blue-600 hover:text-white rounded-lg px-4 py-2.5 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z" />
                        <path d="M12 22V12" />
                        <path d="m3.3 7 7.703 4.734a2 2 0 0 0 1.994 0L20.7 7" />
                        <path d="m7.5 4.27 9 5.15" />
                    </svg>
                    Ver Inventario
                </a>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('modulos.edit', $modulo->id) }}"
                    class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-amber-700 bg-amber-100 hover:bg-amber-500 hover:text-white rounded-lg transition-colors">
                    <i data-lucide="square-pen" class="w-4 h-4"></i>
                    Editar
                </a>
                <button type="button" onclick="abrirEliminar({{ $modulo->id }}, '{{ addslashes($modulo->nombre) }}')"
                    class="flex items-center gap-2 text-sm font-medium text-red-700 bg-red-100 hover:bg-red-600 hover:text-white rounded-lg px-4 py-2.5 transition-colors">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                    Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

@include('modulo.modals.modal-delete')

@push('scripts')
<script>
    lucide.createIcons();
    function abrirEliminar(id, nombre) {
        document.getElementById('deleteNombre').textContent = nombre;
        document.getElementById('deleteForm').action = '{{ url("modulos") }}/' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }
</script>
@endpush
@endsection