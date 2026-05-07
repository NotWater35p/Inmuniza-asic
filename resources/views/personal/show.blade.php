@extends('layouts.app')
@section('title')
Ver | {{ $personal->nombre }} {{ $personal->apellido }}
@endsection

@section('content')
<div class="px-4 py-6 mx-auto max-w-3xl bg-white/90 backdrop:blur-sm rounded-lg shadow-sm">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-600 flex items-center gap-2">
                <div class="p-2 bg-gray-600 rounded text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11.5 15H7a4 4 0 0 0-4 4v2" />
                        <path
                            d="M21.378 16.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z" />
                        <circle cx="10" cy="7" r="4" />
                    </svg>
                </div>
                Ficha del Personal
            </h1>
        </div>
        <a href="{{ route('personal.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Volver
        </a>
    </div>

    @php
    $nivel = $personal->cargo?->nivel_acceso ?? 0;
    $badgeCfg = match($nivel) {
    5 => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'avatarBg' => 'bg-red-100', 'avatarText' => 'text-red-700'],
    3 => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'avatarBg' => 'bg-blue-100', 'avatarText' =>
    'text-blue-700'],
    2 => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'avatarBg' => 'bg-yellow-100', 'avatarText' =>
    'text-yellow-700'],
    1 => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'avatarBg' => 'bg-green-100', 'avatarText' =>
    'text-green-700'],
    default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'avatarBg' => 'bg-gray-100', 'avatarText' =>
    'text-gray-600'],
    };
    $bannerFrom = match($nivel) {
    5 => 'from-red-700 to-red-900',
    3 => 'from-blue-700 to-blue-900',
    2 => 'from-yellow-600 to-yellow-800',
    1 => 'from-green-700 to-green-900',
    default => 'from-gray-700 to-gray-900',
    };
    @endphp

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">

        {{-- Banner --}}
        <div
            class="bg-linear-to-r {{ $bannerFrom }} px-6 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div
                    class="w-14 h-14 rounded-full {{ $badgeCfg['avatarBg'] }} flex items-center justify-center shrink-0">
                    <span class="text-xl font-bold {{ $badgeCfg['avatarText'] }}">
                        {{ strtoupper(substr($personal->nombre, 0, 1)) }}
                    </span>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">{{ $personal->nombre }} {{ $personal->apellido }}</h2>
                    <div class="flex items-center gap-3 mt-1.5">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badgeCfg['bg'] }} {{ $badgeCfg['text'] }}">
                            {{ $personal->cargo?->nombre ?? 'Sin cargo' }}
                        </span>
                    </div>
                </div>
            </div>
            {{-- Acceso al sistema --}}
            @if($personal->user)
            <span
                class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                Usuario activo en el sistema
            </span>
            @else
            <span
                class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">
                <i data-lucide="shield-off" class="w-3.5 h-3.5"></i>
                Sin acceso al sistema
            </span>
            @endif
        </div>

        {{-- Datos --}}
        <div class="divide-y divide-gray-50">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-0">

                <div class="flex items-start gap-4 p-5">
                    <div class="p-2 bg-gray-100 rounded-lg mt-0.5 shrink-0">
                        <i data-lucide="id-card" class="w-4 h-4 text-gray-500"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Cédula</p>
                        <p class="text-sm font-mono font-bold text-gray-900 mt-0.5">{{ $personal->cedula }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-4 p-5 border-t sm:border-t-0 sm:border-l border-gray-50">
                    <div class="p-2 bg-gray-100 rounded-lg mt-0.5 shrink-0">
                        <i data-lucide="building-2" class="w-4 h-4 text-gray-500"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">ASIC</p>
                        <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $personal->asic?->nombre ?? '—' }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-4 p-5 border-t border-gray-50">
                    <div class="p-2 bg-gray-100 rounded-lg mt-0.5 shrink-0">
                        <i data-lucide="phone" class="w-4 h-4 text-gray-500"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Teléfono</p>
                        <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $personal->telefono ?? '—' }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-4 p-5 border-t border-gray-50 sm:border-l">
                    <div class="p-2 bg-gray-100 rounded-lg mt-0.5 shrink-0">
                        <i data-lucide="mail" class="w-4 h-4 text-gray-500"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Correo electrónico</p>
                        <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $personal->correo ?? '—' }}</p>
                    </div>
                </div>
            </div>

        </div>

        {{-- Acciones --}}
        <div
            class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-4 border-t border-gray-100 bg-gray-50">
            <a href="{{ route('personal.pdf', $personal->cedula) }}"
                class="flex items-center gap-2 text-sm font-medium text-success bg-success-subtle hover:text-white hover:bg-success rounded-lg px-4 py-2.5">
                <i data-lucide="file-down" class="w-4 h-4"></i>
                Ficha PDF
            </a>
            <div class="flex gap-2">
                <button type="button"
                    onclick="abrirEliminarShow('{{ $personal->cedula }}', '{{ addslashes($personal->nombre) }} {{ addslashes($personal->apellido) }}', {{ $personal->user ? 'true' : 'false' }})"
                    class="flex items-center gap-2 text-sm font-medium text-danger bg-danger-subtle hover:text-white hover:bg-danger rounded-lg px-4 py-2.5">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                    Eliminar
                </button>
                <a href="{{ route('personal.edit', $personal->cedula) }}"
                    class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-warning bg-warning-subtle hover:text-white hover:bg-warning rounded-lg">
                    <i data-lucide="square-pen" class="w-4 h-4"></i>
                    Editar
                </a>
            </div>
        </div>
    </div>
</div>

@include('personal.modals.delete-modal')

@push('scripts')
<script>
    lucide.createIcons();
    function abrirEliminarShow(cedula, nombre, tieneUsuario) {
        document.getElementById('deleteShowNombre').textContent = nombre;
        document.getElementById('deleteShowCedula').textContent = 'CI: ' + cedula;
        document.getElementById('deleteShowForm').action = '{{ url("personal") }}/' + cedula;
        const warning = document.getElementById('deleteShowWarning');
        const btn     = document.getElementById('deleteShowBtn');
        warning.classList.toggle('hidden', !tieneUsuario);
        btn.disabled = tieneUsuario;
        document.getElementById('deleteShowModal').classList.remove('hidden');
        lucide.createIcons();
    }
</script>
@endpush
@endsection