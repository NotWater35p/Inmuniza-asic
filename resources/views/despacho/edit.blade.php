@extends('layouts.app')
@section('title', 'Editar Despacho')

@section('content')
<div class="px-4 py-6 mx-auto max-w-3xl bg-white/90 backdrop-blur-lg rounded-lg shadow">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-warning flex items-center gap-2">
                <div class="p-2 text-warning bg-yellow-300 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-square-pen-icon lucide-square-pen">
                        <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                        <path
                            d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z" />
                    </svg>
                </div>
                Editar Registro de Despacho
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Despacho #{{ str_pad($despacho->id, 6, '0', STR_PAD_LEFT) }} &bull;
                {{ \Carbon\Carbon::parse($despacho->fecha_envio)->format('d/m/Y') }}
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('despachos.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Volver
            </a>
        </div>
    </div>

    @if(session('error_stock'))
    @php $es = session('error_stock'); @endphp
    <div class="flex items-start gap-3 p-4 mb-5 text-red-800 bg-red-50 border border-red-200 rounded-lg">
        <i data-lucide="alert-triangle" class="w-5 h-5 shrink-0 mt-0.5"></i>
        <div>
            <p class="font-semibold text-sm">Stock insuficiente</p>
            <p class="text-sm mt-0.5">
                <strong>{{ $es['vacuna'] }}</strong>: solicitaste
                <strong>{{ number_format($es['solicitado']) }}</strong>, disponibles:
                <strong>{{ number_format($es['disponible']) }}</strong>.
            </p>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="flex items-start gap-3 p-4 mb-5 text-red-800 bg-red-50 border border-red-200 rounded-lg">
        <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 mt-0.5"></i>
        <ul class="text-sm list-disc list-inside">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    {{-- Stock disponible (excluyendo despacho actual) --}}
    <div class="p-4 mb-5 bg-indigo-100 border border-indigo-100 rounded-lg flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i data-lucide="package" class="w-4 h-4 text-indigo-600"></i>
            <div>
                <p class="text-xs text-indigo-500 font-medium">Stock disponible para edición</p>
                <p class="text-sm text-indigo-800 font-semibold">{{ $despacho->vacuna?->nombre }}</p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-2xl font-bold {{ $stockDisponible <= 0 ? 'text-red-600' : 'text-indigo-700' }}">
                {{ number_format($stockDisponible + $despacho->cantidad) }}
            </p>
            <p class="text-xs text-indigo-500">dosis totales disponibles</p>
            <p class="text-xs text-indigo-400">(incluye las {{ number_format($despacho->cantidad) }} de este despacho)
            </p>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="p-5 border-b border-gray-100 flex items-center gap-2">
            <i data-lucide="clipboard-list" class="w-4 h-4 text-primary-600"></i>
            <h2 class="text-base font-semibold text-gray-800">Datos del Despacho</h2>
        </div>

        <form method="POST" action="{{ route('despachos.update', $despacho->id) }}">
            @csrf
            @method('PATCH')
            <input type="hidden" name="asic_id" value="{{ $asic->id }}">

            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Vacuna --}}
                <div class="sm:col-span-2">
                    <label class="block mb-1.5 text-sm font-medium text-gray-700">
                        Vacuna <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="hidden" name="vacuna_id" id="vacuna_id_hidden"
                            value="{{ old('vacuna_id', $despacho->vacuna_id) }}">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="syringe" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <input type="text" id="vacuna_search"
                                value="{{ old('vacuna_id') ? $vacunas->firstWhere('id', old('vacuna_id'))?->nombre : $despacho->vacuna?->nombre }}"
                                placeholder="Buscar vacuna..." autocomplete="off"
                                class="pl-9 pr-4 bg-gray-50 border {{ $errors->has('vacuna_id') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        </div>
                        <div id="vacuna_dropdown"
                            class="hidden absolute z-30 w-full bottom-full mb-1  bg-white border border-gray-200 rounded-lg shadow-lg max-h-52 overflow-y-auto">
                            @foreach($vacunas as $v)
                            <div data-id="{{ $v->id }}" data-name="{{ $v->nombre }}"
                                class="px-4 py-2.5 cursor-pointer hover:bg-primary-50 hover:text-primary-700 text-gray-700 text-sm {{ $v->id == $despacho->vacuna_id ? 'bg-primary-50 font-medium text-primary-700' : '' }}">
                                {{ $v->nombre }}
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @error('vacuna_id')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror

                    <div id="stockWidget" class="hidden mt-2 p-3 rounded-lg border flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i data-lucide="package" class="w-4 h-4"></i>
                            <span class="text-sm font-medium" id="stockVacunaNombre"></span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-gray-500">Stock disponible</span>
                            <p class="text-lg font-bold" id="stockCantidad"></p>
                        </div>
                    </div>
                </div>

                {{-- Módulo --}}
                <div class="sm:col-span-2">
                    <label class="block mb-1.5 text-sm font-medium text-gray-700">
                        Módulo Destino <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="hidden" name="modulo_id" id="modulo_id_hidden"
                            value="{{ old('modulo_id', $despacho->modulo_id) }}">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="building-2" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <input type="text" id="modulo_search"
                                value="{{ old('modulo_id') ? $modulos->firstWhere('id', old('modulo_id'))?->nombre : $despacho->modulo?->nombre }}"
                                placeholder="Buscar módulo..." autocomplete="off"
                                class="pl-9 pr-4 bg-gray-50 border {{ $errors->has('modulo_id') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        </div>
                        <div id="modulo_dropdown"
                            class="hidden absolute z-20 w-full bottom-full mb-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                            @foreach($modulos as $m)
                            <div data-id="{{ $m->id }}" data-name="{{ $m->nombre }}"
                                class="px-4 py-2.5 cursor-pointer hover:bg-primary-50 text-gray-700 text-sm {{ $m->id == $despacho->modulo_id ? 'bg-primary-50 font-medium text-primary-700' : '' }}">
                                <p class="font-medium">{{ $m->nombre }}</p>
                                <p class="text-xs text-gray-400">{{ $m->direccion }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @error('modulo_id')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- Responsable --}}
                <div class="sm:col-span-2">
                    <label class="block mb-1.5 text-sm font-medium text-gray-700">
                        Responsable del Envío <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="hidden" name="responsable_envio" id="responsable_hidden"
                            value="{{ old('responsable_envio', $despacho->responsable_envio) }}">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="user-check" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <input type="text" id="responsable_search"
                                value="{{ old('responsable_envio') ? '' : ($despacho->responsable ? $despacho->responsable->nombre . ' ' . $despacho->responsable->apellido : '') }}"
                                placeholder="Buscar personal..." autocomplete="off"
                                class="pl-9 pr-4 bg-gray-50 border {{ $errors->has('responsable_envio') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        </div>
                        <div id="responsable_dropdown"
                            class="hidden absolute z-20 w-full bottom-full mb-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                            @foreach($personal as $p)
                            <div data-id="{{ $p->cedula }}" data-name="{{ $p->nombre }} {{ $p->apellido }}"
                                class="px-4 py-2.5 cursor-pointer hover:bg-primary-50 text-gray-700 text-sm {{ $p->cedula == $despacho->responsable_envio ? 'bg-primary-50 font-medium text-primary-700' : '' }}">
                                <div class="flex items-center justify-between">
                                    <p class="font-medium">{{ $p->nombre }} {{ $p->apellido }}</p>
                                    <span class="text-xs text-gray-400 font-mono">{{ $p->cedula }}</span>
                                </div>
                                @if($p->cargo)
                                <p class="text-xs text-gray-400 mt-0.5">{{ $p->cargo->nombre }}</p>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @error('responsable_envio')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- Lote --}}
                <div>
                    <label for="lote" class="block mb-1.5 text-sm font-medium text-gray-700">
                        <span class="flex items-center gap-1.5">
                            <i data-lucide="tag" class="w-3.5 h-3.5 text-gray-400"></i>
                            Lote <span class="text-gray-400 font-normal text-xs">(opcional)</span>
                        </span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i data-lucide="hash" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input type="text" name="lote" id="lote" value="{{ old('lote', $despacho?->lote) }}"
                            placeholder="LOT-2024-A"
                            class="pl-9 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>
                    @error('lote')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- Cantidad --}}
                <div>
                    <label for="cantidad" class="block mb-1.5 text-sm font-medium text-gray-700">
                        Cantidad <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i data-lucide="boxes" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input type="number" name="cantidad" id="cantidad" min="1"
                            value="{{ old('cantidad', $despacho->cantidad) }}" oninput="validarCantidad(this.value)"
                            class="pl-9 bg-gray-50 border {{ $errors->has('cantidad') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                    </div>
                    @error('cantidad')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    <div id="cantidadAviso" class="hidden mt-1.5 flex items-center gap-1.5 text-xs font-medium">
                        <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i>
                        <span id="cantidadAvisoText"></span>
                    </div>
                </div>

                {{-- Fecha --}}
                <div>
                    <label for="fecha_envio" class="block mb-1.5 text-sm font-medium text-gray-700">
                        Fecha de Envío <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input type="date" name="fecha_envio" id="fecha_envio"
                            value="{{ old('fecha_envio', $despacho->fecha_envio?->format('Y-m-d')) }}"
                            class="pl-9 bg-gray-50 border {{ $errors->has('fecha_envio') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                    </div>
                    @error('fecha_envio')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div
                class="mx-5 mb-5 p-3 bg-blue-50 border border-blue-100 rounded-lg flex items-center gap-2.5 text-sm text-blue-700">
                <i data-lucide="building-2" class="w-4 h-4 shrink-0"></i>
                <span>ASIC: <strong>{{ $asic->nombre }}</strong></span>
            </div>

            <div
                class="flex items-center justify-between gap-3 px-5 py-4 border-t border-gray-100 bg-gray-50 rounded-b-lg">
                <a href="{{ route('despachos.show', $despacho->id) }}"
                    class="flex items-center gap-1.5 text-fg-brand bg-blue-200 hover:bg-brand hover:text-white focus:ring-4 focus:ring-brand-subtle font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                    Ver Detalles
                </a>
                <button type="submit" id="btnSubmit"
                    class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-warning bg-yellow-300 hover:bg-warning hover:text-white focus:ring-4 focus:ring-brand-subtle leading-5 rounded-base focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    lucide.createIcons();

    // Stock máximo disponible para esta edición (stock actual + lo que ya tiene este despacho)
    let stockActual = {{ $stockDisponible + $despacho->cantidad }};
    const excludeId = {{ $despacho->id }};

    function initSearchable(inputId, hiddenId, dropdownId, onSelect) {
        const input    = document.getElementById(inputId);
        const hidden   = document.getElementById(hiddenId);
        const dropdown = document.getElementById(dropdownId);
        if (!input) return;
        input.addEventListener('input', function () {
            const q = this.value.toLowerCase();
            let vis = 0;
            dropdown.querySelectorAll('[data-id]').forEach(item => {
                const m = item.dataset.name.toLowerCase().includes(q);
                item.style.display = m ? '' : 'none';
                if (m) vis++;
            });
            dropdown.classList.toggle('hidden', vis === 0);
            if (!this.value) hidden.value = '';
        });
        input.addEventListener('focus', () => {
            dropdown.querySelectorAll('[data-id]').forEach(i => i.style.display = '');
            dropdown.classList.remove('hidden');
        });
        dropdown.querySelectorAll('[data-id]').forEach(item => {
            item.addEventListener('mousedown', function (e) {
                e.preventDefault();
                input.value  = this.dataset.name;
                hidden.value = this.dataset.id;
                dropdown.classList.add('hidden');
                if (onSelect) onSelect(this.dataset.id);
            });
        });
        document.addEventListener('click', e => {
            if (!input.contains(e.target) && !dropdown.contains(e.target))
                dropdown.classList.add('hidden');
        });
    }

    function actualizarStock(vacunaId) {
        if (!vacunaId) return;
        fetch(`{{ route('despachos.stock.check') }}?vacuna_id=${vacunaId}&exclude_id=${excludeId}`)
            .then(r => r.json())
            .then(data => {
                stockActual = data.stock + {{ $despacho->cantidad }};
                const widget   = document.getElementById('stockWidget');
                const nombreEl = document.getElementById('stockVacunaNombre');
                const cantEl   = document.getElementById('stockCantidad');
                widget.classList.remove('hidden');
                nombreEl.textContent = data.vacuna;
                cantEl.textContent   = stockActual.toLocaleString() + ' dosis';
                const base = 'mt-2 p-3 rounded-lg border flex items-center justify-between';
                widget.className  = base + (stockActual === 0 ? ' border-red-200 bg-red-50' : ' border-green-200 bg-green-50');
                nombreEl.className = 'text-sm font-medium ' + (stockActual === 0 ? 'text-red-700' : 'text-green-700');
                cantEl.className   = 'text-lg font-bold '   + (stockActual === 0 ? 'text-red-700' : 'text-green-700');
                lucide.createIcons();
                const cant = parseInt(document.getElementById('cantidad').value) || 0;
                if (cant > 0) validarCantidad(cant);
            });
    }

    function validarCantidad(valor) {
        const cant  = parseInt(valor) || 0;
        const aviso = document.getElementById('cantidadAviso');
        const texto = document.getElementById('cantidadAvisoText');
        const btn   = document.getElementById('btnSubmit');
        if (cant === 0) { aviso.classList.add('hidden'); return; }
        if (cant > stockActual) {
            aviso.classList.remove('hidden');
            aviso.className   = 'mt-1.5 flex items-center gap-1.5 text-xs font-medium text-red-600';
            texto.textContent = `Excede el stock (${stockActual.toLocaleString()} dosis). El despacho será rechazado.`;
            btn.disabled = true;
        } else {
            aviso.classList.add('hidden');
            btn.disabled = false;
        }
        lucide.createIcons();
    }

    initSearchable('vacuna_search',      'vacuna_id_hidden',   'vacuna_dropdown',     actualizarStock);
    initSearchable('modulo_search',      'modulo_id_hidden',   'modulo_dropdown');
    initSearchable('responsable_search', 'responsable_hidden', 'responsable_dropdown');
</script>
@endpush
@endsection