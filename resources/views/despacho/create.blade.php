@extends('layouts.app')
@section('title', 'Registrar Despacho')

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl bg-white/90 backdrop-blur-lg rounded-lg shadow">

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-purple-800 flex items-center gap-2">
                <div class="p-2 text-purple-300 bg-purple-800 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>
                </div>
                Registrar Despacho
            </h1>
            <p class="text-sm text-gray-500 mt-1">Envío de vacunas desde el ASIC hacia módulos afiliados</p>
        </div>
        <a href="{{ route('despachos.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Volver
        </a>
    </div>

    {{-- Alertas --}}
    @if(session('error_stock'))
    @php $es = session('error_stock'); @endphp
    <div class="flex items-start gap-3 p-4 mb-5 text-red-800 bg-red-50 border border-red-200 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
        <div>
            <p class="font-semibold text-sm">Stock insuficiente — Despacho no registrado</p>
            <p class="text-sm mt-0.5">
                <strong>{{ $es['vacuna'] }}</strong>: solicitaste
                <strong>{{ number_format($es['solicitado']) }}</strong> dosis,
                disponibles: <strong>{{ number_format($es['disponible']) }}</strong>.
            </p>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="flex items-start gap-3 p-4 mb-5 text-red-800 bg-red-50 border border-red-200 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
        <div>
            <p class="font-semibold text-sm mb-1">Corrige los siguientes errores:</p>
            <ul class="text-sm list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    </div>
    @endif

    {{-- TABS --}}
    <div class="mb-0">
        <div class="flex border-b border-gray-200">
            <button id="tab-individual" onclick="switchTab('individual')"
                class="tab-btn flex items-center gap-2 px-5 py-3 text-sm font-medium border-b-2 border-primary-600 text-primary-600 -mb-px">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" x2="12" y1="18" y2="12"/><line x1="9" x2="15" y1="15" y2="15"/></svg>
                Registro Individual
            </button>
            <button id="tab-bulk" onclick="switchTab('bulk')"
                class="tab-btn flex items-center gap-2 px-5 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 -mb-px">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15.5 2H8.6c-.4 0-.8.2-1.1.5-.3.3-.5.7-.5 1.1v12.8c0 .4.2.8.5 1.1.3.3.7.5 1.1.5h9.8c.4 0 .8-.2 1.1-.5.3-.3.5-.7.5-1.1V6.5L15.5 2z"/><polyline points="15 2 15 7 20 7"/><path d="M10 12a1 1 0 0 0-1 1v1a1 1 0 0 1-1 1 1 1 0 0 1 1 1v1a1 1 0 0 0 1 1"/><path d="M14 18a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1 1 1 0 0 1-1-1v-1a1 1 0 0 0-1-1"/></svg>
                Registro Múltiple
            </button>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- TAB INDIVIDUAL --}}
    {{-- ============================================================ --}}
    <div id="panel-individual" class="tab-panel">
        <div class="bg-white border border-gray-200 rounded-b-lg shadow-sm">
            <div class="p-5 border-b border-gray-100 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-primary-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" x2="21" y1="6" y2="6"/><line x1="8" x2="21" y1="12" y2="12"/><line x1="8" x2="21" y1="18" y2="18"/><line x1="3" x2="3.01" y1="6" y2="6"/><line x1="3" x2="3.01" y1="12" y2="12"/><line x1="3" x2="3.01" y1="18" y2="18"/></svg>
                <h2 class="text-base font-semibold text-gray-800">Datos del Despacho</h2>
            </div>

            <form method="POST" action="{{ route('despachos.store') }}">
                @csrf
                <input type="hidden" name="asic_id" value="{{ $asic->id }}">

                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">

                    {{-- Vacuna --}}
                    <div class="sm:col-span-2">
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Vacuna <span class="text-red-500">*</span>
                        </label>
                        <input type="hidden" name="vacuna_id" id="si_vacuna_hidden" value="{{ old('vacuna_id') }}">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 2 4 4"/><path d="m17 7 3-3"/><path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5"/><path d="m9 11 4 4"/><path d="m5 19-3 3"/><path d="m14 4 6 6"/></svg>
                            </div>
                            <input type="text" id="si_vacuna_input" placeholder="Escribe para buscar vacuna..."
                                autocomplete="off"
                                value="{{ old('vacuna_id') ? $vacunas->firstWhere('id', old('vacuna_id'))?->nombre : '' }}"
                                class="pl-9 pr-4 bg-gray-50 border {{ $errors->has('vacuna_id') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        </div>
                        @error('vacuna_id')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror

                        {{-- Widget stock --}}
                        <div id="si_stock_widget" class="hidden mt-2 p-3 rounded-lg border flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"/><path d="M12 22V12"/></svg>
                                <span class="text-sm font-medium" id="si_stock_nombre"></span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs text-gray-500">Stock total disponible</span>
                                <p class="text-lg font-bold" id="si_stock_cantidad"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Módulo --}}
                    <div class="sm:col-span-2">
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Módulo Destino <span class="text-red-500">*</span>
                        </label>
                        <input type="hidden" name="modulo_id" id="si_modulo_hidden" value="{{ old('modulo_id') }}">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M12 6h.01"/><path d="M12 10h.01"/><path d="M12 14h.01"/><path d="M16 10h.01"/><path d="M16 14h.01"/><path d="M8 10h.01"/><path d="M8 14h.01"/></svg>
                            </div>
                            <input type="text" id="si_modulo_input" placeholder="Buscar módulo destino..."
                                autocomplete="off"
                                value="{{ old('modulo_id') ? $modulos->firstWhere('id', old('modulo_id'))?->nombre : '' }}"
                                class="pl-9 pr-4 bg-gray-50 border {{ $errors->has('modulo_id') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        </div>
                        @error('modulo_id')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Responsable --}}
                    <div class="sm:col-span-2">
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">
                            Responsable del Envío <span class="text-red-500">*</span>
                        </label>
                        <input type="hidden" name="responsable_envio" id="si_resp_hidden" value="{{ old('responsable_envio') }}">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/></svg>
                            </div>
                            <input type="text" id="si_resp_input" placeholder="Buscar personal por nombre o cédula..."
                                autocomplete="off"
                                class="pl-9 pr-4 bg-gray-50 border {{ $errors->has('responsable_envio') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        </div>
                        @error('responsable_envio')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- ====================================================== --}}
                    {{-- LOTE — SELECT cargado por AJAX (OBLIGATORIO)            --}}
                    {{-- ====================================================== --}}
                    <div class="sm:col-span-2">
                        <label for="si_lote" class="block mb-1.5 text-sm font-medium text-gray-700">
                            <span class="flex items-center gap-1.5 flex-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" x2="7.01" y1="7" y2="7"/></svg>
                                Lote <span class="text-red-500">*</span>
                                <span class="text-gray-400 font-normal text-xs">(selecciona la vacuna primero)</span>
                                <span id="si_lote_cargando" class="hidden text-xs text-blue-500 font-normal animate-pulse">cargando lotes...</span>
                            </span>
                        </label>
                        <select name="lote" id="si_lote" required disabled
                            class="bg-gray-50 border {{ $errors->has('lote') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 disabled:opacity-60 disabled:cursor-not-allowed">
                            <option value="">— Selecciona una vacuna primero —</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-400 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                            Solo se muestran lotes con stock disponible. El despacho descuenta de la carga seleccionada.
                        </p>
                        @error('lote')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Cantidad --}}
                    <div>
                        <label for="si_cantidad" class="block mb-1.5 text-sm font-medium text-gray-700">
                            Cantidad <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                            </div>
                            <input type="number" name="cantidad" id="si_cantidad" min="1" value="{{ old('cantidad') }}"
                                placeholder="0" oninput="validarCantidadSingle(this.value)"
                                class="pl-9 bg-gray-50 border {{ $errors->has('cantidad') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        </div>
                        @error('cantidad')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                        <div id="si_aviso" class="hidden mt-1.5 flex items-center gap-1.5 text-xs font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                            <span id="si_aviso_text"></span>
                        </div>
                    </div>

                    {{-- Fecha --}}
                    <div>
                        <label for="si_fecha" class="block mb-1.5 text-sm font-medium text-gray-700">
                            Fecha de Envío <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                            </div>
                            <input type="date" name="fecha_envio" id="si_fecha"
                                value="{{ old('fecha_envio', date('Y-m-d')) }}"
                                max="{{ date('Y-m-d') }}"
                                class="pl-9 bg-gray-50 border {{ $errors->has('fecha_envio') ? 'border-red-500' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        </div>
                        @error('fecha_envio')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Info ASIC --}}
                <div class="mx-5 mb-5 p-3 bg-blue-50 border border-blue-100 rounded-lg flex items-center gap-2.5 text-sm text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M12 6h.01"/><path d="M12 10h.01"/><path d="M12 14h.01"/></svg>
                    <span>Despacho desde: <strong>{{ $asic->nombre }}</strong></span>
                </div>

                {{-- Botones --}}
                <div class="flex items-center justify-end gap-3 px-5 py-4 border-t border-gray-100 bg-gray-50 rounded-b-lg">
                    <a href="{{ route('despachos.index') }}"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit" id="si_btn_submit"
                        class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-purple-700 rounded-lg hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                        Registrar Despacho
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- TAB BULK --}}
    {{-- ============================================================ --}}
    <div id="panel-bulk" class="tab-panel hidden">
        <div class="bg-white border border-gray-200 rounded-b-lg shadow-sm">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-primary-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3h18v18H3z"/><path d="M3 9h18"/><path d="M3 15h18"/><path d="M9 3v18"/></svg>
                    <h2 class="text-base font-semibold text-gray-800">Registro Múltiple</h2>
                    <span class="px-2.5 py-0.5 text-xs font-medium bg-blue-100 text-blue-700 rounded-full" id="bulk_row_count">1 fila</span>
                </div>
                <button type="button" onclick="bulkAgregarFila()"
                    class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>
                    Agregar fila
                </button>
            </div>

            <div class="mx-5 mt-4 p-3 bg-amber-50 border border-amber-100 rounded-lg flex items-start gap-2.5 text-sm text-amber-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                <p>Cada fila representa un registro independiente. Cada uno valida su stock por separado.</p>
            </div>

            <form method="POST" action="{{ route('despachos.store.bulk') }}" id="bulk_form">
                @csrf
                <input type="hidden" name="asic_id" value="{{ $asic->id }}">

                <div class="p-5">
                    {{-- Controles rápidos --}}
                    <div class="flex flex-wrap items-center gap-3 mb-4 p-3 bg-gray-50 border border-gray-200 rounded-lg text-xs">
                        <span class="font-medium text-gray-500 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                            Copiar primera fila:
                        </span>
                        <button type="button" onclick="bulkCopiarFecha()"
                            class="flex items-center gap-1 px-2.5 py-1.5 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700 font-medium">
                            Fecha a todas
                        </button>
                        <button type="button" onclick="bulkCopiarResponsable()"
                            class="flex items-center gap-1 px-2.5 py-1.5 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700 font-medium">
                            Responsable a todas
                        </button>
                        <div class="ml-auto flex items-center gap-2">
                            <span class="text-gray-400">Total dosis:</span>
                            <span id="bulk_total" class="font-bold text-primary-700 text-sm">0</span>
                        </div>
                    </div>

                    {{-- Tabla --}}
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                                    <th class="px-3 py-2.5 text-left font-semibold" style="width:220px">Vacuna *</th>
                                    <th class="px-3 py-2.5 text-left font-semibold" style="width:180px">Módulo *</th>
                                    <th class="px-3 py-2.5 text-left font-semibold" style="width:200px">Responsable *</th>
                                    <th class="px-3 py-2.5 text-left font-semibold" style="width:130px">Fecha *</th>
                                    <th class="px-3 py-2.5 text-left font-semibold" style="width:160px">Lote *</th>
                                    <th class="px-3 py-2.5 text-left font-semibold" style="width:110px">Cantidad *</th>
                                    <th class="px-3 py-2.5 text-left font-semibold" style="width:40px"></th>
                                </tr>
                            </thead>
                            <tbody id="bulk_tbody" class="divide-y divide-gray-100">
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Info ASIC --}}
                <div class="mx-5 mb-5 p-3 bg-blue-50 border border-blue-100 rounded-lg flex items-center gap-2.5 text-sm text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/></svg>
                    <span>Todos los despachos se registran desde: <strong>{{ $asic->nombre }}</strong></span>
                </div>

                {{-- Botones --}}
                <div class="flex items-center justify-between gap-3 px-5 py-4 border-t border-gray-100 bg-gray-50 rounded-b-lg">
                    <p class="text-xs text-gray-400 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        Cada fila valida stock independientemente antes de guardar
                    </p>
                    <div class="flex gap-2">
                        <a href="{{ route('despachos.index') }}"
                            class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-purple-700 rounded-lg hover:bg-purple-800 focus:ring-4 focus:ring-purple-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                            Guardar registros múltiples
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- DROPDOWN FLOTANTE --}}
<div id="floating_dropdown"
    class="hidden fixed z-50 bg-white border border-gray-200 rounded-lg shadow-xl overflow-y-auto"
    style="max-height:220px; min-width:200px;" onmousedown="event.preventDefault()">
