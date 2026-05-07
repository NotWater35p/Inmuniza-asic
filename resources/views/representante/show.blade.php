@extends('layouts.app')

@section('title')
    {{ $representante->nombre }} {{ $representante->apellido }} | Representante
@endsection

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-7xl mx-auto bg-white/90 rounded-lg shadow-sm backdrop-blur-sm">
    {{-- Encabezado con botón volver --}}
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl md:text-3xl text-gray-800 font-bold flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Detalles del Representante
        </h1>
        <a href="{{ route('representantes.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Volver al listado
        </a>
    </div>

    {{-- Tarjeta azul con datos del representante --}}
    <div class="bg-linear-to-br from-blue-600 to-blue-700 rounded-2xl shadow-lg overflow-hidden mb-10">
        <div class="px-6 py-6 sm:px-8 sm:py-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-3 bg-white/20 rounded-xl text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-book-open-text-icon lucide-book-open-text"><path d="M12 7v14"/><path d="M16 12h2"/><path d="M16 8h2"/><path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"/><path d="M6 12h2"/><path d="M6 8h2"/></svg>
                </div>
                <h2 class="text-2xl font-bold text-white">{{ $representante->nombre }} {{ $representante->apellido }}</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 text-white">
                <div class="flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-id-card-icon lucide-id-card"><path d="M16 10h2"/><path d="M16 14h2"/><path d="M6.17 15a3 3 0 0 1 5.66 0"/><circle cx="9" cy="11" r="2"/><rect x="2" y="5" width="20" height="14" rx="2"/></svg>
                    <div>
                        <p class="text-sm font-medium text-blue-100">Cédula</p>
                        <p class="text-lg font-semibold">{{ $representante->cedula }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone flex-shrink-0 mt-0.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8 10a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <div>
                        <p class="text-sm font-medium text-blue-100">Teléfono</p>
                        <p class="text-lg font-semibold">{{ $representante->telefono ?? '—' }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin flex-shrink-0 mt-0.5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                    <div>
                        <p class="text-sm font-medium text-blue-100">Dirección</p>
                        <p class="text-lg font-semibold">{{ $representante->direccion ?? '—' }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 sm:col-span-2 lg:col-span-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-handshake flex-shrink-0 mt-0.5"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5"/><path d="M8 17c0 2 1 4 4 5 3-1 4-3 4-5"/><path d="M12 22v-5"/><path d="M9 12h6"/><path d="M12 9v3"/></svg>
                    <div>
                        <p class="text-sm font-medium text-blue-100">Parentesco / Relación</p>
                        <p class="text-lg font-semibold">{{ $representante->relacion ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sección de pacientes asociados --}}
    <div class="mb-5">
        <h3 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            Pacientes asociados
            <span class="ml-2 text-sm font-normal text-gray-500">({{ $representante->pacientes->count() }})</span>
        </h3>
    </div>

    @if($representante->pacientes->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($representante->pacientes as $paciente)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800">{{ $paciente->nombres }} {{ $paciente->apellidos }}</h4>
                                <p class="text-sm text-gray-500 flex items-center gap-1 mt-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                                    {{ optional($paciente->fecha_nacimiento)->format('d/m/Y') ?? '—' }}
                                    ({{ optional($paciente->fecha_nacimiento)->age ?? '?' }} años)
                                </p>
                            </div>
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full 
                                {{ $paciente->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $paciente->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>

                        <div class="space-y-2 text-sm">
                            <div class="flex items-center gap-2 text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-credit-card"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                                <span>{{ $paciente->cedula ?: 'Sin cédula' }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-venus-and-mars"><path d="M10 20h4"/><path d="M12 16v4"/><circle cx="12" cy="12" r="4"/><path d="M20 4 16 8"/><path d="M18 4h4v4"/></svg>
                                <span>{{ $paciente->sexo === 'M' ? 'Masculino' : ($paciente->sexo === 'F' ? 'Femenino' : '—') }}</span>
                            </div>
                            @if($paciente->etnia)
                            <div class="flex items-center gap-2 text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-globe"><circle cx="12" cy="12" r="10"/><path d="M12 2a14 14 0 0 0 0 20 14 14 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                                <span>{{ $paciente->etnia->nombre }}</span>
                            </div>
                            @endif
                            @if($paciente->sector)
                            <div class="flex items-center gap-2 text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building-2"><path d="M10 21v-6a2 2 0 0 1 2-2h0a2 2 0 0 1 2 2v6"/><path d="M6 7h12"/><path d="M2 21h20"/><path d="M6 3v4"/><path d="M18 3v4"/><rect x="6" y="7" width="12" height="12" rx="1"/></svg>
                                <span>{{ $paciente->sector->nombre }}</span>
                            </div>
                            @endif
                        </div>

                        <div class="mt-5 pt-4 border-t border-gray-100 flex justify-end">
                            <a href="{{ route('pacientes.show', $paciente->id) }}" 
                               class="inline-flex items-center gap-1.5 text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">
                                Ver paciente
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-200 p-10 text-center">
            <div class="inline-flex p-4 bg-gray-100 rounded-full mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users text-gray-400"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <h4 class="text-lg font-medium text-gray-800 mb-1">No hay pacientes asociados</h4>
            <p class="text-gray-500">Este representante aún no tiene un paciente registrado.</p>
        </div>
    @endif
</div>
@endsection