@extends('layouts.app')
@section('title', 'Editar Marca · ' . $marca->nombre)

@section('content')
<div class="px-4 py-6 mx-auto max-w-2xl bg-white/80 rounded-lg shadow backdrop-blur-lg">

    {{-- Header --}}
    <div class="mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <h1 class="text-xl font-bold text-gray-900 flex items-center gap-2.5">
            <div class="p-2 bg-amber-500 rounded-lg text-white shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg>
            </div>
            Editar Marca
        </h1>
        <div class="flex gap-2">
            <a href="{{ route('marcas.show', $marca->id) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                Ver
            </a>
            <a href="{{ route('marcas.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                Volver
            </a>
        </div>
    </div>

    {{-- Errores --}}
    @if($errors->any())
    <div class="mb-4 flex items-start gap-2.5 p-3.5 text-sm text-red-800 bg-red-50 border border-red-200 rounded-lg">
        <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    {{-- Formulario --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <form method="POST" action="{{ route('marcas.update', $marca->id) }}">
            @method('PUT')
            @csrf
            @include('marca.form')
        </form>
    </div>

</div>
@endsection