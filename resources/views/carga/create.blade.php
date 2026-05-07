@extends('layouts.app')
@section('title', 'Registrar Carga')

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl bg-white/90 rounded-lg shadow-sm backdrop-blur-sm">

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-teal-800 flex items-center gap-2">
                <div class="p-2 bg-teal-800 rounded text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-package-plus-icon lucide-package-plus">
                        <path d="M12 22V12" />
                        <path d="M16 17h6" />
                        <path d="M19 14v6" />
                        <path
                            d="M21 10.535V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.729l7 4a2 2 0 0 0 2 .001l1.675-.955" />
                        <path d="M3.29 7 12 12l8.71-5" />
                        <path d="m7.5 4.27 8.997 5.148" />
                    </svg>
                </div>
                Registrar Cargas
            </h1>
            <p class="text-sm text-gray-500 mt-1">Ingresa una o varias cargas de vacunas al ASIC</p>
        </div>
        <a href="{{ route('cargas.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Volver
        </a>
    </div>

    {{-- Aviso si viene de clonar --}}
    @if(isset($clonado_de))
    <div class="flex items-center gap-3 p-4 mb-5 bg-purple-50 border border-purple-200 rounded-lg text-purple-700">
        <i data-lucide="copy" class="w-5 h-5 shrink-0"></i>
        <span class="text-sm font-medium">
            Clonando desde lote <strong>{{ $clonado_de->lote }}</strong> ({{ $clonado_de->vacuna?->nombre }}) — Modifica
            los campos antes de guardar.
        </span>
    </div>
    @endif

    @if($errors->any())
    <div class="flex items-start gap-3 p-4 mb-5 text-red-800 bg-red-50 border border-red-200 rounded-lg">
        <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 mt-0.5"></i>
        <ul class="text-sm list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    {{-- TABS --}}
    <div class="mb-0">
        <div class="flex border-b border-gray-200">
            <button id="tab-individual" onclick="switchTab('individual')"
                class="tab-btn flex items-center gap-2 px-5 py-3 text-sm font-medium border-b-2 border-primary-600 text-primary-600 -mb-px">
                <i data-lucide="file-plus" class="w-4 h-4"></i>
                Registro Individual
            </button>
            <button id="tab-lote" onclick="switchTab('lote')"
                class="tab-btn flex items-center gap-2 px-5 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 -mb-px">
                <i data-lucide="files" class="w-4 h-4"></i>
                Registro en Lote
            </button>
        </div>
    </div>

    {{-- ====== TAB INDIVIDUAL ====== --}}
    <div id="panel-individual" class="tab-panel">
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="p-5 border-b border-gray-100 flex items-center gap-2">
                <i data-lucide="syringe" class="w-4 h-4 text-primary-600"></i>
                <h2 class="text-base font-semibold text-gray-800">Datos de la Carga</h2>
            </div>

            <form method="POST" action="{{ route('cargas.store') }}">
                @csrf
                <input type="hidden" name="asic_id" value="{{ $asic->id }}">

                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">

                    {{-- Vacuna --}}
                    <div class="sm:col-span-2">
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Vacuna <span class="text-red-500">*</span>
                        </label>
                        <input type="hidden" name="vacuna_id" id="si_vacuna_hidden"
                            value="{{ old('vacuna_id', $carga->vacuna_id ?? '') }}">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="syringe" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <input type="text" id="si_vacuna_input" placeholder="Escribe para buscar vacuna..."
                                autocomplete="off"
                                value="{{ old('vacuna_id') ? $vacunas->firstWhere('id', old('vacuna_id'))?->nombre : ($carga->vacuna?->nombre ?? '') }}"
                                class="pl-9 pr-4 bg-gray-50 border {{ $errors->has('vacuna_id') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        </div>
                        @error('vacuna_id')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Lote --}}
                    <div>
                        <label for="lote" class="block mb-1.5 text-sm font-medium text-gray-700">
                            Número de Lote <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="hash" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <input type="text" name="lote" id="lote" value="{{ old('lote') }}"
                                placeholder="Ej: LOT-2024-001"
                                class="pl-9 bg-gray-50 border {{ $errors->has('lote') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        </div>
                        @error('lote')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Cantidad --}}
                    <div>
                        <label for="cantidad" class="block mb-1.5 text-sm font-medium text-gray-700">
                            Cantidad (dosis) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="boxes" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <input type="number" name="cantidad" id="cantidad" min="1" value="{{ old('cantidad') }}"
                                placeholder="0"
                                class="pl-9 bg-gray-50 border {{ $errors->has('cantidad') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        </div>
                        @error('cantidad')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Fecha llegada --}}
                    <div>
                        <label for="fecha_llegada" class="block mb-1.5 text-sm font-medium text-gray-700">
                            Fecha de Llegada <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <input type="date" name="fecha_llegada" id="fecha_llegada"
                                value="{{ old('fecha_llegada', date('Y-m-d')) }}"
                                class="pl-9 bg-gray-50 border {{ $errors->has('fecha_llegada') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        </div>
                        @error('fecha_llegada')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Fecha vencimiento --}}
                    <div>
                        <label for="fecha_vencimiento" class="block mb-1.5 text-sm font-medium text-gray-700">
                            Fecha de Vencimiento <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="calendar-clock" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <input type="date" name="fecha_vencimiento" id="fecha_vencimiento"
                                value="{{ old('fecha_vencimiento') }}"
                                class="pl-9 bg-gray-50 border {{ $errors->has('fecha_vencimiento') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        </div>
                        @error('fecha_vencimiento')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Observaciones --}}
                    <div class="sm:col-span-2">
                        <label for="observaciones" class="block mb-1.5 text-sm font-medium text-gray-700">
                            Observaciones <span class="text-gray-400 font-normal">(opcional)</span>
                        </label>
                        <textarea name="observaciones" id="observaciones" rows="3"
                            placeholder="Condiciones de llegada, proveedor, notas relevantes..."
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">{{ old('observaciones', $carga->observaciones ?? '') }}</textarea>
                        @error('observaciones')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div
                    class="mx-5 mb-5 p-3 bg-blue-50 border border-blue-100 rounded-lg flex items-center gap-2.5 text-sm text-blue-700">
                    <i data-lucide="building-2" class="w-4 h-4 shrink-0"></i>
                    <span>Carga registrada al: <strong>{{ $asic->nombre }}</strong></span>
                </div>

                <div
                    class="flex items-center justify-end gap-3 px-5 py-4 border-t border-gray-100 bg-gray-50 rounded-b-lg">
                    <a href="{{ route('cargas.index') }}"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancelar</a>
                    <button type="submit"
                        class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white  bg-success rounded-lg hover:bg-success-strong  focus:ring-4 focus:ring-primary-300">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Registrar Carga
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ====== TAB EN LOTE ====== --}}
    <div id="panel-lote" class="tab-panel hidden">
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="table" class="w-4 h-4 text-primary-600"></i>
                    <h2 class="text-base font-semibold text-gray-800">Registro Masivo</h2>
                    <span class="px-2.5 py-0.5 text-xs font-medium bg-blue-100 text-blue-700 rounded-full"
                        id="bulk_row_count">1 fila</span>
                </div>
                <button type="button" onclick="bulkAgregarFila()"
                    class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i>
                    Agregar fila
                </button>
            </div>

            <div
                class="mx-5 mt-4 p-3 bg-amber-50 border border-amber-100 rounded-lg flex items-start gap-2.5 text-sm text-amber-700">
                <i data-lucide="info" class="w-4 h-4 shrink-0 mt-0.5"></i>
                <p>Cada fila representa un lote diferente. Puedes copiar la fecha de llegada y vencimiento de la primera
                    fila a todas.</p>
            </div>

            <form method="POST" action="{{ route('cargas.store.bulk') }}">
                @csrf
                <input type="hidden" name="asic_id" value="{{ $asic->id }}">

                <div class="p-5">
                    {{-- Controles rápidos --}}
                    <div
                        class="flex flex-wrap items-center gap-3 mb-4 p-3 bg-gray-50 border border-gray-200 rounded-lg text-xs">
                        <span class="font-medium text-gray-500 flex items-center gap-1">
                            <i data-lucide="copy" class="w-3.5 h-3.5"></i> Copiar primera fila:
                        </span>
                        <button type="button" onclick="bulkCopiarFechaLlegada()"
                            class="flex items-center gap-1 px-2.5 py-1.5 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700 font-medium">
                            <i data-lucide="calendar" class="w-3 h-3"></i> F. Llegada a todas
                        </button>
                        <button type="button" onclick="bulkCopiarFechaVencimiento()"
                            class="flex items-center gap-1 px-2.5 py-1.5 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700 font-medium">
                            <i data-lucide="calendar-clock" class="w-3 h-3"></i> F. Vencimiento a todas
                        </button>
                        <div class="ml-auto flex items-center gap-2">
                            <span class="text-gray-400">Total dosis:</span>
                            <span id="bulk_total" class="font-bold text-primary-700 text-sm">0</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="w-full text-sm" style="overflow:visible;">
                            <thead>
                                <tr class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                                    <th class="px-3 py-2.5 text-left font-semibold" style="width:220px">Vacuna *</th>
                                    <th class="px-3 py-2.5 text-left font-semibold" style="width:150px">Lote *</th>
                                    <th class="px-3 py-2.5 text-left font-semibold" style="width:130px">F. Llegada *
                                    </th>
                                    <th class="px-3 py-2.5 text-left font-semibold" style="width:130px">F. Vencimiento *
                                    </th>
                                    <th class="px-3 py-2.5 text-left font-semibold" style="width:110px">Cantidad *</th>
                                    <th class="px-3 py-2.5 text-left font-semibold">Observaciones</th>
                                    <th class="px-3 py-2.5" style="width:40px"></th>
                                </tr>
                            </thead>
                            <tbody id="bulk_tbody" class="divide-y divide-gray-100"></tbody>
                        </table>
                    </div>
                </div>

                <div
                    class="mx-5 mb-5 p-3 bg-blue-50 border border-blue-100 rounded-lg flex items-center gap-2.5 text-sm text-blue-700">
                    <i data-lucide="building-2" class="w-4 h-4 shrink-0"></i>
                    <span>Cargas registradas al: <strong>{{ $asic->nombre }}</strong></span>
                </div>

                <div
                    class="flex items-center justify-between gap-3 px-5 py-4 border-t border-gray-100 bg-gray-50 rounded-b-lg">
                    <p class="text-xs text-gray-400 flex items-center gap-1">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                        Todos los campos marcados con * son obligatorios
                    </p>
                    <div class="flex gap-2">
                        <a href="{{ route('cargas.index') }}"
                            class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancelar</a>
                        <button type="submit"
                            class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-success rounded-lg hover:bg-success-strong focus:ring-4 focus:ring-primary-300">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            Guardar todas las cargas
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Dropdown flotante universal --}}
<div id="floating_dropdown"
    class="hidden fixed z-[9999] bg-white border border-gray-200 rounded-lg shadow-xl overflow-y-auto"
    style="max-height:220px; min-width:200px;" onmousedown="event.preventDefault()">
