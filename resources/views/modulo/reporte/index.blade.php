@extends('layouts.app')
@section('title', 'Reporte Mensual · ' . $modulo->nombre)

@section('content')
<div class="px-4 py-6 mx-auto max-w-5xl bg-white/90 rounded-lg shadow backdrop-blur-sm">

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-blue-800 flex items-center gap-2">
                <div class="p-2 bg-blue-800 rounded text-white">
                    <i data-lucide="file-chart-column" class="w-6 h-6"></i>
                </div>
                Reporte Mensual
            </h1>
            <p class="text-sm text-gray-500 mt-1">{{ $modulo->nombre }} · {{ $modulo->asic->nombre ?? '' }}</p>
        </div>
        <a href="{{ auth()->user()->esJefeModulo() ? route('modulo.dashboard') : route('inicio') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Volver
        </a>
    </div>

    {{-- Selector de período --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5 mb-6">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block mb-1.5 text-sm font-medium text-gray-700">Mes</label>
                <select name="mes"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                    @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" @selected($m==$mes)>
                        {{ Carbon\Carbon::createFromDate(null, $m, 1)->locale('es')->monthName }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block mb-1.5 text-sm font-medium text-gray-700">Año</label>
                <select name="anio"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                    @foreach(range(date('Y'), 2024, -1) as $a)
                    <option value="{{ $a }}" @selected($a==$anio)>{{ $a }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                <i data-lucide="search" class="w-4 h-4"></i>
                Consultar
            </button>
        </form>
    </div>

    @if($jornadas->count() > 0)

    {{-- Resumen de dosis --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-5">
        <div class="p-4 border-b border-gray-100 flex items-center gap-2">
            <i data-lucide="syringe" class="w-4 h-4 text-blue-600"></i>
            <h3 class="font-semibold text-gray-800">Dosis aplicadas en {{ ucfirst($nombreMes) }} {{ $anio }}</h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                <tr>
                    <th class="px-4 py-3 text-left">Vacuna</th>
                    <th class="px-4 py-3 text-center">Dosis aplicadas</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($resumenVacunas as $v)
                <tr>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $v->nombre }}</td>
                    <td class="px-4 py-3 text-center font-bold text-blue-700">{{ $v->dosis_aplicadas }}</td>
                </tr>
                @endforeach
                <tr class="bg-gray-50 font-bold">
                    <td class="px-4 py-3 text-gray-700">TOTAL</td>
                    <td class="px-4 py-3 text-center text-blue-800">{{ $totalDosis }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Detalle por jornada --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-5">
        <div class="p-4 border-b border-gray-100 flex items-center gap-2">
            <i data-lucide="calendar-check" class="w-4 h-4 text-violet-600"></i>
            <h3 class="font-semibold text-gray-800">Detalle por jornada</h3>
        </div>
        @foreach($jornadas as $jornada)
        <div class="border-b border-gray-100 last:border-0">
            <div class="px-4 py-3 bg-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
                    <span class="text-sm font-semibold text-gray-700">
                        {{ $jornada->fecha_jornada->format('d/m/Y') }}
                    </span>
                    @if($jornada->responsable)
                    <span class="text-xs text-gray-400">·
                        {{ $jornada->responsable->nombre }} {{ $jornada->responsable->apellido }}
                    </span>
                    @endif
                </div>
                <span class="text-xs font-medium text-violet-700 bg-violet-100 px-2 py-0.5 rounded-full">
                    {{ $jornada->tratamientos->count() }} tratamientos
                </span>
            </div>
            @if($jornada->tratamientos->count())
            <table class="w-full text-xs">
                <thead class="bg-white text-gray-400 uppercase">
                    <tr>
                        <th class="px-4 py-2 text-left">Paciente CI</th>
                        <th class="px-4 py-2 text-left">Vacuna</th>
                        <th class="px-4 py-2 text-center">Dosis N°</th>
                        <th class="px-4 py-2 text-left hidden sm:table-cell">Observaciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($jornada->tratamientos as $t)
                    <tr>
                        <td class="px-4 py-2 font-mono text-gray-700">
                            {{ $t->paciente?->cedula ?? 'Sin cédula' }}
                        </td>
                        <td class="px-4 py-2 font-medium text-gray-800">{{ optional($t->vacuna)->nombre }}</td>
                        <td class="px-4 py-2 text-center text-gray-600">{{ $t->dosis_aplicada }}</td>
                        <td class="px-4 py-2 text-gray-400 hidden sm:table-cell">{{ $t->observaciones ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
        @endforeach
    </div>

    {{-- Botones de exportación --}}
    <div class="flex justify-end gap-3">
        <a href="{{ route('modulo.reporte.excel', [$modulo->id, 'mes' => $mes, 'anio' => $anio]) }}"
            class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700">
            <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
            Descargar Excel
        </a>
        <a href="{{ route('modulo.reporte.pdf', [$modulo->id, 'mes' => $mes, 'anio' => $anio]) }}"
            class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
            <i data-lucide="file-down" class="w-4 h-4"></i>
            Descargar PDF
        </a>
    </div>

    @else
    <div class="py-20 text-center text-gray-400">
        <i data-lucide="calendar-x" class="w-14 h-14 mx-auto mb-3 text-gray-300"></i>
        <p class="font-semibold text-gray-500">Sin jornadas en {{ ucfirst($nombreMes) }} {{ $anio }}</p>
        <p class="text-sm mt-1">Selecciona otro período o registra jornadas para este módulo.</p>
    </div>
    @endif

</div>

@push('scripts')
<script>
    lucide.createIcons();
</script>
@endpush
@endsection