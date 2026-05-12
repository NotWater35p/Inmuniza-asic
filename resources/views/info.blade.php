@extends('layouts.app')
@section('title', 'Información del Proyecto')

@section('content')
<div class="px-4 py-6 mx-auto max-w-5xl space-y-6 bg-white/70 rounded-lg shadow-lg backdrop-blur-lg">

    {{-- Banner del proyecto --}}
    <div class="bg-linear-to-br from-[#7788ff] to-[#3e3af2] rounded-2xl overflow-hidden shadow-lg">
        <div class="flex flex-col sm:flex-row items-center gap-6 p-6 sm:p-8">
            <div class="shrink-0">
                <img src="{{ asset('img/svg/logo.svg') }}" alt="Logo INMUNIZA" class="w-20 h-20 drop-shadow-lg" style="filter: invert(100%);">
            </div>
            <div class="text-center sm:text-left">
                <p class="text-blue-200 text-xs font-semibold uppercase tracking-widest mb-1">Sistema de Gestión</p>
                <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight leading-tight">
                    INMU<span class="text-blue-200">NIZA</span>
                </h1>
                <p class="text-blue-100 text-sm mt-2 max-w-lg leading-relaxed">
                    Aplicación web para la gestión de vacunas en los módulos afiliados al ASIC ILAPECA,
                    Villa del Rosario, Estado Zulia.
                </p>
            </div>
            <div class="sm:ml-auto shrink-0">
                <a href="https://github.com/NotWater35p/Inmuniza-asic" target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 hover:bg-white/30 text-white text-sm font-medium rounded-xl transition-colors border border-white/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/></svg>
                    Ver Repositorio
                </a>
            </div>
        </div>
    </div>

    {{-- Universidad --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="flex flex-col sm:flex-row items-center gap-5 p-6">
            <div class="shrink-0 p-3 bg-gray-50 rounded-xl border border-gray-100">
                <img src="{{ asset('img/uptma-xd.png') }}" alt="Logo UPTMA" class="w-24 h-24 object-contain">
            </div>
            <div>
                <p class="text-xs font-semibold text-indigo-600 uppercase tracking-widest mb-1">Casa de Estudios</p>
                <h2 class="text-lg font-bold text-gray-900 leading-snug">
                    Universidad Politécnica Territorial de Maracaibo
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">Extensión Villa del Rosario · Estado Zulia</p>
                <p class="text-sm text-gray-600 mt-2">
                    <span class="font-medium">Programa:</span> Nacional de Formación en Informática
                </p>
                <div class="flex flex-wrap gap-2 mt-3">
                    <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-full border border-indigo-100">
                        PNF Informática
                    </span>
                    <span class="px-3 py-1 bg-gray-50 text-gray-600 text-xs font-semibold rounded-full border border-gray-200">
                        Abril 2026
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Autores --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <h3 class="text-sm font-bold text-gray-800">Equipo Desarrollador</h3>
        </div>
        <div class="divide-y divide-gray-50">
            @php
            $autores = [
                ['nombre' => 'Fernández P., Ángeles N.',  'cedula' => 'V-33.118.438', 'color' => 'bg-blue-100 text-blue-700',   'init' => 'AF'],
                ['nombre' => 'Herazo G., Dany M.',        'cedula' => 'V-30.938.548', 'color' => 'bg-violet-100 text-violet-700','init' => 'XD'],
                ['nombre' => 'Medina N., Samanta M.',     'cedula' => 'V-31.614.072', 'color' => 'bg-pink-100 text-pink-700',    'init' => 'SM'],
                ['nombre' => 'Mesa V., Deivis J.',        'cedula' => 'V-27.846.981', 'color' => 'bg-amber-100 text-amber-700',  'init' => 'DM'],
                ['nombre' => 'Viana G., Samuel D.',       'cedula' => 'V-30.940.495', 'color' => 'bg-emerald-100 text-emerald-700','init' => 'SV'],
            ];
            @endphp

            @foreach($autores as $autor)
            <div class="flex items-center gap-4 px-6 py-3.5">
                <div class="w-10 h-10 rounded-full {{ $autor['color'] }} flex items-center justify-center font-bold text-sm shrink-0">
                    {{ $autor['init'] }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $autor['nombre'] }}</p>
                    <p class="text-xs text-gray-400 font-mono">{{ $autor['cedula'] }}</p>
                </div>
                <span class="text-xs font-medium text-gray-400 bg-gray-50 border border-gray-100 px-2.5 py-1 rounded-full hidden sm:block">
                    Autor
                </span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Asesores --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center font-bold text-sm shrink-0">NB</div>
            <div>
                <p class="text-xs text-teal-600 font-semibold uppercase tracking-wide mb-0.5">Asesora de Proyecto</p>
                <p class="text-sm font-bold text-gray-900">Ing. Noemí Báez</p>
                <p class="text-xs text-gray-400 font-mono">V-20.509.656</p>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-sky-100 text-sky-700 flex items-center justify-center font-bold text-sm shrink-0">RA</div>
            <div>
                <p class="text-xs text-sky-600 font-semibold uppercase tracking-wide mb-0.5">Tutor de Proyecto</p>
                <p class="text-sm font-bold text-gray-900">Ing. Rafael Acosta</p>
                <p class="text-xs text-gray-400 font-mono">V-25.311.919</p>
            </div>
        </div>

    </div>

    {{-- Stack tecnológico --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 16 4-4-4-4"/><path d="m6 8-4 4 4 4"/><path d="m14.5 4-5 16"/></svg>
            <h3 class="text-sm font-bold text-gray-800">Stack Tecnológico</h3>
        </div>
        <div class="p-5 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            @php
            $stack = [
                ['name' => 'Laravel 12',      'color' => 'bg-red-50 text-red-700 border-red-100'],
                ['name' => 'MySQL',           'color' => 'bg-blue-50 text-blue-700 border-blue-100'],
                ['name' => 'Tailwind CSS',    'color' => 'bg-cyan-50 text-cyan-700 border-cyan-100'],
                ['name' => 'Flowbite',        'color' => 'bg-indigo-50 text-indigo-700 border-indigo-100'],
                ['name' => 'DomPDF',          'color' => 'bg-orange-50 text-orange-700 border-orange-100'],
                ['name' => 'Maatwebsite Excel', 'color' => 'bg-green-50 text-green-700 border-green-100'],
                ['name' => 'PHP 8.3',         'color' => 'bg-violet-50 text-violet-700 border-violet-100'],
                ['name' => 'Lucide Icons',    'color' => 'bg-gray-50 text-gray-700 border-gray-200'],
            ];
            @endphp
            @foreach($stack as $tech)
            <div class="flex items-center justify-center px-3 py-2.5 rounded-xl border {{ $tech['color'] }} text-xs font-semibold text-center">
                {{ $tech['name'] }}
            </div>
            @endforeach
        </div>
    </div>

    {{-- Footer --}}
    <p class="text-center text-xs text-gray-400 pb-2">
        INMUNIZA · ASIC ILAPECA · Villa del Rosario, Zulia · 2026
    </p>

</div>
@endsection