@extends('layouts.app')
@section('title')
Editar\{{ $carga->lote }}
@endsection

@section('content')
<div class="px-4 py-6 mx-auto max-w-3xl bg-white/90 rounded-lg shadow-sm backdrop-blur-sm">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-warning flex items-center gap-2">
                <div class="p-2 bg-warning rounded text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-file-pen-line-icon lucide-file-pen-line">
                        <path
                            d="M14.364 13.634a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506l4.013-4.009a1 1 0 0 0-3.004-3.004z" />
                        <path d="M14.487 7.858A1 1 0 0 1 14 7V2" />
                        <path
                            d="M20 19.645V20a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l2.516 2.516" />
                        <path d="M8 18h1" />
                    </svg>
                </div>
                Editar Registro
            </h1>
            <p class="text-sm text-gray-500 mt-1">Lote: <span class="font-mono font-semibold text-gray-700">{{
                    $carga->lote }}</span></p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('cargas.show', $carga->id) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300">
                <i data-lucide="eye" class="w-4 h-4"></i> Ver
            </a>
            <a href="{{ route('cargas.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Volver
            </a>
        </div>
    </div>

    @if($errors->any())
    <div class="flex items-start gap-3 p-4 mb-5 text-red-800 bg-red-50 border border-red-200 rounded-lg">
        <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 mt-0.5"></i>
        <ul class="text-sm list-disc list-inside">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="p-5 border-b border-gray-100 flex items-center gap-2">
            <i data-lucide="package-2" class="w-4 h-4 text-primary-600"></i>
            <h2 class="text-base font-semibold text-gray-800">Datos de la Carga</h2>
        </div>

        <form method="POST" action="{{ route('cargas.update', $carga->id) }}">
            @csrf @method('PATCH')
            <input type="hidden" name="asic_id" value="{{ $asic->id }}">

            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Vacuna --}}
                <div class="sm:col-span-2">
                    <label class="block mb-1.5 text-sm font-medium text-gray-700">
                        Vacuna <span class="text-red-500">*</span>
                    </label>
                    <input type="hidden" name="vacuna_id" id="edit_vacuna_hidden"
                        value="{{ old('vacuna_id', $carga->vacuna_id) }}">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i data-lucide="syringe" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input type="text" id="edit_vacuna_input"
                            value="{{ old('vacuna_id') ? $vacunas->firstWhere('id', old('vacuna_id'))?->nombre : $carga->vacuna?->nombre }}"
                            placeholder="Buscar vacuna..." autocomplete="off"
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
                        <input type="text" name="lote" id="lote" value="{{ old('lote', $carga->lote) }}"
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
                        <input type="number" name="cantidad" id="cantidad" min="1"
                            value="{{ old('cantidad', $carga->cantidad) }}"
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
                            value="{{ old('fecha_llegada', $carga->fecha_llegada?->format('Y-m-d')) }}"
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
                            value="{{ old('fecha_vencimiento', $carga->fecha_vencimiento?->format('Y-m-d')) }}"
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
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">{{ old('observaciones', $carga->observaciones) }}</textarea>
                    @error('observaciones')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div
                class="mx-5 mb-5 p-3 bg-blue-50 border border-blue-100 rounded-lg flex items-center gap-2.5 text-sm text-blue-700">
                <i data-lucide="building-2" class="w-4 h-4 shrink-0"></i>
                <span>ASIC: <strong>{{ $asic->nombre }}</strong></span>
            </div>

            <div
                class="flex items-center justify-between gap-3 px-5 py-4 border-t border-gray-100 bg-gray-50 rounded-b-lg">
                    <a href="{{ route('cargas.index') }}"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancelar</a>
                    <button type="submit"
                        class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white  bg-warning rounded-lg hover:bg-warning-strong focus:ring-4 focus:ring-primary-300">
                        <i data-lucide="save" class="w-4 h-4"></i> Guardar cambios
                    </button>

            </div>
        </form>
    </div>
</div>

{{-- Dropdown flotante --}}
<div id="floating_dropdown"
    class="hidden fixed z-[9999] bg-white border border-gray-200 rounded-lg shadow-xl overflow-y-auto"
    style="max-height:220px; min-width:200px;" onmousedown="event.preventDefault()"></div>

@push('scripts')
<script>
    lucide.createIcons();
    const VACUNAS = @json($vacunas->map(fn($v) => ['id' => $v->id, 'nombre' => $v->nombre]));
    const floatDD = document.getElementById('floating_dropdown');
    let activeInput = null, activeHidden = null;
    let scrollHandler = null, resizeHandler = null;

    function openFloatingDD(inputEl, hiddenEl, items, renderFn, onSelect) {
        activeInput = inputEl; activeHidden = hiddenEl;
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
        const rect = inputEl.getBoundingClientRect();
        const ddH  = Math.min(220, floatDD.scrollHeight || 220);
        floatDD.style.width = rect.width + 'px';
        floatDD.style.left  = rect.left  + 'px';
        floatDD.style.top   = (window.innerHeight - rect.bottom < ddH + 8 && rect.top > ddH)
            ? (rect.top - ddH - 2) + 'px'
            : (rect.bottom + 2) + 'px';
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
                if (onSelect) onSelect(item.id);
            });
            floatDD.appendChild(div);
            vis++;
        });
        if (vis === 0) floatDD.innerHTML = '<div class="px-3 py-3 text-xs text-gray-400 text-center">Sin resultados</div>';
    }

    document.addEventListener('click', e => {
        if (activeInput && !activeInput.contains(e.target) && !floatDD.contains(e.target)) {
            floatDD.classList.add('hidden'); activeInput = null;
        }
    });
    window.addEventListener('scroll', () => {
        if (activeInput && !floatDD.classList.contains('hidden')) posicionarDD(activeInput);
    }, true);

    const renderVacuna = v => `<span>${v.nombre}</span>`;
    const input  = document.getElementById('edit_vacuna_input');
    const hidden = document.getElementById('edit_vacuna_hidden');
    input.addEventListener('focus', () => openFloatingDD(input, hidden, VACUNAS, renderVacuna));
    input.addEventListener('input', () => {
        if (floatDD.classList.contains('hidden')) openFloatingDD(input, hidden, VACUNAS, renderVacuna);
        else { renderFloatItems(VACUNAS, input.value, renderVacuna); posicionarDD(input); }
        if (!input.value) hidden.value = '';
    });
</script>
@endpush
@endsection