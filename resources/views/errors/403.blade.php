@extends('layouts.app')
@section('title', 'Acceso denegado')

@section('content')
<div class="flex flex-col items-center justify-center py-28 text-center px-4 bg-white/90 backdrop-blur-sm shadow">
    <div class="p-4 bg-red-100 rounded-full mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600"><circle cx="12" cy="12" r="10"/><path d="m4.9 4.9 14.2 14.2"/></svg>
    </div>
    <h1 class="text-4xl font-bold text-gray-900 mb-2">403</h1>
    <p class="text-lg font-semibold text-gray-700 mb-1">Acceso denegado</p>
    <p class="text-sm text-gray-400 mb-8 max-w-sm">
        No tienes permisos para acceder a esta sección del sistema.
    </p>
    <a href="{{ auth()->user()?->esJefeModulo() ? route('modulo.dashboard') : route('inicio') }}"
        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
        Volver al inicio
    </a>
</div>
@push('scripts')<script>lucide.createIcons();</script>@endpush
@endsection