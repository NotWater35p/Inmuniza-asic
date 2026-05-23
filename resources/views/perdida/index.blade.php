@extends('layouts.app')
@section('title', 'Historial de Pérdidas')

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl space-y-4 bg-white/80 rounded-lg backdrop-blur-lg shadow-sm">

    {{-- ── HEADER ──────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-gray-900 flex items-center gap-2.5">
                <div class="p-2 bg-red-600 rounded-lg text-white shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                </div>
                Historial de Pérdidas
            </h1>
            <p class="text-sm text-gray-400 mt-0.5 ml-10">ASIC y módulos afiliados · {{ $perdidas->total() }} registro(s)</p>
        </div>
        <a href="{{ route('inventario.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Volver al Inventario
        </a>
    </div>

    {{-- Alerta éxito --}}
    @if(session('success'))
        <div id="alert-success" class="flex items-center justify-between gap-3 p-4 text-sm text-green-800 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.closest('[id]').remove()"
                class="shrink-0 p-1 rounded-lg hover:bg-green-100 text-green-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
    @endif

    {{-- ── FILTROS ──────────────────────────────────────────────── --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4">
        <form method="GET" action="{{ route('perdida.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="min-w-[150px] flex-1">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Vacuna</label>
                <select name="p_vacuna" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-red-300 focus:border-red-500 block w-full p-2 transition-colors">
                    <option value="">Todas</option>
                    @foreach($vacunas as $v)
                    <option value="{{ $v->id }}" @selected(request('p_vacuna') == $v->id)>{{ $v->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[140px]">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Origen</label>
                <select name="p_modulo" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-red-300 focus:border-red-500 block w-full p-2 transition-colors">
                    <option value="">Todos</option>
                    <option value="asic" @selected(request('p_modulo') === 'asic')>ASIC (sin módulo)</option>
                    @foreach($modulos as $m)
                    <option value="{{ $m->id }}" @selected(request('p_modulo') == $m->id)>{{ $m->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[120px]">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Motivo</label>
                <select name="p_motivo" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-red-300 focus:border-red-500 block w-full p-2 transition-colors">
                    <option value="">Todos</option>
                    @foreach(\App\Models\Perdida::MOTIVOS as $m)
                    <option value="{{ $m }}" @selected(request('p_motivo') === $m)>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[130px]">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Desde</label>
                <input type="date" name="p_desde" value="{{ request('p_desde') }}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-red-300 focus:border-red-500 block w-full p-2 transition-colors">
            </div>
            <div class="min-w-[130px]">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Hasta</label>
                <input type="date" name="p_hasta" value="{{ request('p_hasta') }}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-red-300 focus:border-red-500 block w-full p-2 transition-colors">
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="flex items-center gap-1.5 px-3 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    Filtrar
                </button>
                @if(request('p_vacuna') || request('p_modulo') || request('p_motivo') || request('p_desde') || request('p_hasta'))
                <a href="{{ route('perdida.index') }}"
                    class="flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    Limpiar
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ── TABLA ────────────────────────────────────────────────── --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        @if($perdidas->isEmpty())
        <div class="py-16 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-3 text-gray-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
            <p class="text-sm font-semibold text-gray-400">No hay pérdidas con estos filtros</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Vacuna</th>
                        <th class="px-4 py-3 font-semibold">Origen</th>
                        <th class="px-4 py-3 font-semibold">Lote</th>
                        <th class="px-4 py-3 font-semibold text-center">Cantidad</th>
                        <th class="px-4 py-3 font-semibold">Motivo</th>
                        <th class="px-4 py-3 font-semibold">Fecha</th>
                        <th class="px-4 py-3 font-semibold">Observación</th>
                        <th class="px-4 py-3 w-10"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($perdidas as $perdida)
                    @php
                        $motivoColor = match($perdida->motivo) {
                            'Vencimiento'    => 'bg-orange-100 text-orange-700 border-orange-200',
                            'Rotura'         => 'bg-red-100 text-red-700 border-red-200',
                            'Cadena de frío' => 'bg-blue-100 text-blue-700 border-blue-200',
                            default          => 'bg-gray-100 text-gray-600 border-gray-200',
                        };
                        $urlEliminar = route('perdida.destroy', $perdida->id);
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3.5 font-semibold text-gray-900">{{ $perdida->vacuna?->nombre ?? '—' }}</td>
                        <td class="px-4 py-3.5">
                            @if($perdida->modulo_id)
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-violet-100 text-violet-700 border border-violet-200">
                                    {{ $perdida->modulo?->nombre ?? 'Módulo' }}
                                </span>
                            @else
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 border border-blue-200">ASIC</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5">
                            @if($perdida->lote)
                                <span class="font-mono text-xs bg-gray-100 px-2 py-0.5 rounded border border-gray-200 text-gray-600">{{ $perdida->lote }}</span>
                            @else
                                <span class="text-gray-400 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-100 text-red-700 font-bold text-xs">
                                {{ $perdida->cantidad }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5">
                            <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full border {{ $motivoColor }}">
                                {{ $perdida->motivo }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 text-gray-500 text-xs whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($perdida->fecha)->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3.5 text-gray-400 text-xs max-w-xs truncate">
                            {{ $perdida->observacion ?? '—' }}
                        </td>
                        {{-- Dropdown ··· --}}
                            <td class="px-3 py-3">
                                <button type="button" onclick="abrirModalPerdida('{{ $urlEliminar }}')"
                                    class="w-8 h-8 inline-flex items-center justify-center rounded-full border-2 border-gray-100 text-red-300 hover:border-danger hover:bg-danger hover:text-white transition-colors font-bold text-sm leading-none">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6" />
                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                        <path d="M10 11v6" />
                                        <path d="M14 11v6" />
                                    </svg>
                                </button>
                            </td>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($perdidas->hasPages())
        <div class="px-5 py-3 border-t border-gray-100">
            {{ $perdidas->withQueryString()->links() }}
        </div>
        @endif
        @endif
    </div>

</div>

{{-- Modal de confirmación --}}
@include('components.modal-eliminar-perdida')

@endsection