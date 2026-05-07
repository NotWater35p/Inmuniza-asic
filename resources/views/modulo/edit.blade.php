@extends('layouts.app')

@section('title')
    Editar Módulo
@endsection

@section('content')
<section class="bg-white/90 rounded-lg shadow-sm backdrop-blur-sm p-4 max-w-4xl mx-auto">
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-5">
        <div class="mb-6 flex items-center gap-3">
            <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hospital-icon lucide-hospital"><path d="M12 7v4"/><path d="M14 21v-3a2 2 0 0 0-4 0v3"/><path d="M14 9h-4"/><path d="M18 11h2a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-9a2 2 0 0 1 2-2h2"/><path d="M18 21V5a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16"/></svg>
            </div>
            <h2 class="text-2xl font-semibold text-gray-800">Editar Módulo</h2>
        </div>

        <form method="POST" action="{{ route('modulos.update', $modulo->id) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            @include('modulo.form')
        </form>
    </div>
</section>
@endsection