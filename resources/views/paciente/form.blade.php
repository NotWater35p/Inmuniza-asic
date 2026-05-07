{{-- Determinar si el paciente es menor o ya tiene representante --}}
@php
    $tieneFechaNac = isset($paciente) && $paciente->fecha_nacimiento;
    $edad = $tieneFechaNac ? \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age : null;
    $esMenor = $edad !== null && $edad < 18;
    $tieneRep = isset($paciente) && $paciente->representante_id;
    $mostrarRep = old('mostrar_representante', $esMenor || $tieneRep ? '1' : '0');
@endphp

<div class="grid gap-5 sm:grid-cols-2 sm:gap-6 p-5">

    {{-- Cédula --}}
    <div class="sm:col-span-2">
        <label for="cedula" class="block mb-1.5 text-sm font-medium text-gray-700">
            Cédula de Identidad
            <span class="text-gray-400 font-normal text-xs ml-1">(opcional — para menores sin cédula)</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i data-lucide="id-card" class="w-4 h-4 text-gray-400"></i>
            </div>
            <input type="number" name="cedula" id="cedula" value="{{ old('cedula', $paciente?->cedula) }}"
                placeholder="12345678"
                class="pl-9 bg-gray-50 border {{ $errors->has('cedula') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        </div>
        @error('cedula')
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Nombres --}}
    <div>
        <label for="nombres" class="block mb-1.5 text-sm font-medium text-gray-700">
            Nombres <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i data-lucide="user" class="w-4 h-4 text-gray-400"></i>
            </div>
            <input type="text" name="nombres" id="nombres" value="{{ old('nombres', $paciente?->nombres) }}"
                placeholder="Juan Carlos"
                class="pl-9 bg-gray-50 border {{ $errors->has('nombres') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        </div>
        @error('nombres')
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Apellidos --}}
    <div>
        <label for="apellidos" class="block mb-1.5 text-sm font-medium text-gray-700">
            Apellidos <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i data-lucide="user" class="w-4 h-4 text-gray-400"></i>
            </div>
            <input type="text" name="apellidos" id="apellidos" value="{{ old('apellidos', $paciente?->apellidos) }}"
                placeholder="Pérez Rodríguez"
                class="pl-9 bg-gray-50 border {{ $errors->has('apellidos') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        </div>
        @error('apellidos')
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Fecha de nacimiento --}}
    <div>
        <label for="fecha_nacimiento" class="block mb-1.5 text-sm font-medium text-gray-700">
            Fecha de Nacimiento <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
            </div>
            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                value="{{ old('fecha_nacimiento', $paciente?->fecha_nacimiento?->format('Y-m-d')) }}"
                max="{{ date('Y-m-d') }}" onchange="calcularEdad(this.value)"
                class="pl-9 bg-gray-50 border {{ $errors->has('fecha_nacimiento') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        </div>
        {{-- Indicador de edad + alerta menor --}}
        <div id="edadIndicador" class="mt-1.5 text-xs hidden"></div>
        @error('fecha_nacimiento')
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Sexo --}}
    <div>
        <label class="block mb-1.5 text-sm font-medium text-gray-700">
            Sexo <span class="text-red-500">*</span>
        </label>
        <div class="flex gap-3 mt-2">
            <label
                class="flex-1 flex items-center justify-center gap-2 p-2.5 border rounded-lg cursor-pointer transition-colors
                {{ old('sexo', $paciente?->sexo) === 'M' ? 'border-blue-300 bg-blue-50 text-blue-700' : 'border-gray-200 text-gray-600 hover:border-blue-200' }}">
                <input type="radio" name="sexo" value="M" class="sr-only"
                    {{ old('sexo', $paciente?->sexo) === 'M' ? 'checked' : '' }}
                    onchange="actualizarSexo()">
                <i data-lucide="user" class="w-4 h-4"></i>
                <span class="text-sm font-medium">Masculino</span>
            </label>
            <label
                class="flex-1 flex items-center justify-center gap-2 p-2.5 border rounded-lg cursor-pointer transition-colors
                {{ old('sexo', $paciente?->sexo) === 'F' ? 'border-pink-300 bg-pink-50 text-pink-700' : 'border-gray-200 text-gray-600 hover:border-pink-200' }}">
                <input type="radio" name="sexo" value="F" class="sr-only"
                    {{ old('sexo', $paciente?->sexo) === 'F' ? 'checked' : '' }}
                    onchange="actualizarSexo()">
                <i data-lucide="user" class="w-4 h-4"></i>
                <span class="text-sm font-medium">Femenino</span>
            </label>
        </div>
        @error('sexo')
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Teléfono --}}
    <div>
        <label for="telefono" class="block mb-1.5 text-sm font-medium text-gray-700">
            Teléfono <span class="text-gray-400 font-normal text-xs">(opcional)</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i data-lucide="phone" class="w-4 h-4 text-gray-400"></i>
            </div>
            <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $paciente?->telefono) }}"
                placeholder="0412-1234567"
                class="pl-9 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        </div>
    </div>

    {{-- Dirección --}}
    <div>
        <label for="direccion" class="block mb-1.5 text-sm font-medium text-gray-700">
            Dirección <span class="text-gray-400 font-normal text-xs">(opcional)</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i data-lucide="map-pin" class="w-4 h-4 text-gray-400"></i>
            </div>
            <input type="text" name="direccion" id="direccion"
                value="{{ old('direccion', $paciente?->direccion) }}" placeholder="Av. Principal, Calle..."
                class="pl-9 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        </div>
    </div>

    {{-- Sector (searchable) --}}
    <div>
        <label class="block mb-1.5 text-sm font-medium text-gray-700 flex items-center justify-between">
            <span class="flex items-center gap-1.5">
                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-gray-400"></i>
                Sector
                <span class="text-gray-400 font-normal text-xs">(opcional)</span>
            </span>
            <button type="button" onclick="abrirModalSector()"
                class="flex items-center gap-1 text-xs text-blue-600 hover:text-blue-800 font-medium">
                <i data-lucide="plus" class="w-3 h-3"></i>
                Nuevo sector
            </button>
        </label>
        <div class="relative">
            <input type="hidden" name="sector_id" id="sector_id_hidden"
                value="{{ old('sector_id', $paciente?->sector_id) }}">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i data-lucide="map-pin" class="w-4 h-4 text-gray-400"></i>
                </div>
                <input type="text" id="sector_search" placeholder="Buscar o seleccionar sector..."
                    autocomplete="off"
                    value="{{ old('sector_id') ? $sectores->firstWhere('id', old('sector_id'))?->nombre : $paciente?->sector?->nombre ?? '' }}"
                    class="pl-9 pr-4 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
            <div id="sector_dropdown"
                class="hidden absolute z-30 w-full bottom-full mb-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                <div class="px-3 py-2 text-xs text-gray-400 border-b">Sectores registrados</div>
                @foreach ($sectores as $s)
                    <div data-id="{{ $s->id }}" data-name="{{ $s->nombre }}"
                        class="px-4 py-2.5 cursor-pointer hover:bg-blue-50 hover:text-blue-700 text-gray-700 text-sm {{ $paciente?->sector_id == $s->id ? 'bg-blue-50 text-blue-700 font-medium' : '' }}">
                        {{ $s->nombre }}
                    </div>
                @endforeach
                @if ($sectores->isEmpty())
                    <div class="px-4 py-3 text-xs text-gray-400 text-center">Sin sectores. Crea uno.</div>
                @endif
            </div>
        </div>
    </div>

    {{-- Etnia (searchable) --}}
    <div>
        <label class="block mb-1.5 text-sm font-medium text-gray-700 flex items-center justify-between">
            <span class="flex items-center gap-1.5">
                <i data-lucide="globe" class="w-3.5 h-3.5 text-gray-400"></i>
                Etnia
                <span class="text-gray-400 font-normal text-xs">(opcional — solo si indígena)</span>
            </span>
            <button type="button" onclick="abrirModalEtnia()"
                class="flex items-center gap-1 text-xs text-blue-600 hover:text-blue-800 font-medium">
                <i data-lucide="plus" class="w-3 h-3"></i>
                Nueva etnia
            </button>
        </label>
        <div class="relative">
            <input type="hidden" name="etnia_id" id="etnia_id_hidden"
                value="{{ old('etnia_id', $paciente?->etnia_id) }}">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i data-lucide="globe" class="w-4 h-4 text-gray-400"></i>
                </div>
                <input type="text" id="etnia_search" placeholder="Buscar etnia..." autocomplete="off"
                    value="{{ old('etnia_id') ? $etnias->firstWhere('id', old('etnia_id'))?->nombre : $paciente?->etnia?->nombre ?? '' }}"
                    class="pl-9 pr-4 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
            <div id="etnia_dropdown"
                class="hidden absolute z-30 w-full bottom-full mb-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                <div class="px-3 py-2 text-xs text-gray-400 border-b">Etnias registradas</div>
                @foreach ($etnias as $e)
                    <div data-id="{{ $e->id }}" data-name="{{ $e->nombre }}"
                        class="px-4 py-2.5 cursor-pointer hover:bg-blue-50 hover:text-blue-700 text-gray-700 text-sm {{ $paciente?->etnia_id == $e->id ? 'bg-blue-50 text-blue-700 font-medium' : '' }}">
                        {{ $e->nombre }}
                    </div>
                @endforeach
                @if ($etnias->isEmpty())
                    <div class="px-4 py-3 text-xs text-gray-400 text-center">Sin etnias. Crea una.</div>
                @endif
            </div>
        </div>
    </div>

    {{-- Activo --}}
    <div class="sm:col-span-2">
        <label class="relative inline-flex items-center gap-3 cursor-pointer">
            <input type="hidden" name="activo" value="0">
            <input type="checkbox" name="activo" value="1" id="activo" class="sr-only peer"
                {{ old('activo', $paciente?->activo ?? true) ? 'checked' : '' }}>
            <div
                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
            </div>
            <span class="text-sm font-medium text-gray-700">
                Paciente activo
                <span class="text-xs text-gray-400 font-normal">(desactivar en caso de defunción, no
                    eliminar)</span>
            </span>
        </label>
    </div>
