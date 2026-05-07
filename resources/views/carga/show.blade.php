@extends('layouts.app')
@section('title', 'Detalle de Carga')

@section('content')
<div class="px-4 py-6 mx-auto max-w-3xl bg-white/90 rounded-lg shadow-sm backdrop-blur-sm">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-brand flex items-center gap-2">
                <div class="p-2 bg-brand rounded text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package-search-icon lucide-package-search"><path d="M12 22V12"/><path d="M20.27 18.27 22 20"/><path d="M21 10.498V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.729l7 4a2 2 0 0 0 2 .001l.98-.559"/><path d="M3.29 7 12 12l8.71-5"/><path d="m7.5 4.27 8.997 5.148"/><circle cx="18.5" cy="16.5" r="2.5"/></svg>
                </div>
                Detalles de Cargas
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Registrada el {{ \Carbon\Carbon::parse($carga->created_at)->format('d/m/Y \a \l\a\s H:i') }}
            </p>
        </div>
        <a href="{{ route('cargas.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Volver
        </a>
    </div>

    @php
    $hoy = \Carbon\Carbon::today();
    $vence = \Carbon\Carbon::parse($carga->fecha_vencimiento);
    $diasLeft = $hoy->diffInDays($vence, false);
    if ($diasLeft < 0) $estado=['Vencida', 'bg-red-100' , 'text-red-700' , 'alert-circle' ]; elseif ($diasLeft <=30)
        $estado=['Próx. a vencer','bg-orange-100', 'text-orange-700' , 'alarm-clock' ]; elseif ($diasLeft <=90)
        $estado=['Por vencer', 'bg-yellow-100' , 'text-yellow-700' , 'clock' ]; else $estado=['Vigente', 'bg-green-100'
        , 'text-green-700' , 'check-circle' ]; @endphp <div
        class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">

        {{-- Banner --}}
        <div
            class="p-5 bg-linear-to-r from-primary-50 to-blue-50 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <p class="text-xs font-medium text-primary-600 uppercase tracking-wide mb-1">Vacuna</p>
                <h2 class="text-xl font-bold text-gray-900">{{ $carga->vacuna?->nombre ?? '—' }}</h2>
                @if($carga->vacuna?->marca)
                <p class="text-sm text-gray-500 mt-0.5">{{ $carga->vacuna->marca->nombre }}</p>
                @endif
            </div>
            <span
                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-semibold {{ $estado[1] }} {{ $estado[2] }}">
                <i data-lucide="{{ $estado[3] }}" class="w-4 h-4"></i>
                {{ $estado[0] }}
                @if($diasLeft >= 0)
                <span class="font-normal">&bull; {{ $diasLeft }} días</span>
                @endif
            </span>
        </div>

        {{-- Datos --}}
        <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">

            <div class="flex items-start gap-3">
                <div class="p-2 bg-gray-100 rounded-lg mt-0.5">
                    <i data-lucide="building-2" class="w-4 h-4 text-gray-500"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">ASIC</p>
                    <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $carga->asic?->nombre ?? '—' }}</p>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <div class="p-2 bg-gray-100 rounded-lg mt-0.5">
                    <i data-lucide="hash" class="w-4 h-4 text-gray-500"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Número de Lote</p>
                    <p class="text-sm font-mono font-semibold text-gray-900 mt-0.5">{{ $carga->lote }}</p>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <div class="p-2 bg-green-100 rounded-lg mt-0.5">
                    <i data-lucide="boxes" class="w-4 h-4 text-green-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Cantidad</p>
                    <p class="text-2xl font-bold text-gray-900 mt-0.5">
                        {{ number_format($carga->cantidad) }}
                        <span class="text-sm font-normal text-gray-500">dosis</span>
                    </p>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <div class="p-2 bg-blue-100 rounded-lg mt-0.5">
                    <i data-lucide="calendar" class="w-4 h-4 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Fecha de Llegada</p>
                    <p class="text-sm font-semibold text-gray-900 mt-0.5">
                        {{ \Carbon\Carbon::parse($carga->fecha_llegada)->format('d \d\e F, Y') }}
                    </p>
                </div>
            </div>

            <div class="flex items-start gap-3 sm:col-span-2">
                <div class="p-2 {{ $diasLeft <= 30 ? 'bg-red-100' : 'bg-gray-100' }} rounded-lg mt-0.5">
                    <i data-lucide="calendar-clock"
                        class="w-4 h-4 {{ $diasLeft <= 30 ? 'text-red-600' : 'text-gray-500' }}"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Fecha de Vencimiento</p>
                    <p
                        class="text-sm font-semibold mt-0.5 {{ $diasLeft < 0 ? 'text-red-600' : ($diasLeft <= 30 ? 'text-orange-600' : 'text-gray-900') }}">
                        {{ \Carbon\Carbon::parse($carga->fecha_vencimiento)->format('d \d\e F, Y') }}
                    </p>
                    @if($diasLeft >= 0)
                    <p class="text-xs text-gray-400 mt-0.5">{{ $diasLeft }} días restantes</p>
                    @else
                    <p class="text-xs text-red-500 mt-0.5">Venció hace {{ abs($diasLeft) }} días</p>
                    @endif
                </div>
            </div>

            @if($carga->observaciones)
            <div class="sm:col-span-2 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                <p class="text-xs text-gray-400 uppercase tracking-wide font-medium mb-2 flex items-center gap-1.5">
                    <i data-lucide="message-square" class="w-3.5 h-3.5"></i> Observaciones
                </p>
                <p class="text-sm text-gray-700">{{ $carga->observaciones }}</p>
            </div>
            @endif
        </div>

        {{-- Acciones --}}
        <div
            class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-4 border-t border-gray-100 bg-gray-50">
            <div class="flex items-center gap-2">
                <a href="{{ route('cargas.reporte.individual', $carga->id) }}"
                    class="flex items-center gap-2 px-4 py-2.5 font-medium text-sm bg-success text-white hover:text-success hover:bg-green-300 rounded-lg">
                    <i data-lucide="file-down" class="w-4 h-4"></i> Descargar PDF
                </a>
                <span class="text-gray-300">·</span>
                {{-- <a href="{{ route('cargas.clone', $carga->id) }}"
                    class="flex items-center gap-2 text-sm text-gray-600 hover:text-purple-700">
                    <i data-lucide="copy" class="w-4 h-4"></i> Clonar
                </a> --}}
            </div>
            <div class="flex gap-2">
                <button type="button"
                    onclick="abrirEliminar({{ $carga->id }}, '{{ addslashes($carga->vacuna?->nombre ?? '') }}', '{{ $carga->lote }}')"
                    class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-danger rounded-lg hover:text-danger hover:bg-red-300 focus:ring-4 focus:ring-danger-300">
                    <i data-lucide="trash-2" class="w-4 h-4"></i> Eliminar
                </button>
                <a href="{{ route('cargas.edit', $carga->id) }}"
                    class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-warning rounded-lg hover:bg-yellow-300 hover:text-warning focus:ring-4 focus:ring-warning-300">
                    <i data-lucide="square-pen" class="w-4 h-4"></i> Editar
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
            <h3 class="text-lg font-bold text-gray-900 mb-1">¿Eliminar esta carga?</h3>
            <p id="delVacuna" class="text-sm font-medium text-gray-700 mb-0.5"></p>
            <p id="delLote" class="text-xs text-gray-400 font-mono mb-4"></p>
            <p class="text-sm text-gray-500 mb-6">Esta acción es permanente y no se puede deshacer.</p>
            <div class="flex justify-center gap-3">
                <button onclick="document.getElementById('deleteModal').classList.add('hidden')"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancelar
                </button>
                <form id="deleteForm" method="POST">
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
    function abrirEliminar(id, vacuna, lote) {
        document.getElementById('delVacuna').textContent = 'Vacuna: ' + vacuna;
        document.getElementById('delLote').textContent   = 'Lote: ' + lote;
        document.getElementById('deleteForm').action = '{{ url("cargas") }}/' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }
</script>
@endpush
@endsection