@extends('layouts.app')
@section('title', 'Registrar Despacho')

@section('content')
<div class="px-4 py-6 mx-auto max-w-5xl bg-white/90 rounded-lg shadow-sm backdrop-blur-lg">

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-purple-900 flex items-center gap-3">
                <div class="p-2 bg-purple-800 rounded text-white">
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package-plus-icon lucide-package-plus"><path d="M12 22V12"/><path d="M16 17h6"/><path d="M19 14v6"/><path d="M21 10.535V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.729l7 4a2 2 0 0 0 2 .001l1.675-.955"/><path d="M3.29 7 12 12l8.71-5"/><path d="m7.5 4.27 8.997 5.148"/></svg>
                </div>
                Registrar Despacho
            </h1>
            <p class="text-sm text-gray-500 mt-1 ml-11">Envío de vacunas desde el ASIC hacia módulos afiliados</p>
        </div>
        <a href="{{ route('despachos.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Volver al listado
        </a>
    </div>

    @if(session('error_stock'))
    @php $es = session('error_stock'); @endphp
    <div class="flex items-start gap-3 p-4 mb-5 text-red-800 bg-red-50 border border-red-200 rounded-xl">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0 mt-0.5 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
        <div>
            <p class="font-semibold text-sm">Stock insuficiente</p>
            <p class="text-sm mt-0.5"><strong>{{ $es['vacuna'] }}</strong>: solicitaste <strong>{{ number_format($es['solicitado']) }}</strong>, disponibles: <strong>{{ number_format($es['disponible']) }}</strong>.</p>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="flex items-start gap-3 p-4 mb-5 text-red-800 bg-red-50 border border-red-200 rounded-xl">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0 mt-0.5 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
        <ul class="text-sm list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    {{-- TABS --}}
    <div class="flex border-b border-gray-200">
        <button id="tab-individual" onclick="switchTab('individual')"
            class="tab-btn flex items-center gap-2 px-5 py-3 text-sm font-semibold border-b-2 border-purple-600 text-purple-700 -mb-px bg-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            Individual
        </button>
        <button id="tab-bulk" onclick="switchTab('bulk')"
            class="tab-btn flex items-center gap-2 px-5 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700 -mb-px">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
            Múltiple
        </button>
    </div>

    {{-- TAB INDIVIDUAL --}}
    <div id="panel-individual" class="tab-panel">
        <div class="bg-white border border-gray-200 border-t-0 rounded-b-xl shadow-sm">
            <form method="POST" action="{{ route('despachos.store') }}">
                @csrf
                <input type="hidden" name="asic_id" value="{{ $asic->id }}">
                <div class="p-6 space-y-5">

                    {{-- Vacuna --}}
                    <div>
                        <label class="block mb-1.5 text-sm font-semibold text-gray-700">
                            <span class="flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 2 4 4"/><path d="m17 7 3-3"/><path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5"/><path d="m9 11 4 4"/><path d="m5 19-3 3"/><path d="m14 4 6 6"/></svg>
                                Vacuna <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <input type="hidden" name="vacuna_id" id="si_vacuna_hidden" value="{{ old('vacuna_id') }}">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            </div>
                            <input type="text" id="si_vacuna_input" placeholder="Escribe para buscar vacuna..." autocomplete="off"
                                value="{{ old('vacuna_id') ? $vacunas->firstWhere('id', old('vacuna_id'))?->nombre : '' }}"
                                class="pl-9 pr-4 bg-gray-50 border {{ $errors->has('vacuna_id') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-purple-300 focus:border-purple-500 block w-full p-2.5 transition-colors">
                        </div>
                        @error('vacuna_id')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror

                        <div id="si_stock_widget" class="hidden mt-2 p-3 rounded-lg border flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2 min-w-0">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-boxes-icon lucide-boxes w-5 h-5 shrink-0 text-success"><path d="M2.97 12.92A2 2 0 0 0 2 14.63v3.24a2 2 0 0 0 .97 1.71l3 1.8a2 2 0 0 0 2.06 0L12 19v-5.5l-5-3-4.03 2.42Z"/><path d="m7 16.5-4.74-2.85"/><path d="m7 16.5 5-3"/><path d="M7 16.5v5.17"/><path d="M12 13.5V19l3.97 2.38a2 2 0 0 0 2.06 0l3-1.8a2 2 0 0 0 .97-1.71v-3.24a2 2 0 0 0-.97-1.71L17 10.5l-5 3Z"/><path d="m17 16.5-5-3"/><path d="m17 16.5 4.74-2.85"/><path d="M17 16.5v5.17"/><path d="M7.97 4.42A2 2 0 0 0 7 6.13v4.37l5 3 5-3V6.13a2 2 0 0 0-.97-1.71l-3-1.8a2 2 0 0 0-2.06 0l-3 1.8Z"/><path d="M12 8 7.26 5.15"/><path d="m12 8 4.74-2.85"/><path d="M12 13.5V8"/></svg>
                                <span class="text-sm font-semibold truncate" id="si_stock_nombre"></span>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-xs text-gray-400">Stock disponible</p>
                                <p class="text-lg font-black tabular-nums" id="si_stock_cantidad"></p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        {{-- Módulo --}}
                        <div>
                            <label class="block mb-1.5 text-sm font-semibold text-gray-700">
                                <span class="flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 7v4"/><path d="M14 21v-3a2 2 0 0 0-4 0v3"/><path d="M14 9h-4"/><path d="M18 11h2a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-9a2 2 0 0 1 2-2h2"/><path d="M18 21V5a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16"/></svg>
                                    Módulo Destino <span class="text-red-500">*</span>
                                </span>
                            </label>
                            <input type="hidden" name="modulo_id" id="si_modulo_hidden" value="{{ old('modulo_id') }}">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                </div>
                                <input type="text" id="si_modulo_input" placeholder="Buscar módulo destino..." autocomplete="off"
                                    value="{{ old('modulo_id') ? $modulos->firstWhere('id', old('modulo_id'))?->nombre : '' }}"
                                    class="pl-9 pr-4 bg-gray-50 border {{ $errors->has('modulo_id') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-purple-300 focus:border-purple-500 block w-full p-2.5 transition-colors">
                            </div>
                            @error('modulo_id')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Responsable --}}
                        <div>
                            <label class="block mb-1.5 text-sm font-semibold text-gray-700">
                                <span class="flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/></svg>
                                    Responsable <span class="text-red-500">*</span>
                                </span>
                            </label>
                            <input type="hidden" name="responsable_envio" id="si_resp_hidden" value="{{ old('responsable_envio') }}">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                </div>
                                <input type="text" id="si_resp_input" placeholder="Buscar por nombre o cédula..." autocomplete="off"
                                    class="pl-9 pr-4 bg-gray-50 border {{ $errors->has('responsable_envio') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-purple-300 focus:border-purple-500 block w-full p-2.5 transition-colors">
                            </div>
                            @error('responsable_envio')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Fecha --}}
                        <div>
                            <label for="si_fecha" class="block mb-1.5 text-sm font-semibold text-gray-700">
                                <span class="flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                                    Fecha de Envío <span class="text-red-500">*</span>
                                </span>
                            </label>
                            <input type="date" name="fecha_envio" id="si_fecha"
                                value="{{ old('fecha_envio', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}"
                                class="bg-gray-50 border {{ $errors->has('fecha_envio') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-purple-300 focus:border-purple-500 block w-full p-2.5 transition-colors">
                            @error('fecha_envio')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Cantidad --}}
                        <div>
                            <label for="si_cantidad" class="block mb-1.5 text-sm font-semibold text-gray-700">
                                <span class="flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package-open-icon lucide-package-open w-3.5 h-3.5 text-gray-700"><path d="M12 22v-9"/><path d="M15.17 2.21a1.67 1.67 0 0 1 1.63 0L21 4.57a1.93 1.93 0 0 1 0 3.36L8.82 14.79a1.655 1.655 0 0 1-1.64 0L3 12.43a1.93 1.93 0 0 1 0-3.36z"/><path d="M20 13v3.87a2.06 2.06 0 0 1-1.11 1.83l-6 3.08a1.93 1.93 0 0 1-1.78 0l-6-3.08A2.06 2.06 0 0 1 4 16.87V13"/><path d="M21 12.43a1.93 1.93 0 0 0 0-3.36L8.83 2.2a1.64 1.64 0 0 0-1.63 0L3 4.57a1.93 1.93 0 0 0 0 3.36l12.18 6.86a1.636 1.636 0 0 0 1.63 0z"/></svg>
                                    Cantidad <span class="text-red-500">*</span>
                                </span>
                            </label>
                            <input type="number" name="cantidad" id="si_cantidad" min="1"
                                value="{{ old('cantidad') }}" placeholder="0"
                                oninput="validarCantidadSingle(this.value)"
                                class="bg-gray-50 border {{ $errors->has('cantidad') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-purple-300 focus:border-purple-500 block w-full p-2.5 transition-colors">
                            @error('cantidad')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                            <div id="si_aviso" class="hidden mt-1.5 items-center gap-1.5 text-xs font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                                <span id="si_aviso_text"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Lote SELECT --}}
                    <div>
                        <label for="si_lote" class="block mb-1.5 text-sm font-semibold text-gray-700">
                            <span class="flex items-center gap-1.5 flex-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" x2="7.01" y1="7" y2="7"/></svg>
                                Lote <span class="text-red-500">*</span>
                                <span class="text-gray-400 font-normal text-xs">(selecciona la vacuna primero)</span>
                                <span id="si_lote_cargando" class="hidden text-xs text-purple-500 font-medium animate-pulse">cargando lotes...</span>
                            </span>
                        </label>
                        <div class="relative">
                            <select name="lote" id="si_lote" required disabled
                                class="appearance-none bg-gray-100 border border-gray-200 text-gray-400 text-sm rounded-lg block w-full p-2.5 pr-9 cursor-not-allowed focus:ring-2 focus:ring-purple-300 focus:border-purple-500 transition-all">
                                <option value="">— Selecciona una vacuna primero —</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </div>
                        </div>
                        <p class="mt-1.5 text-xs text-gray-400 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                            Solo lotes con stock disponible. El despacho descuenta de la carga seleccionada.
                        </p>
                        @error('lote')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="p-3 bg-purple-50 border border-purple-100 rounded-lg flex items-center gap-2.5 text-sm text-purple-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 7v4"/><path d="M14 21v-3a2 2 0 0 0-4 0v3"/><path d="M14 9h-4"/><path d="M18 11h2a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-9a2 2 0 0 1 2-2h2"/><path d="M18 21V5a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16"/></svg>
                        Despacho desde: <strong>{{ $asic->nombre }}</strong>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-xl">
                    <a href="{{ route('despachos.index') }}"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" id="si_btn_submit"
                        class="flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-purple-700 rounded-lg hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                        Registrar Despacho
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- TAB BULK --}}
    <div id="panel-bulk" class="tab-panel hidden">
        <div class="bg-white border border-gray-200 border-t-0 rounded-b-xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <h2 class="text-base font-semibold text-gray-800">Registro Múltiple</h2>
                    <span class="px-2.5 py-0.5 text-xs font-semibold bg-purple-100 text-purple-700 rounded-full" id="bulk_row_count">1 fila</span>
                </div>
                <button type="button" onclick="bulkAgregarFila()"
                    class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>
                    Agregar fila
                </button>
            </div>

            <div class="mx-6 mt-4 p-3 bg-amber-50 border border-amber-100 rounded-lg flex items-start gap-2 text-sm text-amber-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                Cada fila es un registro independiente y valida su stock por separado.
            </div>

            <form method="POST" action="{{ route('despachos.store.bulk') }}">
                @csrf
                <div class="p-6">
                    <div class="flex flex-wrap items-center gap-3 mb-4 p-3 bg-gray-50 border border-gray-200 rounded-lg text-xs">
                        <span class="font-semibold text-gray-500">Copiar primera fila:</span>
                        <button type="button" onclick="bulkCopiarFecha()" class="px-2.5 py-1.5 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700 font-medium">Fecha → todas</button>
                        <button type="button" onclick="bulkCopiarResponsable()" class="px-2.5 py-1.5 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700 font-medium">Responsable → todas</button>
                        <div class="ml-auto flex items-center gap-2">
                            <span class="text-gray-400">Total:</span>
                            <span id="bulk_total" class="font-black text-purple-700 text-sm tabular-nums">0 dosis</span>
                        </div>
                    </div>
                    <div class="overflow-x-auto rounded-xl border border-gray-200">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                                    <th class="px-3 py-3 text-left font-semibold" style="min-width:180px">Vacuna *</th>
                                    <th class="px-3 py-3 text-left font-semibold" style="min-width:160px">Módulo *</th>
                                    <th class="px-3 py-3 text-left font-semibold" style="min-width:180px">Responsable *</th>
                                    <th class="px-3 py-3 text-left font-semibold" style="min-width:130px">Fecha *</th>
                                    <th class="px-3 py-3 text-left font-semibold" style="min-width:170px">Lote *</th>
                                    <th class="px-3 py-3 text-left font-semibold" style="min-width:100px">Cantidad *</th>
                                    <th class="px-3 py-3" style="min-width:40px"></th>
                                </tr>
                            </thead>
                            <tbody id="bulk_tbody" class="divide-y divide-gray-100"></tbody>
                        </table>
                    </div>
                </div>

                <div class="mx-6 mb-6 p-3 bg-purple-50 border border-purple-100 rounded-lg text-sm text-purple-700 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 7v4"/><path d="M14 21v-3a2 2 0 0 0-4 0v3"/><path d="M14 9h-4"/><path d="M18 11h2a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-9a2 2 0 0 1 2-2h2"/><path d="M18 21V5a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16"/></svg>
                    Todos los despachos desde: <strong>{{ $asic->nombre }}</strong>
                </div>

                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-xl">
                    <a href="{{ route('despachos.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancelar</a>
                    <button type="submit" class="flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-purple-700 rounded-lg hover:bg-purple-800 focus:ring-4 focus:ring-purple-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                        Guardar todo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- DROPDOWN FLOTANTE — position:fixed, SIN scroll offset --}}
