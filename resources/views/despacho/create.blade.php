@extends('layouts.app')
@section('title', 'Registrar Despacho')

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl bg-white/90 backdrop-blur-lg rounded-lg shadow">

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-purple-800 flex items-center gap-2">
                <div class="p-2 text-purple-300 bg-purple-800 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-circle-plus-icon lucide-circle-plus">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M8 12h8" />
                        <path d="M12 8v8" />
                    </svg>
                </div>
                Registrar Despacho
            </h1>
            <p class="text-sm text-gray-500 mt-1">Envío de vacunas desde el ASIC hacia módulos afiliados</p>
        </div>
        <a href="{{ route('despachos.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Volver
        </a>
    </div>

    {{-- Alertas --}}
    @if(session('error_stock'))
    @php $es = session('error_stock'); @endphp
    <div class="flex items-start gap-3 p-4 mb-5 text-red-800 bg-red-50 border border-red-200 rounded-lg">
        <i data-lucide="alert-triangle" class="w-5 h-5 shrink-0 mt-0.5"></i>
        <div>
            <p class="font-semibold text-sm">Stock insuficiente — Despacho no registrado</p>
            <p class="text-sm mt-0.5">
                <strong>{{ $es['vacuna'] }}</strong>: solicitaste
                <strong>{{ number_format($es['solicitado']) }}</strong> dosis,
                disponibles: <strong>{{ number_format($es['disponible']) }}</strong>.
            </p>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="flex items-start gap-3 p-4 mb-5 text-red-800 bg-red-50 border border-red-200 rounded-lg">
        <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 mt-0.5"></i>
        <div>
            <p class="font-semibold text-sm mb-1">Corrige los siguientes errores:</p>
            <ul class="text-sm list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
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
            <button id="tab-bulk" onclick="switchTab('bulk')"
                class="tab-btn flex items-center gap-2 px-5 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 -mb-px">
                <i data-lucide="files" class="w-4 h-4"></i>
                Registro Múltiple
            </button>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- TAB INDIVIDUAL --}}
    {{-- ============================================================ --}}
    <div id="panel-individual" class="tab-panel">
        <div class="bg-white border border-gray-200 rounded-b-lg shadow-sm">
            <div class="p-5 border-b border-gray-100 flex items-center gap-2">
                <i data-lucide="clipboard-list" class="w-4 h-4 text-primary-600"></i>
                <h2 class="text-base font-semibold text-gray-800">Datos del Despacho</h2>
            </div>

            <form method="POST" action="{{ route('despachos.store') }}">
                @csrf
                <input type="hidden" name="asic_id" value="{{ $asic->id }}">

                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">

                    {{-- Vacuna --}}
                    <div class="sm:col-span-2">
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Vacuna <span class="text-red-500">*</span>
                        </label>
                        <input type="hidden" name="vacuna_id" id="si_vacuna_hidden" value="{{ old('vacuna_id') }}">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="syringe" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <input type="text" id="si_vacuna_input" placeholder="Escribe para buscar vacuna..."
                                autocomplete="off"
                                value="{{ old('vacuna_id') ? $vacunas->firstWhere('id', old('vacuna_id'))?->nombre : '' }}"
                                class="pl-9 pr-4 bg-gray-50 border {{ $errors->has('vacuna_id') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        </div>
                        @error('vacuna_id')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror

                        {{-- Widget stock --}}
                        <div id="si_stock_widget"
                            class="hidden mt-2 p-3 rounded-lg border flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i data-lucide="package" class="w-4 h-4"></i>
                                <span class="text-sm font-medium" id="si_stock_nombre"></span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs text-gray-500">Stock total disponible</span>
                                <p class="text-lg font-bold" id="si_stock_cantidad"></p>
                            </div>
                        </div>

                        {{-- Sugerencia de lotes --}}
                        <div id="si_lotes_sugeridos" class="hidden mt-2">
                            <p class="text-xs text-gray-400 mb-1.5 flex items-center gap-1">
                                <i data-lucide="layers" class="w-3 h-3"></i>
                                Lotes con stock — clic para seleccionar:
                            </p>
                            <div id="si_lotes_badges" class="flex flex-wrap gap-1.5"></div>
                        </div>
                    </div>

                    {{-- Módulo --}}
                    <div class="sm:col-span-2">
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Módulo Destino <span class="text-red-500">*</span>
                        </label>
                        <input type="hidden" name="modulo_id" id="si_modulo_hidden" value="{{ old('modulo_id') }}">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="building-2" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <input type="text" id="si_modulo_input" placeholder="Buscar módulo destino..."
                                autocomplete="off"
                                value="{{ old('modulo_id') ? $modulos->firstWhere('id', old('modulo_id'))?->nombre : '' }}"
                                class="pl-9 pr-4 bg-gray-50 border {{ $errors->has('modulo_id') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        </div>
                        @error('modulo_id')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Responsable --}}
                    <div class="sm:col-span-2">
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Responsable del Envío <span class="text-red-500">*</span>
                        </label>
                        <input type="hidden" name="responsable_envio" id="si_resp_hidden"
                            value="{{ old('responsable_envio') }}">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="user-check" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <input type="text" id="si_resp_input" placeholder="Buscar personal por nombre o cédula..."
                                autocomplete="off"
                                class="pl-9 pr-4 bg-gray-50 border {{ $errors->has('responsable_envio') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        </div>
                        @error('responsable_envio')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Lote --}}
                    <div class="sm:col-span-2">
                        <label for="si_lote" class="block mb-1.5 text-sm font-medium text-gray-700">
                            <span class="flex items-center gap-1.5">
                                <i data-lucide="tag" class="w-3.5 h-3.5 text-gray-400"></i>
                                Lote <span class="text-gray-400 font-normal text-xs">(opcional — se usará para el
                                    control por lote en inventario)</span>
                            </span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="hash" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <input type="text" name="lote" id="si_lote" value="{{ old('lote') }}"
                                placeholder="LOT-2024-A"
                                class="pl-9 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </div>
                        @error('lote')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Cantidad --}}
                    <div>
                        <label for="si_cantidad" class="block mb-1.5 text-sm font-medium text-gray-700">
                            Cantidad <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="boxes" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <input type="number" name="cantidad" id="si_cantidad" min="1" value="{{ old('cantidad') }}"
                                placeholder="0" oninput="validarCantidadSingle(this.value)"
                                class="pl-9 bg-gray-50 border {{ $errors->has('cantidad') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        </div>
                        @error('cantidad')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                        <div id="si_aviso" class="hidden mt-1.5 flex items-center gap-1.5 text-xs font-medium">
                            <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i>
                            <span id="si_aviso_text"></span>
                        </div>
                    </div>

                    {{-- Fecha --}}
                    <div>
                        <label for="si_fecha" class="block mb-1.5 text-sm font-medium text-gray-700">
                            Fecha de Envío <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <input type="date" name="fecha_envio" id="si_fecha"
                                value="{{ old('fecha_envio', date('Y-m-d')) }}"
                                class="pl-9 bg-gray-50 border {{ $errors->has('fecha_envio') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        </div>
                        @error('fecha_envio')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Info ASIC --}}
                <div
                    class="mx-5 mb-5 p-3 bg-blue-50 border border-blue-100 rounded-lg flex items-center gap-2.5 text-sm text-blue-700">
                    <i data-lucide="building-2" class="w-4 h-4 shrink-0"></i>
                    <span>Despacho desde: <strong>{{ $asic->nombre }}</strong></span>
                </div>

                {{-- Botones --}}
                <div
                    class="flex items-center justify-end gap-3 px-5 py-4 border-t border-gray-100 bg-gray-50 rounded-b-lg">
                    <a href="{{ route('despachos.index') }}"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit" id="si_btn_submit"
                        class="flex items-center gap-2 font-medium text-brand bg-blue-300 hover:bg-brand hover:text-white focus:ring-4 focus:ring-neutral-tertiary leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        Registrar Despacho
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- TAB BULK --}}
    {{-- ============================================================ --}}
    <div id="panel-bulk" class="tab-panel hidden">
        <div class="bg-white border border-gray-200 rounded-b-lg shadow-sm">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="table" class="w-4 h-4 text-primary-600"></i>
                    <h2 class="text-base font-semibold text-gray-800">Registro Múltiple</h2>
                    <span class="px-2.5 py-0.5 text-xs font-medium bg-blue-100 text-blue-700 rounded-full"
                        id="bulk_row_count">1 fila</span>
                </div>
                <button type="button" onclick="bulkAgregarFila()"
                    class="flex items-center gap-2 font-medium text-dark bg-gray-300 hover:bg-dark hover:text-white focus:ring-4 focus:ring-neutral-tertiary leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i>
                    Agregar fila
                </button>
            </div>

            <div
                class="mx-5 mt-4 p-3 bg-amber-50 border border-amber-100 rounded-lg flex items-start gap-2.5 text-sm text-amber-700">
                <i data-lucide="info" class="w-4 h-4 shrink-0 mt-0.5"></i>
                <p>Cada fila representa un registro independiente. Cada uno valida su stock por separado. Puede copiar
                    la fecha y responsable de la primera fila a todas las demás.</p>
            </div>

            <form method="POST" action="{{ route('despachos.store.bulk') }}" id="bulk_form">
                @csrf
                <input type="hidden" name="asic_id" value="{{ $asic->id }}">

                <div class="p-5">
                    {{-- Controles rápidos --}}
                    <div
                        class="flex flex-wrap items-center gap-3 mb-4 p-3 bg-gray-50 border border-gray-200 rounded-lg text-xs">
                        <span class="font-medium text-gray-500 flex items-center gap-1">
                            <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                            Copiar primera fila:
                        </span>
                        <button type="button" onclick="bulkCopiarFecha()"
                            class="flex items-center gap-1 px-2.5 py-1.5 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700 font-medium">
                            <i data-lucide="calendar" class="w-3 h-3"></i>
                            Fecha a todas
                        </button>
                        <button type="button" onclick="bulkCopiarResponsable()"
                            class="flex items-center gap-1 px-2.5 py-1.5 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700 font-medium">
                            <i data-lucide="user" class="w-3 h-3"></i>
                            Responsable a todas
                        </button>
                        <div class="ml-auto flex items-center gap-2">
                            <span class="text-gray-400">Total dosis:</span>
                            <span id="bulk_total" class="font-bold text-primary-700 text-sm">0</span>
                        </div>
                    </div>

                    {{-- Tabla --}}
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                                    <th class="px-3 py-2.5 text-left font-semibold" style="width:220px">Vacuna *</th>
                                    <th class="px-3 py-2.5 text-left font-semibold" style="width:180px">Módulo *</th>
                                    <th class="px-3 py-2.5 text-left font-semibold" style="width:200px">Responsable *
                                    </th>
                                    <th class="px-3 py-2.5 text-left font-semibold" style="width:130px">Fecha *</th>
                                    <th class="px-3 py-2.5 text-left font-semibold" style="width:110px">Lote</th>
                                    <th class="px-3 py-2.5 text-left font-semibold" style="width:110px">Cantidad *</th>
                                    <th class="px-3 py-2.5 text-left font-semibold" style="width:40px"></th>
                                </tr>
                            </thead>
                            <tbody id="bulk_tbody" class="divide-y divide-gray-100">
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Info ASIC --}}
                <div
                    class="mx-5 mb-5 p-3 bg-blue-50 border border-blue-100 rounded-lg flex items-center gap-2.5 text-sm text-blue-700">
                    <i data-lucide="building-2" class="w-4 h-4 shrink-0"></i>
                    <span>Todos los despachos se registran desde: <strong>{{ $asic->nombre }}</strong></span>
                </div>

                {{-- Botones --}}
                <div
                    class="flex items-center justify-between gap-3 px-5 py-4 border-t border-gray-100 bg-gray-50 rounded-b-lg">
                    <p class="text-xs text-gray-400 flex items-center gap-1">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                        Cada fila valida stock independientemente antes de guardar
                    </p>
                    <div class="flex gap-2">
                        <a href="{{ route('despachos.index') }}"
                            class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="flex items-center gap-2 font-medium text-brand bg-blue-300 hover:bg-brand hover:text-white focus:ring-4 focus:ring-neutral-tertiary leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                            <i data-lucide="send" class="w-4 h-4"></i>
                            Guardar registros múltiples
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- DROPDOWN FLOTANTE
{{-- ============================================================ --}}
<div id="floating_dropdown"
    class="hidden fixed z-9999 bg-white border border-gray-200 rounded-lg shadow-xl overflow-y-auto"
    style="max-height:220px; min-width:200px;" onmousedown="event.preventDefault()">
