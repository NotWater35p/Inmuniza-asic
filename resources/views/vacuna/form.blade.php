{{-- Sección 1: Identificación --}}
<div class="px-5 pt-5">
    <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2">
        <i data-lucide="tag" class="w-3.5 h-3.5"></i> Identificación
    </p>
    <div class="grid gap-4 sm:grid-cols-2">

        {{-- Nombre --}}
        <div class="sm:col-span-2">
            <label for="nombre" class="block mb-1.5 text-sm font-medium text-gray-700">
                Nombre de la Vacuna <span class="text-red-500">*</span>
                @if(isset($vacuna) && $vacuna->id)
                <span
                    class="ml-2 text-xs font-normal text-amber-600 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-full">
                    El nombre no puede modificarse
                </span>
                @endif
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                        <rect width="8" height="4" x="8" y="2" rx="1" ry="1" />
                    </svg>
                </div>
                @if(isset($vacuna) && $vacuna->id)
                {{-- En edición: solo lectura, no se envía al servidor --}}
                <input type="text" value="{{ $vacuna->nombre }}" disabled
                    class="pl-9 bg-gray-100 border border-gray-200 text-gray-500 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed">
                {{-- Campo hidden para que pase la validación si el request lo requiere --}}
                <input type="hidden" name="nombre" value="{{ $vacuna->nombre }}">
                @else
                <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $vacuna?->nombre) }}"
                    maxlength="150" required
                    class="pl-9 bg-gray-50 border {{ $errors->has('nombre') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                @endif
            </div>
            @error('nombre')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        {{-- Marca --}}
        <div>
            <label class="block mb-1.5 text-sm font-medium text-gray-700">
                Marca / Fabricante <span class="text-red-500">*</span>
            </label>
            <div class="flex gap-2">
                <select name="marca_id" id="marca_id" required
                    class="bg-gray-50 border {{ $errors->has('marca_id') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Seleccione...</option>
                    @foreach($marcas as $marca)
                    <option value="{{ $marca->id }}" @selected(old('marca_id', $vacuna?->marca_id) == $marca->id)>
                        {{ $marca->nombre }}
                    </option>
                    @endforeach
                </select>
                <button type="button" onclick="abrirModalMarca()"
                    class="flex items-center gap-1.5 px-3 py-2 bg-gray-50 hover:bg-gray-700 hover:text-white text-gray-700 border border-gray-300 rounded-lg transition-colors text-xs font-medium shrink-0"
                    title="Nueva marca">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                </button>
            </div>
            @error('marca_id')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        {{-- Tipo --}}
        <div>
            <label class="block mb-1.5 text-sm font-medium text-gray-700">
                Tipo de producto <span class="text-red-500">*</span>
            </label>
            <div class="grid grid-cols-3 gap-2">
                @php $tipoVal = old('tipo', $vacuna?->tipo ?? 'vacuna'); @endphp
                {{-- Vacuna --}}
                <label class="relative cursor-pointer">
                    <input type="radio" name="tipo" value="vacuna" class="peer sr-only" {{ $tipoVal==='vacuna'
                        ? 'checked' : '' }} required>
                    <div class="flex flex-col items-center gap-1 p-2.5 rounded-lg border-2 transition-all
                        text-gray-500 border-gray-200 bg-gray-50 hover:border-blue-300
                        peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700">
                        <i data-lucide="syringe" class="w-4 h-4"></i>
                        <span class="text-xs font-semibold">Vacuna</span>
                    </div>
                </label>

                {{-- Suero --}}
                <label class="relative cursor-pointer">
                    <input type="radio" name="tipo" value="suero" class="peer sr-only" {{ $tipoVal==='suero' ? 'checked'
                        : '' }}>
                    <div class="flex flex-col items-center gap-1 p-2.5 rounded-lg border-2 transition-all
                        text-gray-500 border-gray-200 bg-gray-50 hover:border-amber-300
                        peer-checked:border-amber-500 peer-checked:bg-amber-50 peer-checked:text-amber-700">
                        <i data-lucide="test-tube-2" class="w-4 h-4"></i>
                        <span class="text-xs font-semibold">Suero</span>
                    </div>
                </label>

                {{-- Insumo --}}
                <label class="relative cursor-pointer">
                    <input type="radio" name="tipo" value="insumo" class="peer sr-only" {{ $tipoVal==='insumo'
                        ? 'checked' : '' }}>
                    <div class="flex flex-col items-center gap-1 p-2.5 rounded-lg border-2 transition-all
                        text-gray-500 border-gray-200 bg-gray-50 hover:border-emerald-300
                        peer-checked:border-gray-500 peer-checked:bg-gray-100 peer-checked:text-gray-700">
                        <i data-lucide="package" class="w-4 h-4"></i>
                        <span class="text-xs font-semibold">Insumo</span>
                    </div>
                </label>
            </div>
            @error('tipo')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        {{-- Presentación --}}
        <div>
            <label for="presentacion" class="block mb-1.5 text-sm font-medium text-gray-700">Presentación</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i data-lucide="package" class="w-4 h-4 text-gray-400"></i>
                </div>
                <input type="text" name="presentacion" id="presentacion"
                    value="{{ old('presentacion', $vacuna?->presentacion) }}" maxlength="50"
                    class="pl-9 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
        </div>

        {{-- Enfermedad --}}
        <div>
            <label for="enfermedad" class="block mb-1.5 text-sm font-medium text-gray-700">Enfermedad que
                previene</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i data-lucide="biohazard" class="w-4 h-4 text-gray-400"></i>
                </div>
                <input type="text" name="enfermedad" id="enfermedad"
                    value="{{ old('enfermedad', $vacuna?->enfermedad) }}" maxlength="50"
                    class="pl-9 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
        </div>

    </div>
