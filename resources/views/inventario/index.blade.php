@extends('layouts.app')
@section('title', 'Inventario General')

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl bg-white/90 rounded-lg shadow-sm backdrop-blur-sm">
        <div class="bg-white relative shadow-md sm:rounded-lg overflow-hidden">

            {{-- Cabecera --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                <div class="flex-1 flex items-center gap-2.5">
                    <h1 class="text-xl font-bold text-red-500 flex items-center gap-2.5">
                        <div class="p-2 bg-red-500 rounded text-white shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clipboard-list-icon lucide-clipboard-list"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/></svg>
                        </div>
                        Inventario General
                    </h1>
                    <span class="text-sm text-gray-400 bg-gray-100 px-2 py-0.5 rounded-lg font-medium">
                        {{ $vacunas->total() }} {{ $vacunas->total() == 1 ? 'Producto' : 'Productos' }}
                    </span>
                </div>
            </div>

            {{-- Barra: búsqueda + botón --}}
            <div class="flex flex-col md:flex-row items-stretch md:items-center md:space-x-3 space-y-3 md:space-y-0 justify-between mx-4 py-4 border-t border-gray-100">
                <div class="w-full md:w-1/2">
                    <form method="GET" action="{{ route('inventario.index') }}">
                        <label for="buscar" class="sr-only">Buscar</label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                            </div>
                            <input type="text" name="buscar" id="buscar"
                                value="{{ request('buscar') }}"
                                placeholder="Buscar vacuna o insumo..."
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2">
                        </div>
                    </form>
                </div>
                <div class="w-full md:w-auto flex justify-end">
                    <a type="button" href="{{ route('perdida.index') }}"
                        class="inline-flex items-center justify-center gap-1.5 rounded-lg  text-danger bg-danger-soft box-border border border-danger-subtle hover:bg-danger hover:text-white focus:ring-4 focus:ring-danger-medium shadow-xs font-medium leading-5 text-sm px-4 py-2.5 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-triangle-alert-icon lucide-triangle-alert"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                        Registro de Perdidas
                    </a>
                </div>
            </div>

            {{-- Alertas --}}
            @if(session('success'))
            <div class="mx-4 mb-3 flex items-center gap-2 p-3 text-sm text-green-800 bg-green-50 border border-green-200 rounded-lg">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('success') }}
            </div>
            @endif
            @if($errors->any())
            <div class="mx-4 mb-3 flex items-start gap-2 p-3 text-sm text-red-800 bg-red-50 border border-red-200 rounded-lg">
                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                <ul class="list-disc list-inside space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            {{-- Tabla --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-y border-gray-100">
                        <tr>
                            <th scope="col" class="px-4 py-3">Producto / Vacuna</th>
                            <th scope="col" class="px-4 py-3 text-center hidden sm:table-cell">Despachado</th>
                            <th scope="col" class="px-4 py-3 text-center hidden sm:table-cell">Pérdidas ASIC</th>
                            <th scope="col" class="px-4 py-3 text-center">Disponible</th>
                            <th scope="col" class="px-4 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vacunas as $v)
                        @php
                            $disp = $v->stock_actual;
                            $desp = $v->total_despachado;
                            $perd = $v->total_perdido;
                            $pct  = $desp > 0 ? round(($disp / $desp) * 100) : ($disp > 0 ? 100 : 0);
                            [$badgeBg, $badgeText] = match(true) {
                                $disp === 0 => ['bg-red-100', 'text-red-700'],
                                $pct < 25   => ['bg-orange-100', 'text-orange-700'],
                                $pct < 60   => ['bg-yellow-100', 'text-yellow-700'],
                                default     => ['bg-green-100', 'text-green-700'],
                            };
                        @endphp
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">

                            {{-- Producto --}}
                            <th scope="row" class="px-4 py-3 font-medium text-gray-900">
                                <div class="flex items-center gap-3">
                                    <div class="p-1.5 bg-blue-50 rounded-lg shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 2 4 4"/><path d="m17 7 3-3"/><path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5"/><path d="m9 11 4 4"/><path d="m5 19-3 3"/><path d="m14 4 6 6"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800 leading-tight">{{ $v->nombre }}</p>
                                        @if($v->marca)
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $v->marca->nombre }}</p>
                                        @endif
                                    </div>
                                </div>
                            </th>

                            {{-- Despachado --}}
                            <td class="px-4 py-3 text-center hidden sm:table-cell">
                                <span class="font-mono text-sm font-medium text-gray-700">{{ number_format($desp) }}</span>
                            </td>

                            {{-- Pérdidas --}}
                            <td class="px-4 py-3 text-center hidden sm:table-cell">
                                @if($perd > 0)
                                    <span class="font-mono text-sm font-semibold text-red-500">{{ number_format($perd) }}</span>
                                @else
                                    <span class="text-gray-300 font-mono text-sm">0</span>
                                @endif
                            </td>

                            {{-- Disponible --}}
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $badgeBg }} {{ $badgeText }}">
                                    {{ number_format($disp) }}
                                </span>
                                {{-- @if($v->stock_vencido > 0)
                                <p class="text-xs text-orange-500 mt-1 font-medium leading-none">
                                    +{{ $v->stock_vencido }} vencido(s)
                                </p>
                                @endif --}}
                            </td>

                            {{-- Acciones --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-2">

                                    {{-- ! Registrar pérdida --}}
                                    <div class="relative group/perdida">
                                        <button type="button"
                                            onclick="abrirModalPerdida({{ $v->id }}, '{{ addslashes($v->nombre) }}')"
                                            class="w-8 h-8 inline-flex items-center justify-center rounded-full border border-gray-200 text-gray-400 box-border hover:bg-danger-subtle hover:text-danger hover:border-danger focus:ring-4 focus:ring-danger-medium px-4 py-2.5 focus:outline-none transition-colors font-bold text-sm leading-none">
                                            !
                                        </button>
                                        <div class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover/perdida:block z-20">
                                            <div class="bg-gray-800 text-white text-xs rounded-md px-2.5 py-1.5 whitespace-nowrap shadow-lg">Registrar pérdida</div>
                                            <div class="w-2 h-2 bg-gray-800 rotate-45 mx-auto -mt-1"></div>
                                        </div>
                                    </div>

                                    {{-- Campana vencimientos --}}
                                    <div class="relative group/venc">
                                        <button type="button"
                                            onclick="verVencimientos({{ $v->id }}, '{{ addslashes($v->nombre) }}')"
                                            class="w-8 h-8 inline-flex items-center justify-center rounded-full border transition-colors
                                                {{ $v->has_vencidos
                                                    ? 'border-orange-400 text-orange-500 bg-orange-50 hover:bg-orange-100'
                                                    : 'border-gray-200 text-gray-400 hover:border-gray-300 hover:bg-gray-50' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                                        </button>
                                        <div class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover/venc:block z-20">
                                            <div class="bg-gray-800 text-white text-xs rounded-md px-2.5 py-1.5 whitespace-nowrap shadow-lg">
                                                {{ $v->has_vencidos ? '⚠ Lotes vencidos' : 'Ver vencimientos' }}
                                            </div>
                                            <div class="w-2 h-2 bg-gray-800 rotate-45 mx-auto -mt-1"></div>
                                        </div>
                                    </div>

                                    {{-- Lotes --}}
                                    <button type="button"
                                        onclick="verLotes({{ $v->id }}, '{{ addslashes($v->nombre) }}')"
                                        class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg px-2.5 py-1.5 transition-colors whitespace-nowrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/></svg>
                                        Lotes
                                    </button>

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-14 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto mb-3 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"/><path d="M12 22V12"/></svg>
                                <p class="text-sm text-gray-400">No hay vacunas en el inventario.</p>
                                @if(request('buscar'))
                                    <a href="{{ route('inventario.index') }}" class="text-xs text-blue-500 hover:underline mt-1 inline-block">Limpiar búsqueda</a>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if($vacunas->hasPages())
            <nav class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4 border-t border-gray-100">
                <span class="text-sm font-normal text-gray-500">
                    Mostrando
                    <span class="font-semibold text-gray-900">{{ $vacunas->firstItem() }}–{{ $vacunas->lastItem() }}</span>
                    de
                    <span class="font-semibold text-gray-900">{{ $vacunas->total() }}</span>
                </span>
                {{ $vacunas->withQueryString()->links() }}
            </nav>
            @endif

        </div>
    </div>



{{-- MODAL: Registrar Pérdida --}}
<div id="modalPerdida" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                Registrar Pérdida del ASIC
            </h3>
            <button onclick="cerrarModalPerdida()" class="p-1.5 text-gray-400 hover:bg-gray-100 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('inventario.storePerdida') }}" class="p-5 space-y-4">
            @csrf
            <div>
                <label for="p_vacuna_id" class="block mb-1.5 text-sm font-medium text-gray-700">Vacuna / Insumo <span class="text-red-500">*</span></label>
                <select name="vacuna_id" id="p_vacuna_id" required
                    class="bg-gray-50 border {{ $errors->has('vacuna_id') ? 'border-red-400' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5">
                    <option value="">— Seleccionar vacuna —</option>
                    @foreach($todasLasVacunas as $tv)
                        <option value="{{ $tv->id }}" {{ old('vacuna_id') == $tv->id ? 'selected' : '' }}>{{ $tv->nombre }}</option>
                    @endforeach
                </select>
                @error('vacuna_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="p_cantidad" class="block mb-1.5 text-sm font-medium text-gray-700">Cantidad <span class="text-red-500">*</span></label>
                    <input type="number" name="cantidad" id="p_cantidad" value="{{ old('cantidad') }}" min="1" step="1" required placeholder="0"
                        class="bg-gray-50 border {{ $errors->has('cantidad') ? 'border-red-400' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5">
                    @error('cantidad')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="p_motivo" class="block mb-1.5 text-sm font-medium text-gray-700">Motivo <span class="text-red-500">*</span></label>
                    <select name="motivo" id="p_motivo" required
                        class="bg-gray-50 border {{ $errors->has('motivo') ? 'border-red-400' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5">
                        <option value="">— Motivo —</option>
                        @foreach(\App\Models\Perdida::MOTIVOS as $mot)
                            <option value="{{ $mot }}" {{ old('motivo') == $mot ? 'selected' : '' }}>{{ $mot }}</option>
                        @endforeach
                    </select>
                    @error('motivo')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="p_lote" class="block mb-1.5 text-sm font-medium text-gray-700">
                        Lote
                        <span id="p_lote_cargando" class="hidden text-xs text-blue-500 font-normal ml-1">cargando...</span>
                    </label>
                    <select name="lote" id="p_lote"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5">
                        <option value="">— Selecciona una vacuna primero —</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-400">Solo lotes con stock disponible</p>
                </div>
                <div>
                    <label for="p_fecha" class="block mb-1.5 text-sm font-medium text-gray-700">Fecha <span class="text-red-500">*</span></label>
                    <input type="date" name="fecha" id="p_fecha" value="{{ old('fecha', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required
                        class="bg-gray-50 border {{ $errors->has('fecha') ? 'border-red-400' : 'border-gray-300' }} text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5">
                    @error('fecha')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label for="p_observacion" class="block mb-1.5 text-sm font-medium text-gray-700">Observación</label>
                <textarea name="observacion" id="p_observacion" rows="2" maxlength="500" placeholder="Detalles adicionales..."
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5">{{ old('observacion') }}</textarea>
            </div>
            <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                <button type="button" onclick="cerrarModalPerdida()"
                    class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancelar
                </button>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Registrar Pérdida
                </button>
            </div>
        </form>
    </div>
</div>


{{-- MODAL: Ver Lotes --}}
<div id="modalLotes" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-xl">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/></svg>
                <span id="modalLotesTitulo">Lotes</span>
            </h3>
            <button onclick="cerrarModalLotes()" class="p-1.5 text-gray-400 hover:bg-gray-100 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <div class="p-5 max-h-[70vh] overflow-y-auto">
            <div id="modalLotesBody"></div>
        </div>
    </div>
</div>


{{-- MODAL: Vencimientos --}}
<div id="modalVenc" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-orange-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                <span id="modalVencTitulo">Vencimientos</span>
            </h3>
            <button onclick="cerrarModalVenc()" class="p-1.5 text-gray-400 hover:bg-gray-100 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <div class="p-5 max-h-[70vh] overflow-y-auto">
            <div id="modalVencBody"></div>
        </div>
    </div>
</div>


@push('scripts')
<script>
function fmtFecha(f) {
    if (!f) return '—';
    const s = String(f).substring(0, 10);
    const p = s.split('-');
    return p.length === 3 ? `${p[2]}/${p[1]}/${p[0]}` : s;
}

function spinnerHTML() {
    return `<div class="flex items-center justify-center gap-2 py-10 text-gray-400">
        <svg class="animate-spin w-5 h-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
        </svg>
        <span class="text-sm">Cargando...</span>
    </div>`;
}

// ── Modal Pérdida ────────────────────────────────────────────────────
function cargarLotesParaPerdida(vacunaId) {
    const sel      = document.getElementById('p_lote');
    const spinner  = document.getElementById('p_lote_cargando');

    sel.innerHTML = '<option value="">Cargando...</option>';
    sel.disabled  = true;
    spinner.classList.remove('hidden');

    if (!vacunaId) {
        sel.innerHTML = '<option value="">— Selecciona una vacuna primero —</option>';
        sel.disabled  = false;
        spinner.classList.add('hidden');
        return;
    }

    fetch(`/inventario/lotes/${vacunaId}`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        const vigentes = (data.lotes || []).filter(l => !l.vencido && (l.disponible ?? 0) > 0);
        sel.innerHTML = '<option value="">— Sin lote específico —</option>';
        if (vigentes.length > 0) {
            vigentes.forEach(l => {
                const vence = l.fecha_vencimiento ? ` · Vence: ${fmtFecha(l.fecha_vencimiento)}` : '';
                const opt   = document.createElement('option');
                opt.value   = l.lote;
                opt.textContent = `${l.lote} (${l.disponible} disp.${vence})`;
                sel.appendChild(opt);
            });
        } else {
            const opt = document.createElement('option');
            opt.disabled = true;
            opt.textContent = 'Sin lotes vigentes disponibles';
            sel.appendChild(opt);
        }
    })
    .catch(() => {
        sel.innerHTML = '<option value="">Error al cargar lotes</option>';
    })
    .finally(() => {
        sel.disabled = false;
        spinner.classList.add('hidden');
    });
}

function abrirModalPerdida(vacunaId = null, vacunaNombre = null) {
    const sel = document.getElementById('p_vacuna_id');
    if (vacunaId && sel) {
        sel.value = vacunaId;
        cargarLotesParaPerdida(vacunaId);
    } else {
        document.getElementById('p_lote').innerHTML =
            '<option value="">— Selecciona una vacuna primero —</option>';
    }
    document.getElementById('modalPerdida').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function cerrarModalPerdida() {
    document.getElementById('modalPerdida').classList.add('hidden');
    document.body.style.overflow = '';
    // Limpiar select de lotes al cerrar
    document.getElementById('p_lote').innerHTML =
        '<option value="">— Selecciona una vacuna primero —</option>';
}


// ── Modal Lotes ──────────────────────────────────────────────────────
function verLotes(id, nombre) {
    document.getElementById('modalLotesTitulo').textContent = nombre + ' — Lotes';
    document.getElementById('modalLotesBody').innerHTML = spinnerHTML();
    document.getElementById('modalLotes').classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    fetch(`/inventario/lotes/${id}`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        const lotes = data.lotes || [];
        if (!lotes.length) {
            document.getElementById('modalLotesBody').innerHTML =
                '<p class="text-center text-gray-400 py-8 text-sm">No hay lotes registrados.</p>';
            return;
        }

        // Solo lotes vigentes — los vencidos van a la campana
        const vigentes = lotes.filter(l => !l.vencido);

        const thead = `<thead><tr class="text-xs text-gray-400 uppercase border-b border-gray-100">
            <th class="pb-2 pr-2 text-left">Lote</th>
            <th class="pb-2 px-2 text-center">Vence</th>
            <th class="pb-2 px-2 text-center">Entrado</th>
            <th class="pb-2 px-2 text-center">Despachado</th>
            <th class="pb-2 px-2 text-center">Perdido</th>
            <th class="pb-2 pl-2 text-center">Disponible</th>
        </tr></thead>`;

        const fila = l => `<tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50">
            <td class="py-2.5 pr-2 font-mono text-xs font-medium text-gray-700">${l.lote || '—'}</td>
            <td class="py-2.5 px-2 text-center text-xs">${fmtFecha(l.fecha_vencimiento)}</td>
            <td class="py-2.5 px-2 text-center font-mono text-xs">${l.entrado ?? 0}</td>
            <td class="py-2.5 px-2 text-center font-mono text-xs text-orange-600">${l.despachado ?? 0}</td>
            <td class="py-2.5 px-2 text-center font-mono text-xs text-red-500">${l.perdido ?? 0}</td>
            <td class="py-2.5 pl-2 text-center">
                <span class="px-2 py-0.5 rounded-full text-xs font-bold ${(l.disponible ?? 0) > 0 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'}">
                    ${l.disponible ?? 0}
                </span>
            </td>
        </tr>`;

        const body = vigentes.length
            ? vigentes.map(l => fila(l)).join('')
            : '<tr><td colspan="6" class="py-8 text-center text-sm text-gray-400">Sin lotes vigentes disponibles.</td></tr>';

        document.getElementById('modalLotesBody').innerHTML =
            `<div class="overflow-x-auto"><table class="w-full text-sm">${thead}<tbody>${body}</tbody></table></div>`;
    })
    .catch(() => {
        document.getElementById('modalLotesBody').innerHTML =
            '<p class="text-center text-red-500 py-6 text-sm">Error al cargar los lotes.</p>';
    });
}
function cerrarModalLotes() {
    document.getElementById('modalLotes').classList.add('hidden');
    document.getElementById('modalLotesBody').innerHTML = '';
    document.body.style.overflow = '';
}

// ── Modal Vencimientos ───────────────────────────────────────────────
function verVencimientos(id, nombre) {
    document.getElementById('modalVencTitulo').textContent = nombre + ' — Vencimientos';
    document.getElementById('modalVencBody').innerHTML = spinnerHTML();
    document.getElementById('modalVenc').classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    fetch(`/inventario/lotes/${id}`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        const hoy = new Date().toISOString().substring(0, 10);
        const vencidos = (data.lotes || []).filter(l =>
            l.vencido || (l.fecha_vencimiento && String(l.fecha_vencimiento).substring(0, 10) < hoy)
        );

        if (!vencidos.length) {
            document.getElementById('modalVencBody').innerHTML = `
                <div class="py-10 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mx-auto mb-2 text-green-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <p class="text-sm font-medium text-gray-500">Sin lotes vencidos</p>
                    <p class="text-xs text-gray-400 mt-1">Todos los lotes están vigentes.</p>
                </div>`;
            return;
        }

        const rows = vencidos.map(l => `
            <tr class="border-b border-red-50 last:border-0 bg-red-50/30">
                <td class="py-2.5 pr-3"><span class="font-mono text-xs font-semibold text-gray-800">${l.lote || '—'}</span></td>
                <td class="py-2.5 px-3 text-center"><span class="text-xs font-semibold text-red-600">${fmtFecha(l.fecha_vencimiento)}</span></td>
                <td class="py-2.5 pl-3 text-center">
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold ${(l.disponible ?? 0) > 0 ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-400'}">
                        ${l.disponible ?? 0} disp.
                    </span>
                </td>
            </tr>`).join('');

        document.getElementById('modalVencBody').innerHTML = `
            <div class="mb-3 flex items-start gap-2 text-sm text-orange-700 bg-orange-50 border border-orange-200 rounded-lg px-3 py-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                <span>Los lotes vencidos <strong>no cuentan en stock</strong>. Si tienen unidades restantes, regístralas como pérdida por vencimiento.</span>
            </div>
            <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="text-xs text-gray-400 uppercase border-b border-gray-100">
                    <th class="pb-2 pr-3 text-left">Lote</th>
                    <th class="pb-2 px-3 text-center">Venció el</th>
                    <th class="pb-2 pl-3 text-center">Stock vencido</th>
                </tr></thead>
                <tbody>${rows}</tbody>
            </table>
            </div>`;
    })
    .catch(() => {
        document.getElementById('modalVencBody').innerHTML =
            '<p class="text-center text-red-500 py-6 text-sm">Error al cargar los datos.</p>';
    });
}
function cerrarModalVenc() {
    document.getElementById('modalVenc').classList.add('hidden');
    document.getElementById('modalVencBody').innerHTML = '';
    document.body.style.overflow = '';
}

// ── Cerrar con clic fuera ────────────────────────────────────────────
// Listener global: cerrar modales al clic fuera + cambio de vacuna en modal pérdida
document.addEventListener('DOMContentLoaded', () => {
    // Cerrar al hacer clic en el backdrop
    [
        { id: 'modalPerdida', fn: cerrarModalPerdida },
        { id: 'modalLotes',   fn: cerrarModalLotes   },
        { id: 'modalVenc',    fn: cerrarModalVenc     },
    ].forEach(({ id, fn }) => {
        document.getElementById(id)?.addEventListener('click', e => {
            if (e.target.id === id) fn();
        });
    });

    // Escuchar cambio manual de vacuna en el modal
    document.getElementById('p_vacuna_id')?.addEventListener('change', function () {
        cargarLotesParaPerdida(this.value || null);
    });

    // Re-abrir modal si hubo errores de validación
    @if($errors->any() && old('vacuna_id'))
    abrirModalPerdida({{ (int) old('vacuna_id') }});
    @endif
});
</script>
@endpush
@endsection