</div>

@push('scripts')
<script>
    lucide.createIcons();
    let scrollHandler = null;
    let resizeHandler = null;

    // ============================================================
    // TABS
    // ============================================================
    function switchTab(tab) {
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('border-primary-600', 'text-primary-600');
            b.classList.add('border-transparent', 'text-gray-500');
        });
        document.getElementById('panel-' + tab).classList.remove('hidden');
        const btn = document.getElementById('tab-' + tab);
        btn.classList.add('border-primary-600', 'text-primary-600');
        btn.classList.remove('border-transparent', 'text-gray-500');
    }

    // ============================================================
    // DATOS DESDE BLADE
    // ============================================================
    const VACUNAS   = @json($vacunas->map(fn($v) => ['id' => $v->id, 'nombre' => $v->nombre]));
    const MODULOS   = @json($modulos->map(fn($m) => ['id' => $m->id, 'nombre' => $m->nombre, 'dir' => $m->direccion]));
    const PERSONAL  = @json($personal->map(fn($p) => ['id' => $p->cedula, 'nombre' => $p->nombre . ' ' . $p->apellido, 'cargo' => $p->cargo?->nombre ?? '']));
    const STOCK_URL = '{{ route("despachos.stock.check") }}';
    // ============================================================
    // DROPDOWN FLOTANTE UNIVERSAL
    // ============================================================
    let activeInput   = null;
    let activeHidden  = null;
    let activeItems   = [];
    let activeCallback = null;

    const floatDD = document.getElementById('floating_dropdown');

    function openFloatingDD(inputEl, hiddenEl, items, renderItem, onSelect) {
    activeInput    = inputEl;
    activeHidden   = hiddenEl;
    activeItems    = items;
    activeCallback = onSelect;

    renderFloatItems(items, inputEl.value, renderItem, onSelect);
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
    floatDD.style.left = rect.left + 'px'; 

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

        if (vis === 0) {
            floatDD.innerHTML = '<div class="px-3 py-3 text-xs text-gray-400 text-center">Sin resultados</div>';
        }
    }

    function closeFloatingDD() {
    floatDD.classList.add('hidden');
    if (scrollHandler) window.removeEventListener('scroll', scrollHandler);
    if (resizeHandler) window.removeEventListener('resize', resizeHandler);
    activeInput = null;
}

    // Cerrar al hacer click fuera
    document.addEventListener('click', e => {
        if (activeInput && !activeInput.contains(e.target) && !floatDD.contains(e.target)) {
            closeFloatingDD();
        }
    });

    // Reposicionar al scroll
    window.addEventListener('scroll', () => {
        if (activeInput && !floatDD.classList.contains('hidden')) {
            posicionarDD(activeInput);
        }
    }, true);

    // ============================================================
    // SEARCHABLE INDIVIDUAL (tab individual)
    // ============================================================
    function initSingleSearchable(inputId, hiddenId, items, renderFn, onSelect) {
        const input  = document.getElementById(inputId);
        const hidden = document.getElementById(hiddenId);

        input.addEventListener('focus', () => {
            openFloatingDD(input, hidden, items, renderFn, onSelect);
        });
        input.addEventListener('input', () => {
            if (floatDD.classList.contains('hidden')) {
                openFloatingDD(input, hidden, items, renderFn, onSelect);
            } else {
                renderFloatItems(items, input.value, renderFn, onSelect);
                posicionarDD(input);
            }
            if (!input.value) hidden.value = '';
        });
    }

    // Renders
    const renderVacuna = v => `<span>${v.nombre}</span>`;
    const renderModulo = m => `<div><p class="font-medium">${m.nombre}</p><p class="text-xs text-gray-400">${m.dir || ''}</p></div>`;
    const renderPersonal = p => `<div class="flex justify-between items-center"><span class="font-medium">${p.nombre}</span><span class="text-xs text-gray-400 font-mono">${p.id}</span></div>${p.cargo ? '<p class="text-xs text-gray-400">' + p.cargo + '</p>' : ''}`;

    // ---- Stock individual ----
    let siStock = null;

    function verificarStockSingle(vacunaId) {
        const loteSelect    = document.getElementById('si_lote');
        const loteCargando  = document.getElementById('si_lote_cargando');
        const lotesSug      = document.getElementById('si_lotes_sugeridos'); // puede no existir
        const stockWidget   = document.getElementById('si_stock_widget');

        if (!vacunaId) {
            stockWidget.classList.add('hidden');
            loteSelect.innerHTML = '<option value="">— Selecciona una vacuna primero —</option>';
            loteSelect.disabled = true;
            siStock = null;
            return;
        }

        // Mostrar spinner
        loteSelect.disabled = true;
        loteCargando.classList.remove('hidden');
        loteSelect.innerHTML = '<option value="">Cargando lotes...</option>';

        fetch(`${STOCK_URL}?vacuna_id=${vacunaId}`)
            .then(r => r.json())
            .then(d => {
                siStock = d.stock;

                // Widget de stock total
                const w  = document.getElementById('si_stock_widget');
                const nm = document.getElementById('si_stock_nombre');
                const cn = document.getElementById('si_stock_cantidad');
                w.classList.remove('hidden');
                nm.textContent = d.vacuna;
                cn.textContent = d.stock.toLocaleString() + ' unidades';
                const esRojo    = d.stock === 0;
                const esNaranja = d.stock > 0 && d.stock <= 50;
                w.className  = 'mt-2 p-3 rounded-lg border flex items-center justify-between ' +
                    (esRojo ? 'border-red-200 bg-red-50' : esNaranja ? 'border-orange-200 bg-orange-50' : 'border-green-200 bg-green-50');
                nm.className = 'text-sm font-medium ' + (esRojo ? 'text-red-700' : esNaranja ? 'text-orange-700' : 'text-green-700');
                cn.className = 'text-lg font-bold '   + (esRojo ? 'text-red-700' : esNaranja ? 'text-orange-700' : 'text-green-700');

                // Poblar select de lotes
                loteSelect.innerHTML = '<option value="">— Selecciona un lote —</option>';

                if (d.lotes && d.lotes.length > 0) {
                    d.lotes.forEach(lote => {
                        const opt = document.createElement('option');
                        opt.value = lote.lote;
                        const vence = lote.fecha_vencimiento
                            ? ` · Vence: ${lote.fecha_vencimiento}`
                            : '';
                        opt.textContent = `${lote.lote}  (${lote.disponible} disponibles${vence})`;
                        opt.dataset.disponible = lote.disponible;
                        loteSelect.appendChild(opt);
                    });
                    loteSelect.disabled = false;
                } else {
                    loteSelect.innerHTML = '<option value="">Sin lotes con stock disponible</option>';
                    loteSelect.disabled = true;
                }

                document.getElementById('si_btn_submit').disabled = d.stock === 0;

                // Actualizar max de cantidad según lote seleccionado
                loteSelect.addEventListener('change', function () {
                    const opt = this.options[this.selectedIndex];
                    const disp = parseInt(opt.dataset.disponible) || 0;
                    document.getElementById('si_cantidad').max = disp || '';
                    siStock = disp;
                    const cant = parseInt(document.getElementById('si_cantidad').value) || 0;
                    if (cant > 0) validarCantidadSingle(cant);
                }, { once: false });

                lucide.createIcons();
                const cant = parseInt(document.getElementById('si_cantidad').value) || 0;
                if (cant > 0) validarCantidadSingle(cant);
            })
            .catch(() => {
                loteSelect.innerHTML = '<option value="">Error al cargar lotes</option>';
            })
            .finally(() => {
                loteCargando.classList.add('hidden');
            });
    }

    function validarCantidadSingle(val) {
        const cant  = parseInt(val) || 0;
        const aviso = document.getElementById('si_aviso');
        const texto = document.getElementById('si_aviso_text');
        const btn   = document.getElementById('si_btn_submit');
        if (siStock === null || cant === 0) { aviso.classList.add('hidden'); return; }
        if (cant > siStock) {
            aviso.classList.remove('hidden');
            aviso.className   = 'mt-1.5 flex items-center gap-1.5 text-xs font-medium text-red-600';
            texto.textContent = `Excede el stock disponible (${siStock.toLocaleString()} dosis).`;
            btn.disabled = true;
        } else if (cant > siStock * 0.8) {
            aviso.classList.remove('hidden');
            aviso.className   = 'mt-1.5 flex items-center gap-1.5 text-xs font-medium text-orange-500';
            texto.textContent = `Usarás más del 80% del stock disponible.`;
            btn.disabled = false;
        } else {
            aviso.classList.add('hidden');
            btn.disabled = false;
        }
        lucide.createIcons();
    }

    initSingleSearchable('si_vacuna_input', 'si_vacuna_hidden', VACUNAS,   renderVacuna,   (id) => verificarStockSingle(id));
    initSingleSearchable('si_modulo_input', 'si_modulo_hidden', MODULOS,   renderModulo);
    initSingleSearchable('si_resp_input',   'si_resp_hidden',   PERSONAL,  renderPersonal);

    // ============================================================
    // BULK TABLE
    // ============================================================
    let bulkRowIdx = 0;

    // Datos de stock cacheados por vacuna_id
    const stockCache = {};

    function bulkAgregarFila() {
        const idx = bulkRowIdx++;
        const tr  = document.createElement('tr');
        tr.id     = `brow_${idx}`;
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
            <input type="hidden" name="despachos[${idx}][vacuna_id]" id="bv_h_${idx}">
            <input type="text" id="bv_i_${idx}" placeholder="Buscar vacuna..."
                autocomplete="off"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2">
            <div id="bv_stock_${idx}" class="hidden mt-1 text-xs font-medium px-1"></div>
        </td>
        <td class="px-2 py-2 align-top">
            <input type="hidden" name="despachos[${idx}][modulo_id]" id="bm_h_${idx}">
            <input type="text" id="bm_i_${idx}" placeholder="Buscar módulo..."
                autocomplete="off"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2">
        </td>
        <td class="px-2 py-2 align-top">
            <input type="hidden" name="despachos[${idx}][responsable_envio]" id="br_h_${idx}">
            <input type="text" id="br_i_${idx}" placeholder="Buscar responsable..."
                autocomplete="off"
                class="bulk-resp-input bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2">
        </td>
        <td class="px-2 py-2 align-top">
            <input type="date" name="despachos[${idx}][fecha_envio]"
                value="${new Date().toISOString().split('T')[0]}"
                class="bulk-fecha-input bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2">
        </td>
        <td class="px-2 py-2 align-top">
            <input type="text" name="despachos[${idx}][lote]"
                placeholder="LOT-2024-A"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
        </td>
        <td class="px-2 py-2 align-top">
            <input type="number" name="despachos[${idx}][cantidad]" min="1" placeholder="0"
                oninput="bulkValidarCantidad(${idx}, this.value)"
                class="bulk-cantidad bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2">
        </td>
        <td class="px-2 py-2 align-middle text-center">
            <button type="button" onclick="bulkEliminarFila(${idx})"
                class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
            </button>
        </td>`;
    }

    function bulkInitRow(idx) {
        const viInput = document.getElementById(`bv_i_${idx}`);
        const vhInput = document.getElementById(`bv_h_${idx}`);
        const miInput = document.getElementById(`bm_i_${idx}`);
        const mhInput = document.getElementById(`bm_h_${idx}`);
        const riInput = document.getElementById(`br_i_${idx}`);
        const rhInput = document.getElementById(`br_h_${idx}`);

        // Vacuna
        const onVacunaSelect = (id, nombre) => {
            vhInput.value = id;
            bulkCargarStock(idx, id);
        };
        viInput.addEventListener('focus', () => openFloatingDD(viInput, vhInput, VACUNAS, renderVacuna, onVacunaSelect));
        viInput.addEventListener('input', () => {
            if (!floatDD.classList.contains('hidden') && activeInput === viInput) {
                renderFloatItems(VACUNAS, viInput.value, renderVacuna, onVacunaSelect);
            } else {
                openFloatingDD(viInput, vhInput, VACUNAS, renderVacuna, onVacunaSelect);
            }
            if (!viInput.value) { vhInput.value = ''; document.getElementById(`bv_stock_${idx}`).classList.add('hidden'); }
        });

        // Módulo
        const onModuloSelect = (id) => { mhInput.value = id; };
        miInput.addEventListener('focus', () => openFloatingDD(miInput, mhInput, MODULOS, renderModulo, onModuloSelect));
        miInput.addEventListener('input', () => {
            if (!floatDD.classList.contains('hidden') && activeInput === miInput) {
                renderFloatItems(MODULOS, miInput.value, renderModulo, onModuloSelect);
            } else {
                openFloatingDD(miInput, mhInput, MODULOS, renderModulo, onModuloSelect);
            }
            if (!miInput.value) mhInput.value = '';
        });

        // Responsable
        const onRespSelect = (id) => { rhInput.value = id; };
        riInput.addEventListener('focus', () => openFloatingDD(riInput, rhInput, PERSONAL, renderPersonal, onRespSelect));
        riInput.addEventListener('input', () => {
            if (!floatDD.classList.contains('hidden') && activeInput === riInput) {
                renderFloatItems(PERSONAL, riInput.value, renderPersonal, onRespSelect);
            } else {
                openFloatingDD(riInput, rhInput, PERSONAL, renderPersonal, onRespSelect);
            }
            if (!riInput.value) rhInput.value = '';
        });
    }

    function bulkCargarStock(idx, vacunaId) {
        const stockEl = document.getElementById(`bv_stock_${idx}`);
        if (!vacunaId) { stockEl.classList.add('hidden'); return; }

        if (stockCache[vacunaId] !== undefined) {
            bulkMostrarStock(idx, stockCache[vacunaId]);
            return;
        }

        fetch(`${STOCK_URL}?vacuna_id=${vacunaId}`)
            .then(r => r.json())
            .then(d => {
                stockCache[vacunaId] = d.stock;
                bulkMostrarStock(idx, d.stock);
                const cant = parseInt(document.querySelector(`[name="despachos[${idx}][cantidad]"]`)?.value) || 0;
                if (cant > 0) bulkValidarCantidad(idx, cant);
            });
    }

    function bulkMostrarStock(idx, stock) {
        const el = document.getElementById(`bv_stock_${idx}`);
        el.classList.remove('hidden');
        if (stock === 0) {
            el.className = 'mt-1 text-xs font-medium px-1 text-red-600';
            el.textContent = '⚠ Sin stock disponible';
        } else if (stock <= 50) {
            el.className = 'mt-1 text-xs font-medium px-1 text-orange-500';
            el.textContent = `Stock bajo: ${stock.toLocaleString()} dosis`;
        } else {
            el.className = 'mt-1 text-xs font-medium px-1 text-green-600';
            el.textContent = `Disponible: ${stock.toLocaleString()} dosis`;
        }
    }

    function bulkValidarCantidad(idx, val) {
        const cant     = parseInt(val) || 0;
        const vacunaId = document.getElementById(`bv_h_${idx}`)?.value;
        const stock    = vacunaId ? (stockCache[vacunaId] ?? null) : null;
        const input    = document.querySelector(`[name="despachos[${idx}][cantidad]"]`);

        if (stock !== null && cant > stock) {
            input?.classList.add('border-red-500');
            input?.classList.remove('border-gray-300');
        } else {
            input?.classList.remove('border-red-500');
            input?.classList.add('border-gray-300');
        }
        bulkActualizarContadores();
    }

    function bulkEliminarFila(idx) {
        const filas = document.querySelectorAll('#bulk_tbody tr');
        if (filas.length <= 1) { alert('Debe haber al menos una fila.'); return; }
        document.getElementById(`brow_${idx}`)?.remove();
        bulkActualizarContadores();
    }

    function bulkCopiarFecha() {
        const primera = document.querySelector('.bulk-fecha-input');
        if (!primera?.value) { alert('Ingresa la fecha en la primera fila.'); return; }
        document.querySelectorAll('.bulk-fecha-input').forEach(f => f.value = primera.value);
    }

    function bulkCopiarResponsable() {
        const primerInput  = document.querySelector('.bulk-resp-input');
        const primerHidden = document.getElementById('br_h_0');
        if (!primerHidden?.value) { alert('Selecciona el responsable en la primera fila.'); return; }
        let idx = 0;
        document.querySelectorAll('.bulk-resp-input').forEach(inp => {
            inp.value = primerInput.value;
            const hid = document.getElementById(`br_h_${idx}`);
            if (hid) hid.value = primerHidden.value;
            idx++;
        });
    }

    function bulkAgregarFila() {
    const idx = bulkRowIdx++;
    const tr  = document.createElement('tr');
    tr.id     = `brow_${idx}`;
    tr.className = 'border-b border-gray-100';
    tr.innerHTML = bulkBuildRow(idx);
    document.getElementById('bulk_tbody').appendChild(tr);
    lucide.createIcons(); // ← debe estar aquí
    bulkInitRow(idx);
    bulkActualizarContadores();
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