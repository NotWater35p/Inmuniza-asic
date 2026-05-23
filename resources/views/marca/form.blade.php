<div class="grid gap-5 sm:grid-cols-2 sm:gap-6 p-5">

    {{-- Nombre --}}
    <div class="sm:col-span-2">
        <label for="nombre" class="block mb-1.5 text-sm font-medium text-gray-700">
            Nombre del Fabricante <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18h8"/><path d="M3 22h18"/><path d="M14 22a7 7 0 1 0 0-14h-1"/><path d="M9 14h2"/><path d="M9 12a2 2 0 0 1-2-2V6h6v4a2 2 0 0 1-2 2Z"/><path d="M12 6V3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3"/></svg>
            </div>
            <input type="text" name="nombre" id="nombre"
                value="{{ old('nombre', $marca?->nombre) }}"
                placeholder="Ej: Sanofi Pasteur"
                required maxlength="100"
                class="pl-9 bg-gray-50 border {{ $errors->has('nombre') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        </div>
        @error('nombre')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    {{-- Descripción --}}
    <div class="sm:col-span-2">
        <label for="descripcion" class="block mb-1.5 text-sm font-medium text-gray-700">
            Descripción
            <span class="text-gray-400 font-normal">(opcional)</span>
        </label>
        <textarea name="descripcion" id="descripcion" rows="3"
            maxlength="500"
            placeholder="País de origen, especialidad, notas sobre el fabricante..."
            class="bg-gray-50 border {{ $errors->has('descripcion') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('descripcion', $marca?->descripcion) }}</textarea>
        @error('descripcion')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

</div>

{{-- Footer --}}
<div class="flex items-center justify-end gap-3 px-5 py-4 border-t border-gray-100 bg-gray-50 rounded-b-xl">
    <a href="{{ route('marcas.index') }}"
        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
        Cancelar
    </a>
    <button type="submit"
        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
        {{ isset($marca) && $marca->id ? 'Actualizar' : 'Guardar' }} Marca
    </button>
</div>