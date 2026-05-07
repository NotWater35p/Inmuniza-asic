@extends('layouts.app')
@section('title', 'Inventario de Vacunas')

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl bg-white/90 rounded-lg shadow backdrop-blur-sm">

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-blue-800 flex items-center gap-2">
                <div class="p-2 bg-blue-800 rounded text-white">
                    <i data-lucide="package-search" class="w-6 h-6"></i>
                </div>
                Inventario de Vacunas
            </h1>
            <p class="text-sm text-gray-500 mt-1">Stock actual del ASIC · calculado en tiempo real</p>
        </div>
        <button type="button" onclick="abrirModalPerdida()"
            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors shrink-0">
            <i data-lucide="triangle-alert" class="w-4 h-4"></i>
            Registrar Pérdida
        </button>
    </div>

    {{-- Alertas --}}
    @if(session('success'))
    <div class="flex items-center gap-3 p-4 mb-5 text-green-800 bg-green-50 border border-green-200 rounded-lg">
        <i data-lucide="check-circle-2" class="w-5 h-5 shrink-0"></i>
        <span class="text-sm font-medium">{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="ml-auto"><i data-lucide="x" class="w-4 h-4"></i></button>
    </div>
    @endif

    {{-- Buscador --}}
    <div class="mb-5">
        <form method="GET" action="{{ route('inventario.index') }}" class="flex gap-2">
            <div class="relative flex-1 max-w-sm">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Buscar vacuna..."
                    class="pl-9 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
            <button type="submit"
                class="px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                <i data-lucide="search" class="w-4 h-4"></i>
            </button>
            @if(request('search'))
            <a href="{{ route('inventario.index') }}"
                class="flex items-center px-3 py-2.5 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <i data-lucide="x" class="w-4 h-4"></i>
            </a>
            @endif
        </form>
    </div>

    {{-- Tabla --}}
    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-5 py-3">Vacuna</th>
                    <th class="px-5 py-3 text-center">Entrado</th>
                    <th class="px-5 py-3 text-center">Despachado</th>
                    <th class="px-5 py-3 text-center">Pérdidas</th>
                    <th class="px-5 py-3 text-center font-bold text-gray-700">Stock Actual</th>
                    <th class="px-5 py-3 text-center">Lotes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($vacunas as $vacuna)
                @php
                    $stock = $vacuna->stock_actual;
                    $stockClass = $stock <= 0
                        ? 'bg-red-100 text-red-700'
                        : ($stock <= $vacuna->stock_minimo
                            ? 'bg-amber-100 text-amber-700'
                            : 'bg-green-100 text-green-700');
                    $stockIcon = $stock <= 0 ? 'circle-x' : ($stock <= $vacuna->stock_minimo ? 'circle-alert' : 'circle-check');
                @endphp
                <tr class="bg-white hover:bg-gray-50 transition-colors">
                    {{-- Vacuna --}}
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="p-1.5 bg-blue-100 rounded-lg shrink-0">
                                <i data-lucide="syringe" class="w-4 h-4 text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $vacuna->nombre }}</p>
                                @if($vacuna->marca)
                                <p class="text-xs text-gray-400">{{ $vacuna->marca->nombre }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    {{-- Entrado --}}
                    <td class="px-5 py-4 text-center">
                        <span class="font-mono text-gray-700">{{ number_format($vacuna->total_entrado) }}</span>
                    </td>
                    {{-- Despachado --}}
                    <td class="px-5 py-4 text-center">
                        <span class="font-mono text-gray-700">{{ number_format($vacuna->total_despachado) }}</span>
                    </td>
                    {{-- Pérdidas --}}
                    <td class="px-5 py-4 text-center">
                        <span class="font-mono {{ $vacuna->total_perdido > 0 ? 'text-red-600 font-semibold' : 'text-gray-400' }}">
                            {{ number_format($vacuna->total_perdido) }}
                        </span>
                    </td>
                    {{-- Stock actual --}}
                    <td class="px-5 py-4 text-center">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $stockClass }}">
                            <i data-lucide="{{ $stockIcon }}" class="w-3.5 h-3.5"></i>
                            {{ number_format($stock) }}
                        </span>
                    </td>
                    {{-- Lotes --}}
                    <td class="px-5 py-4 text-center">
                        <button type="button"
                            onclick="verLotes({{ $vacuna->id }}, '{{ addslashes($vacuna->nombre) }}')"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                            <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                            Ver lotes
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-16 text-center text-gray-400">
                        <i data-lucide="package-open" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                        <p class="font-medium text-gray-500">No hay vacunas registradas</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Leyenda --}}
    <div class="mt-4 flex flex-wrap items-center gap-4 text-xs text-gray-500">
        <span class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded-full bg-green-400 inline-block"></span> Stock suficiente
        </span>
        <span class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded-full bg-amber-400 inline-block"></span> Stock bajo (≤ mínimo configurado)
        </span>
        <span class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded-full bg-red-400 inline-block"></span> Sin stock
        </span>
    </div>
</div>

