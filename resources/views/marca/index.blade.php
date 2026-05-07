@extends('layouts.app')

@section('title')
Marcas
@endsection

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl bg-white/90 backdrop-blur-sm rounded-lg shadow-sm">
    <h1 class="text-2xl font-bold text-brand flex items-center gap-2 mb-4">
        <div class="p-2 bg-brand rounded text-white">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-microscope-icon lucide-microscope">
                <path d="M6 18h8" />
                <path d="M3 22h18" />
                <path d="M14 22a7 7 0 1 0 0-14h-1" />
                <path d="M9 14h2" />
                <path d="M9 12a2 2 0 0 1-2-2V6h6v4a2 2 0 0 1-2 2Z" />
                <path d="M12 6V3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3" />
            </svg>
        </div>
        Marcas Registradas
    </h1>
    <div class="bg-white shadow-lg rounded-lg border border-gray-200">

        <div class="px-6 py-4 flex flex-wrap gap-4 justify-between items-center">
            {{-- Filtro búsqueda --}}
            <form method="GET" action="{{ route('marcas.index') }}" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar..."
                    class="rounded-md border-gray-300 text-sm">
                <button type="submit"
                    class="px-3 py-1 bg-gray-200 text-gray-500 hover:bg-gray-500 hover:text-gray-200 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-search-icon lucide-search">
                        <path d="m21 21-4.34-4.34" />
                        <circle cx="11" cy="11" r="8" />
                    </svg>
                </button>
                @if(request('search'))
                <a href="{{ route('marcas.index') }}"
                    class="inline-flex items-center px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-x-icon lucide-x">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </a>
                @endif
            </form>

            <div class="flex items-center gap-2">

                <a href="{{ route('marcas.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-brand text-white hover:bg-blue-300 hover:text-brand text-sm font-medium rounded transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                    </svg>
                    Nueva
                </a>

                <a href="{{ route('marcas.pdf.universal') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-success text-white hover:bg-green-300 hover:text-success text-sm font-medium rounded transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-file-down-icon lucide-file-down">
                        <path
                            d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z" />
                        <path d="M14 2v5a1 1 0 0 0 1 1h5" />
                        <path d="M12 18v-6" />
                        <path d="m9 15 3 3 3-3" />
                    </svg>
                    Reporte
                </a>

            </div>
        </div>

        @if ($message = Session::get('success'))
        <div
            class="flex items-center gap-3 p-4 mb-5 text-green-800 bg-green-50 border border-green-200 rounded-lg ml-6 mr-6">
            <i data-lucide="check-circle-2" class="w-5 h-5 shrink-0"></i>
            <span class="text-sm font-medium">{{ $message }}</span>
            <button onclick="this.parentElement.remove()" class="ml-auto"><i data-lucide="x"
                    class="w-4 h-4"></i></button>
        </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nombre</th>
                        <th class="px-6 py-4">Descripción</th>
                        <th class="px-6 py-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($marcas as $marca)
                    <tr class="bg-white hover:bg-gray-50">
                        <td class="px-6 py-4">{{ ++$i }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $marca->nombre }}</td>
                        <td class="px-6 py-4">{{ Str::limit($marca->descripcion, 50) ?: '—' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('marcas.show', $marca->id) }}" class="text-blue-500 hover:text-brand"
                                    title="Ver">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </a>
                                <a href="{{ route('marcas.edit', $marca->id) }}"
                                    class="text-yellow-500 hover:text-warning" title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-square-pen-icon lucide-square-pen">
                                        <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path
                                            d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z" />
                                    </svg>
                                </a>
                                <form action="{{ route('marcas.destroy', $marca->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="event.preventDefault(); confirm('¿Eliminar?') ? this.closest('form').submit() : false;"
                                        class="text-red-500 hover:text-danger" title="Eliminar">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M3 6h18" />
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                            <path d="M8 4V2h8v2" />
                                            <line x1="10" x2="10" y1="11" y2="17" />
                                            <line x1="14" x2="14" y1="11" y2="17" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-6">
        {!! $marcas->withQueryString()->links() !!}
    </div>
</div>

@push('scripts')
<script>
    lucide.createIcons();
</script>
@endpush
@endsection