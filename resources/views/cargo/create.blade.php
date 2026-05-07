@extends('layouts.app')

@section('title', 'Nuevo Cargo')

@section('content')
<section class="p-3 mx-auto max-w-3xl bg-white/90 backdrop-blur-sm rounded-lg shadow-sm">
    <div class="py-8 px-4 mx-auto bg-white shadow-sm rounded-lg">
        <div class="mb-6 flex items-center gap-3 px-6 py-5 bg-gradient-to-r from-indigo-600 to-indigo-800 rounded-t">
            <div class="p-2 bg-indigo-500 rounded text-indigo-200">
                <i data-lucide="shield-plus" class="w-6 h-6"></i>
            </div>
            <h1 class="text-2xl font-semibold text-gray-100">NUEVO CARGO</h1>
        </div>
        <form method="POST" action="{{ route('cargos.store') }}" enctype="multipart/form-data">
            @csrf
            @include('cargo.form')
        </form>
    </div>
</section>
@endsection