</div>

{{-- ======================================================== --}}
{{-- SECCIÓN REPRESENTANTE --}}
{{-- ======================================================== --}}
<div class="mx-5 mb-5">
    {{-- Toggle para mostrar/ocultar --}}
    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors"
        onclick="toggleRepresentante()">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-amber-100 rounded-lg">
                <i data-lucide="users" class="w-4 h-4 text-amber-600"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-800">Datos del Representante</p>
                <p class="text-xs text-gray-500 mt-0.5">
                    Obligatorio para menores de edad · Opcional en otros casos
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @if ($tieneRep)
                <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-medium">Asignado</span>
            @endif
            <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400 transition-transform" id="repChevron"
                style="{{ $mostrarRep === '1' ? 'transform:rotate(180deg)' : '' }}"></i>
        </div>
    </div>
    <input type="hidden" name="mostrar_representante" id="mostrar_representante" value="{{ $mostrarRep }}">

    {{-- Panel representante --}}
    <div id="repPanel"
        class="{{ $mostrarRep !== '1' ? 'hidden' : '' }} border border-t-0 border-gray-200 rounded-b-lg">

        {{-- Alerta menor --}}
        <div id="menorAlert"
            class="{{ !$esMenor ? 'hidden' : '' }} mx-4 mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg">
            <p class="text-xs text-amber-700 flex items-center gap-1.5">
                <i data-lucide="alert-triangle" class="w-3.5 h-3.5 flex-shrink-0"></i>
                El paciente es menor de edad — se requiere un representante o tutor legal.
            </p>
        </div>

        <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

            {{-- Cédula representante --}}
            <div>
                <label for="rep_cedula" class="block mb-1.5 text-sm font-medium text-gray-700">
                    Cédula del Representante <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i data-lucide="id-card" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    <input type="number" name="representante[cedula]" id="rep_cedula"
                        value="{{ old('representante.cedula', $paciente?->representante?->cedula) }}"
                        placeholder="12345678" oninput="buscarRepresentante(this.value)"
                        class="pl-9 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>
                <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                    <i data-lucide="info" class="w-3 h-3"></i>
                    Si ya está registrado, sus datos se cargan automáticamente.
                </p>
            </div>

            {{-- Relación --}}
            <div>
                <label for="rep_relacion" class="block mb-1.5 text-sm font-medium text-gray-700">
                    Parentesco / Relación
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i data-lucide="heart-handshake" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    <select name="representante[relacion]" id="rep_relacion"
                        class="pl-9 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">Seleccionar...</option>
                        @foreach (['Madre', 'Padre', 'Tutor Legal', 'Abuelo/a', 'Hermano/a', 'Tío/a', 'Otro'] as $rel)
                            <option value="{{ $rel }}"
                                {{ old('representante.relacion', $paciente?->representante?->relacion) === $rel ? 'selected' : '' }}>
                                {{ $rel }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Teléfono representante --}}
            <div class="sm:col-span-2">
                <label for="rep_telefono" class="block mb-1.5 text-sm font-medium text-gray-700">
                    Teléfono de contacto
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i data-lucide="phone" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    <input type="text" name="representante[telefono]" id="rep_telefono"
                        value="{{ old('representante.telefono', $paciente?->representante?->telefono) }}"
                        placeholder="0412-1234567"
                        class="pl-9 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Footer --}}
