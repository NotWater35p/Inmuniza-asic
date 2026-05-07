@extends('layouts.app')
@section('title', 'Editar | ' . $vacuna->nombre)

@section('content')
<div class="px-4 py-6 mx-auto max-w-3xl bg-white/90 rounded-lg shadow backdrop-blur-sm">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-amber-700 flex items-center gap-2">
            <div class="p-2 bg-amber-600 rounded text-white">
                <i data-lucide="square-pen" class="w-6 h-6"></i>
            </div>
            Editar Vacuna
        </h1>
        <a href="{{ route('vacunas.show', $vacuna->id) }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Volver
        </a>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
        <form method="POST" action="{{ route('vacunas.update', $vacuna->id) }}">
            @method('PUT')
            @csrf
            @include('vacuna.form')
        </form>
    </div>
</div>

@include('vacuna.modals.create-marca')
@endsection

@push('scripts')
<script src="{{ asset('js/modal-marca.js') }}"></script>
@endpush