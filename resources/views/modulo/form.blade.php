<div class="grid gap-5 sm:grid-cols-2 sm:gap-6 p-5">

    <div>
        <label for="asic_id" class="block mb-1.5 text-sm font-medium text-gray-700">
            <span class="flex items-center gap-1.5">
                <i data-lucide="house-heart" class="w-3.5 h-3.5 text-gray-400"></i>
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

    <div>
        <label for="rif" class="block mb-1.5 text-sm font-medium text-gray-700">
            <span class="flex items-center gap-1.5">
                <i data-lucide="id-card" class="w-3.5 h-3.5 text-gray-400"></i>
                RIF <span class="text-red-500">*</span>
            </span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i data-lucide="hash" class="w-4 h-4 text-gray-400"></i>
            </div>
            <input type="text" name="rif" id="rif"
                value="{{ old('rif', $modulo?->rif) }}"
                placeholder="J-12345678-9"
                maxlength="12"
                required
                pattern="[JGjg]\-\d{8}\-\d"
                title="Formato: J-12345678-9 (una letra, 8 dígitos, un dígito)"
                class="uppercase pl-9 bg-gray-50 border {{ $errors->has('rif') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        </div>
        @error('rif')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    {{-- Nombre (obligatorio, ocupa todo el ancho) --}}
    <div class="sm:col-span-2">
        <label for="nombre" class="block mb-1.5 text-sm font-medium text-gray-700">
            <span class="flex items-center gap-1.5">
                <i data-lucide="hospital" class="w-3.5 h-3.5 text-gray-400"></i>
                Nombre del Módulo <span class="text-red-500">*</span>
            </span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i data-lucide="pencil-line" class="w-4 h-4 text-gray-400"></i>
            </div>
            <input type="text" name="nombre" id="nombre"
                value="{{ old('nombre', $modulo?->nombre) }}"
                required
                maxlength="30"
                placeholder="..."
                class="pl-9 bg-gray-50 border {{ $errors->has('nombre') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        </div>
        @error('nombre')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="tipo_establecimiento" class="block mb-1.5 text-sm font-medium text-gray-700">
            <span class="flex items-center gap-1.5">
                <i data-lucide="building-2" class="w-3.5 h-3.5 text-gray-400"></i>
                Tipo <span class="text-red-500">*</span>
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

    <div>
        <label for="municipio" class="block mb-1.5 text-sm font-medium text-gray-700">
            <span class="flex items-center gap-1.5">
                <i data-lucide="map" class="w-3.5 h-3.5 text-gray-400"></i>
                Municipio
            </span>
        </label>
        <input type="text" name="municipio" id="municipio"
            value="{{ old('municipio', $modulo?->municipio) }}"
            maxlength="100"
            pattern="[A-Za-zÀ-ÿ\s]+"
            title="Solo letras y espacios"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        @error('municipio')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="parroquia" class="block mb-1.5 text-sm font-medium text-gray-700">
            <span class="flex items-center gap-1.5">
                <i data-lucide="map-pinned" class="w-3.5 h-3.5 text-gray-400"></i>
                Parroquia
            </span>
        </label>
        <input type="text" name="parroquia" id="parroquia"
            value="{{ old('parroquia', $modulo?->parroquia) }}"
            maxlength="100"
            pattern="[A-Za-zÀ-ÿ\s]+"
            title="Solo letras y espacios"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        @error('parroquia')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="telefono" class="block mb-1.5 text-sm font-medium text-gray-700">
            <span class="flex items-center gap-1.5">
                <i data-lucide="phone" class="w-3.5 h-3.5 text-gray-400"></i>
                Teléfono
            </span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i data-lucide="phone" class="w-4 h-4 text-gray-400"></i>
            </div>
            <input type="tel" name="telefono" id="telefono"
                value="{{ old('telefono', $modulo?->telefono) }}"
                maxlength="12"
                pattern="[\d\s\+\-\(\)]+"
                title="Solo números, espacios y + - ( )"
                placeholder="0212-1234567"
                class="pl-9 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        </div>
        @error('telefono')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="sm:col-span-2">
        <label for="direccion" class="block mb-1.5 text-sm font-medium text-gray-700">
            <span class="flex items-center gap-1.5">
                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-gray-400"></i>
                Dirección
            </span>
        </label>
        <textarea name="direccion" id="direccion" rows="2"
            maxlength="60"
            placeholder="Av. Principal, Calle..."
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('direccion', $modulo?->direccion) }}</textarea>
        @error('direccion')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="sm:col-span-2">
        <label for="jefe_cedula" class="block mb-1.5 text-sm font-medium text-gray-700">
            <span class="flex items-center gap-1.5">
                <i data-lucide="user-check" class="w-3.5 h-3.5 text-gray-400"></i>
                Jefe de Módulo
            </span>
        </label>
        <select name="jefe_cedula" id="jefe_cedula"
            class="bg-gray-50 border {{ $errors->has('jefe_cedula') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            <option value="">— Sin asignar —</option>
            @foreach($jefes as $jefe)
                <option value="{{ $jefe->cedula }}" @selected(old('jefe_cedula', $modulo?->jefe_cedula) == $jefe->cedula)>
                    {{ $jefe->apellido }}, {{ $jefe->nombre }} · CI {{ $jefe->cedula }}
                </option>
            @endforeach
        </select>
        @error('jefe_cedula')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
        @if($jefes->isEmpty())
            <p class="mt-1.5 text-xs text-amber-600 flex items-center gap-1">
                <i data-lucide="alert-triangle" class="w-3 h-3"></i>
                No hay personal con cargo de Jefe de Módulo registrado.
            </p>
        @endif
    </div>

</div>

<div class="flex items-center justify-end gap-3 px-5 py-4 border-t border-gray-100 bg-gray-50 rounded-b-lg">
    <a href="{{ route('modulos.index') }}"
        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
        Cancelar
    </a>
    <button type="submit"
        class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
        <i data-lucide="save" class="w-4 h-4"></i>
        {{ isset($modulo) && $modulo->id ? 'Actualizar' : 'Guardar' }} Módulo
    </button>
</div>

@push('scripts')
<script>
    lucide.createIcons();
    document.getElementById('rif')?.addEventListener('input', function(e) {
        this.value = this.value.toUpperCase();
    });
</script>
@endpush