<div id="floating_dropdown"
    class="hidden fixed z-[9999] bg-white border border-gray-200 rounded-xl shadow-2xl overflow-y-auto"
    style="max-height:240px;"
    onmousedown="event.preventDefault()">
</div>

@push('scripts')
<script>
function fmtFecha(f) {
    if (!f) return '';
    // Soporta 'YYYY-MM-DD' y 'YYYY-MM-DDTHH:mm:ss.000000Z' (TiDB Cloud)
    const fecha = new Date(f);
    const d = String(fecha.getUTCDate()).padStart(2, '0');
    const m = String(fecha.getUTCMonth() + 1).padStart(2, '0');
    const y = fecha.getUTCFullYear();
    return d + '/' + m + '/' + y;
}

// ── TABS ─────────────────────────────────────────────────────
function switchTab(tab) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('border-purple-600','text-purple-700','bg-white');
        b.classList.add('border-transparent','text-gray-500');
    });
    document.getElementById('panel-' + tab).classList.remove('hidden');
    const btn = document.getElementById('tab-' + tab);
    btn.classList.add('border-purple-600','text-purple-700','bg-white');
    btn.classList.remove('border-transparent','text-gray-500');
}

// ── DATOS ─────────────────────────────────────────────────────
const VACUNAS   = @json($vacunas->map(fn($v) => ['id' => $v->id, 'nombre' => $v->nombre]));
const MODULOS   = @json($modulos->map(fn($m) => ['id' => $m->id, 'nombre' => $m->nombre, 'dir' => $m->direccion ?? '']));
const PERSONAL  = @json($personal->map(fn($p) => ['id' => $p->cedula, 'nombre' => $p->nombre . ' ' . $p->apellido, 'cargo' => $p->cargo?->nombre ?? '']));
const STOCK_URL = '{{ route("despachos.stock.check") }}';

