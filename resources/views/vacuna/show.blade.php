@extends('layouts.app')
@section('title', 'Detalles | ' . $vacuna->nombre)

@section('content')
@php
    $nivelUsuario = auth()->user()?->personal?->cargo?->nivel_acceso ?? 0;
    $esAdmin      = $nivelUsuario >= 5;
    $puedeEditar  = $nivelUsuario >= 3;
@endphp
<div class="px-4 py-6 mx-auto max-w-3xl bg-white/90 rounded-lg shadow backdrop-blur-sm">

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-blue-800 flex items-center gap-2">
                <div class="p-2 bg-blue-800 rounded text-white">
                    <i data-lucide="syringe" class="w-6 h-6"></i>
                </div>
                Detalles de Vacuna
            </h1>
        </div>
        <a href="{{ route('vacunas.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Volver
        </a>
    </div>

    {{-- Banner --}}
    <div class="bg-linear-to-r from-blue-600 to-blue-800 rounded-xl p-6 mb-5 text-white">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                <i data-lucide="syringe" class="w-7 h-7 text-white"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold">{{ $vacuna->nombre }}</h2>
                <div class="flex flex-wrap items-center gap-2 mt-1.5">
                    @if($vacuna->marca)
                    <span class="text-sm text-blue-200">{{ $vacuna->marca->nombre }}</span>
                    @endif
                    @if($vacuna->enfermedad)
                    <span class="px-2 py-0.5 bg-white/20 text-white text-xs rounded-full">
                        {{ $vacuna->enfermedad }}
                    </span>
                    @endif
                    {{-- Badge tipo --}}
                    @php
                    $tipoConfig = match($vacuna->tipo) {
                        'suero'   => ['bg-amber-400/30 text-amber-100', 'Suero'],
                        'insumo'  => ['bg-gray-400/30 text-gray-100',   'Insumo'],
                        default   => ['bg-green-400/30 text-green-100',  'Vacuna'],
                    };
                    @endphp
                    <span class="px-2 py-0.5 text-xs rounded-full font-medium {{ $tipoConfig[0] }}">
                        {{ $tipoConfig[1] }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Grid datos --}}
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-5">
        <div class="p-4 border-b border-gray-100 flex items-center gap-2">
            <i data-lucide="info" class="w-4 h-4 text-blue-600"></i>
            <h3 class="text-sm font-semibold text-gray-800">Información Clínica</h3>
        </div>

        <div class="divide-y divide-gray-50">
            @php
            $campos = [
                ['label' => 'Presentación',        'icon' => 'package',      'valor' => $vacuna->presentacion],
                ['label' => 'Dosificación',        'icon' => 'pill-bottle',  'valor' => $vacuna->dosificacion],
                ['label' => 'Vía de Admin.',       'icon' => 'syringe',      'valor' => $vacuna->via_administracion],
                ['label' => 'Número de dosis',     'icon' => 'hash',         'valor' => $vacuna->numero_dosis],
                ['label' => 'Intervalo',           'icon' => 'calendar-sync','valor' => $vacuna->intervalo],
                ['label' => 'Refuerzo',            'icon' => 'refresh-cw',   'valor' => $vacuna->refuerzo],
            ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2">
                @foreach($campos as $campo)
                <div class="flex items-start gap-3 p-4 {{ !$loop->last ? 'border-b sm:border-b-0 sm:border-r border-gray-50' : '' }}
                    {{ $loop->iteration % 2 === 0 && !$loop->last ? 'sm:border-b border-gray-50' : '' }}">
                    <div class="p-1.5 bg-gray-100 rounded-lg shrink-0 mt-0.5">
                        <i data-lucide="{{ $campo['icon'] }}" class="w-3.5 h-3.5 text-gray-500"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">{{ $campo['label'] }}</p>
                        <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $campo['valor'] ?? '—' }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            @if($vacuna->descripcion)
            <div class="flex items-start gap-3 p-4">
                <div class="p-1.5 bg-gray-100 rounded-lg shrink-0 mt-0.5">
                    <i data-lucide="notebook-tabs" class="w-3.5 h-3.5 text-gray-500"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Descripción</p>
                    <p class="text-sm text-gray-700 mt-0.5 leading-relaxed">{{ $vacuna->descripcion }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Acciones --}}
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="flex items-center {{ $puedeEditar ? 'justify-between' : 'justify-start' }} px-5 py-4">
            <a href="{{ route('vacunas.pdf', $vacuna->id) }}"
                class="flex items-center gap-2 text-sm font-medium text-emerald-700 bg-emerald-100 hover:bg-emerald-600 hover:text-white rounded-lg px-4 py-2.5 transition-colors">
                <i data-lucide="printer" class="w-4 h-4"></i>
                Reporte PDF
            </a>
            @if($puedeEditar)
            <a href="{{ route('vacunas.edit', $vacuna->id) }}"
                class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-amber-700 bg-amber-100 hover:bg-amber-500 hover:text-white rounded-lg transition-colors">
                <i data-lucide="square-pen" class="w-4 h-4"></i>
                Editar
            </a>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>lucide.createIcons();</script>
@endpush
@endsection