</div>

@push('scripts')
<script>
    // ============================================================
    // TABS
    // ============================================================
    function switchTab(tab) {
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('border-primary-600', 'text-primary-600');
            b.classList.add('border-transparent', 'text-gray-500');
        });
        document.getElementById('panel-' + tab).classList.remove('hidden');
        const btn = document.getElementById('tab-' + tab);
        btn.classList.add('border-primary-600', 'text-primary-600');
        btn.classList.remove('border-transparent', 'text-gray-500');
    }

    // ============================================================
    // DATOS DESDE BLADE
    // ============================================================
    const VACUNAS   = @json($vacunas->map(fn($v) => ['id' => $v->id, 'nombre' => $v->nombre]));
    const MODULOS   = @json($modulos->map(fn($m) => ['id' => $m->id, 'nombre' => $m->nombre, 'dir' => $m->direccion]));
    const PERSONAL  = @json($personal->map(fn($p) => ['id' => $p->cedula, 'nombre' => $p->nombre . ' ' . $p->apellido, 'cargo' => $p->cargo?->nombre ?? '']));
    const STOCK_URL = '{{ route("despachos.stock.check") }}';

    // ============================================================
    // DROPDOWN FLOTANTE UNIVERSAL
    // ============================================================
    let activeInput    = null;
    let activeHidden   = null;
    let activeItems    = [];
    let activeCallback = null;
    let scrollHandler  = null;
    let resizeHandler  = null;

    const floatDD = document.getElementById('floating_dropdown');

    function openFloatingDD(inputEl, hiddenEl, items, renderItem, onSelect) {
        activeInput    = inputEl;
        activeHidden   = hiddenEl;
        activeItems    = items;
        activeCallback = onSelect;

        renderFloatItems(items, inputEl.value, renderItem, onSelect);
        posicionarDD(inputEl);
        floatDD.classList.remove('hidden');

        if (scrollHandler) window.removeEventListener('scroll', scrollHandler, true);
        if (resizeHandler) window.removeEventListener('resize', resizeHandler);

        scrollHandler = () => { if (activeInput) requestAnimationFrame(() => posicionarDD(activeInput)); };
        resizeHandler = () => { if (activeInput) requestAnimationFrame(() => posicionarDD(activeInput)); };

        window.addEventListener('scroll', scrollHandler, { passive: true, capture: true });
        window.addEventListener('resize', resizeHandler);
    }

    function posicionarDD(inputEl) {
        if (!inputEl) return;
        const rect       = inputEl.getBoundingClientRect();
        const spaceBelow = window.innerHeight - rect.bottom;
        const ddH        = Math.min(220, floatDD.scrollHeight || 220);

        floatDD.style.width = rect.width + 'px';
        floatDD.style.left  = rect.left + window.scrollX + 'px';

        if (spaceBelow < ddH + 8) {
            floatDD.style.top = (rect.top + window.scrollY - ddH - 2) + 'px';
        } else {
            floatDD.style.top = (rect.bottom + window.scrollY + 2) + 'px';
        }
    }

    function renderFloatItems(items, query, renderFn, onSelect) {
        const q = query.toLowerCase();
        floatDD.innerHTML = '';
        let vis = 0;

        items.forEach(item => {
            if (!item.nombre.toLowerCase().includes(q)) return;
            const div = document.createElement('div');
            div.className = 'px-3 py-2.5 cursor-pointer hover:bg-blue-50 hover:text-blue-700 text-gray-700 text-sm';
            div.innerHTML = renderFn(item);
            div.addEventListener('mousedown', () => {
                activeInput.value  = item.nombre;
                activeHidden.value = item.id;
                floatDD.classList.add('hidden');
                if (onSelect) onSelect(item.id, item.nombre);
            });
            floatDD.appendChild(div);
            vis++;
        });

        if (vis === 0) {
            floatDD.innerHTML = '<div class="px-3 py-3 text-xs text-gray-400 text-center">Sin resultados</div>';
        }
    }

    function closeFloatingDD() {
        floatDD.classList.add('hidden');
        if (scrollHandler) window.removeEventListener('scroll', scrollHandler, true);
        if (resizeHandler) window.removeEventListener('resize', resizeHandler);
        activeInput = null;
    }

    document.addEventListener('click', e => {
        if (activeInput && !activeInput.contains(e.target) && !floatDD.contains(e.target)) {
            closeFloatingDD();
        }
    });

    // ============================================================
    // SEARCHABLE INDIVIDUAL
    // ============================================================
    function initSingleSearchable(inputId, hiddenId, items, renderFn, onSelect) {
        const input  = document.getElementById(inputId);
        const hidden = document.getElementById(hiddenId);
        if (!input || !hidden) return;

        input.addEventListener('focus', () => openFloatingDD(input, hidden, items, renderFn, onSelect));
        input.addEventListener('input', () => {
            if (!input.value) hidden.value = '';
            if (floatDD.classList.contains('hidden')) {
                openFloatingDD(input, hidden, items, renderFn, onSelect);
            } else {
                renderFloatItems(items, input.value, renderFn, onSelect);
                posicionarDD(input);
            }
        });
    }

    const renderVacuna  = v => `<span class="font-medium">${v.nombre}</span>`;
    const renderModulo  = m => `<div><p class="font-medium">${m.nombre}</p><p class="text-xs text-gray-400">${m.dir || ''}</p></div>`;
    const renderPersonal = p => `<div class="flex justify-between items-center gap-3"><span class="font-medium">${p.nombre}</span><span class="text-xs text-gray-400 font-mono">${p.id}</span></div>${p.cargo ? '<p class="text-xs text-gray-400">' + p.cargo + '</p>' : ''}`;

    // ============================================================
    // STOCK Y LOTES — TAB INDIVIDUAL
    // ============================================================
    let siStock = null;

    // Listener del SELECT de lote (definido una sola vez)
    document.getElementById('si_lote').addEventListener('change', function () {
        const opt  = this.options[this.selectedIndex];
        const disp = parseInt(opt?.dataset?.disponible) || 0;
        document.getElementById('si_cantidad').max = disp > 0 ? disp : '';
        siStock = disp > 0 ? disp : null;
        const cant = parseInt(document.getElementById('si_cantidad').value) || 0;
        if (cant > 0) validarCantidadSingle(cant);
    });

    function verificarStockSingle(vacunaId) {
        const loteSelect   = document.getElementById('si_lote');
        const loteCargando = document.getElementById('si_lote_cargando');
        const stockWidget  = document.getElementById('si_stock_widget');

        // Reset
        siStock = null;
        document.getElementById('si_cantidad').removeAttribute('max');
        stockWidget.classList.add('hidden');

        if (!vacunaId) {
            loteSelect.innerHTML = '<option value="">— Selecciona una vacuna primero —</option>';
            loteSelect.disabled  = true;
            return;
        }

        // Mostrar spinner
        loteSelect.innerHTML = '<option value="">Cargando lotes...</option>';
        loteSelect.disabled  = true;
        loteCargando.classList.remove('hidden');

        fetch(`${STOCK_URL}?vacuna_id=${vacunaId}`)
            .then(r => {
                if (!r.ok) throw new Error('HTTP ' + r.status);
                return r.json();
            })
            .then(d => {
                // Widget stock total
                const nm = document.getElementById('si_stock_nombre');
                const cn = document.getElementById('si_stock_cantidad');
                const esRojo    = d.stock === 0;
                const esNaranja = d.stock > 0 && d.stock <= 50;

                stockWidget.classList.remove('hidden');
                stockWidget.className = 'mt-2 p-3 rounded-lg border flex items-center justify-between ' +
                    (esRojo ? 'border-red-200 bg-red-50' : esNaranja ? 'border-orange-200 bg-orange-50' : 'border-green-200 bg-green-50');
                nm.className = 'text-sm font-medium ' + (esRojo ? 'text-red-700' : esNaranja ? 'text-orange-700' : 'text-green-700');
                cn.className = 'text-lg font-bold '   + (esRojo ? 'text-red-700' : esNaranja ? 'text-orange-700' : 'text-green-700');
                nm.textContent = d.vacuna;
                cn.textContent = d.stock.toLocaleString() + ' unidades';

                // Poblar SELECT de lotes
                loteSelect.innerHTML = '<option value="">— Selecciona un lote —</option>';

                if (d.lotes && d.lotes.length > 0) {
                    d.lotes.forEach(lote => {
                        const opt = document.createElement('option');
                        opt.value = lote.lote ?? '';
                        const vence = lote.fecha_vencimiento ? ` · Vence: ${lote.fecha_vencimiento}` : '';
                        opt.textContent      = `${lote.lote}  (${lote.disponible} disponibles${vence})`;
                        opt.dataset.disponible = lote.disponible;
                        loteSelect.appendChild(opt);
                    });
                    loteSelect.disabled = false;
                } else {
                    loteSelect.innerHTML = '<option value="">Sin lotes con stock disponible</option>';
                    loteSelect.disabled  = true;
                }

                document.getElementById('si_btn_submit').disabled = (d.stock === 0);
            })
            .catch(err => {
                console.error('Error checkStock:', err);
                loteSelect.innerHTML = '<option value="">Error al cargar — recarga la página</option>';
                loteSelect.disabled  = true;
            })
            .finally(() => {
                loteCargando.classList.add('hidden');
            });
    }

    function validarCantidadSingle(val) {
        const cant  = parseInt(val) || 0;
        const aviso = document.getElementById('si_aviso');
        const texto = document.getElementById('si_aviso_text');
        const btn   = document.getElementById('si_btn_submit');

        if (siStock === null || cant === 0) { aviso.classList.add('hidden'); return; }

        if (cant > siStock) {
            aviso.classList.remove('hidden');
            aviso.className   = 'mt-1.5 flex items-center gap-1.5 text-xs font-medium text-red-600';
            texto.textContent = `Excede el stock disponible del lote (${siStock.toLocaleString()} dosis).`;
            btn.disabled      = true;
        } else if (cant > siStock * 0.8) {
            aviso.classList.remove('hidden');
            aviso.className   = 'mt-1.5 flex items-center gap-1.5 text-xs font-medium text-orange-500';
            texto.textContent = `Usarás más del 80% del stock de este lote.`;
            btn.disabled      = false;
        } else {
            aviso.classList.add('hidden');
            btn.disabled = false;
        }
    }

    // Iniciar búsquedas
    initSingleSearchable('si_vacuna_input', 'si_vacuna_hidden', VACUNAS,  renderVacuna,  (id) => verificarStockSingle(id));
    initSingleSearchable('si_modulo_input', 'si_modulo_hidden', MODULOS,  renderModulo);
    initSingleSearchable('si_resp_input',   'si_resp_hidden',   PERSONAL, renderPersonal);

    // ============================================================
    // BULK TABLE
    // ============================================================
    let bulkRowIdx = 0;
    const stockCache = {};

    function bulkBuildRow(idx) {
        return `
        <td class="px-2 py-2 align-top">
            <input type="hidden" name="despachos[${idx}][vacuna_id]" id="bv_h_${idx}">
            <input type="text" id="bv_i_${idx}" placeholder="Buscar vacuna..." autocomplete="off"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
            <div id="bv_stock_${idx}" class="hidden mt-1 text-xs font-medium px-1"></div>
        </td>
        <td class="px-2 py-2 align-top">
            <input type="hidden" name="despachos[${idx}][modulo_id]" id="bm_h_${idx}">
            <input type="text" id="bm_i_${idx}" placeholder="Buscar módulo..." autocomplete="off"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
        </td>
        <td class="px-2 py-2 align-top">
            <input type="hidden" name="despachos[${idx}][responsable_envio]" id="br_h_${idx}">
            <input type="text" id="br_i_${idx}" placeholder="Responsable..." autocomplete="off"
                class="bulk-resp-input bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
        </td>
        <td class="px-2 py-2 align-top">
            <input type="date" name="despachos[${idx}][fecha_envio]"
                value="${new Date().toISOString().split('T')[0]}"
                max="${new Date().toISOString().split('T')[0]}"
                class="bulk-fecha-input bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
        </td>
        <td class="px-2 py-2 align-top">
            <select name="despachos[${idx}][lote]" id="blote_${idx}" required disabled
                class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 disabled:opacity-60">
                <option value="">— Elige vacuna —</option>
            </select>
        </td>
        <td class="px-2 py-2 align-top">
            <input type="number" name="despachos[${idx}][cantidad]" id="bcant_${idx}" min="1" placeholder="0"
                oninput="bulkValidarCantidad(${idx}, this.value)"
                class="bulk-cantidad bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
        </td>
        <td class="px-2 py-2 align-middle text-center">
            <button type="button" onclick="bulkEliminarFila(${idx})"
                class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
            </button>
        </td>`;
    }

    function bulkInitRow(idx) {
        const viInput = document.getElementById(`bv_i_${idx}`);
        const vhInput = document.getElementById(`bv_h_${idx}`);
        const miInput = document.getElementById(`bm_i_${idx}`);
        const mhInput = document.getElementById(`bm_h_${idx}`);
        const riInput = document.getElementById(`br_i_${idx}`);
        const rhInput = document.getElementById(`br_h_${idx}`);

        const onVacunaSelect = (id) => {
            vhInput.value = id;
            bulkCargarLotes(idx, id);
        };
        viInput.addEventListener('focus', () => openFloatingDD(viInput, vhInput, VACUNAS, renderVacuna, onVacunaSelect));
        viInput.addEventListener('input', () => {
            if (!viInput.value) {
                vhInput.value = '';
                document.getElementById(`bv_stock_${idx}`).classList.add('hidden');
                const loteSelect = document.getElementById(`blote_${idx}`);
                loteSelect.innerHTML = '<option value="">— Elige vacuna —</option>';
                loteSelect.disabled = true;
            }
            if (!floatDD.classList.contains('hidden') && activeInput === viInput) {
                renderFloatItems(VACUNAS, viInput.value, renderVacuna, onVacunaSelect);
            } else {
                openFloatingDD(viInput, vhInput, VACUNAS, renderVacuna, onVacunaSelect);
            }
        });

        const onModuloSelect = (id) => { mhInput.value = id; };
        miInput.addEventListener('focus', () => openFloatingDD(miInput, mhInput, MODULOS, renderModulo, onModuloSelect));
        miInput.addEventListener('input', () => {
            if (!miInput.value) mhInput.value = '';
            if (!floatDD.classList.contains('hidden') && activeInput === miInput) {
                renderFloatItems(MODULOS, miInput.value, renderModulo, onModuloSelect);
            } else {
                openFloatingDD(miInput, mhInput, MODULOS, renderModulo, onModuloSelect);
            }
        });

        const onRespSelect = (id) => { rhInput.value = id; };
        riInput.addEventListener('focus', () => openFloatingDD(riInput, rhInput, PERSONAL, renderPersonal, onRespSelect));
        riInput.addEventListener('input', () => {
            if (!riInput.value) rhInput.value = '';
            if (!floatDD.classList.contains('hidden') && activeInput === riInput) {
                renderFloatItems(PERSONAL, riInput.value, renderPersonal, onRespSelect);
            } else {
                openFloatingDD(riInput, rhInput, PERSONAL, renderPersonal, onRespSelect);
            }
        });

        // Listener de lote del bulk
        document.getElementById(`blote_${idx}`).addEventListener('change', function() {
            const opt  = this.options[this.selectedIndex];
            const disp = parseInt(opt?.dataset?.disponible) || 0;
            const cantInput = document.getElementById(`bcant_${idx}`);
            cantInput.max = disp > 0 ? disp : '';
            if (disp > 0) stockCache[vhInput.value + '_lote'] = disp;
        });
    }

    function bulkCargarLotes(idx, vacunaId) {
        const stockEl    = document.getElementById(`bv_stock_${idx}`);
        const loteSelect = document.getElementById(`blote_${idx}`);
        if (!vacunaId) {
            stockEl.classList.add('hidden');
            loteSelect.innerHTML = '<option value="">— Elige vacuna —</option>';
            loteSelect.disabled  = true;
            return;
        }

        loteSelect.innerHTML = '<option value="">Cargando...</option>';
        loteSelect.disabled  = true;
        stockEl.classList.add('hidden');

        fetch(`${STOCK_URL}?vacuna_id=${vacunaId}`)
            .then(r => r.json())
            .then(d => {
                stockCache[vacunaId] = d.stock;

                // Mostrar stock
                stockEl.classList.remove('hidden');
                if (d.stock === 0) {
                    stockEl.className   = 'mt-1 text-xs font-medium px-1 text-red-600';
                    stockEl.textContent = '⚠ Sin stock disponible';
                } else if (d.stock <= 50) {
                    stockEl.className   = 'mt-1 text-xs font-medium px-1 text-orange-500';
                    stockEl.textContent = `Stock bajo: ${d.stock.toLocaleString()} dosis`;
                } else {
                    stockEl.className   = 'mt-1 text-xs font-medium px-1 text-green-600';
                    stockEl.textContent = `Disponible: ${d.stock.toLocaleString()} dosis`;
                }

                // Poblar lotes
                loteSelect.innerHTML = '<option value="">— Selecciona lote —</option>';
                if (d.lotes && d.lotes.length > 0) {
                    d.lotes.forEach(lote => {
                        const opt = document.createElement('option');
                        opt.value = lote.lote ?? '';
                        const vence = lote.fecha_vencimiento ? ` · ${lote.fecha_vencimiento}` : '';
                        opt.textContent        = `${lote.lote} (${lote.disponible} disp.${vence})`;
                        opt.dataset.disponible = lote.disponible;
                        loteSelect.appendChild(opt);
                    });
                    loteSelect.disabled = false;
                } else {
                    loteSelect.innerHTML = '<option value="">Sin lotes disponibles</option>';
                    loteSelect.disabled  = true;
                }
            })
            .catch(() => {
                loteSelect.innerHTML = '<option value="">Error al cargar</option>';
                loteSelect.disabled  = true;
            });
    }

    function bulkValidarCantidad(idx, val) {
        const cant     = parseInt(val) || 0;
        const vacunaId = document.getElementById(`bv_h_${idx}`)?.value;
        const stock    = vacunaId ? (stockCache[vacunaId] ?? null) : null;
        const input    = document.getElementById(`bcant_${idx}`);
        if (stock !== null && cant > stock) {
            input?.classList.add('border-red-500');
            input?.classList.remove('border-gray-300');
        } else {
            input?.classList.remove('border-red-500');
            input?.classList.add('border-gray-300');
        }
        bulkActualizarContadores();
    }

    function bulkEliminarFila(idx) {
        const filas = document.querySelectorAll('#bulk_tbody tr');
        if (filas.length <= 1) { alert('Debe haber al menos una fila.'); return; }
        document.getElementById(`brow_${idx}`)?.remove();
        bulkActualizarContadores();
    }

    function bulkCopiarFecha() {
        const primera = document.querySelector('.bulk-fecha-input');
        if (!primera?.value) { alert('Ingresa la fecha en la primera fila.'); return; }
        document.querySelectorAll('.bulk-fecha-input').forEach(f => f.value = primera.value);
    }

    function bulkCopiarResponsable() {
        const primerInput  = document.querySelector('.bulk-resp-input');
        const primerHidden = document.getElementById('br_h_0');
        if (!primerHidden?.value) { alert('Selecciona el responsable en la primera fila.'); return; }
        let idx = 0;
        document.querySelectorAll('.bulk-resp-input').forEach(inp => {
            inp.value = primerInput.value;
            const hid = document.getElementById(`br_h_${idx}`);
            if (hid) hid.value = primerHidden.value;
            idx++;
        });
    }

    function bulkAgregarFila() {
        const idx = bulkRowIdx++;
        const tr  = document.createElement('tr');
        tr.id       = `brow_${idx}`;
        tr.className = 'border-b border-gray-100 hover:bg-gray-50';
        tr.innerHTML = bulkBuildRow(idx);
        document.getElementById('bulk_tbody').appendChild(tr);
        bulkInitRow(idx);
        bulkActualizarContadores();
    }

    function bulkActualizarContadores() {
        let total = 0;
        document.querySelectorAll('.bulk-cantidad').forEach(c => total += parseInt(c.value) || 0);
        document.getElementById('bulk_total').textContent = total.toLocaleString() + ' dosis';
        const rows = document.querySelectorAll('#bulk_tbody tr').length;
        document.getElementById('bulk_row_count').textContent = rows + (rows === 1 ? ' fila' : ' filas');
    }

    // Iniciar con una fila
    bulkAgregarFila();
</script>
@endpush
@endsection