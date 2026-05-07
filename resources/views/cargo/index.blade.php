@extends('layouts.app')

@section('template_title')
Cargos
@endsection

@section('content')

<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-7xl mx-auto">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <div class="p-2 bg-indigo-100 rounded-lg">
                    <i data-lucide="shield-check" class="w-6 h-6 text-indigo-600"></i>
                </div>
                Cargos del Sistema
            </h1>
            <p class="text-sm text-gray-500 mt-1">Roles y niveles de acceso del personal</p>
        </div>
        <a href="{{ route('cargos.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Nuevo Cargo
        </a>
    </div>

    {{-- Mensaje de éxito --}}
    @if(session('success'))
    <div class="flex items-center gap-3 p-4 mb-5 text-green-800 bg-green-50 border border-green-200 rounded-lg">
        <i data-lucide="check-circle-2" class="w-5 h-5 shrink-0"></i>
        <span class="text-sm font-medium">{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="ml-auto"><i data-lucide="x" class="w-4 h-4"></i></button>
    </div>
    @endif

    {{-- Mosaico de tarjetas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($cargos as $cargo)
        @php
        $colors = [
        5 => ['bg' => 'bg-red-50 border-red-200', 'badge' => 'bg-red-100 text-red-700'],
        3 => ['bg' => 'bg-blue-50 border-blue-200', 'badge' => 'bg-blue-100 text-blue-700'],
        2 => ['bg' => 'bg-yellow-50 border-yellow-200', 'badge' => 'bg-yellow-100 text-yellow-700'],
        1 => ['bg' => 'bg-green-50 border-green-200', 'badge' => 'bg-green-100 text-green-700'],
        ];
        $color = $colors[$cargo->nivel_acceso] ?? ['bg' => 'bg-gray-50 border-gray-200', 'badge' => 'bg-gray-100
        text-gray-600'];
        @endphp
        <div class="rounded-xl border shadow-sm hover:shadow-md transition-shadow {{ $color['bg'] }}">
            <div class="p-5">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white rounded-lg">
                            <i data-lucide="{{ $config[$cargo->nivel_acceso]['icon'] ?? 'user' }}"
                                class="w-5 h-5 {{ $color['badge'] }}"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">{{ $cargo->nombre }}</h3>
                    </div>
                    <span class="text-xs font-mono bg-white/60 px-2 py-1 rounded">Nivel {{ $cargo->nivel_acceso
                        }}</span>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-200/60 flex justify-end gap-2">
                    <a href="{{ route('cargos.edit', $cargo->id) }}"
                        class="inline-flex items-center gap-1 text-sm font-medium text-green-600 hover:text-green-800">
                        <i data-lucide="pencil" class="w-4 h-4"></i>
                        Editar
                    </a>
                    @if($cargo->personal()->count() === 0)
                    <button type="button" onclick="confirmDelete({{ $cargo->id }}, '{{ $cargo->nombre }}')"
                        class="inline-flex items-center gap-1 text-sm font-medium text-red-600 hover:text-red-800">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        Eliminar
                    </button>
                    @else
                    <span class="inline-flex items-center gap-1 text-sm text-gray-400 cursor-not-allowed"
                        title="No se puede eliminar: tiene personal asignado">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        Eliminar
                    </span>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full p-10 text-center text-gray-500">
            <i data-lucide="shield-alert" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
            <p class="font-medium">No se encontraron cargos.</p>
            <a href="{{ route('cargos.create') }}" class="text-blue-600 hover:underline">Crear primer cargo</a>
        </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $cargos->links() }}
    </div>
</div>

{{-- Modal de confirmación de eliminación --}}
<div id="deleteModal" class="hidden fixed inset-0 z-50 flex justify-center items-center bg-gray-900/40">
    <div class="relative p-4 w-full max-w-md">
        <div class="bg-white rounded-xl shadow-xl text-center p-6">
            <button onclick="document.getElementById('deleteModal').classList.add('hidden')"
                class="absolute top-3 right-3 text-gray-400 hover:bg-gray-100 rounded-lg p-1.5">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
            <div class="mx-auto mb-4 w-14 h-14 bg-red-100 rounded-full flex items-center justify-center">
                <i data-lucide="trash-2" class="w-7 h-7 text-red-600"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">¿Eliminar este cargo?</h3>
            <p id="deleteNombre" class="text-sm font-medium text-gray-700 mb-4"></p>
            <p class="text-sm text-gray-500 mb-6">Solo se pueden eliminar cargos sin personal asignado.</p>
            <div class="flex justify-center gap-3">
                <button onclick="document.getElementById('deleteModal').classList.add('hidden')"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancelar
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        Sí, eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    lucide.createIcons();

    function confirmDelete(id, nombre) {
        document.getElementById('deleteNombre').textContent = nombre;
        document.getElementById('deleteForm').action = '{{ url("cargos") }}/' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }
</script>
@endpush
@endsection