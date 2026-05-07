<div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">

    {{-- Fecha --}}
    <div class="sm:col-span-2">
        <label for="fecha_jornada" class="block mb-1.5 text-sm font-medium text-gray-700">
            Fecha de la Jornada <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
            </div>
            <input type="date" name="fecha_jornada" id="fecha_jornada"
                value="{{ old('fecha_jornada', $jornada?->fecha_jornada?->format('Y-m-d') ?? date('Y-m-d')) }}"
                class="pl-9 bg-gray-50 border {{ $errors->has('fecha_jornada') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2.5">
        </div>
        @error('fecha_jornada')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

@if(auth()->user()->esAdmin())
<div>
    <label class="block mb-1.5 text-sm font-medium text-gray-700">
        Módulo <span class="text-gray-400 text-xs font-normal">(opcional)</span>
    </label>
    <select name="modulo_id"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        <option value="">— Sin módulo (jornada del ASIC) —</option>
        @foreach($modulos as $mod)
        <option value="{{ $mod->id }}"
            @selected(old('modulo_id', $jornada?->modulo_id) == $mod->id)>
            {{ $mod->nombre }}
        </option>
        @endforeach
    </select>
</div>
@else
{{-- El jefe de módulo no necesita seleccionar — se asigna automáticamente --}}
<input type="hidden" name="modulo_id" value="">
@endif

    {{-- Responsable --}}
    <div class="sm:col-span-2">
        <label for="personal_responsable" class="block mb-1.5 text-sm font-medium text-gray-700">
            Personal Responsable <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i data-lucide="user-check" class="w-4 h-4 text-gray-400"></i>
            </div>
            <select name="personal_responsable" id="personal_responsable"
                class="pl-9 bg-gray-50 border {{ $errors->has('personal_responsable') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2.5">
                <option value="">Seleccionar responsable...</option>
                @foreach($personal as $p)
                <option value="{{ $p->cedula }}"
                    @selected(old('personal_responsable', $jornada?->personal_responsable) == $p->cedula)>
                    {{ $p->nombre }} {{ $p->apellido }}
                    @if($p->cargo) — {{ $p->cargo->nombre }}@endif
                </option>
                @endforeach
            </select>
        </div>
        @error('personal_responsable')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    {{-- Descripción --}}
    <div class="sm:col-span-2">
        <label for="descripcion" class="block mb-1.5 text-sm font-medium text-gray-700">
            Descripción <span class="text-gray-400 font-normal">(opcional)</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i data-lucide="file-text" class="w-4 h-4 text-gray-400"></i>
            </div>
            <input type="text" name="descripcion" id="descripcion"
                value="{{ old('descripcion', $jornada?->descripcion) }}"
                placeholder="Ej: Jornada de vacunación infantil, Brigada comunitaria..."
                class="pl-9 bg-gray-50 border {{ $errors->has('descripcion') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2.5">
        </div>
        @error('descripcion')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    {{-- ASIC --}}
    <div class="sm:col-span-2">
        <div class="p-3 bg-emerald-50 border border-emerald-100 rounded-lg flex items-center gap-2.5 text-sm text-emerald-700">
            <i data-lucide="building-2" class="w-4 h-4 flex-shrink-0"></i>
            <span>Jornada registrada al: <strong>{{ $asic->nombre }}</strong></span>
        </div>
    </div>
</div>
{{-- ASIC siempre se envía aunque sea vacío, el controlador lo completa --}}
@if(!auth()->user()->esAdmin())
<input type="hidden" name="asic_id" value="">
@endif

<div class="flex items-center justify-end gap-3 px-5 py-4 border-t border-gray-100 bg-gray-50 rounded-b-lg">
    <a href="{{ route('jornadas.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
        Cancelar
    </a>
    <button type="submit" class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-emerald-700 rounded-lg hover:bg-emerald-800 focus:ring-4 focus:ring-emerald-300">
        <i data-lucide="save" class="w-4 h-4"></i>
        {{ isset($jornada) && $jornada->id ? 'Actualizar' : 'Registrar' }} Jornada
    </button>
</div>