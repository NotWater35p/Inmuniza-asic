<div class="grid gap-5 sm:grid-cols-2 sm:gap-6">
    <div class="sm:col-span-2">
        <label for="nombre" class="block mb-1.5 text-sm font-medium text-gray-700">
            Nombre del Cargo <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                <i data-lucide="briefcase" class="w-4 h-4"></i>
            </div>
            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $cargo?->nombre) }}"
                class="pl-9 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('nombre') border-red-500 @enderror"
                placeholder="Ej: Administrador" required>
        </div>
        @error('nombre')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="nivel_acceso" class="block mb-1.5 text-sm font-medium text-gray-700">
            Nivel de Acceso <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                <i data-lucide="lock" class="w-4 h-4"></i>
            </div>
            <select name="nivel_acceso" id="nivel_acceso" required
                class="pl-9 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('nivel_acceso') border-red-500 @enderror">
                <option value="">Seleccione un nivel</option>
                <option value="5" @selected(old('nivel_acceso', $cargo?->nivel_acceso) == 5)>5 - Administrador</option>
                <option value="3" @selected(old('nivel_acceso', $cargo?->nivel_acceso) == 3)>3 - Asistente Administrativo</option>
                <option value="2" @selected(old('nivel_acceso', $cargo?->nivel_acceso) == 2)>2 - Jefe de Módulo</option>
                <option value="1" @selected(old('nivel_acceso', $cargo?->nivel_acceso) == 1)>1 - Vacunador</option>
            </select>
        </div>
        @error('nivel_acceso')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
</div>

<div class="mt-6 flex justify-end">
    <button type="submit"
        class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors focus:ring-4 focus:ring-blue-300">
        <i data-lucide="save" class="w-4 h-4"></i>
        {{ isset($cargo) && $cargo->id ? 'Actualizar' : 'Guardar' }} Cargo
    </button>
</div>