// ── DROPDOWN FLOTANTE (position:fixed → NO + scrollX/Y) ──────
let activeInput = null, activeHidden = null, activeCallback = null;
const floatDD   = document.getElementById('floating_dropdown');

function posicionarDD(el) {
    const r = el.getBoundingClientRect();
    const spaceBelow = window.innerHeight - r.bottom;
    floatDD.style.width = r.width + 'px';
    floatDD.style.left  = r.left  + 'px';   // fixed: NO + scrollX
    if (spaceBelow < 248 && r.top > 248) {
        floatDD.style.top = (r.top - 244) + 'px';
    } else {
        floatDD.style.top = (r.bottom + 4) + 'px'; // fixed: NO + scrollY
    }
}

function abrirDD(inputEl, hiddenEl, items, renderFn, onSelect) {
    activeInput = inputEl; activeHidden = hiddenEl; activeCallback = onSelect;
    renderDDItems(items, inputEl.value, renderFn, onSelect);
    posicionarDD(inputEl);
    floatDD.classList.remove('hidden');
}

function renderDDItems(items, query, renderFn, onSelect) {
    const q = (query||'').toLowerCase();
    floatDD.innerHTML = '';
    let n = 0;
    items.forEach(item => {
        if (q && !item.nombre.toLowerCase().includes(q)) return;
        const div = document.createElement('div');
        div.className = 'px-3 py-2.5 cursor-pointer hover:bg-purple-50 text-gray-800 text-sm border-b border-gray-50 last:border-0';
        div.innerHTML = renderFn(item);
        div.addEventListener('mousedown', () => {
            activeInput.value  = item.nombre;
            activeHidden.value = item.id;
            floatDD.classList.add('hidden');
            if (onSelect) onSelect(item.id, item.nombre);
        });
        floatDD.appendChild(div); n++;
    });
    if (!n) floatDD.innerHTML = '<div class="px-3 py-4 text-xs text-gray-400 text-center">Sin resultados</div>';
}

