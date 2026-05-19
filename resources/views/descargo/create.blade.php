@extends('layouts.app')
@section('title', 'Descargo Rápido de Vacunas')

@section('content')
<div class="px-4 py-6 mx-auto max-w-4xl bg-white/90 backdrop-blur-lg shadow-sm rounded-lg">

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-orange-700 flex items-center gap-2">
                <div class="p-2 bg-orange-600 rounded text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><path d="M13 2v7h7"/><path d="M12 12v6"/><path d="M9 15h6"/></svg>
                </div>
                Descargo Rápido
            </h1>
            <p class="text-sm text-gray-500 mt-1">Registra el uso de vacunas sin necesidad de vincular un paciente</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('tratamientos.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-teal-700 bg-teal-50 border border-teal-200 rounded-lg hover:bg-teal-100">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 2 4 4"/><path d="m17 7 3-3"/><path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5"/><path d="m9 11 4 4"/><path d="m5 19-3 3"/><path d="m14 4 6 6"/></svg>
                Con paciente
            </a>
            <a href="{{ route('tratamientos.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                Volver
            </a>
        </div>
    </div>

    {{-- Alerta de contexto --}}
    <div class="flex items-start gap-3 p-4 mb-5 bg-amber-50 border border-amber-200 rounded-xl">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-600 shrink-0 mt-0.5"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
        <div class="text-sm text-amber-700">
            <p class="font-semibold">Descargo sin paciente registrado</p>
            <p class="mt-0.5">Las dosis quedarán registradas como "uso anónimo" y se descontarán del inventario del módulo. Para trazabilidad completa usa <a href="{{ route('tratamientos.create') }}" class="underline font-medium">Registrar con paciente</a>.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="flex items-center gap-3 p-4 mb-5 text-green-800 bg-green-50 border border-green-200 rounded-xl">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif

    {{-- TABS --}}
    <div class="flex border-b border-gray-200 mb-0">
        <button id="tab-individual" onclick="switchTab('individual')"
            class="tab-btn flex items-center gap-2 px-5 py-3 text-sm font-medium border-b-2 border-orange-500 text-orange-600 -mb-px">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 2 4 4"/><path d="m17 7 3-3"/><path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5"/><path d="m9 11 4 4"/><path d="m5 19-3 3"/><path d="m14 4 6 6"/></svg>
            Una vacuna
        </button>
        <button id="tab-bulk" onclick="switchTab('bulk')"
            class="tab-btn flex items-center gap-2 px-5 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 -mb-px">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
            Múltiples vacunas
        </button>
    </div>

    {{-- ====================================================== --}}
    {{-- TAB INDIVIDUAL --}}
    {{-- ====================================================== --}}
    <div id="panel-individual" class="tab-panel">
        <div class="bg-white border border-gray-200 border-t-0 rounded-b-xl shadow-sm">
            <form method="POST" action="{{ route('descargo.store') }}">
                @csrf

                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">

                    {{-- Vacuna --}}
                    <div class="sm:col-span-2">
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Vacuna <span class="text-red-500">*</span>
                        </label>
                        <select name="vacuna_id" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 @error('vacuna_id') border-red-500 @enderror">
                            <option value="">Seleccionar vacuna...</option>
                            @foreach($vacunas as $v)
                            <option value="{{ $v->id }}" @selected(old('vacuna_id') == $v->id)>
                                {{ $v->nombre }}
                            </option>
                            @endforeach
                        </select>
                        @error('vacuna_id')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Cantidad --}}
                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Cantidad de dosis <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><path d="M2 7.5V5a2 2 0 0 1 2-2h2.5"/><path d="M7.5 2H5a2 2 0 0 0-2 2v2.5"/><path d="M22 16.5V19a2 2 0 0 1-2 2h-2.5"/><path d="M16.5 22H19a2 2 0 0 0 2-2v-2.5"/><rect width="10" height="10" x="7" y="7" rx="2"/></svg>
                            </div>
                            <input type="number" name="cantidad" min="1" value="{{ old('cantidad', 1) }}" required
                                class="pl-9 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 @error('cantidad') border-red-500 @enderror">
                        </div>
                        @error('cantidad')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Fecha --}}
                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Fecha de aplicación <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                            </div>
                            <input type="date" name="fecha_aplicacion"
                                value="{{ old('fecha_aplicacion', date('Y-m-d')) }}" required
                                class="pl-9 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5">
                        </div>
                        @error('fecha_aplicacion')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Subtipo --}}
                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">Tipo de paciente</label>
                        <select name="subtipo_paciente"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5">
                            <option value="general">General</option>
                            <option value="personal_salud">Personal de Salud</option>
                            <option value="dialisis">Pacientes en Diálisis</option>
                            <option value="privado_libertad">Privados de Libertad</option>
                            <option value="trabajador_sexual">Trabajadores Sexuales</option>
                            <option value="embarazada">Embarazadas</option>
                        </select>
                    </div>

                    {{-- Jornada --}}
                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Jornada <span class="text-gray-400 font-normal text-xs">(opcional)</span>
                        </label>
                        <select name="jornada_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5">
                            <option value="">— Sin jornada —</option>
                            @foreach($jornadas as $j)
                            <option value="{{ $j->id }}" @selected(old('jornada_id') == $j->id || request('jornada_id') == $j->id)>
                                {{ $j->fecha_jornada->format('d/m/Y') }}
                                @if($j->descripcion) · {{ Str::limit($j->descripcion, 30) }}@endif
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Observaciones --}}
                    <div class="sm:col-span-2">
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">Observaciones</label>
                        <textarea name="observaciones" rows="2"
                            placeholder="Campaña especial, brigada, motivo del descargo..."
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5">{{ old('observaciones') }}</textarea>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-end gap-3 px-5 py-4 border-t border-gray-100 bg-gray-50 rounded-b-xl">
                    <a href="{{ route('tratamientos.index') }}"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700 focus:ring-4 focus:ring-orange-300 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                        Registrar Descargo
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ====================================================== --}}
    {{-- TAB BULK --}}
    {{-- ====================================================== --}}
    <div id="panel-bulk" class="tab-panel hidden">
        <div class="bg-white border border-gray-200 border-t-0 rounded-b-xl shadow-sm">
            <form method="POST" action="{{ route('descargo.bulk') }}">
                @csrf

                {{-- Jornada común --}}
                <div class="p-5 border-b border-gray-100">
                    <div class="flex flex-wrap items-end gap-4">
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">
                                Jornada común <span class="text-gray-400 font-normal text-xs">(aplica a todos)</span>
                            </label>
                            <select name="jornada_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 p-2.5 min-w-52">
                                <option value="">— Sin jornada —</option>
                                @foreach($jornadas as $j)
                                <option value="{{ $j->id }}">
                                    {{ $j->fecha_jornada->format('d/m/Y') }}
                                    @if($j->descripcion) · {{ Str::limit($j->descripcion, 25) }}@endif
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-3 ml-auto">
                            <span class="text-sm text-gray-500">Total dosis:</span>
                            <span id="bulk_total" class="text-lg font-bold text-orange-600">0</span>
                            <button type="button" onclick="agregarFila()"
                                class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-orange-700 bg-orange-50 border border-orange-200 rounded-lg hover:bg-orange-100">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>
                                Agregar vacuna
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Tabla --}}
                <div class="p-5">
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 text-xs text-gray-500 uppercase border-b border-gray-200">
                                <tr>
                                    <th class="px-3 py-2.5 text-left" style="min-width:200px">Vacuna *</th>
                                    <th class="px-3 py-2.5 text-left" style="min-width:100px">Cantidad *</th>
                                    <th class="px-3 py-2.5 text-left" style="min-width:130px">Fecha *</th>
                                    <th class="px-3 py-2.5 text-left" style="min-width:160px">Tipo paciente</th>
                                    <th class="px-3 py-2.5 text-left" style="min-width:180px">Observaciones</th>
                                    <th class="px-3 py-2.5 w-10"></th>
                                </tr>
                            </thead>
                            <tbody id="bulk_tbody" class="divide-y divide-gray-100"></tbody>
                        </table>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-end gap-3 px-5 py-4 border-t border-gray-100 bg-gray-50 rounded-b-xl">
                    <a href="{{ route('tratamientos.index') }}"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700 focus:ring-4 focus:ring-orange-300 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                        Registrar todos
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
const VACUNAS = @json($vacunas->map(fn($v) => ['id' => $v->id, 'nombre' => $v->nombre]));
const today   = '{{ date("Y-m-d") }}';
let rowIdx    = 0;

