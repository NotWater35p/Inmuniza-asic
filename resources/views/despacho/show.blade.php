@extends('layouts.app')
@section('title', 'Detalle de Despacho')

@section('content')
<div class="px-4 py-6 mx-auto max-w-3xl bg-white/85 rounded-lg shadow-sm backdrop-blur-sm">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-brand flex items-center gap-2">
                <div class="p-2 text-brand bg-blue-300 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-eye-icon lucide-eye">
                        <path
                            d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </div>
                Detalles del Despacho
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Registrado el {{ \Carbon\Carbon::parse($despacho->created_at)->format('d/m/Y \a \l\a\s H:i') }}
            </p>
        </div>
        <a href="{{ route('despachos.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Volver
        </a>
    </div>

    {{-- Banner vacuna + módulo --}}
    <div class="bg-linear-to-r from-primary-50 to-blue-50 border border-blue-200 rounded-lg p-5 mb-5">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <p class="text-xs font-semibold text-primary-600 uppercase tracking-wide mb-1">Vacuna Despachada</p>
                <h2 class="text-xl font-bold text-gray-900">{{ $despacho->vacuna?->nombre ?? '—' }}</h2>
                @if($despacho->vacuna?->marca)
                <p class="text-sm text-gray-500 mt-0.5">{{ $despacho->vacuna->marca->nombre }}</p>
                @endif
            </div>
            <div class="flex flex-col items-start sm:items-end gap-1">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Despacho ID</p>
                <span
                    class="font-mono text-sm font-bold text-gray-700 bg-white border border-gray-200 px-3 py-1 rounded-lg">
                    #{{ str_pad($despacho->id, 6, '0', STR_PAD_LEFT) }}
                </span>
            </div>
        </div>
    </div>

    {{-- Grid principal --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">

        {{-- Cantidad --}}
        <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i data-lucide="boxes" class="w-6 h-6 text-green-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Cantidad Despachada</p>
                    <p class="text-3xl font-bold text-gray-900 mt-0.5">{{ number_format($despacho->cantidad) }}</p>
                    <p class="text-xs text-gray-400">unidades</p>
                </div>
            </div>
        </div>

        {{-- Stock actual después del despacho --}}
        <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div
                    class="p-3 {{ $stockActual <= 0 ? 'bg-red-100' : ($stockActual <= 50 ? 'bg-orange-100' : 'bg-blue-100') }} rounded-lg">
                    <i data-lucide="package"
                        class="w-6 h-6 {{ $stockActual <= 0 ? 'text-red-600' : ($stockActual <= 50 ? 'text-orange-600' : 'text-blue-600') }}"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Stock Actual en ASIC</p>
                    <p
                        class="text-3xl font-bold {{ $stockActual <= 0 ? 'text-red-600' : ($stockActual <= 50 ? 'text-orange-600' : 'text-gray-900') }} mt-0.5">
                        {{ number_format($stockActual) }}
                    </p>
                    <p class="text-xs text-gray-400">unidades disponibles</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tarjeta de infomacion detallada --}}
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                <i data-lucide="info" class="w-4 h-4 text-primary-600"></i>
                Información del Despacho
            </h3>
        </div>

        <div class="divide-y divide-gray-50">

            {{-- Módulo destino --}}
            <div class="flex items-start gap-4 p-5">
                <div class="p-2 bg-purple-100 rounded-lg mt-0.5 shrink-0">
                    <i data-lucide="building-2" class="w-4 h-4 text-purple-600"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium mb-0.5">Módulo Destino</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $despacho->modulo?->nombre ?? '—' }}</p>
                    @if($despacho->modulo?->direccion)
                    <p class="text-xs text-gray-400 mt-0.5">{{ $despacho->modulo->direccion }}</p>
                    @endif
                    @if($despacho->modulo?->telefono)
                    <p class="text-xs text-gray-400 flex items-center gap-1 mt-0.5">
                        <i data-lucide="phone" class="w-3 h-3"></i>
                        {{ $despacho->modulo->telefono }}
                    </p>
                    @endif
                </div>
            </div>

            {{-- ASIC --}}
            <div class="flex items-start gap-4 p-5">
                <div class="p-2 bg-blue-100 rounded-lg mt-0.5 shrink-0">
                    <i data-lucide="home" class="w-4 h-4 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium mb-0.5">ASIC de Origen</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $despacho->asic?->nombre ?? '—' }}</p>
                </div>
            </div>

            {{-- Fecha --}}
            <div class="flex items-start gap-4 p-5">
                <div class="p-2 bg-gray-100 rounded-lg mt-0.5 shrink-0">
                    <i data-lucide="calendar" class="w-4 h-4 text-gray-500"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium mb-0.5">Fecha de Envío</p>
                    <p class="text-sm font-semibold text-gray-900">
                        {{ \Carbon\Carbon::parse($despacho->fecha_envio)->format('d \d\e F \d\e Y') }}
                    </p>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Hace {{ \Carbon\Carbon::parse($despacho->fecha_envio)->diffForHumans() }}
                    </p>
                </div>
            </div>

            {{-- Responsable --}}
            <div class="flex items-start gap-4 p-5">
                <div class="p-2 bg-green-100 rounded-lg mt-0.5 shrink-0">
                    <i data-lucide="user-check" class="w-4 h-4 text-green-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium mb-0.5">Responsable del Envío
                    </p>
                    @if($despacho->responsable)
                    <p class="text-sm font-semibold text-gray-900">
                        {{ $despacho->responsable->nombre }} {{ $despacho->responsable->apellido }}
                    </p>
                    <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-2">
                        <span>CI: {{ $despacho->responsable_envio }}</span>
                        @if($despacho->responsable->cargo)
                        <span>&bull; {{ $despacho->responsable->cargo->nombre }}</span>
                        @endif
                    </p>
                    @else
                    <p class="text-sm text-gray-500">CI: {{ $despacho->responsable_envio }}</p>
                    @endif
                </div>
            </div>

            {{-- Vacuna detalles --}}
            @if($despacho->vacuna)
            <div class="p-5 bg-gray-50">
                <p class="text-xs text-gray-400 uppercase tracking-wide font-medium mb-3 flex items-center gap-1.5">
                    <i data-lucide="syringe" class="w-3.5 h-3.5"></i>
                    Detalles de la Vacuna
                </p>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @if($despacho->vacuna->enfermedad)
                    <div>
                        <p class="text-xs text-gray-400">Enfermedad</p>
                        <p class="text-sm font-medium text-gray-700">{{ $despacho->vacuna->enfermedad }}</p>
                    </div>
                    @endif
                    @if($despacho->vacuna->via_administracion)
                    <div>
                        <p class="text-xs text-gray-400">Vía Admin.</p>
                        <p class="text-sm font-medium text-gray-700">{{ $despacho->vacuna->via_administracion }}</p>
                    </div>
                    @endif
                    @if($despacho->vacuna->numero_dosis)
                    <div>
                        <p class="text-xs text-gray-400">Nº Dosis</p>
                        <p class="text-sm font-medium text-gray-700">{{ $despacho->vacuna->numero_dosis }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- Footer acciones --}}
        <div
            class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-4 border-t border-gray-100 bg-gray-50">
            <a href="{{ route('despachos.reporte.modulo', $despacho->modulo_id) }}"
                class="flex items-center gap-2 text-sm text-success bg-green-300 hover:bg-success hover:text-white focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded-base px-4 py-2.5 focus:outline-none">
                <i data-lucide="file-down" class="w-4 h-4"></i>
                Reporte ( {{ $despacho->modulo?->nombre }} )
            </a>
            <div class="flex gap-2">
                <button type="button"
                    onclick="abrirEliminar({{ $despacho->id }}, '{{ addslashes($despacho->vacuna?->nombre ?? '') }}', '{{ addslashes($despacho->modulo?->nombre ?? '') }}')"
                    class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-danger bg-red-300 hover:bg-danger hover:text-white focus:ring-4 focus:ring-neutral-tertiary leading-5 rounded-base focus:outline-none">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                    Eliminar
                </button>
                <a href="{{ route('despachos.edit', $despacho->id) }}"
                    class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-warning bg-yellow-300 hover:bg-warning hover:text-white focus:ring-4 focus:ring-neutral-tertiary leading-5 rounded-base focus:outline-none">
                    <i data-lucide="square-pen" class="w-4 h-4"></i>
                    Editar
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Modal eliminar --}}
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
            <h3 class="text-lg font-bold text-gray-900 mb-1">¿Eliminar este despacho?</h3>
            <p id="delVacuna" class="text-sm font-medium text-gray-700 mb-0.5"></p>
            <p id="delModulo" class="text-xs text-gray-400 mb-2"></p>
            <p class="text-xs text-amber-600 font-medium mb-6 flex items-center justify-center gap-1">
                <i data-lucide="refresh-ccw" class="w-3.5 h-3.5"></i>
                Esto restaurará el stock de la vacuna en el ASIC.
            </p>
            <div class="flex justify-center gap-3">
                <button onclick="document.getElementById('deleteModal').classList.add('hidden')"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancelar
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        Sí, eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    lucide.createIcons();
    function abrirEliminar(id, vacuna, modulo) {
        document.getElementById('delVacuna').textContent = 'Vacuna: ' + vacuna;
        document.getElementById('delModulo').textContent = 'Módulo: ' + modulo;
        document.getElementById('deleteForm').action = '{{ url("despachos") }}/' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }
</script>
@endpush
@endsection