document.addEventListener('click', e => {
    if (activeInput && !activeInput.contains(e.target) && !floatDD.contains(e.target)) {
        floatDD.classList.add('hidden'); activeInput = null;
    }
});
window.addEventListener('scroll', () => { if (activeInput) posicionarDD(activeInput); }, { passive:true, capture:true });
window.addEventListener('resize', () => { if (activeInput) posicionarDD(activeInput); }, { passive:true });

// ── AUTOCOMPLETE HELPER ───────────────────────────────────────
function initSearchable(inputId, hiddenId, items, renderFn, onSelect) {
    const input = document.getElementById(inputId);
    const hidden = document.getElementById(hiddenId);
    if (!input || !hidden) return;
    input.addEventListener('focus', () => abrirDD(input, hidden, items, renderFn, onSelect));
    input.addEventListener('input', () => {
        if (!input.value.trim()) hidden.value = '';
        floatDD.classList.contains('hidden')
            ? abrirDD(input, hidden, items, renderFn, onSelect)
            : (renderDDItems(items, input.value, renderFn, onSelect), posicionarDD(input));
    });
    input.addEventListener('keydown', e => { if (e.key === 'Escape') { floatDD.classList.add('hidden'); activeInput = null; } });
}

const renderVacuna   = v => `<span class="font-semibold">${v.nombre}</span>`;
const renderModulo   = m => `<div><p class="font-semibold">${m.nombre}</p>${m.dir ? '<p class="text-xs text-gray-400">'+m.dir+'</p>' : ''}</div>`;
const renderPersonal = p => `<div class="flex justify-between gap-2"><span class="font-semibold">${p.nombre}</span><span class="text-xs text-gray-400 font-mono">${p.id}</span></div>${p.cargo ? '<p class="text-xs text-gray-400">'+p.cargo+'</p>' : ''}`;