{{-- ===== MODAL: Ver lotes ===== --}}
<div id="modalLotes" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg mx-4">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                <i data-lucide="layers" class="w-4 h-4 text-blue-600"></i>
                <span id="modalLotesTitle">Detalle por lotes</span>
            </h3>
            <button onclick="cerrarModalLotes()" class="p-1 hover:bg-gray-100 rounded-lg">
                <i data-lucide="x" class="w-4 h-4 text-gray-500"></i>
            </button>
        </div>
        <div class="p-5">
            <div id="modalLotesBody" class="overflow-x-auto">
                <div class="flex items-center justify-center py-8 text-gray-400">
                    <i data-lucide="loader-circle" class="w-6 h-6 animate-spin mr-2"></i>
                    Cargando...
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL: Registrar pérdida ===== --}}
<div id="modalPerdida" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                <i data-lucide="triangle-alert" class="w-4 h-4 text-red-600"></i>
                Registrar Pérdida
            </h3>
            <button onclick="cerrarModalPerdida()" class="p-1 hover:bg-gray-100 rounded-lg">
                <i data-lucide="x" class="w-4 h-4 text-gray-500"></i>
            </button>
        </div>
        <form action="{{ route('inventario.perdida.store') }}" method="POST" class="p-5 space-y-4">
            @csrf
            {{-- Vacuna --}}
            <div>
                <label class="block mb-1.5 text-sm font-medium text-gray-700">
                    Vacuna <span class="text-red-500">*</span>
                </label>
                <select name="vacuna_id" id="perdida_vacuna_id" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5">
                    <option value="">Seleccionar...</option>
                    @foreach($vacunas as $v)
                    <option value="{{ $v->id }}">{{ $v->nombre }}</option>
                    @endforeach
                </select>
            </div>
            {{-- Lote y Cantidad en grid --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block mb-1.5 text-sm font-medium text-gray-700">Lote</label>
                    <input type="text" name="lote" placeholder="LOT-2024-A"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5">
                </div>
                <div>
                    <label class="block mb-1.5 text-sm font-medium text-gray-700">
                        Cantidad <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="cantidad" min="1" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5">
                </div>
            </div>
            {{-- Motivo y Fecha --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block mb-1.5 text-sm font-medium text-gray-700">
                        Motivo <span class="text-red-500">*</span>
                    </label>
                    <select name="motivo" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5">
                        <option value="Vencimiento">Vencimiento</option>
                        <option value="Rotura">Rotura</option>
                        <option value="Cadena de frío">Cadena de frío</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-1.5 text-sm font-medium text-gray-700">
                        Fecha <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="fecha" required
                        value="{{ date('Y-m-d') }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5">
                </div>
            </div>
            {{-- Observación --}}
            <div>
                <label class="block mb-1.5 text-sm font-medium text-gray-700">Observación</label>
                <textarea name="observacion" rows="2" placeholder="Detalles adicionales..."
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5"></textarea>
            </div>
            {{-- Footer --}}
            <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                <button type="button" onclick="cerrarModalPerdida()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancelar
                </button>
                <button type="submit"
                    class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Registrar
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    lucide.createIcons();

    // ---- Modal Lotes ----
    function verLotes(id, nombre) {
        document.getElementById('modalLotesTitle').textContent = nombre + ' — Lotes';
        document.getElementById('modalLotesBody').innerHTML = `
            <div class="flex items-center justify-center py-8 text-gray-400">
                <i data-lucide="loader-circle" class="w-6 h-6 animate-spin mr-2"></i> Cargando...
            </div>`;
        document.getElementById('modalLotes').classList.remove('hidden');
        lucide.createIcons();

        fetch(`/inventario/lotes/${id}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (!data.lotes.length) {
                document.getElementById('modalLotesBody').innerHTML = `
                    <p class="text-center text-gray-400 py-6 text-sm">No hay lotes registrados para esta vacuna.</p>`;
                return;
            }
            let rows = data.lotes.map(l => `
                <tr class="border-b border-gray-50 last:border-0">
                    <td class="py-2.5 pr-3 font-mono text-xs text-gray-700">${l.lote}</td>
                    <td class="py-2.5 px-3 text-center text-xs">${l.fecha_vencimiento ?? '—'}</td>
                    <td class="py-2.5 px-3 text-center text-xs font-mono">${l.entrado}</td>
                    <td class="py-2.5 px-3 text-center text-xs font-mono text-orange-600">${l.despachado}</td>
                    <td class="py-2.5 px-3 text-center text-xs font-mono text-red-500">${l.perdido}</td>
                    <td class="py-2.5 pl-3 text-center">
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold ${l.disponible <= 0 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'}">
                            ${l.disponible}
                        </span>
                    </td>
                </tr>`).join('');

            document.getElementById('modalLotesBody').innerHTML = `
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-gray-400 uppercase border-b border-gray-100">
                            <th class="pb-2 pr-3 text-left">Lote</th>
                            <th class="pb-2 px-3 text-center">Vence</th>
                            <th class="pb-2 px-3 text-center">Entrado</th>
                            <th class="pb-2 px-3 text-center">Despachado</th>
                            <th class="pb-2 px-3 text-center">Perdido</th>
                            <th class="pb-2 pl-3 text-center">Disponible</th>
                        </tr>
                    </thead>
                    <tbody>${rows}</tbody>
                </table>`;
        })
        .catch(() => {
            document.getElementById('modalLotesBody').innerHTML = `
                <p class="text-center text-red-500 py-6 text-sm">Error al cargar los lotes.</p>`;
        });
    }

    function cerrarModalLotes() {
        document.getElementById('modalLotes').classList.add('hidden');
    }

    // ---- Modal Pérdida ----
    function abrirModalPerdida(vacunaId = null) {
        if (vacunaId) document.getElementById('perdida_vacuna_id').value = vacunaId;
        document.getElementById('modalPerdida').classList.remove('hidden');
    }

    function cerrarModalPerdida() {
        document.getElementById('modalPerdida').classList.add('hidden');
    }

    // Cerrar modales al hacer clic fuera
    ['modalLotes', 'modalPerdida'].forEach(id => {
        document.getElementById(id).addEventListener('click', function(e) {
            if (e.target === this) this.classList.add('hidden');
        });
    });
</script>
@endpush
@endsection