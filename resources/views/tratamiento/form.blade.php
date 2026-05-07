@php
$esEdicion = isset($tratamiento) && $tratamiento->id;
// En edición, la jornada y el paciente no se deben precargar dinámicamente
$jornadaSeleccionada = old('jornada_id', $tratamiento?->jornada_id ?? $jornadaPreload?->id ?? null);
$pacienteIdPreload = old('paciente_id', $tratamiento?->paciente_id ?? $pacientePreload?->id ?? null);
$cedulaPreload = $tratamiento?->paciente?->cedula ?? $pacientePreload?->cedula ?? null;
@endphp

<input type="hidden" name="paciente_id" id="paciente_cedula_hidden" value="{{ $cedulaPreload }}">

<div class="p-5 space-y-6">

    {{-- ─── SECCIÓN 1: Jornada ──────────────────────── --}}
    <div>
        <label class="block mb-1.5 text-sm font-medium text-gray-700 flex items-center gap-1.5">
            <i data-lucide="calendar-check-2" class="w-3.5 h-3.5 text-emerald-600"></i>
            Jornada de Vacunación <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
            </div>
            <select name="jornada_id" id="jornada_id" onchange="onJornadaChange(this.value)"
                class="pl-9 bg-gray-50 border {{ $errors->has('jornada_id') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block w-full p-2.5">
                <option value="">Seleccionar jornada...</option>
                @foreach($jornadas as $j)
                <option value="{{ $j->id }}"
                    data-fecha="{{ \Carbon\Carbon::parse($j->fecha_jornada)->format('Y-m-d') }}"
                    @selected($jornadaSeleccionada==$j->id)>
                    {{ \Carbon\Carbon::parse($j->fecha_jornada)->format('d/m/Y') }}
                    — {{ $j->responsable?->nombre }} {{ $j->responsable?->apellido }}
                    @if($j->descripcion) · {{ \Illuminate\Support\Str::limit($j->descripcion, 30) }}@endif
                </option>
                @endforeach
            </select>
        </div>
        @error('jornada_id')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
        <div class="mt-2">
            <a href="{{ route('jornadas.create') }}" target="_blank"
                class="text-xs text-emerald-600 hover:text-emerald-800 flex items-center gap-1">
                <i data-lucide="plus-circle" class="w-3 h-3"></i> Crear nueva jornada (abre en nueva pestaña)
            </a>
        </div>
    </div>

    {{-- ─── SECCIÓN 2: Paciente ─────────────────────── --}}
    <div class="border border-gray-200 rounded-xl p-5 bg-gray-50/50">
        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <i data-lucide="user" class="w-4 h-4 text-teal-600"></i>
            Paciente
        </h3>

        <div class="mb-4">
            <label class="block mb-1.5 text-sm font-medium text-gray-700">
                Buscar por Cédula <span class="text-red-500">*</span>
            </label>
            <div class="flex gap-2">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i data-lucide="id-card" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    <input type="text" id="paciente_buscar" placeholder="Buscar por nombre o cédula..."
                        autocomplete="off"
                        class="pl-9 bg-white border {{ $errors->has('paciente_id') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block w-full p-2.5">

                    {{-- Dropdown de resultados --}}
                    <div id="paciente_dropdown"
                        class="hidden absolute z-50 w-full bg-white border border-gray-200 rounded-lg shadow-xl mt-1 max-h-52 overflow-y-auto">
                    </div>
                </div>
                <button type="button" onclick="buscarPaciente(document.getElementById('cedula_busqueda').value)"
                    class="px-4 py-2.5 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </button>
            </div>
            @error('paciente_id')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        {{-- Resultado de búsqueda --}}
        <div id="pacienteInfo" class="{{ $cedulaPreload ? '' : 'hidden' }}">
            {{-- Paciente encontrado --}}
            <div id="pacienteEncontrado"
                class="{{ ($cedulaPreload && $tratamiento?->paciente) || ($cedulaPreload && isset($pacientePreload)) ? '' : 'hidden' }} p-4 bg-teal-50 border border-teal-200 rounded-lg">
                <div class="flex items-start justify-between">
                    <div class="flex items-start gap-3">
                        <div id="pacienteAvatar"
                            class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center flex-shrink-0 text-sm font-bold text-teal-700">
                            {{ strtoupper(substr($tratamiento?->paciente?->nombres ?? $pacientePreload?->nombres ?? '?',
                            0, 1)) }}
                        </div>
                        <div>
                            <p id="pacienteNombre" class="text-sm font-bold text-gray-900">
                                {{ $tratamiento?->paciente?->nombres ?? $pacientePreload?->nombres ?? '' }}
                                {{ $tratamiento?->paciente?->apellidos ?? $pacientePreload?->apellidos ?? '' }}
                            </p>
                            <p id="pacienteDatos" class="text-xs text-gray-500 mt-0.5">
                                CI: <span id="pacienteCedula">{{ $cedulaPreload }}</span>
                                @php $edad = $tratamiento?->paciente?->fecha_nacimiento
                                ? \Carbon\Carbon::parse($tratamiento->paciente->fecha_nacimiento)->age
                                : ($pacientePreload?->fecha_nacimiento ?
                                \Carbon\Carbon::parse($pacientePreload->fecha_nacimiento)->age : null);
                                @endphp
                                @if($edad !== null) · {{ $edad }} años @endif
                            </p>
                            <p id="pacienteEstado" class="text-xs mt-1"></p>
                        </div>
                    </div>
                    <a id="pacienteLink"
                        href="{{ $tratamiento?->paciente ? route('pacientes.show', $tratamiento->paciente->id) : ($pacientePreload ? route('pacientes.show', $pacientePreload->id) : '#') }}"
                        target="_blank" class="flex items-center gap-1 text-xs text-teal-600 hover:text-teal-800">
                        <i data-lucide="external-link" class="w-3 h-3"></i> Ver ficha
                    </a>
                </div>
                {{-- Resumen de vacunas previas --}}
                <div id="vacunasPrevias" class="mt-3 pt-3 border-t border-teal-200 hidden">
                    <p class="text-xs text-teal-700 font-medium mb-2 flex items-center gap-1">
                        <i data-lucide="clock" class="w-3 h-3"></i> Vacunas previas de esta vacuna:
                    </p>
                    <div id="vacunasPreviasLista" class="flex flex-wrap gap-1.5"></div>
                </div>
            </div>

            {{-- Paciente NO encontrado --}}
            <div id="pacienteNoEncontrado" class="hidden p-4 bg-amber-50 border border-amber-200 rounded-lg">
                <div class="flex items-start gap-3">
                    <i data-lucide="user-x" class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-semibold text-amber-800">Paciente no registrado</p>
                        <p class="text-xs text-amber-700 mt-0.5">No hay ningún paciente con esa cédula.</p>
                        <a id="btnRegistrarPaciente" href="{{ route('pacientes.create') }}" target="_blank"
                            class="inline-flex items-center gap-1.5 mt-2 px-3 py-1.5 text-xs font-medium text-white bg-amber-600 rounded-lg hover:bg-amber-700">
                            <i data-lucide="user-plus" class="w-3 h-3"></i>
                            Registrar paciente primero
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── SECCIÓN 3: Vacuna + Dosis ──────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

        {{-- Vacuna --}}
        <div>
            <label class="block mb-1.5 text-sm font-medium text-gray-700">
                Vacuna <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i data-lucide="syringe" class="w-4 h-4 text-gray-400"></i>
                </div>
                <select name="vacuna_id" id="vacuna_id" onchange="onVacunaChange(this.value)"
                    class="pl-9 bg-gray-50 border {{ $errors->has('vacuna_id') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block w-full p-2.5">
                    <option value="">Seleccionar vacuna...</option>
                    @foreach($vacunas as $v)
                    <option value="{{ $v->id }}" data-dosis="{{ $v->numero_dosis }}"
                        data-intervalo="{{ $v->intervalo }}" data-refuerzo="{{ $v->refuerzo }}"
                        @selected(old('vacuna_id', $tratamiento?->vacuna_id) == $v->id)>
                        {{ $v->nombre }}
                        @if($v->numero_dosis) ({{ $v->numero_dosis }} dosis)@endif
                    </option>
                    @endforeach
                </select>
            </div>
            @error('vacuna_id')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror

            {{-- Info de la vacuna seleccionada --}}
            <div id="vacunaInfo"
                class="hidden mt-2 p-3 bg-teal-50 border border-teal-100 rounded-lg text-xs text-teal-700 space-y-0.5">
                <p>Dosis totales: <strong id="vi_dosis">—</strong></p>
                <p>Intervalo: <strong id="vi_intervalo">—</strong></p>
                <p id="vi_refuerzo_wrap" class="hidden">Refuerzo: <strong id="vi_refuerzo">—</strong></p>
            </div>
        </div>

        {{-- Dosis aplicada --}}
        <div>
            <label class="block mb-1.5 text-sm font-medium text-gray-700">
                Número de Dosis <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i data-lucide="hash" class="w-4 h-4 text-gray-400"></i>
                </div>
                <input type="number" name="dosis_aplicada" id="dosis_aplicada" min="1"
                    value="{{ old('dosis_aplicada', $tratamiento?->dosis_aplicada ?? 1) }}" onchange="calcularProxima()"
                    class="pl-9 bg-gray-50 border {{ $errors->has('dosis_aplicada') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block w-full p-2.5">
            </div>
            @error('dosis_aplicada')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror

            {{-- Indicador de historial --}}
            <div id="dosisIndicador" class="hidden mt-2">
                <div class="flex flex-wrap gap-1" id="dosisBadges"></div>
                <p id="dosisCompletado" class="hidden mt-1.5 text-xs text-green-600 flex items-center gap-1">
                    <i data-lucide="check-circle" class="w-3 h-3"></i> Esquema de vacunación completado
                </p>
            </div>
        </div>

        {{-- Fecha aplicación --}}
        <div>
            <label class="block mb-1.5 text-sm font-medium text-gray-700">
                Fecha de Aplicación <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
                </div>
                <input type="date" name="fecha_aplicacion" id="fecha_aplicacion"
                    value="{{ old('fecha_aplicacion', $tratamiento?->fecha_aplicacion?->format('Y-m-d') ?? date('Y-m-d')) }}"
                    max="{{ date('Y-m-d') }}" onchange="calcularProxima()"
                    class="pl-9 bg-gray-50 border {{ $errors->has('fecha_aplicacion') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block w-full p-2.5">
            </div>
            @error('fecha_aplicacion')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        {{-- Próxima dosis (calculada, solo lectura) --}}
        <div>
            <label class="block mb-1.5 text-sm font-medium text-gray-700 flex items-center gap-1.5">
                <i data-lucide="calendar-clock" class="w-3.5 h-3.5 text-teal-500"></i>
                Próxima Dosis (calculada)
            </label>
            <div id="proximaDosisBox"
                class="p-3 bg-gray-50 border border-gray-200 rounded-lg min-h-[42px] flex items-center">
                <p id="proximaDosisTexto" class="text-sm text-gray-400 italic">
                    Selecciona vacuna y fecha para calcular
                </p>
            </div>
        </div>
    </div>

    {{-- ─── SECCIÓN 4: Observaciones ──────────────── --}}
    <div>
        <label class="block mb-1.5 text-sm font-medium text-gray-700">
            Observaciones <span class="text-gray-400 font-normal">(opcional)</span>
        </label>
        <textarea name="observaciones" id="observaciones" rows="3"
            placeholder="Reacciones, condiciones del paciente, notas relevantes..."
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block w-full p-2.5 resize-none">{{ old('observaciones', $tratamiento?->observaciones) }}</textarea>
        @error('observaciones')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
