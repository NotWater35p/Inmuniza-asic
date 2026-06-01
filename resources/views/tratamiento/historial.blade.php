@extends('layouts.app')
@section('title', 'Historial de Vacunación')

@section('content')
<div class="px-4 py-6 mx-auto max-w-4xl bg-white/90 rounded-lg shadow-sm backdrop-blur-sm">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-teal-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                Historial de Vacunación
            </h1>
            <p class="text-sm text-gray-500 mt-1">Historia médica completa del paciente</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('tratamientos.create', ['cedula' => $paciente->cedula]) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-teal-700 rounded-lg hover:bg-teal-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 2 4 4"/><path d="m17 7 3-3"/><path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5"/><path d="m9 11 4 4"/><path d="m5 19-3 3"/><path d="m14 4 6 6"/></svg> Nueva vacunación
            </a>
            <a href="{{ route('pacientes.show', $paciente->id) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> Ficha del paciente
            </a>
        </div>
    </div>

    @php
        $edad = $paciente->fecha_nacimiento
            ? \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age
            : null;
    @endphp

    {{-- Banner paciente --}}
    <div class="bg-linear-to-r from-teal-600 to-teal-800 rounded-xl p-5 mb-6 text-white">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full {{ $paciente->sexo === 'F' ? 'bg-pink-100' : 'bg-teal-100' }} flex items-center justify-center shrink-0">
                <span class="text-xl font-bold {{ $paciente->sexo === 'F' ? 'text-pink-700' : 'text-teal-700' }}">
                    {{ strtoupper(substr($paciente->nombres, 0, 1)) }}
                </span>
            </div>
            <div>
                <h2 class="text-xl font-bold">{{ $paciente->nombres }} {{ $paciente->apellidos }}</h2>
                <div class="flex flex-wrap gap-x-4 gap-y-1 mt-1 text-teal-200 text-sm">
                    <span class="font-mono">CI: {{ $paciente->cedula }}</span>
                    @if($edad !== null) <span>· {{ $edad }} años</span> @endif
                    @if($paciente->sector) <span>· {{ $paciente->sector->nombre }}</span> @endif
                </div>
            </div>
            <div class="ml-auto text-center hidden sm:block">
                <p class="text-teal-200 text-xs">Total vacunas</p>
                <p class="text-3xl font-bold">{{ $historial->flatten()->count() }}</p>
            </div>
        </div>
    </div>

    {{-- Historial por vacuna --}}
    @forelse($historial as $vacunaId => $registros)
    @php
        $v          = $registros->first()->vacuna;
        $numDosis   = $v?->numero_dosis ?? null;
        $recibidas  = $registros->count();
        $completado = $numDosis && $recibidas >= $numDosis;
        $ultimo     = $registros->sortByDesc('fecha_aplicacion')->first();
        $proxima    = $ultimo?->fechaProximaDosis();
        $diffDias   = $proxima ? (int) now()->diffInDays($proxima, false) : null;
    @endphp
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-4">
        <div class="p-4 flex items-center justify-between border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="p-2 {{ $completado ? 'bg-green-100' : 'bg-teal-100' }} rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ $completado ? 'text-green-600' : 'text-teal-600' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 2 4 4"/><path d="m17 7 3-3"/><path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5"/><path d="m9 11 4 4"/><path d="m5 19-3 3"/><path d="m14 4 6 6"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">{{ $v?->nombre ?? '—' }}</h3>
                    @if($v?->marca)
                    <p class="text-xs text-gray-400">{{ $v->marca->nombre }}</p>
                    @endif
                    @if($v?->enfermedad)
                    <p class="text-xs text-gray-500">Previene: {{ $v->enfermedad }}</p>
                    @endif
                </div>
            </div>
            <div class="text-right">
                @if($completado)
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg> Esquema completo
                </span>
                @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-teal-100 text-teal-700 text-xs font-semibold rounded-full">
                    {{ $recibidas }}@if($numDosis)/{{ $numDosis }}@endif dosis
                </span>
                @endif

                {{-- Próxima dosis --}}
                @if($proxima && !$completado)
                <div class="mt-1.5">
                    <p class="text-xs {{ $diffDias < 0 ? 'text-red-600' : ($diffDias <= 7 ? 'text-orange-600' : 'text-teal-600') }} font-medium">
                        Próxima: {{ $proxima->format('d/m/Y') }}
                    </p>
                    <p class="text-xs text-gray-400">
                        {{ $diffDias < 0 ? 'Vencido hace '.abs($diffDias).'d' : 'En '.$diffDias.' días' }}
                    </p>
                </div>
                @elseif($proxima && $completado)
                <div class="mt-1.5">
                    <p class="text-xs text-gray-500 font-medium">
                        Refuerzo: {{ $proxima->format('d/m/Y') }}
                    </p>
                </div>
                @endif
            </div>
        </div>

        {{-- Timeline de dosis --}}
        <div class="p-4">
            <div class="relative">
                {{-- Línea de tiempo --}}
                <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                <div class="space-y-4">
                    @foreach($registros->sortBy('dosis_aplicada') as $r)
                    @php $rProxima = $r->fechaProximaDosis(); @endphp
                    <div class="relative flex items-start gap-4 pl-10">
                        {{-- Círculo de la dosis --}}
                        <div class="absolute left-0 flex items-center justify-center w-9 h-9 rounded-full bg-teal-600 text-white text-sm font-bold shadow-sm shrink-0">
                            {{ $r->dosis_aplicada }}
                        </div>
                        <a href="{{ route('tratamientos.show', $r->id) }}"
                            class="flex-1 p-3 bg-gray-50 hover:bg-teal-50 border border-gray-200 hover:border-teal-200 rounded-lg transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">
                                        Dosis {{ $r->dosis_aplicada }}
                                        @if($numDosis) <span class="text-gray-400 font-normal">/ {{ $numDosis }}</span>@endif
                                    </p>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        {{ $r->fecha_aplicacion?->locale('es')->isoFormat('D [de] MMMM, YYYY') }}
                                    </p>
                                    @if($r->jornada)
                                    <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="m9 16 2 2 4-4"/></svg>
                                        Jornada: {{ $r->jornada->responsable?->apellido }}
                                    </p>
                                    @endif
                                    @if($r->observaciones)
                                    <p class="text-xs text-gray-500 mt-1 italic">{{ \Illuminate\Support\Str::limit($r->observaciones, 60) }}</p>
                                    @endif
                                </div>
                                @if($rProxima && $loop->last)
                                <div class="text-right text-xs ml-2">
                                    <p class="text-gray-400">Próxima</p>
                                    <p class="font-medium text-teal-600">{{ $rProxima->format('d/m/Y') }}</p>
                                </div>
                                @endif
                            </div>
                        </a>
                    </div>
                    @endforeach

                    {{-- Próxima dosis pendiente --}}
                    @if(!$completado && $proxima)
                    <div class="relative flex items-start gap-4 pl-10 opacity-50">
                        <div class="absolute left-0 flex items-center justify-center w-9 h-9 rounded-full border-2 border-dashed border-teal-400 bg-white text-teal-600 text-sm font-bold shrink-0">
                            {{ $recibidas + 1 }}
                        </div>
                        <div class="flex-1 p-3 border border-dashed border-teal-300 rounded-lg bg-teal-50/50">
                            <p class="text-xs font-medium text-teal-700">
                                Dosis {{ $recibidas + 1 }} pendiente
                            </p>
                            <p class="text-xs text-teal-600 mt-0.5">
                                Programada: {{ $proxima->locale('es')->isoFormat('D [de] MMMM, YYYY') }}
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-12 text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-3 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 2 4 4"/><path d="m17 7 3-3"/><path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5"/><path d="m9 11 4 4"/><path d="m5 19-3 3"/><path d="m14 4 6 6"/></svg>
        <p class="text-gray-500 font-semibold">Sin vacunaciones registradas</p>
        <a href="{{ route('tratamientos.create', ['cedula' => $paciente->cedula]) }}"
            class="inline-flex items-center gap-2 mt-3 px-4 py-2 text-sm font-medium text-white bg-teal-700 rounded-lg hover:bg-teal-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg> Registrar primera vacuna
        </a>
    </div>
    @endforelse
</div>

@push('scripts')@endpush
@endsection