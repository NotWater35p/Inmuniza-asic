@extends('layouts.app')
@section('title', 'SISPAI · Reporte de Vacunación')

@section('content')
<div class="px-4 py-6 mx-auto max-w-6xl bg-white/90 rounded-lg shadow backdrop-blur-sm">

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-blue-800 flex items-center gap-2">
                <div class="p-2 bg-blue-800 rounded text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                </div>
                Reporte SISPAI
            </h1>
            <p class="text-sm text-gray-500 mt-1">Formato oficial de consolidado mensual de dosis aplicadas</p>
        </div>
        <a href="{{ auth()->user()->esJefeModulo() ? route('modulo.dashboard') : route('inicio') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Volver
        </a>
    </div>

    {{-- Alertas --}}
    @if(session('error'))
    <div class="flex items-center gap-3 p-4 mb-5 text-red-800 bg-red-50 border border-red-200 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
        <span class="text-sm font-medium">{{ session('error') }}</span>
    </div>
    @endif

    {{-- Selector --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5 mb-6">
        <form method="GET" action="{{ route('sispai.index') }}" class="flex flex-wrap items-end gap-4">

            {{-- Módulo (solo admin ve selector) --}}
            @if(!auth()->user()->esJefeModulo())
            <div>
                <label class="block mb-1.5 text-sm font-medium text-gray-700">Módulo / Puesto</label>
                <select name="modulo_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 min-w-48">
                    @foreach($modulos as $m)
                    <option value="{{ $m->id }}" @selected($m->id == $moduloSeleccionado?->id)>
                        {{ $m->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>
            @else
            <input type="hidden" name="modulo_id" value="{{ $moduloSeleccionado?->id }}">
            @endif

            {{-- Mes --}}
            <div>
                <label class="block mb-1.5 text-sm font-medium text-gray-700">Mes</label>
                <select name="mes"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                    @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" @selected($m == $mes)>
                        {{ \Carbon\Carbon::createFromDate(null, $m, 1)->locale('es')->monthName }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Año --}}
            <div>
                <label class="block mb-1.5 text-sm font-medium text-gray-700">Año</label>
                <select name="anio"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                    @foreach(range(date('Y'), 2024, -1) as $a)
                    <option value="{{ $a }}" @selected($a == $anio)>{{ $a }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit"
                class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                Consultar
            </button>
        </form>
    </div>

    @if($moduloSeleccionado)

    {{-- Info del módulo --}}
    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-5 flex flex-wrap items-center gap-4">
        <div class="flex items-center gap-2.5">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
            <span class="text-sm font-semibold text-blue-800">{{ $moduloSeleccionado->nombre }}</span>
        </div>
        @if($moduloSeleccionado->parroquia)
        <span class="text-sm text-blue-600">Parroquia {{ $moduloSeleccionado->parroquia }}</span>
        @endif
        @if($moduloSeleccionado->tipo_establecimiento)
        <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">
            {{ $moduloSeleccionado->tipo_establecimiento }}
        </span>
        @endif
        @if(!$moduloSeleccionado->sispai_fila)
        <span class="ml-auto px-2.5 py-1 bg-amber-100 text-amber-700 text-xs font-medium rounded-full flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
            Sin fila SISPAI configurada — el Excel no se puede generar
        </span>
        @endif
    </div>

    @if($resumen && ($resumen['jornadas']->count() > 0))

    {{-- Stats rápidos --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
        @php
        $statsCards = [
            ['label' => 'Jornadas',         'valor' => $resumen['jornadas']->count(),   'color' => 'blue'],
            ['label' => 'Dosis aplicadas',  'valor' => $resumen['totalDosis'],           'color' => 'teal'],
            ['label' => 'Pacientes',         'valor' => $resumen['totalPacientes'],       'color' => 'violet'],
            ['label' => 'Tipos de vacuna',  'valor' => count($resumen['resumenVacunas']), 'color' => 'amber'],
        ];
        $colores = [
            'blue'   => ['bg-blue-50',   'text-blue-700',   'bg-blue-600'],
            'teal'   => ['bg-teal-50',   'text-teal-700',   'bg-teal-600'],
            'violet' => ['bg-violet-50', 'text-violet-700', 'bg-violet-600'],
            'amber'  => ['bg-amber-50',  'text-amber-700',  'bg-amber-500'],
        ];
        @endphp
        @foreach($statsCards as $card)
        @php $c = $colores[$card['color']]; @endphp
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex items-center gap-3 shadow-sm">
            <div class="w-2 h-10 {{ $c[2] }} rounded-full shrink-0"></div>
            <div>
                <p class="text-xl font-bold {{ $c[1] }}">{{ number_format($card['valor']) }}</p>
                <p class="text-xs text-gray-400">{{ $card['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Tabla resumen de dosis por vacuna --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-5">
        <div class="p-4 border-b border-gray-100 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600"><path d="m18 2 4 4"/><path d="m17 7 3-3"/><path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5"/><path d="m9 11 4 4"/><path d="m5 19-3 3"/><path d="m14 4 6 6"/></svg>
            <h3 class="font-semibold text-gray-800">Resumen — {{ ucfirst($nombreMes) }} {{ $anio }}</h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase border-b border-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left">Vacuna</th>
                    <th class="px-4 py-3 text-center">Dosis aplicadas</th>
                    <th class="px-4 py-3 text-center">% del total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($resumen['resumenVacunas'] as $nombre => $dosis)
                <tr class="hover:bg-gray-50/60">
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $nombre }}</td>
                    <td class="px-4 py-3 text-center font-bold text-blue-700">{{ $dosis }}</td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-100 rounded-full h-1.5">
                                <div class="bg-blue-500 h-1.5 rounded-full"
                                    style="width:{{ $resumen['totalDosis'] > 0 ? round($dosis / $resumen['totalDosis'] * 100) : 0 }}%">
                                </div>
                            </div>
                            <span class="text-xs text-gray-400 w-8 text-right">
                                {{ $resumen['totalDosis'] > 0 ? round($dosis / $resumen['totalDosis'] * 100) : 0 }}%
                            </span>
                        </div>
                    </td>
                </tr>
                @endforeach
                <tr class="bg-blue-50 font-bold border-t border-blue-100">
                    <td class="px-4 py-3 text-blue-800">TOTAL</td>
                    <td class="px-4 py-3 text-center text-blue-800">{{ $resumen['totalDosis'] }}</td>
                    <td class="px-4 py-3 text-center text-blue-600">100%</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Detalle por jornada --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="p-4 border-b border-gray-100 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-violet-600"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/><path d="m9 16 2 2 4-4"/></svg>
            <h3 class="font-semibold text-gray-800">Detalle por jornada</h3>
        </div>
        @foreach($resumen['jornadas'] as $jornada)
        <div class="border-b border-gray-100 last:border-0">
            <div class="px-4 py-2.5 bg-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-3 text-sm">
                    <span class="font-semibold text-gray-700">{{ $jornada->fecha_jornada->format('d/m/Y') }}</span>
                    @if($jornada->responsable)
                    <span class="text-gray-400">· {{ $jornada->responsable->nombre }} {{ $jornada->responsable->apellido }}</span>
                    @endif
                </div>
                <span class="text-xs font-medium bg-violet-100 text-violet-700 px-2 py-0.5 rounded-full">
                    {{ $jornada->tratamientos->count() }} dosis
                </span>
            </div>
            <table class="w-full text-xs">
                <thead class="text-gray-400 uppercase border-b border-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Paciente</th>
                        <th class="px-4 py-2 text-left">Vacuna</th>
                        <th class="px-4 py-2 text-center">Dosis</th>
                        <th class="px-4 py-2 text-left hidden sm:table-cell">Subtipo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($jornada->tratamientos as $t)
                    <tr>
                        <td class="px-4 py-2">
                            <p class="font-medium text-gray-800">{{ $t->paciente?->nombres }} {{ $t->paciente?->apellidos }}</p>
                            <p class="text-gray-400 font-mono">
                                {{ $t->paciente?->cedula ? 'CI: '.$t->paciente->cedula : 'Sin CI' }}
                                @if($t->paciente?->fecha_nacimiento)
                                · {{ \Carbon\Carbon::parse($t->paciente->fecha_nacimiento)->diffInYears($t->fecha_aplicacion) }} a.
                                @endif
                            </p>
                        </td>
                        <td class="px-4 py-2 font-medium text-gray-700">{{ $t->vacuna?->nombre ?? '—' }}</td>
                        <td class="px-4 py-2 text-center">
                            <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-teal-600 rounded-full">
                                {{ $t->dosis_aplicada }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-gray-400 hidden sm:table-cell">
                            {{ $t->subtipo_paciente ? ucfirst(str_replace('_', ' ', $t->subtipo_paciente)) : 'General' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endforeach
    </div>

    {{-- Botones de exportación --}}
    <div class="flex flex-wrap justify-end gap-3">
        <a href="{{ route('sispai.pdf', ['modulo_id' => $moduloSeleccionado->id, 'mes' => $mes, 'anio' => $anio]) }}"
            class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M15 18H9"/><path d="M15 14H9"/><path d="M6 22h12a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2z"/></svg>
            Descargar PDF
        </a>
        @if($moduloSeleccionado->sispai_fila)
        <a href="{{ route('sispai.excel', ['modulo_id' => $moduloSeleccionado->id, 'mes' => $mes, 'anio' => $anio]) }}"
            class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M8 13h2"/><path d="M8 17h2"/><path d="M14 13h2"/><path d="M14 17h2"/></svg>
            Descargar Excel SISPAI (formato oficial)
        </a>
        @else
        <button disabled
            class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed"
            title="Configura la fila SISPAI en el módulo para habilitar esta opción">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
            Excel SISPAI (sin fila configurada)
        </button>
        @endif
    </div>

    @else
    {{-- Sin jornadas --}}
    <div class="py-20 text-center text-gray-400">
        <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-3 text-gray-300"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/></svg>
        <p class="font-semibold text-gray-500 text-lg">Sin jornadas en {{ ucfirst($nombreMes) }} {{ $anio }}</p>
        <p class="text-sm mt-1">Selecciona otro período o registra jornadas para este módulo.</p>
    </div>
    @endif

    @endif {{-- fin moduloSeleccionado --}}
</div>

@push('scripts')
<script>lucide?.createIcons?.();</script>
@endpush
@endsection