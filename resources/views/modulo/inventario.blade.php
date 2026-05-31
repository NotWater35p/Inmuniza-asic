@extends('layouts.app')
@section('title', 'Inventario · ' . $modulo->nombre)

@section('content')
@php
    $nivelUsuario = auth()->user()?->personal?->cargo?->nivel_acceso ?? 0;
    $esAdmin      = $nivelUsuario >= 3;
@endphp

<div class="px-4 py-6 mx-auto max-w-5xl space-y-5 bg-white/80 shadow-sm rounded-lg backdrop-blur-lg">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                <div class="p-2 bg-blue-700 rounded text-white shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"/><path d="M12 22V12"/><path d="m3.3 7 7.703 4.734a2 2 0 0 0 1.994 0L20.7 7"/><path d="m7.5 4.27 9 5.15"/></svg>
                </div>
                Inventario del Módulo
            </h1>
            <p class="text-sm text-gray-500 mt-0.5 ml-11">
                {{ $modulo->nombre }}
                <span class="text-gray-300 mx-1">·</span>
                {{ $modulo->asic->nombre ?? '' }}
                @if($modulo->tipo_establecimiento)
                    <span class="ml-1 text-xs font-medium px-1.5 py-0.5 bg-blue-100 text-blue-700 rounded">
                        {{ $modulo->tipo_establecimiento }}
                    </span>
                @endif
            </p>
        </div>
        <div class="flex gap-2 shrink-0">
            @if($esAdmin)
            <a href="{{ route('despachos.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 10H6"/><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.28a1 1 0 0 0-.684-.948l-1.923-.641a1 1 0 0 1-.578-.502l-1.539-3.076A1 1 0 0 0 16.382 8H14"/><path d="M8 8v4"/><path d="M9 18h6"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/></svg>
                Nuevo Despacho
            </a>
            @endif
            <a href="{{ $esAdmin ? route('modulos.index') : route('modulo.dashboard') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                Volver
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">

        {{-- Biológicos activos --}}
        <div class="py-5 px-4 sm:px-5 border-r border-b border-gray-200 lg:border-b-0">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-600 rounded-lg shrink-0">
                    {{-- Syringe --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 2 4 4"/><path d="m17 7 3-3"/><path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5"/><path d="m9 11 4 4"/><path d="m5 19-3 3"/><path d="m14 4 6 6"/></svg>
                </div>
                <p class="text-2xl font-bold text-gray-900 tabular-nums leading-none">{{ number_format($stats['total_vacunas']) }}</p>
            </div>
            <p class="mt-2 text-xs text-gray-500">Biológicos activos</p>
        </div>

        {{-- Total recibido --}}
        <div class="py-5 px-4 sm:px-5 border-b border-gray-200 lg:border-b-0 lg:border-r">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-emerald-600 rounded-lg shrink-0">
                    {{-- Package / caja recibida --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"/><path d="M12 22V12"/><path d="m3.3 7 7.703 4.734a2 2 0 0 0 1.994 0L20.7 7"/><path d="m7.5 4.27 9 5.15"/></svg>
                </div>
                <p class="text-2xl font-bold text-gray-900 tabular-nums leading-none">{{ number_format($stats['total_recibido']) }}</p>
            </div>
            <p class="mt-2 text-xs text-gray-500">Total recibido</p>
        </div>

        {{-- Total aplicado --}}
        <div class="py-5 px-4 sm:px-5 border-r border-gray-200">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-violet-600 rounded-lg shrink-0">
                    {{-- Activity / dosis aplicadas --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                </div>
                <p class="text-2xl font-bold text-gray-900 tabular-nums leading-none">{{ number_format($stats['total_usado']) }}</p>
            </div>
            <p class="mt-2 text-xs text-gray-500">Total aplicado</p>
        </div>

        {{-- Disponible --}}
        <div class="py-5 px-4 sm:px-5">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-amber-500 rounded-lg shrink-0">
                    {{-- Clipboard check --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/></svg>
                </div>
                <p class="text-2xl font-bold text-gray-900 tabular-nums leading-none">{{ number_format($stats['total_disponible']) }}</p>
            </div>
            <p class="mt-2 text-xs text-gray-500">Disponible</p>
        </div>

    </div>

    {{-- Tabla de inventario --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 2 4 4"/><path d="m17 7 3-3"/><path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5"/><path d="m9 11 4 4"/><path d="m5 19-3 3"/><path d="m14 4 6 6"/></svg>
                <h3 class="font-semibold text-gray-800 text-sm">Stock de Biológicos</h3>
            </div>
            {{-- <p class="text-xs text-gray-400 hidden sm:block">Recibido − Aplicado − Pérdidas = Disponible</p> --}}
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left">Biológico</th>
                        <th class="px-4 py-3 text-center">Recibido</th>
                        <th class="px-4 py-3 text-center">Aplicado</th>
                        <th class="px-4 py-3 text-center">Pérdidas</th>
                        <th class="px-4 py-3 text-center font-bold text-gray-700">Disponible</th>
                        <th class="px-4 py-3 text-center">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($inventario as $vacuna)
                    @php
                        $pct     = $vacuna->total_despachado > 0
                                 ? round(($vacuna->disponible / $vacuna->total_despachado) * 100) : 0;
                        $pct     = max(0, min(100, $pct));
                        $perdido = $vacuna->total_perdido ?? 0;

                        if ($vacuna->disponible <= 0) {
                            $estadoClass = 'bg-red-100 text-red-700';
                            $estadoLabel = 'Agotado';
                            $barColor    = 'bg-red-500';
                        } elseif ($pct <= 25) {
                            $estadoClass = 'bg-amber-100 text-amber-700';
                            $estadoLabel = 'Stock bajo';
                            $barColor    = 'bg-amber-400';
                        } else {
                            $estadoClass = 'bg-green-100 text-green-700';
                            $estadoLabel = 'Disponible';
                            $barColor    = 'bg-green-500';
                        }
                    @endphp
                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <div class="p-1.5 bg-blue-100 rounded-lg shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 2 4 4"/><path d="m17 7 3-3"/><path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5"/><path d="m9 11 4 4"/><path d="m5 19-3 3"/><path d="m14 4 6 6"/></svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $vacuna->nombre }}</p>
                                    @if($vacuna->marca)
                                        <p class="text-xs text-gray-400">{{ $vacuna->marca->nombre }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-2 w-full bg-gray-100 rounded-full h-1.5 max-w-[200px]">
                                <div class="{{ $barColor }} h-1.5 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-center font-mono text-gray-600 tabular-nums">
                            {{ number_format($vacuna->total_despachado) }}
                        </td>
                        <td class="px-4 py-3.5 text-center font-mono text-gray-600 tabular-nums">
                            {{ number_format($vacuna->total_usado) }}
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            @if($perdido > 0)
                                <span class="inline-flex items-center gap-1 font-mono text-red-600 font-semibold tabular-nums">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                                    {{ number_format($perdido) }}
                                </span>
                            @else
                                <span class="text-gray-300 font-mono">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="font-bold text-lg tabular-nums {{ $vacuna->disponible > 0 ? 'text-green-700' : 'text-red-600' }}">
                                {{ number_format($vacuna->disponible) }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $estadoClass }}">
                                {{ $estadoLabel }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-16 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-3 text-gray-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"/><path d="m7.5 4.27 9 5.15"/><polyline points="3.29 7 12 12 20.71 7"/><line x1="12" x2="12" y1="22" y2="12"/></svg>
                            <p class="text-sm font-medium text-gray-400">Sin biológicos despachados a este módulo</p>
                            <p class="text-xs text-gray-300 mt-1">Los biológicos aparecerán aquí cuando el ASIC realice un despacho</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Últimos despachos recibidos --}}
    @if($ultimosDespachos->count())
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 10H6"/><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.28a1 1 0 0 0-.684-.948l-1.923-.641a1 1 0 0 1-.578-.502l-1.539-3.076A1 1 0 0 0 16.382 8H14"/><path d="M8 8v4"/><path d="M9 18h6"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/></svg>
                <h3 class="font-semibold text-gray-800 text-sm">Últimos Despachos Recibidos</h3>
            </div>
            <span class="text-xs text-gray-400">{{ $ultimosDespachos->count() }} registro(s)</span>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($ultimosDespachos as $despacho)
            <div class="px-4 py-3.5 flex items-center justify-between hover:bg-gray-50/50 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 2 4 4"/><path d="m17 7 3-3"/><path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5"/><path d="m9 11 4 4"/><path d="m5 19-3 3"/><path d="m14 4 6 6"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ $despacho->vacuna->nombre }}</p>
                        <div class="flex flex-wrap items-center gap-x-2 mt-0.5">
                            <span class="text-xs text-gray-400">{{ $despacho->fecha_envio->format('d/m/Y') }}</span>
                            @if($despacho->lote)
                                <span class="text-gray-300 text-xs">·</span>
                                <span class="text-xs font-mono bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded">
                                    {{ $despacho->lote }}
                                </span>
                            @endif
                            @if($despacho->responsable)
                                <span class="text-gray-300 text-xs">·</span>
                                <span class="text-xs text-gray-400">
                                    {{ $despacho->responsable->nombres }} {{ $despacho->responsable->apellidos }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <span class="font-bold text-emerald-700 text-sm tabular-nums shrink-0 ml-3">
                    +{{ number_format($despacho->cantidad) }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection