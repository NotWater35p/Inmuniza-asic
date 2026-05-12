<div class="grid gap-5 sm:grid-cols-2 sm:gap-6 p-5">

    {{-- ASIC --}}
    <div>
        <label for="asic_id" class="block mb-1.5 text-sm font-medium text-gray-700">
            <span class="flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                ASIC <span class="text-red-500">*</span>
            </span>
        </label>
        <select name="asic_id" id="asic_id" required
            class="bg-gray-50 border {{ $errors->has('asic_id') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            @foreach($asics as $asic)
                <option value="{{ $asic->id }}" @selected(old('asic_id', $modulo?->asic_id) == $asic->id)>
                    {{ $asic->nombre }}
                </option>
            @endforeach
        </select>
        @error('asic_id')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    {{-- RIF --}}
    <div>
        <label for="rif" class="block mb-1.5 text-sm font-medium text-gray-700">
            <span class="flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                RIF <span class="text-red-500">*</span>
            </span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="9" y2="9"/><line x1="4" x2="20" y1="15" y2="15"/><line x1="10" x2="8" y1="3" y2="21"/><line x1="16" x2="14" y1="3" y2="21"/></svg>
            </div>
            <input type="text" name="rif" id="rif"
                value="{{ old('rif', $modulo?->rif) }}"
                placeholder="J-12345678-9"
                maxlength="12"
                required
                autocomplete="off"
                spellcheck="false"
                class="uppercase pl-9 bg-gray-50 border {{ $errors->has('rif') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        </div>
        <p class="mt-1 text-xs text-gray-400">Formato: J-12345678-9</p>
        @error('rif')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    {{-- Nombre --}}
    <div class="sm:col-span-2">
        <label for="nombre" class="block mb-1.5 text-sm font-medium text-gray-700">
            <span class="flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 7v4"/><path d="M14 21v-3a2 2 0 0 0-4 0v3"/><path d="M14 9h-4"/><path d="M18 11h2a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-9a2 2 0 0 1 2-2h2"/><path d="M18 21V5a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16"/></svg>
                Nombre del Módulo <span class="text-red-500">*</span>
            </span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
            </div>
            <input type="text" name="nombre" id="nombre"
                value="{{ old('nombre', $modulo?->nombre) }}"
                required
                maxlength="150"
                placeholder="Nombre del módulo afiliado"
                class="pl-9 bg-gray-50 border {{ $errors->has('nombre') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        </div>
        @error('nombre')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    {{-- Tipo de establecimiento --}}
    <div>
        <label for="tipo_establecimiento" class="block mb-1.5 text-sm font-medium text-gray-700">
            <span class="flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M12 6h.01"/><path d="M12 10h.01"/><path d="M12 14h.01"/><path d="M16 10h.01"/><path d="M16 14h.01"/><path d="M8 10h.01"/><path d="M8 14h.01"/></svg>
                Tipo de Establecimiento <span class="text-red-500">*</span>
            </span>
        </label>
        <select name="tipo_establecimiento" id="tipo_establecimiento" required
            class="bg-gray-50 border {{ $errors->has('tipo_establecimiento') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            @foreach($tipos as $tipo)
                <option value="{{ $tipo }}" @selected(old('tipo_establecimiento', $modulo?->tipo_establecimiento) == $tipo)>
                    {{ $tipo }}
                </option>
            @endforeach
        </select>
        @error('tipo_establecimiento')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    {{-- Fila SISPAI --}}
    <div>
        <label for="sispai_fila" class="block mb-1.5 text-sm font-medium text-gray-700">
            <span class="flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3h18v18H3z"/><path d="M3 9h18"/><path d="M3 15h18"/><path d="M9 3v18"/></svg>
                Fila SISPAI
            </span>
        </label>
        <input type="number" name="sispai_fila" id="sispai_fila"
            value="{{ old('sispai_fila', $modulo?->sispai_fila) }}"
            min="1"
            max="999"
            step="1"
            placeholder="Ej: 35"
            class="bg-gray-50 border {{ $errors->has('sispai_fila') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        <p class="mt-1 text-xs text-gray-400">Número de fila en la planilla SISPAI (opcional)</p>
        @error('sispai_fila')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    {{-- Municipio --}}
    <div>
        <label for="municipio" class="block mb-1.5 text-sm font-medium text-gray-700">
            <span class="flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                Municipio
            </span>
        </label>
        <input type="text" name="municipio" id="municipio"
            value="{{ old('municipio', $modulo?->municipio) }}"
            maxlength="100"
            placeholder="Ej: Rosario de Perijá"
            class="bg-gray-50 border {{ $errors->has('municipio') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        @error('municipio')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    {{-- Parroquia --}}
    <div>
        <label for="parroquia" class="block mb-1.5 text-sm font-medium text-gray-700">
            <span class="flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                Parroquia
            </span>
        </label>
        <input type="text" name="parroquia" id="parroquia"
            value="{{ old('parroquia', $modulo?->parroquia) }}"
            maxlength="100"
            placeholder="Ej: El Rosario"
            class="bg-gray-50 border {{ $errors->has('parroquia') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        @error('parroquia')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    {{-- Teléfono --}}
    <div>
        <label for="telefono" class="block mb-1.5 text-sm font-medium text-gray-700">
            <span class="flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.38 2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.72a16 16 0 0 0 6.29 6.29l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                Teléfono
            </span>
        </label>
        <input type="tel" name="telefono" id="telefono"
            value="{{ old('telefono', $modulo?->telefono) }}"
            maxlength="20"
            placeholder="0414-1234567"
            class="bg-gray-50 border {{ $errors->has('telefono') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        @error('telefono')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    {{-- Dirección --}}
    <div class="sm:col-span-2">
        <label for="direccion" class="block mb-1.5 text-sm font-medium text-gray-700">
            <span class="flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                Dirección
            </span>
        </label>
        <textarea name="direccion" id="direccion" rows="2"
            maxlength="255"
            placeholder="Av. Principal, Calle..."
            class="bg-gray-50 border {{ $errors->has('direccion') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('direccion', $modulo?->direccion) }}</textarea>
        @error('direccion')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    {{-- Jefe de Módulo --}}
    <div class="sm:col-span-2">
        <label for="jefe_cedula" class="block mb-1.5 text-sm font-medium text-gray-700">
            <span class="flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/></svg>
                Jefe de Módulo
            </span>
        </label>
        <select name="jefe_cedula" id="jefe_cedula"
            class="bg-gray-50 border {{ $errors->has('jefe_cedula') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            <option value="">— Sin asignar —</option>
            @foreach($jefes as $jefe)
                <option value="{{ $jefe->cedula }}" @selected(old('jefe_cedula', $modulo?->jefe_cedula) == $jefe->cedula)>
                    {{ $jefe->apellido }}, {{ $jefe->nombre }} · CI {{ number_format($jefe->cedula, 0, ',', '.') }}
                </option>
            @endforeach
        </select>
        @error('jefe_cedula')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
        @if($jefes->isEmpty())
            <p class="mt-1.5 text-xs text-amber-600 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                No hay personal con cargo de Jefe de Módulo registrado.
            </p>
        @endif
    </div>

