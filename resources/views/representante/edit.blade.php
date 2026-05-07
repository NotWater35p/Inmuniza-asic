@extends('layouts.app')

@section('title')
    Editar Representante
@endsection

@section('content')
<section class="bg-white/90 rounded-lg shadow-sm backdrop-blur-sm p-4 max-w-4xl mx-auto">
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-5">
        <div class="mb-6 flex items-center gap-3">
            <div class="p-2 bg-orange-100 rounded-lg text-orange-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-pen"><path d="M11.5 15H7a4 4 0 0 0-4 4v2"/><path d="M21.378 16.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"/><circle cx="10" cy="7" r="4"/></svg>
            </div>
            <h2 class="text-2xl font-semibold text-gray-800">Editar Representante</h2>
        </div>

        <form method="POST" action="{{ route('representantes.update', $representante->cedula) }}" enctype="multipart/form-data">
            @method('PATCH')
            @csrf

            @include('representante.form')

            <div class="mt-8 flex items-center gap-4">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-colors focus:ring-4 focus:ring-orange-300">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-save"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Actualizar Representante
                </button>
                <a href="{{ route('representantes.index') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</section>
@endsection