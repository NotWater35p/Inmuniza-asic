@extends('layouts.app')
@section('title', 'Registrar Personal')

@section('content')
<div class="px-4 py-6 mx-auto max-w-3xl bg-white/90 backdrop-blur-sm rounded-lg shadow">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-blue-600 flex items-center gap-2">
                <div class="p-2 bg-blue-600 rounded text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11.5 15H7a4 4 0 0 0-4 4v2"/><path d="M21.378 16.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"/><circle cx="10" cy="7" r="4"/></svg>
                </div>
                Registrar Personal
            </h1>
        </div>
        <a href="{{ route('personal.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Volver
        </a>
    </div>

    @if($errors->any())
    <div class="flex items-start gap-3 p-4 mb-5 text-red-800 bg-red-50 border border-red-200 rounded-lg">
        <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 mt-0.5"></i>
        <ul class="text-sm list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
        <form method="POST" action="{{ route('personal.store') }}">
            @csrf
            @include('personal.form')
        </form>
    </div>
</div>

@push('scripts')
<script>
    lucide.createIcons();

    document.getElementById('cedula').addEventListener('input', function (e) {
        this.value = this.value.replace(/\D/g, '');
    });
</script>
@endpush
@endsection