// ── STOCK + LOTES INDIVIDUAL ──────────────────────────────────
let siStock = null;

document.getElementById('si_lote').addEventListener('change', function () {
    const disp = parseInt(this.options[this.selectedIndex]?.dataset?.disponible) || 0;
    document.getElementById('si_cantidad').max = disp > 0 ? disp : '';
    siStock = disp > 0 ? disp : null;
    const cant = parseInt(document.getElementById('si_cantidad').value) || 0;
    if (cant > 0) validarCantidadSingle(cant);
});

function verificarStockSingle(vacunaId) {
    const loteSelect   = document.getElementById('si_lote');
    const loteCargando = document.getElementById('si_lote_cargando');
    const stockWidget  = document.getElementById('si_stock_widget');
    const btn          = document.getElementById('si_btn_submit');

    siStock = null;
    document.getElementById('si_cantidad').removeAttribute('max');
    stockWidget.classList.add('hidden');
    document.getElementById('si_aviso').classList.add('hidden');
    btn.disabled = false;

    if (!vacunaId) {
        loteSelect.innerHTML = '<option value="">— Selecciona una vacuna primero —</option>';
        loteSelect.disabled  = true;
        loteSelect.className = loteSelect.className.replace('bg-gray-50 border-gray-300 text-gray-900','') + ' bg-gray-100 border-gray-200 text-gray-400 cursor-not-allowed';
        return;
    }

    loteSelect.innerHTML = '<option value="">Cargando lotes...</option>';
    loteSelect.disabled  = true;
    loteCargando.classList.remove('hidden');

    fetch(`${STOCK_URL}?vacuna_id=${vacunaId}`)
        .then(r => { if (!r.ok) throw new Error('HTTP '+r.status); return r.json(); })
        .then(d => {
            // Widget stock
            const nm = document.getElementById('si_stock_nombre');
            const cn = document.getElementById('si_stock_cantidad');
            const esRojo = d.stock === 0, esNarj = d.stock > 0 && d.stock <= 50;
            stockWidget.classList.remove('hidden');
            stockWidget.className = 'mt-2 p-3 rounded-lg border flex items-center justify-between gap-3 ' +
                (esRojo ? 'border-red-200 bg-red-50' : esNarj ? 'border-orange-200 bg-orange-50' : 'border-green-200 bg-green-50');
            nm.className = 'text-sm font-semibold truncate ' + (esRojo ? 'text-red-700' : esNarj ? 'text-orange-700' : 'text-green-700');
            cn.className = 'text-lg font-black tabular-nums ' + (esRojo ? 'text-red-700' : esNarj ? 'text-orange-700' : 'text-green-700');
            nm.textContent = d.vacuna;
            cn.textContent = d.stock.toLocaleString() + ' dosis';

            // SELECT de lotes
            loteSelect.innerHTML = '<option value="">— Selecciona un lote —</option>';
            if (d.lotes && d.lotes.length > 0) {
                d.lotes.forEach(lote => {
                    const opt = document.createElement('option');
                    opt.value = lote.lote ?? '';
                    const vence = lote.fecha_vencimiento ? '  ·  Vence: ' + fmtFecha(lote.fecha_vencimiento) : '';
                    opt.textContent        = lote.lote + '  —  ' + lote.disponible + ' disponibles' + vence;
                    opt.dataset.disponible = lote.disponible;
                    loteSelect.appendChild(opt);
                });
                loteSelect.disabled  = false;
                loteSelect.className = 'appearance-none bg-gray-50 border border-green-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 pr-9 focus:ring-2 focus:ring-purple-300 focus:border-purple-500 transition-all';
            } else {
                loteSelect.innerHTML = '<option value="">Sin lotes con stock disponible</option>';
                loteSelect.disabled  = true;
            }
            btn.disabled = (d.stock === 0);
        })
        .catch(err => {
            console.error('checkStock error:', err);
            loteSelect.innerHTML = '<option value="">Error — recarga la página</option>';
            loteSelect.disabled  = true;
        })
        .finally(() => { loteCargando.classList.add('hidden'); });
}