</div>

@push('scripts')
<script>
    lucide.createIcons();

    // ======= TABS =======
    function switchTab(tab) {
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('border-primary-600','text-primary-600');
            b.classList.add('border-transparent','text-gray-500');
        });
        document.getElementById('panel-' + tab).classList.remove('hidden');
        const btn = document.getElementById('tab-' + tab);
        btn.classList.add('border-primary-600','text-primary-600');
        btn.classList.remove('border-transparent','text-gray-500');
    }

    // ======= DATOS =======
    const VACUNAS = @json($vacunas->map(fn($v) => ['id' => $v->id, 'nombre' => $v->nombre]));

    // ======= DROPDOWN FLOTANTE UNIVERSAL =======
    let activeInput = null, activeHidden = null, activeCallback = null;
    let scrollHandler = null, resizeHandler = null;
    const floatDD = document.getElementById('floating_dropdown');

    function openFloatingDD(inputEl, hiddenEl, items, renderFn, onSelect) {
        activeInput = inputEl; activeHidden = hiddenEl; activeCallback = onSelect;
        renderFloatItems(items, inputEl.value, renderFn, onSelect);
        posicionarDD(inputEl);
        floatDD.classList.remove('hidden');

        if (scrollHandler) window.removeEventListener('scroll', scrollHandler);
        if (resizeHandler) window.removeEventListener('resize', resizeHandler);
        scrollHandler = () => { if (activeInput) requestAnimationFrame(() => posicionarDD(activeInput)); };
        resizeHandler  = () => { if (activeInput) requestAnimationFrame(() => posicionarDD(activeInput)); };
        window.addEventListener('scroll', scrollHandler, { passive: true });
        window.addEventListener('resize', resizeHandler);
    }

    function posicionarDD(inputEl) {
        if (!inputEl) return;
        const rect = inputEl.getBoundingClientRect();
        const spaceAbove = rect.top;
        const spaceBelow = window.innerHeight - rect.bottom;
        const ddH = Math.min(220, floatDD.scrollHeight || 220);
        floatDD.style.width = rect.width + 'px';
        floatDD.style.left  = rect.left  + 'px';
        if (spaceBelow < ddH + 8 && spaceAbove > spaceBelow) {
            floatDD.style.top = (rect.top - ddH - 2) + 'px';
        } else {
            floatDD.style.top = (rect.bottom + 2) + 'px';
        }
    }

    function renderFloatItems(items, query, renderFn, onSelect) {
        const q = query.toLowerCase();
        floatDD.innerHTML = '';
        let vis = 0;
        items.forEach(item => {
            if (!item.nombre.toLowerCase().includes(q)) return;
            const div = document.createElement('div');
            div.className = 'px-3 py-2.5 cursor-pointer hover:bg-primary-50 hover:text-primary-700 text-gray-700 text-sm';
            div.innerHTML = renderFn(item);
            div.addEventListener('mousedown', () => {
                activeInput.value  = item.nombre;
                activeHidden.value = item.id;
                floatDD.classList.add('hidden');
                if (onSelect) onSelect(item.id, item.nombre);
            });
            floatDD.appendChild(div);
            vis++;
        });
        if (vis === 0) floatDD.innerHTML = '<div class="px-3 py-3 text-xs text-gray-400 text-center">Sin resultados</div>';
    }

    function closeFloatingDD() {
        floatDD.classList.add('hidden');
        if (scrollHandler) window.removeEventListener('scroll', scrollHandler);
        if (resizeHandler) window.removeEventListener('resize', resizeHandler);
        activeInput = null;
    }

    document.addEventListener('click', e => {
        if (activeInput && !activeInput.contains(e.target) && !floatDD.contains(e.target)) closeFloatingDD();
    });
    window.addEventListener('scroll', () => {
        if (activeInput && !floatDD.classList.contains('hidden')) posicionarDD(activeInput);
    }, true);

    // ======= RENDER VACUNA =======
    const renderVacuna = v => `<span>${v.nombre}</span>`;

    // ======= INDIVIDUAL searchable =======
    function initSingle(inputId, hiddenId, items, renderFn, onSelect) {
        const input  = document.getElementById(inputId);
        const hidden = document.getElementById(hiddenId);
        if (!input) return;
        input.addEventListener('focus', () => openFloatingDD(input, hidden, items, renderFn, onSelect));
        input.addEventListener('input', () => {
            if (floatDD.classList.contains('hidden')) openFloatingDD(input, hidden, items, renderFn, onSelect);
            else { renderFloatItems(items, input.value, renderFn, onSelect); posicionarDD(input); }
            if (!input.value) hidden.value = '';
        });
    }
    initSingle('si_vacuna_input', 'si_vacuna_hidden', VACUNAS, renderVacuna);

    // ======= BULK TABLE =======
    let bulkRowIdx = 0;

    function bulkAgregarFila() {
        const idx = bulkRowIdx++;
        const tr  = document.createElement('tr');
        tr.id = `brow_${idx}`;
        tr.className = 'border-b border-gray-100';
        tr.innerHTML = bulkBuildRow(idx);
        document.getElementById('bulk_tbody').appendChild(tr);
        lucide.createIcons();
        bulkInitRow(idx);
        bulkActualizarContadores();
    }

    function bulkBuildRow(idx) {
        return `
        <td class="px-2 py-2 align-top">
            <input type="hidden" name="cargas[${idx}][vacuna_id]" id="bv_h_${idx}">
            <input type="text" id="bv_i_${idx}" placeholder="Buscar vacuna..." autocomplete="off"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2">
        </td>
        <td class="px-2 py-2 align-top">
            <input type="text" name="cargas[${idx}][lote]" placeholder="LOT-2024-..."
                class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2">
        </td>
        <td class="px-2 py-2 align-top">
            <input type="date" name="cargas[${idx}][fecha_llegada]"
                value="${new Date().toISOString().split('T')[0]}"
                class="bulk-fecha-llegada bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2">
        </td>
        <td class="px-2 py-2 align-top">
            <input type="date" name="cargas[${idx}][fecha_vencimiento]"
                class="bulk-fecha-vencimiento bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2">
        </td>
        <td class="px-2 py-2 align-top">
            <input type="number" name="cargas[${idx}][cantidad]" min="1" placeholder="0"
                oninput="bulkActualizarContadores()"
                class="bulk-cantidad bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2">
        </td>
        <td class="px-2 py-2 align-top">
            <input type="text" name="cargas[${idx}][observaciones]" placeholder="Opcional..."
                class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2">
        </td>
        <td class="px-2 py-2 align-middle text-center">
            <button type="button" onclick="bulkEliminarFila(${idx})" class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
            </button>
        </td>`;
    }

    function bulkInitRow(idx) {
        const viInput = document.getElementById(`bv_i_${idx}`);
        const vhInput = document.getElementById(`bv_h_${idx}`);
        const onSelect = (id) => { vhInput.value = id; };
        viInput.addEventListener('focus', () => openFloatingDD(viInput, vhInput, VACUNAS, renderVacuna, onSelect));
        viInput.addEventListener('input', () => {
            if (!floatDD.classList.contains('hidden') && activeInput === viInput) {
                renderFloatItems(VACUNAS, viInput.value, renderVacuna, onSelect);
            } else {
                openFloatingDD(viInput, vhInput, VACUNAS, renderVacuna, onSelect);
            }
            if (!viInput.value) vhInput.value = '';
        });
    }

    function bulkEliminarFila(idx) {
        if (document.querySelectorAll('#bulk_tbody tr').length <= 1) { alert('Debe haber al menos una fila.'); return; }
        document.getElementById(`brow_${idx}`)?.remove();
        bulkActualizarContadores();
    }

    function bulkCopiarFechaLlegada() {
        const primera = document.querySelector('.bulk-fecha-llegada');
        if (!primera?.value) { alert('Ingresa la fecha de llegada en la primera fila.'); return; }
        document.querySelectorAll('.bulk-fecha-llegada').forEach(f => f.value = primera.value);
    }

    function bulkCopiarFechaVencimiento() {
        const primera = document.querySelector('.bulk-fecha-vencimiento');
        if (!primera?.value) { alert('Ingresa la fecha de vencimiento en la primera fila.'); return; }
        document.querySelectorAll('.bulk-fecha-vencimiento').forEach(f => f.value = primera.value);
    }

    function bulkActualizarContadores() {
        let total = 0;
        document.querySelectorAll('.bulk-cantidad').forEach(c => total += parseInt(c.value) || 0);
        document.getElementById('bulk_total').textContent = total.toLocaleString() + ' dosis';
        const rows = document.querySelectorAll('#bulk_tbody tr').length;
        document.getElementById('bulk_row_count').textContent = rows + (rows === 1 ? ' fila' : ' filas');
    }

    // Iniciar con una fila
    bulkAgregarFila();
</script>
@endpush
@endsection