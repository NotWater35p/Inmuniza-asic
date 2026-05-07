@extends('layouts.app')

@section('title')
Nueva Marca
@endsection

@section('content')
<section class="p-4 mx-auto max-w-4xl bg-white/90 backdrop-blur-sm rounded-lg shadow-sm">
    <div class="mx-auto">
        <div class="mb-8 overflow-hidden rounded-xl shadow-lg">
            <div class="px-6 py-5 bg-linear-to-r from-blue-600 to-blue-700">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-microscope-icon lucide-microscope"><path d="M6 18h8"/><path d="M3 22h18"/><path d="M14 22a7 7 0 1 0 0-14h-1"/><path d="M9 14h2"/><path d="M9 12a2 2 0 0 1-2-2V6h6v4a2 2 0 0 1-2 2Z"/><path d="M12 6V3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3"/></svg>
                     Nueva Marca
                </h2>
                <p class="text-blue-100 mt-1">Registra un nuevo laboratorio o fabricante de vacunas</p>
            </div>
            <div class="p-6 bg-white">
                <form method="POST" action="{{ route('marcas.store') }}">
                    @csrf
                    @include('marca.form')
                </form>
            </div>
        </div>
    </div>
</section>
@endsection