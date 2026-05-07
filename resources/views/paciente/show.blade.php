@extends('layouts.app')
@section('title')
Detalles| {{ $paciente->nombres }} {{ $paciente->apellidos }}< @endsection @section('content') <div
    class="px-4 py-6 mx-auto max-w-4xl bg-white/90 rounded-lg shadow-sm backdrop-blur-sm">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-blue-800 flex items-center gap-2">
                <div class="p-2 bg-blue-800 rounded text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-clipboard-plus-icon lucide-clipboard-plus">
                        <rect width="8" height="4" x="8" y="2" rx="1" ry="1" />
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                        <path d="M9 14h6" />
                        <path d="M12 17v-6" />
                    </svg>
                </div>
                Detalles del Pacientes
            </h1>
        </div>
        <a href="{{ route('tratamientos.historial.paciente', $paciente->id) }}"
            class="flex items-center gap-2 text-sm font-medium text-teal-700 bg-teal-100 hover:bg-teal-600 hover:text-white rounded-lg px-4 py-2.5 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 12h6" />
                <path d="M12 9v6" />
                <path d="M4 6V4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2" />
                <rect width="8" height="6" x="2" y="9" rx="1" />
            </svg>
            Historial Clínico
        </a>
        <a href="{{ route('pacientes.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Volver
        </a>
    </div>

    @php
    $edad = $paciente->fecha_nacimiento ? \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age : null;
    $esMenor = $edad !== null && $edad < 18; @endphp {{-- Banner --}} <div
        class="bg-linear-to-r from-blue-600 to-blue-800 rounded-xl p-6 mb-5 text-white">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full flex items-center justify-center shrink-0
                    {{ $paciente->sexo === 'F' ? 'bg-pink-100' : 'bg-blue-100' }}">
                    <span class="text-xl font-bold {{ $paciente->sexo === 'F' ? 'text-pink-700' : 'text-blue-700' }}">
                        {{ strtoupper(substr($paciente->nombres, 0, 1)) }}
                    </span>
                </div>
                <div>
                    <h2 class="text-xl font-bold">{{ $paciente->nombres }} {{ $paciente->apellidos }}</h2>
                    <div class="flex flex-wrap items-center gap-2 mt-1.5">
                        @if($paciente->cedula)
                        <span class="text-sm text-blue-200 font-mono">CI: {{ $paciente->cedula }}</span>
                        @endif
                        @if($edad !== null)
                        <span class="text-sm text-blue-200">· {{ $edad }} años</span>
                        @endif
                        @if($esMenor)
                        <span
                            class="px-2 py-0.5 bg-amber-400/20 text-amber-200 text-xs font-medium rounded-full border border-amber-400/30">Menor
                            de edad</span>
                        @endif
                    </div>
                </div>
            </div>
            <span class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold
                {{ $paciente->activo ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                <i data-lucide="{{ $paciente->activo ? 'circle-check' : 'circle-x' }}" class="w-3.5 h-3.5"></i>
                {{ $paciente->activo ? 'Paciente Activo' : 'Inactivo' }}
            </span>
        </div>
        </div>

        {{-- Grid datos --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">

            {{-- Datos personales --}}
            <div class="lg:col-span-2 bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex items-center gap-2">
                    <i data-lucide="user" class="w-4 h-4 text-blue-600"></i>
                    <h3 class="text-sm font-semibold text-gray-800">Datos Personales</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 divide-y sm:divide-y-0 sm:divide-x divide-gray-50">
                    @php
                    $campos = [
                    ['label' => 'Fecha de Nacimiento', 'icon' => 'calendar', 'valor' =>
                    $paciente->fecha_nacimiento?->format('d/m/Y') ?? '—'],
                    ['label' => 'Sexo', 'icon' => 'user', 'valor' => $paciente->sexo === 'M' ? 'Masculino' :
                    ($paciente->sexo
                    === 'F' ? 'Femenino' : '—')],
                    ['label' => 'Teléfono', 'icon' => 'phone', 'valor' => $paciente->telefono ?? '—'],
                    ['label' => 'Etnia', 'icon' => 'globe', 'valor' => $paciente->etnia?->nombre ?? 'No especificada'],
                    ];
                    @endphp
                    @foreach($campos as $campo)
                    <div class="flex items-start gap-3 p-4">
                        <div class="p-1.5 bg-gray-100 rounded-lg mt-0.5 shrink-0">
                            <i data-lucide="{{ $campo['icon'] }}" class="w-3.5 h-3.5 text-gray-500"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">{{ $campo['label'] }}
                            </p>
                            <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $campo['valor'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Dirección y sector --}}
                <div class="border-t border-gray-50 p-4 flex items-start gap-3">
                    <div class="p-1.5 bg-gray-100 rounded-lg mt-0.5 shrink-0">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-gray-500"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Dirección / Sector</p>
                        <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $paciente->direccion ?? '—' }}</p>
                        @if($paciente->sector)
                        <span
                            class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 bg-teal-100 text-teal-700 text-xs rounded-full font-medium">
                            <i data-lucide="map-pin" class="w-3 h-3"></i>
                            {{ $paciente->sector->nombre }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Representante --}}
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex items-center gap-2">
                    <i data-lucide="users" class="w-4 h-4 text-amber-600"></i>
                    <h3 class="text-sm font-semibold text-gray-800">Representante</h3>
                </div>
                @if($paciente->representante)
                <div class="p-4 space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
                            <i data-lucide="user" class="w-5 h-5 text-amber-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 font-mono">
                                CI: {{ $paciente->representante->cedula }}
                            </p>
                            @if($paciente->representante->relacion)
                            <span class="text-xs bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded font-medium">
                                {{ $paciente->representante->relacion }}
                            </span>
                            @endif
                        </div>
                    </div>
                    @if($paciente->representante->telefono)
                    <div class="pt-2 border-t border-gray-50">
                        <p class="text-xs text-gray-400 flex items-center gap-1.5">
                            <i data-lucide="phone" class="w-3 h-3"></i>
                            {{ $paciente->representante->telefono }}
                        </p>
                    </div>
                    @endif
                </div>
                @else
                <div class="p-6 text-center text-gray-400">
                    <i data-lucide="users" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                    <p class="text-xs">Sin representante asignado</p>
                    @if($esMenor)
                    <p class="text-xs text-amber-600 mt-1">⚠ El paciente es menor de edad</p>
                    @endif
                </div>
                @endif
            </div>
        </div>

        {{-- Acciones --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-4">
                <a href="{{ route('pacientes.pdf', $paciente->id) }}"
                    class="flex items-center gap-2 text-sm font-medium text-success bg-success-subtle hover:text-white hover:bg-success rounded-lg px-4 py-2.5">
                    <i data-lucide="file-down" class="w-4 h-4"></i>
                    Ficha PDF
                </a>
                <div class="flex gap-2">
                    <button type="button"
                        onclick="abrirEliminarShow({{ $paciente->id }}, '{{ addslashes($paciente->nombres) }} {{ addslashes($paciente->apellidos) }}')"
                        class="flex items-center gap-2 text-sm font-medium text-danger bg-danger-subtle hover:text-white hover:bg-danger rounded-lg px-4 py-2.5">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        Eliminar
                    </button>
                    <a href="{{ route('pacientes.edit', $paciente->id) }}"
                        class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-warning bg-warning-subtle hover:text-white hover:bg-warning rounded-lg">
                        <i data-lucide="square-pen" class="w-4 h-4"></i>
                        Editar
                    </a>
                </div>
            </div>
        </div>
        </div>

        @include('paciente.modals.delete-modal')

        @push('scripts')
        <script>
            lucide.createIcons();
    function abrirEliminarShow(id, nombre) {
        document.getElementById('deleteShowNombre').textContent = nombre;
        document.getElementById('deleteShowForm').action = '{{ url("pacientes") }}/' + id;
        document.getElementById('deleteShowModal').classList.remove('hidden');
    }
        </script>
        @endpush
        @endsection