function validarCantidadSingle(val) {
    const cant  = parseInt(val) || 0;
    const aviso = document.getElementById('si_aviso');
    const texto = document.getElementById('si_aviso_text');
    const btn   = document.getElementById('si_btn_submit');
    if (siStock === null || cant === 0) { aviso.classList.add('hidden'); return; }
    if (cant > siStock) {
        aviso.classList.remove('hidden'); aviso.classList.add('flex');
        aviso.style.color = '#dc2626';
        texto.textContent = 'Excede el stock del lote (' + siStock.toLocaleString() + ' disponibles).';
        btn.disabled = true;
    } else if (cant > siStock * 0.8) {
        aviso.classList.remove('hidden'); aviso.classList.add('flex');
        aviso.style.color = '#f97316';
        texto.textContent = 'Usarás más del 80% del stock de este lote.';
        btn.disabled = false;
    } else {
        aviso.classList.add('hidden');
        btn.disabled = false;
    }
}

initSearchable('si_vacuna_input', 'si_vacuna_hidden', VACUNAS,  renderVacuna,   id => verificarStockSingle(id));
initSearchable('si_modulo_input', 'si_modulo_hidden', MODULOS,  renderModulo);
initSearchable('si_resp_input',   'si_resp_hidden',   PERSONAL, renderPersonal);

// ── BULK ──────────────────────────────────────────────────────
let bulkRowIdx = 0;
const stockCache = {};

