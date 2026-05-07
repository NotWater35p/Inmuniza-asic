@extends('layouts.app')
@section('title', 'Editar ASIC')

@section('content')
<div class="px-4 py-6 mx-auto max-w-2xl bg-white/90 rounded-lg shadow-sm backdrop-blur-sm">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-danger-strong flex items-center gap-2">
                <div class="p-2 bg-danger-strong rounded text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-bolt-icon lucide-bolt">
                        <path
                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                        <circle cx="12" cy="12" r="4" />
                    </svg>
                </div>
                Editar ASIC
            </h1>
            <p class="text-sm text-gray-500 mt-1">Modifica la información del centro de salud</p>
        </div>
    </div>

    @if($errors->any())
    <div class="flex items-start gap-3 p-4 mb-5 text-red-800 bg-red-50 border border-red-200 rounded-lg">
        <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 mt-0.5"></i>
        <ul class="text-sm list-disc list-inside">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="p-5 border-b border-gray-100 flex items-center gap-2">
            <i data-lucide="building-2" class="w-4 h-4 text-primary-600"></i>
            <h2 class="text-base font-semibold text-gray-800">Información del ASIC</h2>
        </div>

        <form method="POST" action="{{ route('asic.update', $asic->id) }}">
            @csrf
            @method('PATCH')

            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- RIF --}}
                <div>
                    <label for="rif" class="block mb-1.5 text-sm font-medium text-gray-700">
                        RIF <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i data-lucide="file-text" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input type="text" name="rif" id="rif" value="{{ old('rif', $asic->rif) }}"
                            placeholder="Ej: J-12345678-9"
                            class="pl-9 bg-gray-50 border {{ $errors->has('rif') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                    </div>
                    @error('rif')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- Teléfono --}}
                <div>
                    <label for="telefono" class="block mb-1.5 text-sm font-medium text-gray-700">
                        Teléfono <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i data-lucide="phone" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $asic->telefono) }}"
                            placeholder="+58 212 5551212"
                            class="pl-9 bg-gray-50 border {{ $errors->has('telefono') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                    </div>
                    @error('telefono')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- Nombre --}}
                <div class="sm:col-span-2">
                    <label for="nombre" class="block mb-1.5 text-sm font-medium text-gray-700">
                        Nombre del ASIC <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i data-lucide="building-2" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $asic->nombre) }}"
                            placeholder="Nombre completo del ASIC"
                            class="pl-9 bg-gray-50 border {{ $errors->has('nombre') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                    </div>
                    @error('nombre')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- Dirección --}}
                <div class="sm:col-span-2">
                    <label for="direccion" class="block mb-1.5 text-sm font-medium text-gray-700">
                        Dirección <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i data-lucide="map-pin" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input type="text" name="direccion" id="direccion"
                            value="{{ old('direccion', $asic->direccion) }}" placeholder="Calle, número, sector, ciudad"
                            class="pl-9 bg-gray-50 border {{ $errors->has('direccion') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                    </div>
                    @error('direccion')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Info de solo lectura --}}
            <div
                class="mx-5 mb-5 p-3 bg-blue-50 border border-blue-100 rounded-lg text-sm text-blue-700 flex items-center gap-2">
                <i data-lucide="info" class="w-4 h-4 shrink-0"></i>
                Estos datos aparecen en todos los reportes PDF generados por el sistema.
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-between gap-3 px-5 py-4 border-t border-gray-100 bg-gray-50 rounded-b-lg">
                    <a href="{{ route('inicio', $asic->id) }}"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-danger rounded-lg hover:bg-fg-danger-strong focus:ring-4 focus:ring-danger">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Guardar cambios
                    </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    lucide.createIcons();
</script>
@endpush
@endsection