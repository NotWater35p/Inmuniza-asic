{{-- edit.blade.php --}}
@extends('layouts.app')
@section('title')
    Editar| {{ $paciente->nombres }} {{ $paciente->apellidos }}<
@endsection

@section('content')
<div class="px-4 py-6 mx-auto max-w-4xl bg-white/90 rounded-lg shadow-sm backdrop-blur-sm">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-warning flex items-center gap-2">
                <div class="p-2 bg-warning rounded text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11.5 15H7a4 4 0 0 0-4 4v2"/><path d="M21.378 16.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"/><circle cx="10" cy="7" r="4"/></svg>
                </div>
                Editar Paciente
            </h1>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('pacientes.show', $paciente->id) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <i data-lucide="eye" class="w-4 h-4"></i>
                Ver
            </a>
            <a href="{{ route('pacientes.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Volver
            </a>
        </div>
    </div>

    @if($errors->any())
    <div class="flex items-start gap-3 p-4 mb-5 text-red-800 bg-red-50 border border-red-200 rounded-lg">
        <i data-lucide="alert-circle" class="w-5 h-5 hrink-0 mt-0.5"></i>
        <ul class="text-sm list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="p-5 border-b border-gray-100 flex items-center gap-2">
            <i data-lucide="pencil" class="w-4 h-4 text-green-600"></i>
            <h2 class="text-base font-semibold text-gray-800">Datos del Paciente</h2>
        </div>
        <form method="POST" action="{{ route('pacientes.update', $paciente->id) }}">
            @csrf @method('PUT')
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