</div>

{{-- Botones --}}
<div class="flex items-center justify-end gap-3 px-5 py-4 border-t border-gray-100 bg-gray-50 rounded-b-lg">
    <a href="{{ route('modulos.index') }}"
        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
        Cancelar
    </a>
    <button type="submit"
        class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
        {{ isset($modulo) && $modulo->id ? 'Actualizar' : 'Guardar' }} Módulo
    </button>
</div>

@push('scripts')
<script>
    // RIF: solo permite J/G + formato automático
    document.getElementById('rif')?.addEventListener('input', function () {
        let v = this.value.toUpperCase().replace(/[^JG0-9\-]/g, '');
        this.value = v;
    });

    // sispai_fila: solo enteros positivos
    document.getElementById('sispai_fila')?.addEventListener('input', function () {
        if (this.value < 1) this.value = '';
        this.value = Math.floor(this.value);
    });

    // municipio / parroquia: solo letras, espacios y tildes
    ['municipio', 'parroquia'].forEach(id => {
        document.getElementById(id)?.addEventListener('input', function () {
            this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g, '');
        });
    });

    // telefono: solo dígitos, espacios y + - ( )
    document.getElementById('telefono')?.addEventListener('input', function () {
        this.value = this.value.replace(/[^\d\s\+\-\(\)]/g, '');
    });
</script>
@endpush