<input type="hidden" name="asic_id" value="{{ $asic->id }}">

<div class="grid gap-5 sm:grid-cols-2 sm:gap-6 p-5">

    {{-- Cédula --}}
    <div class="sm:col-span-2">
        <label for="cedula" class="block mb-1.5 text-sm font-medium text-gray-700">
            Cédula de Identidad <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i data-lucide="id-card" class="w-4 h-4 text-gray-400"></i>
            </div>
            <span class="absolute inset-y-0 left-9 flex items-center text-gray-500 text-sm font-semibold pointer-events-none pr-1 border-r border-gray-300"></span>
            <input type="text" name="cedula" id="cedula"
                inputmode="numeric" pattern="\d+" maxlength="8"
                value="{{ old('cedula', $personal?->cedula) }}"
                placeholder=""
                title="Solo números, máximo 8 dígitos"
                class="pl-13 bg-gray-50 border {{ $errors->has('cedula') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                {{ isset($personal) && $personal->cedula ? 'readonly' : '' }}>
        </div>
        @error('cedula')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
        @if(isset($personal) && $personal->cedula)
        <p class="mt-1 text-xs text-gray-400 flex items-center gap-1">
            <i data-lucide="lock" class="w-3 h-3"></i>
            La cédula no puede modificarse una vez registrada.
        </p>
        @endif
    </div>

    {{-- Nombre --}}
    <div>
        <label for="nombre" class="block mb-1.5 text-sm font-medium text-gray-700">
            Nombres <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i data-lucide="user" class="w-4 h-4 text-gray-400"></i>
            </div>
            <input type="text" name="nombre" id="nombre"
                value="{{ old('nombre', $personal?->nombre) }}"
                placeholder=""
                required
                maxlength="60"
                pattern="[A-Za-zÀ-ÿ\s]+"
                title="Solo letras y espacios"
                class="pl-9 bg-gray-50 border {{ $errors->has('nombre') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                style="text-transform: capitalize;">
        </div>
        @error('nombre')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    {{-- Apellido --}}
    <div>
        <label for="apellido" class="block mb-1.5 text-sm font-medium text-gray-700">
            Apellidos <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i data-lucide="user" class="w-4 h-4 text-gray-400"></i>
            </div>
            <input type="text" name="apellido" id="apellido"
                value="{{ old('apellido', $personal?->apellido) }}"
                placeholder=""
                required
                maxlength="60"
                pattern="[A-Za-zÀ-ÿ\s]+"
                title="Solo letras y espacios"
                class="pl-9 bg-gray-50 border {{ $errors->has('apellido') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                style="text-transform: capitalize;">
        </div>
        @error('apellido')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    {{-- Cargo --}}
    <div>
        <label for="cargo_id" class="block mb-1.5 text-sm font-medium text-gray-700">
            Cargo <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i data-lucide="briefcase" class="w-4 h-4 text-gray-400"></i>
            </div>
            <select name="cargo_id" id="cargo_id" required
                class="pl-9 bg-gray-50 border {{ $errors->has('cargo_id') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                <option value="">Seleccione un cargo</option>
                @foreach($cargos as $cargo)
                <option value="{{ $cargo->id }}"
                    @selected(old('cargo_id', $personal?->cargo_id) == $cargo->id)>
                    {{ $cargo->nombre }}
                </option>
                @endforeach
            </select>
        </div>
        @error('cargo_id')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    {{-- Teléfono --}}
    <div>
        <label for="telefono" class="block mb-1.5 text-sm font-medium text-gray-700">
            Teléfono <span class="text-gray-400 font-normal">(opcional)</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i data-lucide="phone" class="w-4 h-4 text-gray-400"></i>
            </div>
            <input type="tel" name="telefono" id="telefono"
                value="{{ old('telefono', $personal?->telefono) }}"
                placeholder=""
                maxlength="12"
                pattern="[\d\s\+\-\(\)]+"
                title="Solo números, espacios y + - ( )"
                class="pl-9 bg-gray-50 border {{ $errors->has('telefono') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
        </div>
        @error('telefono')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    {{-- Correo --}}
    <div>
        <label for="correo" class="block mb-1.5 text-sm font-medium text-gray-700">
            Correo electrónico <span class="text-gray-400 font-normal">(opcional)</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i data-lucide="mail" class="w-4 h-4 text-gray-400"></i>
            </div>
            <input type="email" name="correo" id="correo"
                value="{{ old('correo', $personal?->correo) }}"
                placeholder="ejemplo@correo.com"
                maxlength="100"
                class="pl-9 bg-gray-50 border {{ $errors->has('correo') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
        </div>
        @error('correo')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    {{-- Info ASIC --}}
    <div class="sm:col-span-2">
        <div class="p-3 bg-blue-50 border border-blue-100 rounded-lg flex items-center gap-2.5 text-sm text-blue-700">
            <i data-lucide="building-2" class="w-4 h-4 shrink-0"></i>
            <span>Personal registrado al: <strong>{{ $asic->nombre }}</strong></span>
        </div>
    </div>
</div>

{{-- Footer del formulario --}}
<div class="flex items-center justify-end gap-3 px-5 py-4 border-t border-gray-100 bg-gray-50 rounded-b-lg">
    <a href="{{ route('personal.index') }}"
        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
        Cancelar
    </a>
    <button type="submit"
        class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium bg-brand-medium text-brand-strong rounded-lg hover:bg-brand-strong hover:text-white focus:ring-4 focus:ring-brand">
        <i data-lucide="save" class="w-4 h-4"></i>
        {{ isset($personal) && $personal->cedula ? 'Actualizar' : 'Registrar' }} Personal
    </button>
</div>

@push('scripts')
<script>
    function capitalizarPrimeraLetra(event) {
        let input = event.target;
        let valor = input.value;
        input.value = valor.replace(/\b\w/g, function(char) {
            return char.toUpperCase();
        });
    }

    document.getElementById('nombre').addEventListener('input', capitalizarPrimeraLetra);
    document.getElementById('apellido').addEventListener('input', capitalizarPrimeraLetra);

    lucide.createIcons();
</script>
@endpush