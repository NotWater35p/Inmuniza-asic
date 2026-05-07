@extends('layouts.app')

@section('title', $marca->nombre . ' | Marca')

@section('content')
<div class="px-4 py-6 mx-auto max-w-3xl bg-white/90 rounded-lg shadow backdrop-blur-sm">

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-red-800 flex items-center gap-2">
                <div class="p-2 bg-red-800 rounded text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-scan-eye-icon lucide-scan-eye"><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><circle cx="12" cy="12" r="1"/><path d="M18.944 12.33a1 1 0 0 0 0-.66 7.5 7.5 0 0 0-13.888 0 1 1 0 0 0 0 .66 7.5 7.5 0 0 0 13.888 0"/></svg>
                </div>
                Detalles de la Marca
            </h1>
        </div>
        <a href="{{ route('marcas.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Volver
        </a>
    </div>

    {{-- Banner --}}
    <div class="bg-linear-to-r from-red-400 to-orange-400 rounded-xl p-6 mb-5 text-white">
        <div class="flex items-start justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-factory-icon lucide-factory"><path d="M12 16h.01"/><path d="M16 16h.01"/><path d="M3 19a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8.5a.5.5 0 0 0-.769-.422l-4.462 2.844A.5.5 0 0 1 15 10.5v-2a.5.5 0 0 0-.769-.422L9.77 10.922A.5.5 0 0 1 9 10.5V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2z"/><path d="M8 16h.01"/></svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold">{{ $marca->nombre }}</h2>
                    <span class="text-sm text-purple-200 mt-0.5 block">Marca</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Información de la Marca --}}
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-5">
        <div class="p-4 border-b border-gray-100 flex items-center gap-2">
            <i data-lucide="info" class="w-4 h-4 text-orange-600"></i>
            <h3 class="text-sm font-semibold text-gray-800">Información de la Marca</h3>
        </div>

        <div class="divide-y divide-gray-50">
            {{-- Descripción --}}
            <div class="flex items-start gap-4 p-5">
                <div class="p-2 bg-gray-100 rounded-lg shrink-0 mt-0.5">
                    <i data-lucide="file-text" class="w-4 h-4 text-gray-500"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Descripción</p>
                    <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $marca->descripcion ?: '—' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Vacunas Asociadas --}}
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-5">
        <div class="p-4 border-b border-gray-100 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-orange-600 lucide lucide-syringe-icon lucide-syringe"><path d="m18 2 4 4"/><path d="m17 7 3-3"/><path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5"/><path d="m9 11 4 4"/><path d="m5 19-3 3"/><path d="m14 4 6 6"/></svg>
            <h3 class="text-sm font-semibold text-gray-800">
                Vacunas Asociadas ({{ $marca->vacunas->count() }})
            </h3>
        </div>

        <div class="divide-y divide-gray-50">
            @forelse($marca->vacunas as $vacuna)
                <div class="flex items-center justify-between p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-100 rounded-lg shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600"><path d="m18 2 4 4"/><path d="m17 7 3-3"/><path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5"/><path d="m9 11 4 4"/><path d="m5 19-3 3"/><path d="m14 4 6 6"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $vacuna->nombre }}</p>
                            <p class="text-xs text-gray-500 flex flex-wrap gap-x-3 gap-y-0.5 mt-0.5">
                                @if($vacuna->enfermedad)
                                    <span>{{ $vacuna->enfermedad }}</span>
                                @endif
                                @if($vacuna->presentacion)
                                    <span>{{ $vacuna->presentacion }}</span>
                                @endif
                                @if($vacuna->numero_dosis)
                                    <span>{{ $vacuna->numero_dosis }} dosis</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('vacunas.show', $vacuna->id) }}"
                        class="flex items-center gap-1.5 text-sm font-medium text-blue-700 bg-blue-100 hover:bg-blue-600 hover:text-white px-3 py-1.5 rounded-lg transition-colors">
                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                        <span>Ver</span>
                    </a>
                </div>
            @empty
                <div class="p-6 text-center text-sm text-gray-500">
                    No hay vacunas registradas para esta marca.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Acciones --}}
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="flex items-center justify-between px-5 py-4">
            <div class="flex gap-2">
                <a href="{{ route('marcas.pdf', $marca->id) }}"
                    class="flex items-center gap-2 text-sm font-medium text-emerald-700 bg-emerald-100 hover:bg-emerald-600 hover:text-white rounded-lg px-4 py-2.5 transition-colors">
                    <i data-lucide="file-down" class="w-4 h-4"></i>
                    PDF
                </a>
            </div>
            {{-- Si necesitas botones Editar/Eliminar, puedes agregarlos aquí con el mismo estilo que en Módulo --}}
        </div>
    </div>
</div>

@push('scripts')
<script>
    lucide.createIcons();
</script>
@endpush
@endsection