</div>

<div class="my-4 border-t border-dashed border-gray-200"></div>

{{-- Sección 2: Administración --}}
<div class="px-5">
    <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2">
        <i data-lucide="stethoscope" class="w-3.5 h-3.5"></i> Administración
    </p>
    <div class="grid gap-4 sm:grid-cols-2">

        {{-- Dosificación --}}
        <div>
            <label for="dosificacion" class="block mb-1.5 text-sm font-medium text-gray-700">Dosificación</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i data-lucide="pill-bottle" class="w-4 h-4 text-gray-400"></i>
                </div>
                <input type="text" name="dosificacion" id="dosificacion"
                    value="{{ old('dosificacion', $vacuna?->dosificacion) }}" maxlength="30"
                    class="pl-9 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
        </div>

        {{-- Vía de administración --}}
        <div>
            <label for="via_administracion" class="block mb-1.5 text-sm font-medium text-gray-700">
                Vía de administración
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i data-lucide="syringe" class="w-4 h-4 text-gray-400"></i>
                </div>
                <select name="via_administracion" id="via_administracion"
                    class="pl-9 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Seleccione...</option>
                    @foreach(['Intramuscular','Subcutánea','Intradérmica','Oral','Intranasal'] as $via)
                    <option value="{{ $via }}" @selected(old('via_administracion', $vacuna?->via_administracion) ===
                        $via)>
                        {{ $via }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Número de dosis --}}
        <div>
            <label for="numero_dosis" class="block mb-1.5 text-sm font-medium text-gray-700">Número de dosis</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i data-lucide="hash" class="w-4 h-4 text-gray-400"></i>
                </div>
                <input type="number" name="numero_dosis" id="numero_dosis" min="1"
                    value="{{ old('numero_dosis', $vacuna?->numero_dosis ?? 1) }}"
                    class="pl-9 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
        </div>

        {{-- Intervalo --}}
        <div>
            <label for="intervalo" class="block mb-1.5 text-sm font-medium text-gray-700">Intervalo entre dosis</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i data-lucide="calendar-sync" class="w-4 h-4 text-gray-400"></i>
                </div>
                <input type="text" name="intervalo" id="intervalo" value="{{ old('intervalo', $vacuna?->intervalo) }}"
                    maxlength="30"
                    class="pl-9 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
        </div>

        {{-- Refuerzo --}}
        <div class="sm:col-span-2">
            <label for="refuerzo" class="block mb-1.5 text-sm font-medium text-gray-700">Refuerzo</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i data-lucide="refresh-cw" class="w-4 h-4 text-gray-400"></i>
                </div>
                <input type="text" name="refuerzo" id="refuerzo" value="{{ old('refuerzo', $vacuna?->refuerzo) }}"
                    maxlength="25"
                    class="pl-9 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
        </div>

    </div>
</div>

<div class="my-4 border-t border-dashed border-gray-200"></div>

{{-- Sección 3: Notas --}}
<div class="px-5">
    <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2">
        <i data-lucide="notebook-tabs" class="w-3.5 h-3.5"></i> Notas
    </p>
    <div>
        <label for="descripcion" class="block mb-1.5 text-sm font-medium text-gray-700">Descripción</label>
        <textarea name="descripcion" id="descripcion" rows="3" maxlength="75"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('descripcion', $vacuna?->descripcion) }}</textarea>
    </div>
</div>

{{-- Footer --}}
<div class="flex items-center justify-end gap-3 px-5 py-4 mt-4 border-t border-gray-100 bg-gray-50 rounded-b-lg">
    <a href="{{ route('vacunas.index') }}"
        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
        Cancelar
    </a>
    <button type="submit"
        class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
        <i data-lucide="save" class="w-4 h-4"></i>
        {{ isset($vacuna) && $vacuna->id ? 'Actualizar' : 'Guardar' }} Vacuna
    </button>
</div>

@push('scripts')
<script>
    lucide.createIcons();
</script>
@endpush