{{-- create.blade.php --}}
@extends('layouts.app')
@section('title', 'Nueva Jornada')

@section('content')
<div class="px-4 py-6 mx-auto max-w-2xl bg-white/90 rounded-lg shadow-sm backdrop-blur-sm">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <div class="p-2 bg-emerald-600 rounded text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="m9 16 2 2 4-4"/></svg>
                </div>
                Nueva Jornada
            </h1>
            <p class="text-sm text-gray-500 mt-1">Registra una sesión de vacunación</p>
        </div>
        <a href="{{ route('jornadas.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Volver
        </a>
    </div>

    @if($errors->any())
    <div class="flex items-start gap-3 p-4 mb-5 text-red-800 bg-red-50 border border-red-200 rounded-lg">
        <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
        <ul class="text-sm list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="p-5 border-b border-gray-100 flex items-center gap-2">
            <i data-lucide="calendar-plus" class="w-4 h-4 text-emerald-600"></i>
            <h2 class="text-base font-semibold text-gray-800">Datos de la Jornada</h2>
        </div>
        <form method="POST" action="{{ route('jornadas.store') }}">
            @csrf
            @include('jornada.form')
        </form>
    </div>
</div>
@push('scripts')<script>lucide.createIcons();</script>@endpush
@endsection