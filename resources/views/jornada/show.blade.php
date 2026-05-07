@extends('layouts.app')
@section('title', 'Detalle de Jornada')

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl bg-white/90 rounded-lg shadow-sm backdrop-blur-sm">

    @if(session('success'))
    <div class="flex items-center gap-3 p-4 mb-5 text-green-800 bg-green-50 border border-green-200 rounded-lg">
        <i data-lucide="check-circle-2" class="w-5 h-5"></i>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <i data-lucide="calendar-check-2" class="w-6 h-6 text-emerald-600"></i>
                Jornada de Vacunación
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ \Carbon\Carbon::parse($jornada->fecha_jornada)->locale('es')->isoFormat('dddd, D [de] MMMM [de]
                YYYY') }}
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('tratamientos.create', ['jornada_id' => $jornada->id]) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                <i data-lucide="syringe" class="w-4 h-4"></i> Registrar vacunación
            </a>
            <a href="{{ route('jornadas.edit', $jornada->id) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <i data-lucide="pencil" class="w-4 h-4"></i>
            </a>
            <a href="{{ route('jornadas.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Volver
            </a>
        </div>
    </div>

    {{-- Info de la jornada --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm flex items-center gap-3">
            <div class="p-2 bg-emerald-100 rounded-lg">
                <i data-lucide="user-check" class="w-5 h-5 text-emerald-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide">Responsable</p>
                <p class="text-sm font-bold text-gray-900">{{ $jornada->responsable?->nombre }} {{
                    $jornada->responsable?->apellido }}</p>
                <p class="text-xs text-gray-500">{{ $jornada->responsable?->cargo?->nombre ?? '' }}</p>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm flex items-center gap-3">
            <div class="p-2 bg-teal-100 rounded-lg">
                <i data-lucide="syringe" class="w-5 h-5 text-teal-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide">Vacunaciones</p>
                <p class="text-3xl font-bold text-gray-900">{{ $jornada->tratamientos->count() }}</p>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm flex items-center gap-3">
            <div class="p-2 bg-blue-100 rounded-lg">
                <i data-lucide="users" class="w-5 h-5 text-blue-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide">Pacientes únicos</p>
                <p class="text-3xl font-bold text-gray-900">
                    {{ $jornada->tratamientos->unique('paciente_id')->count() }}
                </p>
            </div>
        </div>
        @if($jornada->modulo)
        <div
            class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm flex items-center gap-3 sm:col-span-3 md:col-span-1">
            <div class="p-2 bg-purple-100 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="text-purple-600">
                    <path d="M12 6v4" />
                    <path d="M14 14h-4" />
                    <path d="M14 18h-4" />
                    <path d="M14 8h-4" />
                    <path d="M18 12h2a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-9a2 2 0 0 1 2-2h2" />
                    <path d="M18 22V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v18" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide">Módulo</p>
                <p class="text-sm font-bold text-gray-900">{{ $jornada->modulo->nombre }}</p>
            </div>
        </div>
        @endif
    </div>

    @if($jornada->descripcion)
    <div class="bg-emerald-50 border border-emerald-100 rounded-lg p-4 mb-5 flex items-start gap-2.5">
        <i data-lucide="file-text" class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5"></i>
        <p class="text-sm text-emerald-800">{{ $jornada->descripcion }}</p>
    </div>
    @endif

    {{-- Tabla de tratamientos --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="list" class="w-4 h-4 text-teal-600"></i>
                <h3 class="text-sm font-semibold text-gray-800">Vacunaciones en esta Jornada</h3>
            </div>
            <a href="{{ route('tratamientos.create', ['jornada_id' => $jornada->id]) }}"
                class="flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i> Agregar
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left">Paciente</th>
                        <th class="px-4 py-3 text-left">Vacuna</th>
                        <th class="px-4 py-3 text-center">Dosis</th>
                        <th class="px-4 py-3 text-left hidden md:table-cell">Próxima dosis</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($jornada->tratamientos as $t)
                    @php $proxima = $t->fechaProximaDosis(); @endphp
                    <tr class="hover:bg-gray-50/70">
                        <td class="px-4 py-3">
                            <p class="font-semibold text-gray-900 text-sm">
                                {{ $t->paciente?->nombres }} {{ $t->paciente?->apellidos }}
                            </p>
                            <p class="text-xs text-gray-400 font-mono">
                                @if($t->paciente?->cedula)
                                CI: {{ $t->paciente->cedula }}
                                @else
                                Sin cédula
                                @endif
                            </p>
                        </td>
                        <td class="px-4 py-3">
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-700">
                                <i data-lucide="syringe" class="w-3 h-3"></i>
                                {{ $t->vacuna?->nombre }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span
                                class="inline-flex items-center justify-center w-7 h-7 text-xs font-bold text-white bg-teal-600 rounded-full">
                                {{ $t->dosis_aplicada }}
                            </span>
                            @if($t->vacuna?->numero_dosis)
                            <span class="text-xs text-gray-400">/{{ $t->vacuna->numero_dosis }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell">
                            @if($proxima)
                            @php $diffDias = now()->diffInDays($proxima, false); @endphp
                            <p
                                class="text-sm font-medium {{ $diffDias < 0 ? 'text-red-600' : ($diffDias <= 7 ? 'text-orange-600' : 'text-gray-700') }}">
                                {{ $proxima->locale('es')->isoFormat('D MMM, YYYY') }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ $diffDias < 0 ? 'Hace ' .abs($diffDias).' días' : 'En ' .$diffDias.' días' }} </p>
                                    @else
                                    <span class="text-xs text-gray-400">—</span>
                                    @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-1.5">
                                <a href="{{ route('tratamientos.show', $t->id) }}"
                                    class="p-1.5 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('tratamientos.edit', $t->id) }}"
                                    class="p-1.5 text-yellow-500 hover:text-yellow-700 hover:bg-yellow-50 rounded-lg">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-gray-400">
                            <i data-lucide="syringe" class="w-10 h-10 mx-auto mb-2 text-gray-300"></i>
                            <p class="text-sm">Sin vacunaciones en esta jornada.</p>
                            <a href="{{ route('tratamientos.create', ['jornada_id' => $jornada->id]) }}"
                                class="text-teal-600 hover:underline text-sm mt-1 inline-block">Registrar primera
                                vacunación</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')<script>
    lucide.createIcons();
</script>@endpush
@endsection