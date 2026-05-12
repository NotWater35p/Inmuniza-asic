@extends('layouts.app')
@section('title', 'Pérdidas — ' . $modulo->nombre)

@section('content')
<div class="px-4 py-6 mx-auto max-w-5xl space-y-5">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                <div class="p-2 bg-red-100 rounded-lg text-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                </div>
                Pérdidas del Módulo
            </h1>
            <p class="text-sm text-gray-500 mt-0.5 ml-11">{{ $modulo->nombre }}</p>
        </div>
        <a href="{{ route('modulo.inventario', $modulo->id) }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Volver al Inventario
        </a>
    </div>

    {{-- Alertas --}}
    @if(session('success'))
        <div class="flex items-center gap-3 p-4 text-sm text-green-800 bg-green-50 border border-green-200 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="flex items-start gap-3 p-4 text-sm text-red-800 bg-red-50 border border-red-200 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0 mt-0.5 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

{{-- Formulario de registro --}}
<div class="bg-white border border-gray-200 rounded-lg shadow-sm">
    <div class="p-4 border-b border-gray-100 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
        <h2 class="text-sm font-semibold text-gray-800">Registrar Nueva Pérdida</h2>
    </div>

    <form method="POST" action="{{ route('modulo.perdidas.store', $modulo->id) }}" class="p-5">
        @csrf
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

            {{-- Vacuna --}}
            <div class="lg:col-span-2">
                <label for="vacuna_id" class="block mb-1.5 text-sm font-medium text-gray-700">
                    Vacuna / Insumo <span class="text-red-500">*</span>
                </label>
                <select name="vacuna_id" id="vacuna_id" required
                    class="bg-gray-50 border {{ $errors->has('vacuna_id') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">— Seleccionar —</option>
                    @foreach($vacunas as $vacuna)
                        <option value="{{ $vacuna->id }}" @selected(old('vacuna_id') == $vacuna->id)>
                            {{ $vacuna->nombre }}
                            (Stock: {{ $modulo->stockVacuna($vacuna->id) }})
                        </option>
                    @endforeach
                </select>
                @error('vacuna_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Cantidad --}}
            <div>
                <label for="cantidad" class="block mb-1.5 text-sm font-medium text-gray-700">
                    Cantidad <span class="text-red-500">*</span>
                </label>
                <input type="number" name="cantidad" id="cantidad"
                    value="{{ old('cantidad') }}"
                    min="1" step="1" required placeholder="0"
                    class="bg-gray-50 border {{ $errors->has('cantidad') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                <p id="stock-info" class="mt-1 text-xs text-gray-400 hidden">
                    Stock disponible: <span id="stock-valor" class="font-semibold text-gray-600"></span>
                </p>
                @error('cantidad')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Lote — se carga via AJAX --}}
            <div>
                <label for="lote" class="block mb-1.5 text-sm font-medium text-gray-700">
                    Lote
                    <span id="lote-cargando" class="hidden text-xs text-blue-500 font-normal ml-1">cargando...</span>
                </label>
                <select name="lote" id="lote"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">— Selecciona una vacuna primero —</option>
                </select>
                <p class="mt-1 text-xs text-gray-400">Solo lotes con stock disponible</p>
                @error('lote')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Motivo --}}
            <div>
                <label for="motivo" class="block mb-1.5 text-sm font-medium text-gray-700">
                    Motivo <span class="text-red-500">*</span>
                </label>
                <select name="motivo" id="motivo" required
                    class="bg-gray-50 border {{ $errors->has('motivo') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">— Seleccionar —</option>
                    @foreach(\App\Models\Perdida::MOTIVOS as $motivo)
                        <option value="{{ $motivo }}" @selected(old('motivo') == $motivo)>{{ $motivo }}</option>
                    @endforeach
                </select>
                @error('motivo')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Fecha --}}
            <div>
                <label for="fecha" class="block mb-1.5 text-sm font-medium text-gray-700">
                    Fecha <span class="text-red-500">*</span>
                </label>
                <input type="date" name="fecha" id="fecha"
                    value="{{ old('fecha', date('Y-m-d')) }}"
                    max="{{ date('Y-m-d') }}" required
                    class="bg-gray-50 border {{ $errors->has('fecha') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                @error('fecha')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Observación --}}
            <div class="sm:col-span-2 lg:col-span-3">
                <label for="observacion" class="block mb-1.5 text-sm font-medium text-gray-700">Observación</label>
                <textarea name="observacion" id="observacion" rows="2" maxlength="500"
                    placeholder="Detalles adicionales sobre la pérdida..."
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('observacion') }}</textarea>
                @error('observacion')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex justify-end mt-4 pt-4 border-t border-gray-100">
            <button type="submit"
                class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Registrar Pérdida
            </button>
        </div>
    </form>
