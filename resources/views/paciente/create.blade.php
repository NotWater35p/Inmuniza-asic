{{-- create.blade.php --}}
@extends('layouts.app')
@section('title', 'Registrar Paciente')

@section('content')
<div class="px-4 py-6 mx-auto max-w-4xl bg-white/90 rounded-lg shadow-sm backdrop-blur-sm">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-blue-600 flex items-center gap-2">
                <div class="p-2 bg-blue-600 rounded text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                </div>
                Registrar Paciente
            </h1>
            <p class="text-sm text-gray-500 mt-1">Ingresa los datos del nuevo paciente</p>
        </div>
        <a href="{{ route('pacientes.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Volver
        </a>
    </div>

    @if($errors->any())
    <div class="flex items-start gap-3 p-4 mb-5 text-red-800 bg-red-50 border border-red-200 rounded-lg">
        <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
        <ul class="text-sm list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="p-5 border-b border-gray-100 flex items-center gap-2">
            <i data-lucide="user-plus" class="w-4 h-4 text-blue-600"></i>
            <h2 class="text-base font-semibold text-gray-800">Datos del Paciente</h2>
        </div>
        <form method="POST" action="{{ route('pacientes.store') }}">
            @csrf
            @include('paciente.form')
        </form>
    </div>
</div>

@include('paciente.modals.create-etnia')
@include('paciente.modals.create-sector')

@push('scripts')
<script>lucide.createIcons();</script>
@endpush
@endsection