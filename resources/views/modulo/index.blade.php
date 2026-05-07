@extends('layouts.app')
@section('title', 'Módulos Afiliados')

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl bg-white/90 rounded-lg shadow-sm backdrop-blur-sm">

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-red-800 flex items-center gap-2">
                <div class="p-2 bg-red-800 rounded text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-heart-handshake-icon lucide-heart-handshake">
                        <path
                            d="M19.414 14.414C21 12.828 22 11.5 22 9.5a5.5 5.5 0 0 0-9.591-3.676.6.6 0 0 1-.818.001A5.5 5.5 0 0 0 2 9.5c0 2.3 1.5 4 3 5.5l5.535 5.362a2 2 0 0 0 2.879.052 2.12 2.12 0 0 0-.004-3 2.124 2.124 0 1 0 3-3 2.124 2.124 0 0 0 3.004 0 2 2 0 0 0 0-2.828l-1.881-1.882a2.41 2.41 0 0 0-3.409 0l-1.71 1.71a2 2 0 0 1-2.828 0 2 2 0 0 1 0-2.828l2.823-2.762" />
                    </svg>
                </div>
                Módulos Afiliados
            </h1>
        </div>
        <div class="flex gap-2 shrink-0">
            <a href="{{ route('modulos.pdf.universal') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors">
                <i data-lucide="file-text" class="w-4 h-4"></i>
                Reporte
            </a>
            <a href="{{ route('modulos.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Nuevo Módulo
            </a>
        </div>
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
        <button onclick="this.parentElement.remove()" class="ml-auto"><i data-lucide="x" class="w-4 h-4"></i></button>
    </div>
    @endif

    {{-- Barra de búsqueda --}}
    <div class="mb-6">
        <form method="GET" action="{{ route('modulos.index') }}" class="flex gap-2">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Buscar por nombre, RIF o responsable..."
                    class="pl-9 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
            <button type="submit"
                class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-300 rounded-lg hover:text-white hover:bg-gray-500 transition-colors">
                <i data-lucide="search" class="w-4 h-4"></i>
            </button>
            @if(request('search'))
            <a href="{{ route('modulos.index') }}"
                class="flex items-center gap-1 px-3 py-2.5 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <i data-lucide="x" class="w-4 h-4"></i>
            </a>
            @endif
        </form>
    </div>

    @php
    $gradients = [
    'from-red-600 = to-red-500',
    'from-blue-600 = to-blue-500',
    'from-emerald-600 = to-emerald-500',
    'from-violet-600 = to-violet-500',
    'from-amber-600 = to-amber-500',
    ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($modulos as $modulo)
        @php
        $gradient = $gradients[$modulo->id % count($gradients)];
        @endphp
        <div
            class="bg-linear-to-r {{ $gradient }} rounded-xl p-6 text-white shadow-md hover:shadow-lg transition-shadow flex flex-col h-full">

            {{-- Icono y nombre --}}
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-white/20 rounded-xl shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class=" lucide lucide-hospital-icon lucide-hospital">
                        <path d="M12 7v4" />
                        <path d="M14 21v-3a2 2 0 0 0-4 0v3" />
                        <path d="M14 9h-4" />
                        <path d="M18 11h2a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-9a2 2 0 0 1 2-2h2" />
                        <path d="M18 21V5a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16" />
                    </svg>
                </div>
                <div>
                    <p class="text-white/70 text-xs font-semibold uppercase tracking-widest mb-1">Módulo Afiliado</p>
                    <h3 class="text-xl font-bold leading-tight">{{ $modulo->nombre }}</h3>
                </div>
            </div>

            {{-- Datos --}}
            <div class="space-y-2 flex-1">
                <dl>
                    <dt class="font-semibold text-white/80 text-sm">RIF</dt>
                    <dd class="text-white font-mono">{{ $modulo->rif }}</dd>
                </dl>
                @if($modulo->direccion)
                <dl>
                    <dt class="font-semibold text-white/80 text-sm flex items-center gap-1">
                        <i data-lucide="map-pin" class="w-4 h-4"></i> Dirección
                    </dt>
                    <dd class="text-white text-sm leading-snug">{{ $modulo->direccion }}</dd>
                </dl>
                @endif
                @if($modulo->telefono)
                <dl>
                    <dt class="font-semibold text-white/80 text-sm flex items-center gap-1">
                        <i data-lucide="phone" class="w-4 h-4"></i> Teléfono
                    </dt>
                    <dd class="text-white">{{ $modulo->telefono }}</dd>
                </dl>
                @endif
                <dl>
                    <dt class="font-semibold text-white/80 text-sm flex items-center gap-1">
                        <i data-lucide="user-check" class="w-4 h-4"></i> Jefe
                    </dt>
                    <dd class="text-white">
                        @if($modulo->jefe)
                        {{ $modulo->jefe->nombre }} {{ $modulo->jefe->apellido }}
                        @else
                        <span class="text-white/50 italic">Sin jefe asignado</span>
                        @endif
                    </dd>
                </dl>
            </div>

            {{-- Botón --}}
            <div class="mt-4 pt-3 border-t border-white/20">
                <a href="{{ route('modulos.show', $modulo->id) }}"
                    class="inline-flex items-center justify-center gap-2 w-full py-2.5 bg-white/20 hover:bg-white/30 text-white font-medium rounded-lg transition-colors text-sm">
                    Ver detalles
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center">
            <div class="flex flex-col items-center gap-3 text-gray-400">
                <i data-lucide="hospital" class="w-14 h-14 text-gray-300"></i>
                <div>
                    <p class="font-semibold text-gray-500">No se encontraron módulos</p>
                    <p class="text-sm mt-1">
                        @if(request('search'))
                        Ajusta el término de búsqueda.
                        @else
                        <a href="{{ route('modulos.create') }}" class="text-blue-600 hover:underline">Registra el primer
                            módulo</a>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    {{-- Paginación --}}
    @if($modulos->hasPages())
    <div class="mt-6">
        {!! $modulos->withQueryString()->links() !!}
    </div>
    @endif
</div>

{{-- Modal de eliminación (se accede desde el show) --}}
<div id="deleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50">
    <div class="bg-white rounded-xl shadow-xl max-w-sm w-full mx-4 p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="p-2 bg-red-100 rounded-full">
                <i data-lucide="triangle-alert" class="w-5 h-5 text-red-600"></i>
            </div>
            <h3 class="text-base font-semibold text-gray-900">Eliminar módulo</h3>
        </div>
        <p class="text-sm text-gray-600 mb-5">
            ¿Estás seguro de eliminar <strong id="deleteNombre" class="text-gray-900"></strong>? Esta acción no se puede
            deshacer.
        </p>
        <div class="flex justify-end gap-3">
            <button onclick="document.getElementById('deleteModal').classList.add('hidden')"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                Cancelar
            </button>
            <form id="deleteForm" method="POST">
                @csrf @method('DELETE')
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                    Sí, eliminar
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    lucide.createIcons();
    window.abrirEliminar = function(id, nombre) {
        document.getElementById('deleteNombre').textContent = nombre;
        document.getElementById('deleteForm').action = '{{ url("modulos") }}/' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }
</script>
@endpush
@endsection