function switchTab(tab) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('border-orange-500', 'text-orange-600');
        b.classList.add('border-transparent', 'text-gray-500');
    });
    document.getElementById('panel-' + tab).classList.remove('hidden');
    const btn = document.getElementById('tab-' + tab);
    btn.classList.add('border-orange-500', 'text-orange-600');
    btn.classList.remove('border-transparent', 'text-gray-500');
}

function agregarFila() {
    const idx = rowIdx++;
    const tr  = document.createElement('tr');
    tr.id     = `fila_${idx}`;
    tr.className = 'border-b border-gray-100 hover:bg-gray-50/50';

    const optsVacuna = VACUNAS.map(v =>
        `<option value="${v.id}">${v.nombre}</option>`
    ).join('');

    tr.innerHTML = `
        <td class="px-2 py-2 align-top">
            <select name="descargas[${idx}][vacuna_id]" required
                class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2">
                <option value="">Seleccionar...</option>
                ${optsVacuna}
            </select>
        </td>
        <td class="px-2 py-2 align-top">
            <input type="number" name="descargas[${idx}][cantidad]" min="1" value="1" required
                oninput="actualizarTotal()"
                class="bulk-cant bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2">
        </td>
        <td class="px-2 py-2 align-top">
            <input type="date" name="descargas[${idx}][fecha_aplicacion]" value="${today}" required
                class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2">
        </td>
        <td class="px-2 py-2 align-top">
            <select name="descargas[${idx}][subtipo_paciente]"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2">
                <option value="general">General</option>
                <option value="personal_salud">Personal Salud</option>
                <option value="dialisis">Diálisis</option>
                <option value="privado_libertad">Priv. Libertad</option>
                <option value="trabajador_sexual">Trab. Sexual</option>
                <option value="embarazada">Embarazada</option>
            </select>
        </td>
        <td class="px-2 py-2 align-top">
            <input type="text" name="descargas[${idx}][observaciones]"
                placeholder="Opcional..."
                class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2">
        </td>
        <td class="px-2 py-2 align-middle text-center">
            <button type="button" onclick="eliminarFila(${idx})"
                class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
            </button>
        </td>`;

    document.getElementById('bulk_tbody').appendChild(tr);
    actualizarTotal();
}

function eliminarFila(idx) {
    const filas = document.querySelectorAll('#bulk_tbody tr');
    if (filas.length <= 1) return;
    document.getElementById(`fila_${idx}`)?.remove();
    actualizarTotal();
}

function actualizarTotal() {
    let total = 0;
    document.querySelectorAll('.bulk-cant').forEach(i => total += parseInt(i.value) || 0);
    document.getElementById('bulk_total').textContent = total;
}

// Iniciar con una fila
agregarFila();
</script>
@endpush
@endsection