</div>

{{-- Footer --}}
<div class="flex items-center justify-end gap-3 px-5 py-4 border-t border-gray-100 bg-gray-50 rounded-b-lg">
    <a href="{{ route('tratamientos.index') }}"
        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
        Cancelar
    </a>
    <button type="submit" id="btnGuardar"
        class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-teal-700 rounded-lg hover:bg-teal-800 focus:ring-4 focus:ring-teal-300">
        <i data-lucide="save" class="w-4 h-4"></i>
        {{ $esEdicion ? 'Actualizar' : 'Registrar' }} Vacunación
    </button>
</div>

@push('scripts')
<script>
    lucide.createIcons();

    let pacienteActual = null;

    // ── JORNADA: auto-fill fecha al seleccionar ───────────────
    function onJornadaChange(id) {
        if (!id) return;
        const opt = document.querySelector(`#jornada_id option[value="${id}"]`);
        if (opt?.dataset.fecha) {
            document.getElementById('fecha_aplicacion').value = opt.dataset.fecha;
            calcularProxima();
        }
    }

    // ── BUSCAR PACIENTE por cédula (AJAX) ─────────────────────
    let buscarTimer = null;
const PACIENTES_URL = '{{ route("pacientes.index") }}';

document.getElementById('paciente_buscar').addEventListener('input', function() {
    clearTimeout(buscarTimer);
    const q = this.value.trim();
    const dropdown = document.getElementById('paciente_dropdown');

    if (q.length < 2) {
        dropdown.classList.add('hidden');
        return;
    }

    buscarTimer = setTimeout(() => {
        fetch(`/api/paciente-buscar?search=${encodeURIComponent(q)}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            dropdown.innerHTML = '';
            if (!data.length) {
                dropdown.innerHTML = `<div class="px-4 py-3 text-xs text-gray-400 text-center">Sin resultados · <a href="{{ route('pacientes.create') }}" class="text-teal-600 underline">Registrar paciente</a></div>`;
            } else {
                data.forEach(p => {
                    const div = document.createElement('div');
                    div.className = 'px-4 py-2.5 cursor-pointer hover:bg-teal-50 border-b border-gray-50 last:border-0';
                    div.innerHTML = `
                        <p class="text-sm font-semibold text-gray-900">${p.nombres} ${p.apellidos}</p>
                        <p class="text-xs text-gray-400">${p.cedula ? 'CI: ' + p.cedula : 'Sin cédula'} · ${p.edad ? p.edad + ' años' : ''} · ${p.sexo === 'M' ? 'Masculino' : 'Femenino'}</p>
                    `;
                    div.addEventListener('mousedown', () => {
                        seleccionarPaciente(p);
                        dropdown.classList.add('hidden');
                    });
                    dropdown.appendChild(div);
                });
            }
            dropdown.classList.remove('hidden');
        });
    }, 300);
});

document.getElementById('paciente_buscar').addEventListener('blur', () => {
    setTimeout(() => document.getElementById('paciente_dropdown').classList.add('hidden'), 150);
});

function seleccionarPaciente(p) {
    document.getElementById('paciente_cedula_hidden').value = p.id;
    document.getElementById('paciente_buscar').value = p.nombres + ' ' + p.apellidos;
    // Actualizar la tarjeta de info del paciente
    mostrarPaciente(p);
}

    function buscarPacientePropio(cedula) {
        fetch(`{{ route('pacientes.index') }}?search=${cedula}&formato=json`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.found) mostrarPaciente(data);
            else mostrarPacienteNoEncontrado(cedula);
        })
        .catch(() => mostrarPacienteNoEncontrado(cedula));
    }

    function mostrarPaciente(data) {
        pacienteActual = data;
        document.getElementById('paciente_cedula_hidden').value = data.id;
        document.getElementById('pacienteNombre').textContent   = data.nombres + ' ' + data.apellidos;
        document.getElementById('pacienteCedula').textContent   = data.cedula;
        document.getElementById('pacienteAvatar').textContent   = (data.nombres?.[0] ?? '?').toUpperCase();
        document.getElementById('pacienteDatos').innerHTML      =
            `CI: ${data.cedula}` + (data.edad ? ` · ${data.edad} años` : '');
        document.getElementById('pacienteEstado').innerHTML =
            data.activo
            ? `<span class="text-green-600">✓ Activo</span>`
            : `<span class="text-red-600">⚠ Inactivo</span>`;
        document.getElementById('pacienteLink').href = `/pacientes/${data.id}`;
        document.getElementById('pacienteEncontrado').classList.remove('hidden');
        document.getElementById('pacienteNoEncontrado').classList.add('hidden');
        document.getElementById('pacienteInfo').classList.remove('hidden');

        // Actualizar historial de vacunas si ya hay vacuna seleccionada
        const vacunaId = document.getElementById('vacuna_id').value;
        if (vacunaId) cargarDosisHistorial(data.cedula, vacunaId);
        lucide.createIcons();
    }

    function mostrarPacienteNoEncontrado(cedula) {
        document.getElementById('paciente_cedula_hidden').value = '';
        pacienteActual = null;
        document.getElementById('pacienteEncontrado').classList.add('hidden');
        document.getElementById('pacienteNoEncontrado').classList.remove('hidden');
        document.getElementById('pacienteInfo').classList.remove('hidden');
        lucide.createIcons();
    }

    // ── VACUNA: mostrar info y calcular dosis ─────────────────
    function onVacunaChange(vacunaId) {
        const opt = document.querySelector(`#vacuna_id option[value="${vacunaId}"]`);
        const infoBox = document.getElementById('vacunaInfo');

        if (!opt || !vacunaId) {
            infoBox.classList.add('hidden');
            return;
        }

        infoBox.classList.remove('hidden');
        document.getElementById('vi_dosis').textContent      = opt.dataset.dosis || '—';
        document.getElementById('vi_intervalo').textContent  = opt.dataset.intervalo || '—';

        const refuerzo = opt.dataset.refuerzo;
        if (refuerzo) {
            document.getElementById('vi_refuerzo').textContent = refuerzo;
            document.getElementById('vi_refuerzo_wrap').classList.remove('hidden');
        } else {
            document.getElementById('vi_refuerzo_wrap').classList.add('hidden');
        }

        // Cargar historial si hay paciente
        const pacienteId = document.getElementById('paciente_cedula_hidden').value;
        if (cedula) cargarDosisHistorial(cedula, vacunaId);

        calcularProxima();
    }

    // ── HISTORIAL de dosis del paciente para esa vacuna ───────
    function cargarDosisHistorial(cedula, vacunaId) {
        fetch(`{{ route('tratamientos.dosis') }}?cedula=${cedula}&vacuna_id=${vacunaId}`)
        .then(r => r.json())
        .then(data => {
            const indicador = document.getElementById('dosisIndicador');
            const badges    = document.getElementById('dosisBadges');
            const completo  = document.getElementById('dosisCompletado');

            if (data.total_recibidas > 0) {
                indicador.classList.remove('hidden');
                badges.innerHTML = data.historial.map(d =>
                    `<span class="inline-flex items-center gap-1 px-2 py-0.5 bg-teal-100 text-teal-700 text-xs rounded-full">
                        Dosis ${d.dosis} — ${d.fecha}
                    </span>`
                ).join('');
                completo.classList.toggle('hidden', !data.completado);
            } else {
                indicador.classList.add('hidden');
            }

            // Auto-suggest dosis siguiente
            document.getElementById('dosis_aplicada').value = data.dosis_siguiente;
            calcularProxima();

            // Mostrar en sección de vacunas previas del panel del paciente
            const previas = document.getElementById('vacunasPrevias');
            const lista   = document.getElementById('vacunasPreviasLista');
            if (data.total_recibidas > 0) {
                previas.classList.remove('hidden');
                lista.innerHTML = data.historial.map(d =>
                    `<span class="px-2 py-0.5 bg-white border border-teal-200 text-teal-700 text-xs rounded-full">
                        D${d.dosis}: ${d.fecha}
                    </span>`
                ).join('');
            } else {
                previas.classList.add('hidden');
            }
            lucide.createIcons();
        })
        .catch(() => {});
    }

    // ── CALCULAR fecha próxima dosis (AJAX) ───────────────────
    function calcularProxima() {
        const vacunaId     = document.getElementById('vacuna_id').value;
        const fecha        = document.getElementById('fecha_aplicacion').value;
        const dosis        = document.getElementById('dosis_aplicada').value;
        const caja         = document.getElementById('proximaDosisTexto');

        if (!vacunaId || !fecha) {
            caja.textContent = 'Selecciona vacuna y fecha para calcular';
            caja.className = 'text-sm text-gray-400 italic';
            return;
        }

        fetch(`{{ route('tratamientos.proxima-fecha') }}?vacuna_id=${vacunaId}&fecha_aplicacion=${fecha}&dosis_aplicada=${dosis}`)
        .then(r => r.json())
        .then(data => {
            if (data.proxima_fecha_legible) {
                caja.innerHTML = `
                    <div>
                        <p class="text-sm font-semibold text-teal-700">${data.proxima_fecha_legible}</p>
                        ${data.intervalo_texto ? `<p class="text-xs text-gray-400 mt-0.5">Intervalo: ${data.intervalo_texto}</p>` : ''}
                    </div>`;
                caja.className = '';
            } else {
                caja.textContent = 'Sin intervalo definido para esta vacuna';
                caja.className = 'text-sm text-gray-400 italic';
            }
        })
        .catch(() => {
            caja.textContent = '—';
            caja.className = 'text-sm text-gray-400';
        });
    }

    // ── INICIALIZACIÓN ─────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        // Si viene con jornada preseleccionada
        const jornadaId = document.getElementById('jornada_id').value;
        if (jornadaId) onJornadaChange(jornadaId);

        // Si viene con vacuna preseleccionada (edición)
        const vacunaId = document.getElementById('vacuna_id').value;
        if (vacunaId) onVacunaChange(vacunaId);

        // Si viene con paciente precargado
        const cedula = document.getElementById('paciente_cedula_hidden').value;
        if (cedula && vacunaId) cargarDosisHistorial(cedula, vacunaId);

        calcularProxima();
    });
</script>
@endpush