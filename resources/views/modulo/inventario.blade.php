@extends('layouts.app')
@section('title', 'Inventario · ' . $modulo->nombre)

@section('content')
<div class="px-4 py-6 mx-auto max-w-5xl bg-white/90 rounded-lg shadow backdrop-blur-sm">

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-blue-800 flex items-center gap-2">
                <div class="p-2 bg-blue-800 rounded text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z" />
                        <path d="M12 22V12" />
                        <path d="m3.3 7 7.703 4.734a2 2 0 0 0 1.994 0L20.7 7" />
                        <path d="m7.5 4.27 9 5.15" />
                    </svg>
                </div>
                Inventario del Módulo
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ $modulo->nombre }} · {{ $modulo->asic->nombre ?? '' }}
            </p>
        </div>
        <div class="flex gap-2 shrink-0">
            {{-- Solo admin puede ir a despachar --}}
            @if(auth()->user()->esAdmin())
            <a href="{{ route('despachos.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10 10H6" />
                    <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2" />
                    <path
                        d="M19 18h2a1 1 0 0 0 1-1v-3.28a1 1 0 0 0-.684-.948l-1.923-.641a1 1 0 0 1-.578-.502l-1.539-3.076A1 1 0 0 0 16.382 8H14" />
                    <path d="M8 8v4" />
                    <path d="M9 18h6" />
                    <circle cx="17" cy="18" r="2" />
                    <circle cx="7" cy="18" r="2" />
                </svg>
                Nuevo Despacho
            </a>
            @endif
            <a href="{{ auth()->user()->esAdmin() ? route('modulos.index') : route('modulo.dashboard') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 19-7-7 7-7" />
                    <path d="M19 12H5" />
                </svg>
                Volver
            </a>
        </div>
    </div>

    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
        class="lucide lucide-syringe-icon lucide-syringe">
        <path>
    </svg>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
        @php
        $statCards = [
        [
        'label' => 'Vacunas activas',
        'valor' => $stats['total_vacunas'],
        'color' => 'blue',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
            stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m18 2 4 4" />
            <path d="m17 7 3-3" />
            <path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5" />
            <path d="m9 11 4 4" />
            <path d="m5 19-3 3" />
            <path d="m14 4 6 6" />
        </svg>'
        ],
        [
        'label' => 'Total recibido',
        'valor' => $stats['total_recibido'],
        'color' => 'emerald',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
            stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path
                d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z" />
            <path d="M12 10v6" />
            <path d="m9 13 3-3 3 3" />
        </svg>'
        ],
        [
        'label' => 'Total usado',
        'valor' => $stats['total_usado'],
        'color' => 'violet',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
            stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path
                d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z" />
            <path d="M12 10v6" />
            <path d="m15 13-3 3-3-3" />
        </svg>'
        ],
        [
        'label' => 'Disponible',
        'valor' => $stats['total_disponible'],
        'color' => 'amber',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
            stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect width="8" height="4" x="8" y="2" rx="1" ry="1" />
            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
            <path d="M12 11h4" />
            <path d="M12 16h4" />
            <path d="M8 11h.01" />
            <path d="M8 16h.01" />
        </svg>'
        ],
        ];

        $colores = [
        'blue' => ['bg-blue-50', 'bg-blue-600', 'text-blue-700'],
        'emerald' => ['bg-emerald-50', 'bg-emerald-600', 'text-emerald-700'],
        'violet' => ['bg-violet-50', 'bg-violet-600', 'text-violet-700'],
        'amber' => ['bg-amber-50', 'bg-amber-600', 'text-amber-700'],
        ];
        @endphp

        @foreach($statCards as $card)
        @php $c = $colores[$card['color']]; @endphp
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 flex items-center gap-3">
            <div class="p-2.5 {{ $c[1] }} rounded-xl shrink-0">
                {!! $card['icon'] !!}
            </div>
            <div>
                <p class="text-xl font-bold {{ $c[2] }}">{{ number_format($card['valor']) }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $card['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Tabla de inventario --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-5">
        <div class="p-4 border-b border-gray-100 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="text-blue-600">
                <path d="m18 2 4 4" />
                <path d="m17 7 3-3" />
                <path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5" />
                <path d="m9 11 4 4" />
                <path d="m5 19-3 3" />
                <path d="m14 4 6 6" />
            </svg>
            <h3 class="font-semibold text-gray-800">Stock de Vacunas</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left">Vacuna</th>
                        <th class="px-4 py-3 text-center">Recibido</th>
                        <th class="px-4 py-3 text-center">Usado</th>
                        <th class="px-4 py-3 text-center font-bold text-gray-700">Disponible</th>
                        <th class="px-4 py-3 text-center">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($inventario as $vacuna)
                    @php
                    $pct = $vacuna->total_despachado > 0
                    ? round(($vacuna->disponible / $vacuna->total_despachado) * 100)
                    : 0;
                    $pct = max(0, min(100, $pct));
                    if ($vacuna->disponible <= 0) { $estadoClass='bg-red-100 text-red-700' ; $estadoLabel='Agotado' ;
                        $barColor='bg-red-500' ; } elseif ($pct <=25) { $estadoClass='bg-amber-100 text-amber-700' ;
                        $estadoLabel='Stock bajo' ; $barColor='bg-amber-400' ; } else {
                        $estadoClass='bg-green-100 text-green-700' ; $estadoLabel='Disponible' ;
                        $barColor='bg-green-500' ; } @endphp <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2.5">
                                <div class="p-1.5 bg-blue-100 rounded-lg shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="text-blue-600">
                                        <path d="m18 2 4 4" />
                                        <path d="m17 7 3-3" />
                                        <path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5" />
                                        <path d="m9 11 4 4" />
                                        <path d="m5 19-3 3" />
                                        <path d="m14 4 6 6" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $vacuna->nombre }}</p>
                                    @if($vacuna->marca)
                                    <p class="text-xs text-gray-400">{{ $vacuna->marca->nombre }}</p>
                                    @endif
                                </div>
                            </div>
                            {{-- Barra de progreso --}}
                            <div class="mt-2 w-full bg-gray-100 rounded-full h-1.5">
                                <div class="{{ $barColor }} h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center font-mono text-gray-600">
                            {{ number_format($vacuna->total_despachado) }}
                        </td>
                        <td class="px-4 py-3 text-center font-mono text-gray-600">
                            {{ number_format($vacuna->total_usado) }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span
                                class="font-bold text-lg {{ $vacuna->disponible > 0 ? 'text-green-700' : 'text-red-600' }}">
                                {{ number_format($vacuna->disponible) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $estadoClass }}">
                                {{ $estadoLabel }}
                            </span>
                        </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-16 text-center text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" class="mx-auto mb-3 text-gray-300">
                                    <path
                                        d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14" />
                                    <path d="m7.5 4.27 9 5.15" />
                                    <polyline points="3.29 7 12 12 20.71 7" />
                                    <line x1="12" x2="12" y1="22" y2="12" />
                                    <circle cx="18.5" cy="15.5" r="2.5" />
                                    <path d="M20.27 17.27 22 19" />
                                </svg>
                                <p class="font-medium text-gray-500">Sin vacunas despachadas a este módulo</p>
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
        <div class="p-4 border-b border-gray-100 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="text-emerald-600">
                <path d="M10 10H6" />
                <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2" />
                <path
                    d="M19 18h2a1 1 0 0 0 1-1v-3.28a1 1 0 0 0-.684-.948l-1.923-.641a1 1 0 0 1-.578-.502l-1.539-3.076A1 1 0 0 0 16.382 8H14" />
                <path d="M8 8v4" />
                <path d="M9 18h6" />
                <circle cx="17" cy="18" r="2" />
                <circle cx="7" cy="18" r="2" />
            </svg>
            <h3 class="font-semibold text-gray-800">Últimos Despachos Recibidos</h3>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($ultimosDespachos as $despacho)
            <div class="px-4 py-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-1.5 bg-emerald-100 rounded-lg shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="text-emerald-600">
                            <path d="m18 2 4 4" />
                            <path d="m17 7 3-3" />
                            <path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5" />
                            <path d="m9 11 4 4" />
                            <path d="m5 19-3 3" />
                            <path d="m14 4 6 6" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ $despacho->vacuna->nombre }}</p>
                        <p class="text-xs text-gray-400">
                            {{ $despacho->fecha_envio->format('d/m/Y') }}
                            @if($despacho->lote) · Lote: <span class="font-mono">{{ $despacho->lote }}</span>@endif
                        </p>
                    </div>
                </div>
                <span class="font-bold text-emerald-700 text-sm">
                    +{{ number_format($despacho->cantidad) }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

@push('scripts')
<script>
    lucide.createIcons();
</script>
@endpush
@endsection