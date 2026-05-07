@extends('layouts.app')
@section('title', 'Detalles de Usuario')

@section('content')
<div class="px-4 py-6 mx-auto max-w-2xl bg-white/90 rounded-lg shadow">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-3xl font-semibold text-indigo-800 flex items-center gap-2">
                <div class="p-2 bg-indigo-800 rounded text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-user-star-icon lucide-user-star">
                        <path
                            d="M16.051 12.616a1 1 0 0 1 1.909.024l.737 1.452a1 1 0 0 0 .737.535l1.634.256a1 1 0 0 1 .588 1.806l-1.172 1.168a1 1 0 0 0-.282.866l.259 1.613a1 1 0 0 1-1.541 1.134l-1.465-.75a1 1 0 0 0-.912 0l-1.465.75a1 1 0 0 1-1.539-1.133l.258-1.613a1 1 0 0 0-.282-.866l-1.156-1.153a1 1 0 0 1 .572-1.822l1.633-.256a1 1 0 0 0 .737-.535z" />
                        <path d="M8 15H7a4 4 0 0 0-4 4v2" />
                        <circle cx="10" cy="7" r="4" />
                    </svg>
                </div>
                Detalles de Usuario
            </h1>
        </div>
        <a href="{{ route('users.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Volver
        </a>
    </div>

    {{-- Tarjeta principal --}}
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-4">

        {{-- Banner --}}
        <div class="p-6 bg-linear-to-r from-primary-50 to-blue-50 border-b border-gray-100 flex items-center gap-5">
            <div
                class="w-16 h-16 rounded-full flex items-center justify-center shrink-0
                {{ match($user->nivel_acceso) { 5=>'bg-red-100', 3=>'bg-blue-100', 2=>'bg-yellow-100', 1=>'bg-green-100', default=>'bg-gray-100' } }}">
                <span
                    class="text-2xl font-bold
                    {{ match($user->nivel_acceso) { 5=>'text-red-700', 3=>'text-blue-700', 2=>'text-yellow-700', 1=>'text-green-700', default=>'text-gray-600' } }}">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </span>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ $user->email }}</p>
                <div class="mt-2">
                    @include('user.components._nivel_badge', ['nivel' => $user->nivel_acceso])
                </div>
            </div>
        </div>

        {{-- Datos --}}
        <div class="divide-y divide-gray-50">

            <div class="flex items-center gap-4 p-5">
                <div class="p-2 bg-gray-100 rounded-lg">
                    <i data-lucide="key-round" class="w-4 h-4 text-primary-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Cédula</p>
                    <p class="text-sm font-mono font-bold text-gray-900 mt-0.5">{{ $user->personal_cedula }}</p>
                </div>
            </div>

            {{-- <div class="flex items-center gap-4 p-5">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i data-lucide="user" class="w-4 h-4 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Personal Vinculado</p>
                    <p class="text-sm font-semibold text-gray-900 mt-0.5">
                        {{ $user->personal?->nombre }} {{ $user->personal?->apellido }}
                    </p>
                    @if($user->personal?->cargo)
                    <p class="text-xs text-gray-400 mt-0.5">{{ $user->personal->cargo->nombre }}</p>
                    @endif
                </div>
            </div> --}}

            @if($user->personal?->telefono)
            <div class="flex items-center gap-4 p-5">
                <div class="p-2 bg-gray-100 rounded-lg">
                    <i data-lucide="phone" class="w-4 h-4 text-gray-500"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Teléfono</p>
                    <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $user->personal->telefono }}</p>
                </div>
            </div>
            @endif

            <div class="flex items-center gap-4 p-5">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i data-lucide="calendar-check" class="w-4 h-4 text-green-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Cuenta Creada</p>
                    <p class="text-sm font-semibold text-gray-900 mt-0.5">
                        {{ \Carbon\Carbon::parse($user->created_at)->format('d \d\e F \d\e Y') }}
                    </p>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Hace {{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}
                    </p>
                </div>
            </div>

            {{-- Nivel de acceso visual --}}
            <div class="p-5 bg-gray-50">
                <p class="text-xs text-gray-400 uppercase tracking-wide font-medium mb-3 flex items-center gap-1.5">
                    <i data-lucide="shield" class="w-3.5 h-3.5"></i>
                    Nivel de Acceso
                </p>
                <div class="flex items-center gap-3">
                    @foreach([1,2,3,5] as $n)
                    <div class="flex-1 text-center">
                        <div class="h-2 rounded-full mb-1.5
                            {{ $user->nivel_acceso >= $n
                                ? match($user->nivel_acceso) { 5=>'bg-red-400', 3=>'bg-blue-400', 2=>'bg-yellow-400', 1=>'bg-green-400', default=>'bg-gray-300' }
                                : 'bg-gray-200' }}">
                        </div>
                        <p class="text-xs text-gray-400">Nv. {{ $n }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Acciones --}}
        <div class="flex items-center justify-between px-5 py-4 border-t border-gray-100 bg-gray-50">
            <button type="button" onclick="abrirEliminar({{ $user->id }}, '{{ addslashes($user->name) }}')"
                class="flex items-center gap-2 text-danger bg-red-300 hover:bg-danger hover:text-white focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                <i data-lucide="user-x" class="w-4 h-4"></i>
                Revocar Acceso
            </button>
            <a href="{{ route('users.edit', $user->id) }}"
                class="flex items-center gap-2 text-warning bg-yellow-300 hover:bg-warning hover:text-white focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                <i data-lucide="square-pen" class="w-4 h-4"></i>
                Editar
            </a>
        </div>
    </div>
</div>

@include('user.modals.delete-modal')

@push('scripts')
<script>
    lucide.createIcons();
    function abrirEliminar(id, nombre) {
        document.getElementById('delNombre').textContent = nombre;
        document.getElementById('deleteForm').action = '{{ url("users") }}/' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }
</script>
@endpush
@endsection