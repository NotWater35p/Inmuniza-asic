@extends('layouts.app')

@section('title')
    Representantes
@endsection

@section('content')
<div class="px-4 sm:px-6 lg:px-3 py-8 w-full max-w-11xl mx-auto">
    <div class="bg-white shadow-lg rounded-lg border border-gray-200">
        {{-- Cabecera --}}
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h1 class="text-3xl font-semibold text-blue-900">
                Lista de Representantes
            </h1>
            <a href="{{ route('representantes.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                Nuevo
            </a>
        </div>

        {{-- Mensaje de éxito --}}
        @if ($message = Session::get('success'))
            <div class="mx-6 mt-4 p-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-green-100" role="alert">
                {{ $message }}
            </div>
        @endif

        {{-- Contenido de la tabla --}}
        <div class="p-4">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3">No</th>
                            <th class="px-6 py-3">Cédula</th>
                            <th class="px-6 py-3">Nombre</th>
                            <th class="px-6 py-3">Apellido</th>
                            <th class="px-6 py-3">Teléfono</th>
                            <th class="px-6 py-3">Dirección</th>
                            <th class="px-6 py-3">Relación</th>
                            <th class="px-6 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($representantes as $representante)
                            <tr class="bg-white hover:bg-gray-50">
                                <td class="px-6 py-4">{{ ++$i }}</td>
                                <td class="px-6 py-4">{{ $representante->cedula }}</td>
                                <td class="px-6 py-4">{{ $representante->nombre }}</td>
                                <td class="px-6 py-4">{{ $representante->apellido }}</td>
                                <td class="px-6 py-4">{{ $representante->telefono }}</td>
                                <td class="px-6 py-4">{{ $representante->direccion }}</td>
                                <td class="px-6 py-4">{{ $representante->relacion }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        {{-- Ver --}}
                                        <a href="{{ route('representantes.show', $representante->cedula) }}"
                                           class="text-blue-600 hover:text-blue-800"
                                           title="Ver">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                                        </a>
                                        {{-- Editar --}}
                                        <a href="{{ route('representantes.edit', $representante->cedula) }}"
                                           class="text-orange-600 hover:text-orange-800"
                                           title="Editar">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                        </a>
                                        {{-- Eliminar --}}
                                        <form action="{{ route('representantes.destroy', $representante->cedula) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="event.preventDefault(); confirm('¿Estás seguro de eliminar?') ? this.closest('form').submit() : false;"
                                                    class="text-red-600 hover:text-red-800"
                                                    title="Eliminar">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 4V2h8v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
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
    </div>

    {{-- Paginación --}}
    <div class="mt-6">
        {!! $representantes->withQueryString()->links() !!}
    </div>
</div>
@endsection