</div>

    {{-- Tabla de pérdidas --}}
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" x2="21" y1="6" y2="6"/><line x1="8" x2="21" y1="12" y2="12"/><line x1="8" x2="21" y1="18" y2="18"/><line x1="3" x2="3.01" y1="6" y2="6"/><line x1="3" x2="3.01" y1="12" y2="12"/><line x1="3" x2="3.01" y1="18" y2="18"/></svg>
                <h2 class="text-sm font-semibold text-gray-800">Historial de Pérdidas</h2>
            </div>
            <span class="text-xs text-gray-400">{{ $perdidas->total() }} registro(s)</span>
        </div>

        @if($perdidas->isEmpty())
            <div class="py-12 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-300 mx-auto mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                <p class="text-sm text-gray-400">No hay pérdidas registradas para este módulo.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-5 py-3">Vacuna / Insumo</th>
                            <th class="px-5 py-3">Lote</th>
                            <th class="px-5 py-3 text-center">Cantidad</th>
                            <th class="px-5 py-3">Motivo</th>
                            <th class="px-5 py-3">Fecha</th>
                            <th class="px-5 py-3">Observación</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($perdidas as $perdida)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-3.5 font-medium text-gray-900">
                                    {{ $perdida->vacuna->nombre ?? '—' }}
                                </td>
                                <td class="px-5 py-3.5">
                                    @if($perdida->lote)
                                        <span class="font-mono text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded">
                                            {{ $perdida->lote }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs italic">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-100 text-red-700 font-bold text-xs">
                                        {{ $perdida->cantidad }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    @php
                                        $colores = [
                                            'Vencimiento'    => 'bg-orange-100 text-orange-700',
                                            'Rotura'         => 'bg-red-100 text-red-700',
                                            'Cadena de frío' => 'bg-blue-100 text-blue-700',
                                            'Otro'           => 'bg-gray-100 text-gray-600',
                                        ];
                                        $clase = $colores[$perdida->motivo] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <span class="text-xs font-medium px-2.5 py-0.5 rounded-full {{ $clase }}">
                                        {{ $perdida->motivo }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-gray-600 whitespace-nowrap">
                                    {{ $perdida->fecha->format('d/m/Y') }}
                                </td>
                                <td class="px-5 py-3.5 text-gray-500 max-w-xs truncate">
                                    {{ $perdida->observacion ?? '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if($perdidas->hasPages())
                <div class="px-5 py-3 border-t border-gray-100">
                    {{ $perdidas->withQueryString()->links() }}
                </div>
            @endif
        @endif
    </div>

</div>

@push('scripts')
<script>
    const LOTES_URL_BASE = '{{ url("modulo/" . $modulo->id . "/lotes") }}';
    const selectVacuna   = document.getElementById('vacuna_id');
    const selectLote     = document.getElementById('lote');
    const stockInfo      = document.getElementById('stock-info');
    const stockValor     = document.getElementById('stock-valor');
    const inputCantidad  = document.getElementById('cantidad');
    const loteCargando   = document.getElementById('lote-cargando');

    selectVacuna?.addEventListener('change', function () {
        const vacunaId = this.value;
        const opt = this.options[this.selectedIndex];

        // Mostrar stock
        if (vacunaId) {
            // Extraer el número del texto "(Stock: N)"
            const match = opt.text.match(/Stock:\s*(\d+)/);
            if (match) {
                stockValor.textContent = match[1] + ' dosis';
                stockInfo.classList.remove('hidden');
                inputCantidad.max = parseInt(match[1]);
            }
        } else {
            stockInfo.classList.add('hidden');
            inputCantidad.removeAttribute('max');
        }

        // Cargar lotes
        selectLote.innerHTML = '<option value="">Cargando...</option>';
        selectLote.disabled = true;
        loteCargando.classList.remove('hidden');

        if (!vacunaId) {
            selectLote.innerHTML = '<option value="">— Selecciona una vacuna primero —</option>';
            selectLote.disabled = false;
            loteCargando.classList.add('hidden');
            return;
        }

        fetch(`${LOTES_URL_BASE}/${vacunaId}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            selectLote.innerHTML = '<option value="">— Sin lote específico —</option>';
            if (data.lotes && data.lotes.length > 0) {
                data.lotes.forEach(l => {
                    const vence = l.fecha_vencimiento ? ` · Vence: ${l.fecha_vencimiento}` : '';
                    const opt = document.createElement('option');
                    opt.value = l.lote;
                    opt.textContent = `${l.lote} (${l.disponible} disp.${vence})`;
                    selectLote.appendChild(opt);
                });
            } else {
                selectLote.innerHTML += '<option disabled>Sin lotes con stock disponible</option>';
            }
        })
        .catch(() => {
            selectLote.innerHTML = '<option value="">Error al cargar lotes</option>';
        })
        .finally(() => {
            selectLote.disabled = false;
            loteCargando.classList.add('hidden');
        });
    });

    // Validar cantidad contra max
    inputCantidad?.addEventListener('input', function () {
        const max = parseInt(this.max) || Infinity;
        if (parseInt(this.value) > max) {
            this.value = max;
        }
        if (parseInt(this.value) < 1) this.value = '';
    });
</script>
@endpush
@endsection