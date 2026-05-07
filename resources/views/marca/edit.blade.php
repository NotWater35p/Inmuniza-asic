@extends('layouts.app')

@section('title')
    Editar Marca
@endsection

@section('content')
<section class="p-4 mx-auto max-w-4xl bg-white/90 backdrop-blur-sm rounded-lg shadow-sm">
    <div class="mx-auto">
        <div class="mb-8 overflow-hidden rounded-xl shadow-lg">
            <div class="px-6 py-5 bg-linear-to-r from-yellow-600 to-yellow-700">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen-icon lucide-square-pen"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg>
                    Editar Marca
                </h2>
                <p class="text-green-100 mt-1">Modifica los datos del fabricante</p>
            </div>
            <div class="p-6 bg-white">
                <form method="POST" action="{{ route('marcas.update', $marca->id) }}">
                    @method('PUT')
                    @csrf
                    @include('marca.form')
                </form>
            </div>
        </div>
    </div>
</section>
@endsection