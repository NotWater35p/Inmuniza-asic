@extends('layouts.app')
@section('title', 'Mi Módulo · ' . ($modulo?->nombre ?? 'Sin asignar'))

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl bg-white/80 backdrop-blur-lg shadow-sm rounded-lg">

    {{-- Sin módulo asignado --}}
    @if(isset($sinModulo) && $sinModulo)
    <div class="flex flex-col items-center justify-center py-24 text-center text-gray-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-300 mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 7v4"/><path d="M14 21v-3a2 2 0 0 0-4 0v3"/><path d="M14 9h-4"/><path d="M18 11h2a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-9a2 2 0 0 1 2-2h2"/><path d="M18 21V5a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16"/></svg>
        <h2 class="text-xl font-semibold text-gray-600">No tienes un módulo asignado</h2>
        <p class="text-sm mt-2">Contacta al administrador del ASIC para que te asigne como Jefe de Módulo.</p>
    </div>

    @else

    {{-- ══════════════════════════════════════════════════
         HEADER
    ══════════════════════════════════════════════════ --}}
    <div class="mb-4 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                <div class="p-2 bg-blue-700 rounded-lg text-white shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 7v4"/><path d="M14 21v-3a2 2 0 0 0-4 0v3"/><path d="M14 9h-4"/><path d="M18 11h2a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-9a2 2 0 0 1 2-2h2"/><path d="M18 21V5a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16"/></svg>
                </div>
                {{ $modulo->nombre }}
            </h1>

            {{-- Info strip del módulo --}}
            <div class="mt-1.5 ml-12 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-gray-400">
                @if($modulo->tipo_establecimiento)
                <span class="inline-flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="16" height="20" x="4" y="2" rx="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M12 6h.01"/><path d="M12 10h.01"/></svg>
                    <span class="px-1.5 py-0.5 bg-blue-50 text-blue-600 text-xs font-medium rounded">{{ $modulo->tipo_establecimiento }}</span>
                </span>
                @endif
                @if($modulo->municipio)
                <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                    {{ $modulo->municipio }}@if($modulo->parroquia), {{ $modulo->parroquia }}@endif
                </span>
                @endif
                @if($modulo->asic)
                <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9h13a5 5 0 0 1 0 10H7M3 9l4-4M3 9l4 4"/></svg>
                    {{ $modulo->asic->nombre }}
                </span>
                @endif
            </div>
        </div>

        <div class="flex flex-wrap gap-2 shrink-0">
            <a href="{{ route('modulo.reporte.index', $modulo->id) }}"
                class="inline-flex items-center gap-1.5 px-3 py-2 text-sm text-body bg-neutral-primary border border-default hover:bg-neutral-secondary-soft hover:text-heading focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded-base focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M15 18H9"/><path d="M15 14H9"/><path d="M6 22h12a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2z"/></svg>
                Reporte
            </a>
            <a href="{{ route('descargo.create') }}"
                class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-warning bg-neutral-primary hover:bg-warning hover:text-white focus:ring-4 focus:ring-neutral-tertiary leading-5 rounded-base focus:outline-none transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><path d="M13 2v7h7"/><path d="M12 12v6"/><path d="M9 15h6"/></svg>
                Descargo Rápido
            </a>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════
         STATS 
    ══════════════════════════════════════════════════ --}}
    @php
    $stats_cards = [
        [
            'label'  => 'Jornadas realizadas',
            'valor'  => $stats['total_jornadas'],
            'color'  => 'text-blue-500',
            'bg'     => 'bg-blue-50',
            'icon'   => '<svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M10 16h4"/><path d="M12 14v4"/></svg>',
        ],
        [
            'label'  => 'Dosis recibidas',
            'valor'  => $stats['dosis_recibidas'],
            'color'  => 'text-emerald-500',
            'bg'     => 'bg-emerald-50',
            'icon'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package-check-icon lucide-package-check w-8 h-8"><path d="M12 22V12"/><path d="m16 17 2 2 4-4"/><path d="M21 11.127V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.729l7 4a2 2 0 0 0 2 .001l1.32-.753"/><path d="M3.29 7 12 12l8.71-5"/><path d="m7.5 4.27 8.997 5.148"/></svg>',
        ],
        [
            'label'  => 'Dosis aplicadas',
            'valor'  => $stats['dosis_aplicadas'],
            'color'  => 'text-violet-500',
            'bg'     => 'bg-violet-50',
            'icon'   => '<svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="m18 2 4 4"/><path d="m17 7 3-3"/><path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5"/><path d="m9 11 4 4"/><path d="m5 19-3 3"/><path d="m14 4 6 6"/></svg>',
        ],
        [
            'label'  => 'Pacientes atendidos',
            'valor'  => $stats['total_pacientes'],
            'color'  => 'text-amber-500',
            'bg'     => 'bg-amber-50',
            'icon'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-book-user-icon lucide-book-user w-8 h-8"><path d="M15 13a3 3 0 1 0-6 0"/><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20"/><circle cx="12" cy="8" r="2"/></svg>',
        ],
    ];
    @endphp

    <div class="grid grid-cols-2 lg:grid-cols-4 mb-6 bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
        @foreach($stats_cards as $i => $card)
        @php
            $clasesBorde = trim(implode(' ', [
                ($i % 2 === 0) ? 'border-r border-gray-200'    : '',
                ($i < 2)       ? 'border-b border-gray-200'    : '',
                ($i < 3)       ? 'lg:border-r'  : 'lg:border-r-0',
                'lg:border-b-0',
            ]));
        @endphp
        <div class="py-5 px-4 sm:px-6 {{ $clasesBorde }}">
            <div class="flex items-center gap-3">
                <div class="p-2 {{ $card['bg'] }} {{ $card['color'] }} rounded-xl shrink-0">
                    {!! $card['icon'] !!}
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900 tabular-nums leading-none">
                    {{ number_format($card['valor']) }}
                </p>
            </div>
            <p class="mt-2 text-sm text-gray-500 leading-snug font-semibold">{{ $card['label'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- ══════════════════════════════════════════════════
         INVENTARIO
    ══════════════════════════════════════════════════ --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-5">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"/><path d="M12 22V12"/><path d="m3.3 7 7.703 4.734a2 2 0 0 0 1.994 0L20.7 7"/><path d="m7.5 4.27 9 5.15"/></svg>
                Inventario del Módulo
            </h3>
        </div>

        @if($inventario->isEmpty())
        <div class="py-12 text-center text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto mb-3 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
            <p class="text-sm">Aún no se han despachado vacunas a este módulo.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold">Vacuna</th>
                        <th class="px-4 py-3 text-center font-semibold">Recibido</th>
                        <th class="px-4 py-3 text-center font-semibold">Aplicado</th>
                        <th class="px-4 py-3 text-center font-semibold">Disponible</th>
                        <th class="px-5 py-3 text-left font-semibold hidden sm:table-cell">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($inventario as $v)
                    @php
                        $desp     = $v->total_despachado ?? $v->despachado ?? 0;
                        $usado    = $v->total_usado ?? $v->usado ?? 0;
                        $disp     = $v->disponible ?? 0;
                        $pct      = $desp > 0 ? round(($disp / $desp) * 100) : 0;
                        $barColor = $pct > 50 ? 'bg-green-500' : ($pct > 20 ? 'bg-amber-400' : 'bg-red-500');
                        $dispClass = $disp > 0
                            ? ($pct > 50 ? 'text-green-700 bg-green-50' : 'text-amber-700 bg-amber-50')
                            : 'text-red-600 bg-red-50';
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3.5 font-medium text-gray-900">{{ $v->nombre }}</td>
                        <td class="px-4 py-3.5 text-center font-mono text-xs text-gray-500">{{ number_format($desp) }}</td>
                        <td class="px-4 py-3.5 text-center font-mono text-xs text-gray-500">{{ number_format($usado) }}</td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-bold {{ $dispClass }}">
                                {{ number_format($disp) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 hidden sm:table-cell">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-gray-100 rounded-full h-1.5 min-w-[4rem]">
                                    <div class="{{ $barColor }} h-1.5 rounded-full" style="width: {{ min($pct, 100) }}%"></div>
                                </div>
                                <span class="text-xs text-gray-400 w-8 text-right tabular-nums">{{ $pct }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════════
         FILA INFERIOR
    ══════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Últimas jornadas --}}
        <div class="lg:col-span-2 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-violet-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M10 16h4"/><path d="M12 14v4"/></svg>
                    Últimas Jornadas
                </h3>
                <a href="{{ route('jornadas.index') }}" class="text-xs text-blue-600 hover:underline font-medium">
                    Ver todas →
                </a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($ultimasJornadas as $jornada)
                <div class="px-5 py-3.5 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="p-2 bg-violet-50 rounded-lg shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-violet-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-800">
                                {{ $jornada->fecha_jornada->format('d/m/Y') }}
                            </p>
                            <p class="text-xs text-gray-400 truncate">
                                {{ optional($jornada->responsable)->nombre }}
                                {{ optional($jornada->responsable)->apellido }}
                                · {{ $jornada->tratamientos->count() }} tratamiento(s)
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('jornadas.show', $jornada->id) }}"
                        class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg shrink-0 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                </div>
                @empty
                <div class="py-12 text-center text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto mb-3 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                    <p class="text-sm">No hay jornadas registradas aún.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Accesos rápidos --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><path d="M13 2v7h7"/></svg>
                    Accesos Rápidos
                </h3>
            </div>
            <div class="p-3 space-y-1.5">

                <a href="{{ route('jornadas.create') }}"
                    class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:bg-blue-50 hover:border-blue-200 transition-colors group">
                    <div class="p-2 bg-blue-100 rounded-lg shrink-0 group-hover:bg-blue-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M10 16h4"/><path d="M12 14v4"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Nueva Jornada</p>
                        <p class="text-xs text-gray-400">Abrir jornada de vacunación</p>
                    </div>
                </a>

                <a href="{{ route('tratamientos.create') }}"
                    class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:bg-violet-50 hover:border-violet-200 transition-colors group">
                    <div class="p-2 bg-violet-100 rounded-lg shrink-0 group-hover:bg-violet-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-violet-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 11v4"/><path d="M14 13h-4"/><path d="M16 6V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><rect width="20" height="14" x="2" y="6" rx="2"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Nuevo Tratamiento</p>
                        <p class="text-xs text-gray-400">Registrar dosis aplicada</p>
                    </div>
                </a>

                <a href="{{ route('pacientes.create') }}"
                    class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:bg-amber-50 hover:border-amber-200 transition-colors group">
                    <div class="p-2 bg-amber-100 rounded-lg shrink-0 group-hover:bg-amber-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Nuevo Paciente</p>
                        <p class="text-xs text-gray-400">Registrar paciente</p>
                    </div>
                </a>

                <a href="{{ route('modulo.perdidas.index', $modulo->id) }}"
                    class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:bg-red-50 hover:border-red-200 transition-colors group">
                    <div class="p-2 bg-red-100 rounded-lg shrink-0 group-hover:bg-red-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Registrar Pérdida</p>
                        <p class="text-xs text-gray-400">Vacunas dañadas o vencidas</p>
                    </div>
                </a>

                <a href="{{ route('vacunas.index') }}"
                    class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:bg-gray-50 hover:border-gray-300 transition-colors group">
                    <div class="p-2 bg-gray-100 rounded-lg shrink-0 group-hover:bg-gray-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m18 2 4 4"/><path d="m17 7 3-3"/><path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5"/><path d="m9 11 4 4"/><path d="m5 19-3 3"/><path d="m14 4 6 6"/></svg>
                    </div>
                    <div>
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