<div class="flex items-center justify-end gap-3 px-5 py-4 border-t border-gray-100 bg-gray-50 rounded-b-lg">
    <a href="{{ route('pacientes.index') }}"
        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
        Cancelar
    </a>
    <button type="submit"
        class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
        <i data-lucide="save" class="w-4 h-4"></i>
        {{ isset($paciente) && $paciente->id ? 'Actualizar' : 'Registrar' }} Paciente
    </button>
</div>

@push('scripts')
    <script>
        lucide.createIcons();

        // ---- TOGGLE REPRESENTANTE ----
        function toggleRepresentante() {
            const panel = document.getElementById('repPanel');
            const chevron = document.getElementById('repChevron');
            const hidden = document.getElementById('mostrar_representante');
            const visible = !panel.classList.contains('hidden');

            panel.classList.toggle('hidden', visible);
            chevron.style.transform = visible ? '' : 'rotate(180deg)';
            hidden.value = visible ? '0' : '1';
            lucide.createIcons();
        }

        // ---- CALCULAR EDAD y mostrar alerta menor ----
        function calcularEdad(fechaStr) {
            if (!fechaStr) return;
            const hoy = new Date();
            const nac = new Date(fechaStr);
            let edad = hoy.getFullYear() - nac.getFullYear();
            const m = hoy.getMonth() - nac.getMonth();
            if (m < 0 || (m === 0 && hoy.getDate() < nac.getDate())) edad--;

            const indicador = document.getElementById('edadIndicador');
            const alerta = document.getElementById('menorAlert');
            const panel = document.getElementById('repPanel');
            const chevron = document.getElementById('repChevron');
            const hidden = document.getElementById('mostrar_representante');

            indicador.classList.remove('hidden');

            if (edad < 18) {
                indicador.innerHTML =
                    `<span class="flex items-center gap-1 text-amber-600 font-medium"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg> ${edad} años — Menor de edad</span>`;
                alerta?.classList.remove('hidden');
                // Abrir panel representante automáticamente
                if (panel.classList.contains('hidden')) {
                    panel.classList.remove('hidden');
                    chevron.style.transform = 'rotate(180deg)';
                    hidden.value = '1';
                }
            } else {
                indicador.innerHTML = `<span class="text-green-600">${edad} años</span>`;
                alerta?.classList.add('hidden');
            }
            lucide.createIcons();
        }

        // ---- BUSCAR REPRESENTANTE por cédula (AJAX) ----
        let repTimer = null;

        function buscarRepresentante(cedula) {
            clearTimeout(repTimer);
            if (cedula.length < 5) return;
            repTimer = setTimeout(() => {
                fetch(`/representantes/buscar?cedula=${cedula}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.found) {
                            document.getElementById('rep_telefono').value = data.telefono || '';
                            if (data.relacion) {
                                document.getElementById('rep_relacion').value = data.relacion;
                            }
                        }
                    })
                    .catch(() => {});
            }, 500);
        }

        // ---- RADIO SEXO visual ----
        function actualizarSexo() {
            document.querySelectorAll('input[name="sexo"]').forEach(r => {
                const label = r.closest('label');
                if (r.checked) {
                    label.classList.remove('border-gray-200', 'text-gray-600');
                    label.classList.add(r.value === 'M' ? 'border-blue-300' : 'border-pink-300',
                        r.value === 'M' ? 'bg-blue-50' : 'bg-pink-50',
                        r.value === 'M' ? 'text-blue-700' : 'text-pink-700');
                } else {
                    label.classList.remove('border-blue-300', 'bg-blue-50', 'text-blue-700',
                        'border-pink-300', 'bg-pink-50', 'text-pink-700');
                    label.classList.add('border-gray-200', 'text-gray-600');
                }
            });
        }

        document.querySelectorAll('input[name="sexo"]').forEach(r =>
            r.addEventListener('change', actualizarSexo)
        );

        // ---- SEARCHABLE SECTOR ----
        initSearchable('sector_search', 'sector_id_hidden', 'sector_dropdown');

        // ---- SEARCHABLE ETNIA ----
        initSearchable('etnia_search', 'etnia_id_hidden', 'etnia_dropdown');

        function initSearchable(inputId, hiddenId, dropdownId) {
            const input = document.getElementById(inputId);
            const hidden = document.getElementById(hiddenId);
            const dropdown = document.getElementById(dropdownId);
            if (!input) return;

            input.addEventListener('input', function() {
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
                item.addEventListener('mousedown', function(e) {
                    e.preventDefault();
                    input.value = this.dataset.name;
                    hidden.value = this.dataset.id;
                    dropdown.classList.add('hidden');
                });
            });
            document.addEventListener('click', e => {
                if (!input.contains(e.target) && !dropdown.contains(e.target))
                    dropdown.classList.add('hidden');
            });
        }

        // Calcular edad si ya hay fecha (en edit)
        const fechaInit = document.getElementById('fecha_nacimiento').value;
        if (fechaInit) calcularEdad(fechaInit);
    </script>
@endpush