function bulkBuildRow(idx) {
    const hoy = new Date().toISOString().split('T')[0];
    return `<td class="px-2 py-2 align-top">
        <input type="hidden" name="despachos[${idx}][vacuna_id]" id="bv_h_${idx}">
        <input type="text" id="bv_i_${idx}" placeholder="Buscar vacuna..." autocomplete="off"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-1 focus:ring-purple-400 focus:border-purple-400 block w-full p-2 transition-colors">
        <div id="bv_stock_${idx}" class="hidden mt-1 text-xs font-medium px-1"></div>
    </td>
    <td class="px-2 py-2 align-top">
        <input type="hidden" name="despachos[${idx}][modulo_id]" id="bm_h_${idx}">
        <input type="text" id="bm_i_${idx}" placeholder="Buscar módulo..." autocomplete="off"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-1 focus:ring-purple-400 focus:border-purple-400 block w-full p-2 transition-colors">
    </td>
    <td class="px-2 py-2 align-top">
        <input type="hidden" name="despachos[${idx}][responsable_envio]" id="br_h_${idx}">
        <input type="text" id="br_i_${idx}" placeholder="Responsable..." autocomplete="off"
            class="bulk-resp-input bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-1 focus:ring-purple-400 focus:border-purple-400 block w-full p-2 transition-colors">
    </td>
    <td class="px-2 py-2 align-top">
        <input type="date" name="despachos[${idx}][fecha_envio]" value="${hoy}" max="${hoy}"
            class="bulk-fecha-input bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-1 focus:ring-purple-400 focus:border-purple-400 block w-full p-2 transition-colors">
    </td>
    <td class="px-2 py-2 align-top">
        <select name="despachos[${idx}][lote]" id="blote_${idx}" required disabled
            class="bg-gray-100 border border-gray-200 text-gray-400 text-xs rounded-lg block w-full p-2 disabled:opacity-60 disabled:cursor-not-allowed focus:ring-1 focus:ring-purple-400 focus:border-purple-400 transition-colors">
            <option value="">— Elige vacuna —</option>
        </select>
    </td>
    <td class="px-2 py-2 align-top">
        <input type="number" name="despachos[${idx}][cantidad]" id="bcant_${idx}" min="1" placeholder="0"
            oninput="bulkValidarCantidad(${idx}, this.value)"
            class="bulk-cantidad bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-1 focus:ring-purple-400 focus:border-purple-400 block w-full p-2 transition-colors">
    </td>
    <td class="px-2 py-2 align-middle text-center">
        <button type="button" onclick="bulkEliminarFila(${idx})"
            class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
        </button>
    </td>`;
}

function bulkInitRow(idx) {
    const bind = (inputEl, hiddenEl, items, renderFn, onSelect) => {
        inputEl.addEventListener('focus', () => abrirDD(inputEl, hiddenEl, items, renderFn, onSelect));
        inputEl.addEventListener('input', () => {
            if (!inputEl.value.trim()) hiddenEl.value = '';
            floatDD.classList.contains('hidden')
                ? abrirDD(inputEl, hiddenEl, items, renderFn, onSelect)
                : (renderDDItems(items, inputEl.value, renderFn, onSelect), posicionarDD(inputEl));
        });
    };
    const vI = document.getElementById(`bv_i_${idx}`), vH = document.getElementById(`bv_h_${idx}`);
    const mI = document.getElementById(`bm_i_${idx}`), mH = document.getElementById(`bm_h_${idx}`);
    const rI = document.getElementById(`br_i_${idx}`), rH = document.getElementById(`br_h_${idx}`);
    bind(vI, vH, VACUNAS,  renderVacuna,   id => { vH.value = id; bulkCargarLotes(idx, id); });
    bind(mI, mH, MODULOS,  renderModulo,   id => { mH.value = id; });
    bind(rI, rH, PERSONAL, renderPersonal, id => { rH.value = id; });
    document.getElementById(`blote_${idx}`).addEventListener('change', function() {
        const disp = parseInt(this.options[this.selectedIndex]?.dataset?.disponible) || 0;
        document.getElementById(`bcant_${idx}`).max = disp > 0 ? disp : '';
    });
}

