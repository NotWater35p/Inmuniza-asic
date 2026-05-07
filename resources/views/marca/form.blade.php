<div class="grid gap-5 sm:grid-cols-2 sm:gap-6">
    <div class="sm:col-span-2">
        <label for="nombre" class="block mb-2 text-sm font-medium text-gray-700">Nombre de la Marca</label>
        <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $marca?->nombre) }}"
               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('nombre') border-red-500 @enderror"
               placeholder="" required>
        @error('nombre')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div class="sm:col-span-2">
        <label for="descripcion" class="block mb-2 text-sm font-medium text-gray-700">Descripción (opcional)</label>
        <textarea name="descripcion" id="descripcion" rows="3"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('descripcion', $marca?->descripcion) }}</textarea>
        @error('descripcion')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
</div>
<div class="mt-6 flex justify-between">
    <a href="{{ route('marcas.index') }}"
        class="inline-flex items-center gap-2 px-6 py-3 bg-gray-200 hover:bg-red-700 hover:text-neutral-primary-strong text-gray-700 text-sm font-medium rounded-lg transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-x">
            <path d="M18 6 6 18" />
            <path d="m6 6 12 12" />
        </svg>
        Cancelar
    </a>

    <button type="submit"
            class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
        {{ isset($marca) && $marca->id ? 'Actualizar' : 'Guardar' }} Marca
    </button>
</div>