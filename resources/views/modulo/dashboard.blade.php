@extends('layouts.app')
@section('title', 'Mi Módulo · ' . ($modulo?->nombre ?? 'Sin asignar'))

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl">

    @if(isset($sinModulo) && $sinModulo)
    <div class="flex flex-col items-center justify-center py-24 text-center text-gray-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-300 mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 7v4"/><path d="M14 21v-3a2 2 0 0 0-4 0v3"/><path d="M14 9h-4"/><path d="M18 11h2a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-9a2 2 0 0 1 2-2h2"/><path d="M18 21V5a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16"/></svg>
        <h2 class="text-xl font-semibold text-gray-600">No tienes un módulo asignado</h2>
        <p class="text-sm mt-2">Contacta al administrador para que te asigne como Jefe de Módulo.</p>
    </div>
    @else

    {{-- Header --}}
    <div class="mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-blue-900 flex items-center gap-3">
                <div class="p-2 bg-blue-800 rounded-lg text-white shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 7v4"/><path d="M14 21v-3a2 2 0 0 0-4 0v3"/><path d="M14 9h-4"/><path d="M18 11h2a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-9a2 2 0 0 1 2-2h2"/><path d="M18 21V5a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16"/></svg>
                </div>
                {{ $modulo->nombre }}
            </h1>
            <p class="text-sm text-gray-400 mt-1 ml-12">
                {{ $modulo->asic->nombre ?? '' }}
                @if($modulo->parroquia) · {{ $modulo->parroquia }} @endif
                @if($modulo->tipo_establecimiento)
                    <span class="ml-1 px-1.5 py-0.5 bg-gray-100 text-gray-500 text-xs rounded">{{ $modulo->tipo_establecimiento }}</span>
                @endif
            </p>
        </div>
        <div class="flex flex-wrap gap-2 shrink-0">
            <a href="{{ route('jornadas.create') }}"
                class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M10 16h4"/><path d="M12 14v4"/></svg>
                Nueva Jornada
            </a>
            <a href="{{ route('descargo.create') }}"
                class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white bg-orange-500 rounded-lg hover:bg-orange-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><path d="M13 2v7h7"/><path d="M12 12v6"/><path d="M9 15h6"/></svg>
                Descargo Rápido
            </a>
            <a href="{{ route('modulo.reporte.index', $modulo->id) }}"
                class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M15 18H9"/><path d="M15 14H9"/><path d="M6 22h12a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2z"/></svg>
                Reporte Mensual
            </a>
        </div>
    </div>

    {{-- ═══ STAT CARDS ════════════════════════════════════════════ --}}
    @php
    $cards = [
        [
            'label'    => 'Jornadas realizadas',
            'valor'    => $stats['total_jornadas'],
            'sub'      => 'total acumulado',
            'from'     => 'from-blue-400',
            'to'       => 'to-blue-600',
            'ring'     => 'ring-blue-200',
            'num'      => 'text-blue-600',
            'icon'     => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M10 16h4"/><path d="M12 14v4"/></svg>',
        ],
        [
            'label'    => 'Dosis recibidas',
            'valor'    => $stats['dosis_recibidas'],
            'sub'      => 'del ASIC',
            'from'     => 'from-emerald-400',
            'to'       => 'to-emerald-600',
            'ring'     => 'ring-emerald-200',
            'num'      => 'text-emerald-600',
            'icon'     => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"/><path d="M12 22V12"/><path d="m3.3 7 7.703 4.734a2 2 0 0 0 1.994 0L20.7 7"/></svg>',
        ],
        [
            'label'    => 'Dosis aplicadas',
            'valor'    => $stats['dosis_aplicadas'],
            'sub'      => 'en jornadas',
            'from'     => 'from-violet-400',
            'to'       => 'to-violet-600',
            'ring'     => 'ring-violet-200',
            'num'      => 'text-violet-600',
            'icon'     => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 2 4 4"/><path d="m17 7 3-3"/><path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5"/><path d="m9 11 4 4"/><path d="m5 19-3 3"/><path d="m14 4 6 6"/></svg>',
        ],
        [
            'label'    => 'Pacientes atendidos',
            'valor'    => $stats['total_pacientes'],
            'sub'      => 'registros únicos',
            'from'     => 'from-amber-400',
            'to'       => 'to-amber-500',
            'ring'     => 'ring-amber-200',
            'num'      => 'text-amber-600',
            'icon'     => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
        ],
    ];
    @endphp

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach($cards as $card)
        <div class="relative bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group">
            {{-- Franja lateral de color --}}
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b {{ $card['from'] }} {{ $card['to'] }}"></div>

            <div class="pl-5 pr-4 pt-4 pb-4">
                {{-- Icono + sublabel --}}
                <div class="flex items-center justify-between mb-3">
                    <div class="p-2 bg-gradient-to-br {{ $card['from'] }} {{ $card['to'] }} rounded-xl shadow-sm ring-4 {{ $card['ring'] }}">
                        {!! $card['icon'] !!}
                    </div>
                    <span class="text-xs text-gray-400 font-medium leading-tight text-right hidden sm:block max-w-[80px]">
                        {{ $card['sub'] }}
                    </span>
                </div>

                {{-- Número grande --}}
                <p class="text-3xl sm:text-4xl font-black {{ $card['num'] }} leading-none tabular-nums tracking-tight">
                    {{ number_format($card['valor']) }}
                </p>

                {{-- Etiqueta --}}
                <p class="text-xs sm:text-sm text-gray-500 font-medium mt-2 leading-snug">
                    {{ $card['label'] }}
                </p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ═══ INVENTARIO — fila completa ═══════════════════════════ --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-5">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"/><path d="M12 22V12"/><path d="m3.3 7 7.703 4.734a2 2 0 0 0 1.994 0L20.7 7"/><path d="m7.5 4.27 9 5.15"/></svg>
                Inventario del Módulo
            </h3>
            <span class="text-xs text-gray-400">Recibido − Usado</span>
        </div>

        @if($inventario->isEmpty())
            <div class="py-10 text-center text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto mb-2 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                <p class="text-sm">Aún no se han despachado vacunas a este módulo.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-5 py-3 text-left">Vacuna</th>
                            <th class="px-4 py-3 text-center">Recibido</th>
                            <th class="px-4 py-3 text-center">Aplicado</th>
                            <th class="px-4 py-3 text-center font-bold text-gray-600">Disponible</th>
                            <th class="px-5 py-3 text-left">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($inventario as $v)
                        @php
                            $pct      = $v->despachado > 0 ? round(($v->disponible / $v->despachado) * 100) : 0;
                            $barColor = $pct > 50 ? 'bg-green-500' : ($pct > 20 ? 'bg-amber-400' : 'bg-red-500');
                            $txtColor = $v->disponible > 0 ? 'text-green-700' : 'text-red-600';
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3 font-medium text-gray-900">{{ $v->nombre }}</td>
                            <td class="px-4 py-3 text-center text-gray-500 font-mono text-xs">{{ $v->despachado }}</td>
                            <td class="px-4 py-3 text-center text-gray-500 font-mono text-xs">{{ $v->usado }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="font-bold text-base {{ $txtColor }}">{{ $v->disponible }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-gray-100 rounded-full h-1.5 min-w-16">
                                        <div class="{{ $barColor }} h-1.5 rounded-full transition-all" style="width: {{ min($pct, 100) }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-400 w-8 text-right">{{ $pct }}%</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- ═══ Jornadas + Accesos rápidos ══════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Últimas jornadas (2/3) --}}
        <div class="lg:col-span-2 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-violet-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M10 16h4"/><path d="M12 14v4"/></svg>
                    Últimas Jornadas
                </h3>
                <a href="{{ route('jornadas.index') }}" class="text-xs text-blue-600 hover:underline font-medium">Ver todas →</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($ultimasJornadas as $jornada)
                <div class="px-5 py-3.5 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-violet-50 rounded-lg shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-violet-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $jornada->fecha_jornada->format('d/m/Y') }}</p>
                            <p class="text-xs text-gray-400">
                                {{ optional($jornada->responsable)->nombre }}
                                {{ optional($jornada->responsable)->apellido }}
                                · {{ $jornada->tratamientos->count() }} tratamientos
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('jornadas.show', $jornada->id) }}"
                        class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                </div>
                @empty
                <div class="py-10 text-center text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto mb-2 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                    <p class="text-sm">No hay jornadas registradas este mes.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Accesos rápidos (1/3) --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><path d="M13 2v7h7"/></svg>
                    Accesos Rápidos
                </h3>
            </div>
            <div class="p-3 space-y-2">
                <a href="{{ route('tratamientos.create') }}"
                    class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:bg-violet-50 hover:border-violet-200 transition-colors group">
                    <div class="p-2 bg-violet-100 rounded-lg group-hover:bg-violet-200 transition-colors shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-violet-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 11v4"/><path d="M14 13h-4"/><path d="M16 6V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><rect width="20" height="14" x="2" y="6" rx="2"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-800">Nuevo Tratamiento</p>
                        <p class="text-xs text-gray-400">Registrar dosis aplicada</p>
                    </div>
                </a>

                <a href="{{ route('pacientes.create') }}"
                    class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:bg-amber-50 hover:border-amber-200 transition-colors group">
                    <div class="p-2 bg-amber-100 rounded-lg group-hover:bg-amber-200 transition-colors shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-800">Nuevo Paciente</p>
                        <p class="text-xs text-gray-400">Registrar paciente</p>
                    </div>
                </a>

                <a href="{{ route('modulo.perdidas.index', $modulo->id) }}"
                    class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:bg-red-50 hover:border-red-200 transition-colors group">
                    <div class="p-2 bg-red-100 rounded-lg group-hover:bg-red-200 transition-colors shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-800">Registrar Pérdida</p>
                        <p class="text-xs text-gray-400">Vacunas dañadas o vencidas</p>
                    </div>
                </a>

                <a href="{{ route('vacunas.index') }}"
                    class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:bg-blue-50 hover:border-blue-200 transition-colors group">
                    <div class="p-2 bg-blue-100 rounded-lg group-hover:bg-blue-200 transition-colors shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 2 4 4"/><path d="m17 7 3-3"/><path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5"/><path d="m9 11 4 4"/><path d="m5 19-3 3"/><path d="m14 4 6 6"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-800">Catálogo Vacunas</p>
                        <p class="text-xs text-gray-400">Consultar fichas técnicas</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    @endif
</div>
@endsection