function bulkCargarLotes(idx, vacunaId) {
    const stockEl = document.getElementById(`bv_stock_${idx}`);
    const loteSel = document.getElementById(`blote_${idx}`);
    if (!vacunaId) { stockEl.classList.add('hidden'); loteSel.innerHTML = '<option value="">— Elige vacuna —</option>'; loteSel.disabled = true; return; }
    loteSel.innerHTML = '<option value="">Cargando...</option>'; loteSel.disabled = true; stockEl.classList.add('hidden');
    fetch(`${STOCK_URL}?vacuna_id=${vacunaId}`)
        .then(r => r.json())
        .then(d => {
            stockCache[vacunaId] = d.stock;
            stockEl.classList.remove('hidden');
            stockEl.className = 'mt-1 text-xs font-medium px-1 ' + (d.stock === 0 ? 'text-red-600' : 'text-green-600');
            stockEl.textContent = d.stock === 0 ? '⚠ Sin stock' : '✓ ' + d.stock.toLocaleString() + ' disponibles';
            loteSel.innerHTML = '<option value="">— Selecciona lote —</option>';
            if (d.lotes && d.lotes.length > 0) {
                d.lotes.forEach(l => {
                    const opt = document.createElement('option');
                    opt.value = l.lote ?? '';
                    const v = l.fecha_vencimiento ? '  ·  ' + fmtFecha(l.fecha_vencimiento) : '';
                    opt.textContent = l.lote + ' — ' + l.disponible + ' disp.' + v;
                    opt.dataset.disponible = l.disponible;
                    loteSel.appendChild(opt);
                });
                loteSel.disabled = false;
                loteSel.className = loteSel.className.replace('bg-gray-100 border-gray-200 text-gray-400','bg-gray-50 border-gray-300 text-gray-900');
            } else {
                loteSel.innerHTML = '<option value="">Sin lotes disponibles</option>';
                loteSel.disabled = true;
            }
        })
        .catch(() => { loteSel.innerHTML = '<option value="">Error</option>'; loteSel.disabled = true; });
}

function bulkValidarCantidad(idx, val) {
    const stock = stockCache[document.getElementById(`bv_h_${idx}`)?.value] ?? null;
    const input = document.getElementById(`bcant_${idx}`);
    const cant  = parseInt(val) || 0;
    if (stock !== null && cant > stock) { input.classList.add('border-red-400'); input.classList.remove('border-gray-300'); }
    else { input.classList.remove('border-red-400'); input.classList.add('border-gray-300'); }
    bulkActualizarContadores();
}
function bulkEliminarFila(idx) {
    if (document.querySelectorAll('#bulk_tbody tr').length <= 1) { alert('Debe haber al menos una fila.'); return; }
    document.getElementById(`brow_${idx}`)?.remove(); bulkActualizarContadores();
}
function bulkCopiarFecha() {
    const p = document.querySelector('.bulk-fecha-input');
    if (!p?.value) { alert('Ingresa la fecha en la primera fila.'); return; }
    document.querySelectorAll('.bulk-fecha-input').forEach(f => f.value = p.value);
}
function bulkCopiarResponsable() {
    const pI = document.querySelector('.bulk-resp-input'), pH = document.getElementById('br_h_0');
    if (!pH?.value) { alert('Selecciona el responsable en la primera fila.'); return; }
    let i = 0;
    document.querySelectorAll('.bulk-resp-input').forEach(inp => { inp.value = pI.value; const h = document.getElementById(`br_h_${i}`); if (h) h.value = pH.value; i++; });
}
function bulkAgregarFila() {
    const idx = bulkRowIdx++, tr = document.createElement('tr');
    tr.id = `brow_${idx}`; tr.className = 'hover:bg-gray-50 transition-colors';
    tr.innerHTML = bulkBuildRow(idx);
    document.getElementById('bulk_tbody').appendChild(tr);
    bulkInitRow(idx); bulkActualizarContadores();
}
function bulkActualizarContadores() {
    let t = 0; document.querySelectorAll('.bulk-cantidad').forEach(c => t += parseInt(c.value)||0);
    document.getElementById('bulk_total').textContent = t.toLocaleString() + ' dosis';
    const r = document.querySelectorAll('#bulk_tbody tr').length;
    document.getElementById('bulk_row_count').textContent = r + (r===1?' fila':' filas');
}

bulkAgregarFila();
</script>
@endpush
@endsection