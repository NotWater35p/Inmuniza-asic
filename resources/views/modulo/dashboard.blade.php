@extends('layouts.app')
@section('title', 'Mi Módulo · ' . ($modulo?->nombre ?? 'Sin asignar'))

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl bg-white/90 rounded-lg backdrop-blur-sm">

    @if(isset($sinModulo) && $sinModulo)
    {{-- Sin módulo asignado --}}
    <div class="flex flex-col items-center justify-center py-24 text-center text-gray-400">
        <i data-lucide="hospital" class="w-16 h-16 text-gray-300 mb-4"></i>
        <h2 class="text-xl font-semibold text-gray-600">No tienes un módulo asignado</h2>
        <p class="text-sm mt-2">Contacta al administrador para que te asigne como Jefe de Módulo.</p>
    </div>
    @else

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-blue-800 flex items-center gap-2">
                <div class="p-2 bg-blue-800 rounded text-white">
                    <i data-lucide="hospital" class="w-6 h-6"></i>
                </div>
                {{ $modulo->nombre }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">{{ $modulo->asic->nombre ?? '' }} · {{ $modulo->direccion ?? '' }}</p>
        </div>
        <div class="flex gap-2 shrink-0">
            <a href="{{ route('modulo.reporte.index', $modulo->id) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                <i data-lucide="file-chart-column" class="w-4 h-4"></i>
                Reporte Mensual
            </a>
        </div>
    </div>

    {{-- Stats --}}
    @php
    $cards = [
        ['label' => 'Jornadas realizadas', 'valor' => $stats['total_jornadas'],  'icon' => 'calendar-check', 'color' => 'blue'],
        ['label' => 'Dosis recibidas',     'valor' => $stats['dosis_recibidas'], 'icon' => 'package',        'color' => 'emerald'],
        ['label' => 'Dosis aplicadas',     'valor' => $stats['dosis_aplicadas'], 'icon' => 'syringe',        'color' => 'violet'],
        ['label' => 'Pacientes atendidos', 'valor' => $stats['total_pacientes'], 'icon' => 'users',          'color' => 'amber'],
    ];
    @endphp
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach($cards as $card)
        @php
        $colors = [
            'blue'    => ['bg' => 'bg-blue-50',    'icon' => 'bg-blue-600',    'text' => 'text-blue-700'],
            'emerald' => ['bg' => 'bg-emerald-50', 'icon' => 'bg-emerald-600', 'text' => 'text-emerald-700'],
            'violet'  => ['bg' => 'bg-violet-50',  'icon' => 'bg-violet-600',  'text' => 'text-violet-700'],
            'amber'   => ['bg' => 'bg-amber-50',   'icon' => 'bg-amber-600',   'text' => 'text-amber-700'],
        ];
        $c = $colors[$card['color']];
        @endphp
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="p-3 {{ $c['icon'] }} rounded-xl shrink-0">
                <i data-lucide="{{ $card['icon'] }}" class="w-5 h-5 text-white"></i>
            </div>
            <div>
                <p class="text-2xl font-bold {{ $c['text'] }}">{{ number_format($card['valor']) }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $card['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Inventario del módulo --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i data-lucide="package-search" class="w-4 h-4 text-blue-600"></i>
                    Mi Inventario
                </h3>
                <span class="text-xs text-gray-400">Despachado − Usado</span>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($inventario as $vacuna)
                @php
                $pct = $vacuna->despachado > 0 ? round(($vacuna->disponible / $vacuna->despachado) * 100) : 0;
                $barColor = $pct > 50 ? 'bg-green-500' : ($pct > 20 ? 'bg-amber-400' : 'bg-red-500');
                @endphp
                <div class="px-4 py-3">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-medium text-gray-800">{{ $vacuna->nombre }}</span>
                        <span class="text-sm font-bold {{ $vacuna->disponible > 0 ? 'text-green-700' : 'text-red-600' }}">
                            {{ $vacuna->disponible }} disponibles
                        </span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                        <div class="{{ $barColor }} h-1.5 rounded-full transition-all" style="width: {{ min($pct, 100) }}%"></div>
                    </div>
                    <div class="flex justify-between mt-1">
                        <span class="text-xs text-gray-400">Recibido: {{ $vacuna->despachado }}</span>
                        <span class="text-xs text-gray-400">Usado: {{ $vacuna->usado }}</span>
                    </div>
                </div>
                @empty
                <div class="py-10 text-center text-gray-400">
                    <i data-lucide="package-open" class="w-10 h-10 mx-auto mb-2 text-gray-300"></i>
                    <p class="text-sm">Aún no se han despachado vacunas a este módulo.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Últimas jornadas --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i data-lucide="calendar-check" class="w-4 h-4 text-violet-600"></i>
                    Últimas Jornadas
                </h3>
                <a href="{{ route('jornadas.create') }}"
                    class="text-xs font-medium text-blue-600 hover:underline flex items-center gap-1">
                    <i data-lucide="plus" class="w-3 h-3"></i> Nueva
                </a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($ultimasJornadas as $jornada)
                <div class="px-4 py-3 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $jornada->fecha_jornada->format('d/m/Y') }}
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ optional($jornada->responsable)->nombre }} ·
                            {{ $jornada->tratamientos->count() }} tratamientos
                        </p>
                    </div>
                    <a href="{{ route('jornadas.show', $jornada->id) }}"
                        class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg">
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
                @empty
                <div class="py-10 text-center text-gray-400">
                    <i data-lucide="calendar-x" class="w-10 h-10 mx-auto mb-2 text-gray-300"></i>
                    <p class="text-sm">No hay jornadas registradas este mes.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
    @endif
</div>

@push('scripts')
<script>lucide.createIcons();</script>
@endpush
@endsection