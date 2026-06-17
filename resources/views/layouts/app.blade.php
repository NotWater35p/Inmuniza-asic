<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    <title>@yield('title', 'Inmuniza')</title>
    <link rel="icon" href="{{ asset('img/svg/logo_alter.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
        background: url({{ asset('img/mask.jpg')}});
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        background-attachment: fixed;
        height: 100%;
        }

        /* Link activo en sidebar */
        .sidebar-link.active {
            background-color: rgb(239 246 255);
            /* blue-50 */
            color: rgb(37 99 235);
            /* blue-600 */
            font-weight: 600;
        }

        .sidebar-link.active svg {
            color: rgb(37 99 235);
        }

        /* Accordion activo */
        .accordion-btn.active {
            color: rgb(37 99 235);
        }

        .accordion-btn.active svg:first-child {
            color: rgb(37 99 235);
        }
    </style>
</head>

<body>
    @php
    $user = Auth::user();
    $nivel = $user?->nivel_acceso ?? 0;
    $esAdmin = $nivel >= 4;
    $esJefe = $nivel === 2;
    $moduloJefe = $esJefe ? $user->modulo() : null;
    $rutaActual = request()->route()?->getName() ?? '';
    @endphp

    {{-- NAVBAR --}}
    <nav class="fixed top-0 z-50 w-full bg-neutral-primary-soft/90 backdrop-blur-md border-b border-default">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start rtl:justify-end">
                    {{-- Botón sidebar móvil --}}
                    <button data-drawer-target="top-bar-sidebar" data-drawer-toggle="top-bar-sidebar"
                        aria-controls="top-bar-sidebar" type="button"
                        class="sm:hidden text-heading bg-transparent border border-transparent hover:bg-neutral-secondary-medium focus:ring-4 focus:ring-neutral-tertiary rounded-base text-sm p-2 focus:outline-none">
                        <span class="sr-only">Abrir sidebar</span>
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                d="M5 7h14M5 12h14M5 17h10" />
                        </svg>
                    </button>
                    <h1 class="flex ms-2 md:me-24">
                        <img src="{{ asset('img/svg/logo_alter.svg') }}" class="h-9 me-3" alt="Logo" />
                        <span class="self-center text-2xl font-semibold whitespace-nowrap">
                            <span class="text-[#7788ff] tracking-[-0.5px]">INMU<b class="text-[#3e3af2]">NIZA</b></span>
                        </span>
                    </h1>
                </div>

                {{-- Navbar derecho --}}
                <div class="flex items-center ms-3">
                    <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300"
                        aria-expanded="false" data-dropdown-toggle="dropdown-user">
                        <span class="sr-only">Abrir menú</span>
                        <img class="w-9 h-9 rounded-full" src="{{ asset('img/logo.png') }}" alt="foto usuario">
                    </button>

                    <div class="z-50 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-52"
                        id="dropdown-user">
                        {{-- Info usuario --}}
                        <div class="px-4 py-3 border-b border-default-medium">
                            <p class="text-sm font-semibold text-heading">
                                {{ $user->personal->nombre }} {{ $user->personal->apellido }}
                            </p>
                            <p class="text-xs text-body truncate mt-0.5">{{ $user->personal->cargo->nombre }}</p>
                            @if($esJefe && $moduloJefe)
                            <p class="text-xs text-blue-600 font-medium mt-1 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M12 6v4" />
                                    <path d="M14 14h-4" />
                                    <path d="M14 18h-4" />
                                    <path d="M14 8h-4" />
                                    <path
                                        d="M18 12h2a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-9a2 2 0 0 1 2-2h2" />
                                    <path d="M18 22V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v18" />
                                </svg>
                                {{ $moduloJefe->nombre }}
                            </p>
                            @endif
                        </div>

                        <ul class="p-2 text-sm text-body font-medium space-y-0.5">
                            {{-- Perfil - todos los roles --}}
                            {{-- <li>
                                <a href="{{ route('users.show', $user->id) }}"
                                    class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded gap-2.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="8" r="5" />
                                        <path d="M20 21a8 8 0 1 0-16 0" />
                                    </svg>
                                    Mi Perfil
                                </a>
                            </li> --}}
                            {{-- Usuarios - solo admin --}}
                            @if($esAdmin)
                            <li>
                                <a href="{{ route('users.index') }}"
                                    class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded gap-2.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-id-card-lanyard-icon lucide-id-card-lanyard">
                                        <path d="M13.5 8h-3" />
                                        <path
                                            d="m15 2-1 2h3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h3" />
                                        <path d="M16.899 22A5 5 0 0 0 7.1 22" />
                                        <path d="m9 2 3 6" />
                                        <circle cx="12" cy="15" r="3" />
                                    </svg>
                                    Usuarios
                                </a>
                            </li>
                            @endif
                            {{-- Info del sistema --}}
                            <li>
                                <a href="{{ route('info') }}"
                                    class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded gap-2.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M12 16v-4" />
                                        <path d="M12 8h.01" />
                                    </svg>
                                    Info
                                </a>
                            </li>
                            {{-- Manual de usuario --}}
                            <li>
                                <a href="{{ route('info') }}"
                                    class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded gap-2.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-book-open-icon lucide-book-open">
                                        <path d="M12 7v14" />
                                        <path
                                            d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z" />
                                    </svg>
                                    Ayuda
                                </a>
                            </li>
                            {{-- Cerrar sesión --}}
                            <li class="border-t border-default-medium mt-1 pt-1">
                                <form action="{{ route('logout') }}" method="post">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center w-full p-2 text-fg-warning-subtle hover:bg-warning-strong hover:text-white focus:ring-4 focus:ring-warning-medium rounded-base gap-2.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                            <polyline points="16 17 21 12 16 7" />
                                            <line x1="21" x2="9" y1="12" y2="12" />
                                        </svg>
                                        Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- SIDEBAR --}}
    <aside id="top-bar-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-full transition-transform -translate-x-full sm:translate-x-0"
        aria-label="Sidebar">
        <div class="h-full px-3 py-4 overflow-y-auto bg-neutral-primary border-e border-default">
            <ul class="space-y-1 font-medium mt-14" data-accordion="accordion">

                {{-- ═══════════════════════════════════════════════ --}}
                {{-- VISTA JEFE DE MÓDULO --}}
                {{-- ═══════════════════════════════════════════════ --}}
                @if($esJefe)

                {{-- Dashboard --}}
                <li>
                    <a href="{{ route('modulo.dashboard') }}"
                        class="sidebar-link flex items-center gap-3 px-2 py-1.5 text-body rounded-base hover:bg-blue-50 hover:text-blue-600 w-full {{ $rutaActual === 'modulo.dashboard' ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
                            <path
                                d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        </svg>
                        <span>Panel de Control</span>
                    </a>
                </li>

                {{-- Catálogo de Vacunas --}}
                <li>
                    <a href="{{ route('vacunas.index') }}"
                        class="sidebar-link flex items-center gap-3 px-2 py-1.5 text-body rounded-base hover:bg-blue-50 hover:text-blue-600 w-full {{ str_starts_with($rutaActual, 'vacunas') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m18 2 4 4" />
                            <path d="m17 7 3-3" />
                            <path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5" />
                            <path d="m9 11 4 4" />
                            <path d="m5 19-3 3" />
                            <path d="m14 4 6 6" />
                        </svg>
                        <span>Catálogo Vacunas</span>
                    </a>
                </li>

                {{-- Clínica accordion --}}
                <li>
                    <button data-accordion-target="#clinica-content" aria-expanded="false"
                        class="accordion-btn flex items-center justify-between px-2 py-1.5 text-body rounded-base hover:bg-blue-50 hover:text-blue-600 w-full {{ str_starts_with($rutaActual, 'pacientes') || str_starts_with($rutaActual, 'jornadas') || str_starts_with($rutaActual, 'tratamientos') || str_starts_with($rutaActual, 'descargo') ? 'active' : '' }}">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M2 6h4" />
                                <path d="M2 10h4" />
                                <path d="M2 14h4" />
                                <path d="M2 18h4" />
                                <rect width="16" height="20" x="4" y="2" rx="2" />
                                <path d="M9.5 8h5" />
                                <path d="M9.5 12H16" />
                                <path d="M9.5 16H14" />
                            </svg>
                            <span>Pacientes</span>
                        </div>
                        <svg class="w-4 h-4 shrink-0 transition-transform duration-200" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 0 1 1.414 0L10 10.586l3.293-3.293a1 1 0 1 1 1.414 1.414l-4 4a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 0-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </li>
                <li id="clinica-content" class="accordion-content hidden">
                    <ul class="py-1 space-y-0.5 pl-2">
                        <li>
                            <a href="{{ route('pacientes.index') }}"
                                class="sidebar-link flex items-center gap-2.5 px-3 py-1.5 text-body text-sm rounded-base hover:bg-blue-50 hover:text-blue-600 w-full {{ str_starts_with($rutaActual, 'pacientes') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                                Pacientes Registrados
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('jornadas.index') }}"
                                class="sidebar-link flex items-center gap-2.5 px-3 py-1.5 text-body text-sm rounded-base hover:bg-blue-50 hover:text-blue-600 w-full {{ str_starts_with($rutaActual, 'jornadas') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M8 2v4" />
                                    <path d="M16 2v4" />
                                    <rect width="18" height="18" x="3" y="4" rx="2" />
                                    <path d="M3 10h18" />
                                    <path d="M10 16h4" />
                                    <path d="M12 14v4" />
                                </svg>
                                Jornadas
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tratamientos.index') }}"
                                class="sidebar-link flex items-center gap-2.5 px-3 py-1.5 text-body text-sm rounded-base hover:bg-blue-50 hover:text-blue-600 w-full {{ str_starts_with($rutaActual, 'tratamientos') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M12 11v4" />
                                    <path d="M14 13h-4" />
                                    <path d="M16 6V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2" />
                                    <rect width="20" height="14" x="2" y="6" rx="2" />
                                </svg>
                                Tratamientos
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('descargo.create') }}"
                                class="sidebar-link flex items-center gap-2.5 px-3 py-1.5 text-body text-sm rounded-base hover:bg-orange-50 hover:text-orange-600 w-full {{ str_starts_with($rutaActual, 'descargo') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z" />
                                    <path d="M13 2v7h7" />
                                    <path d="M12 12v6" />
                                    <path d="M9 15h6" />
                                </svg>
                                Descargo Rápido
                            </a>
                        </li>
                    </ul>
                </li>


                @if($moduloJefe)
                {{-- Pérdidas --}}
                <li>
                    <a href="{{ route('modulo.perdidas.index', $moduloJefe->id) }}"
                        class="sidebar-link flex items-center gap-3 px-2 py-1.5 text-body rounded-base hover:bg-blue-50 hover:text-blue-600 w-full {{ str_starts_with($rutaActual, 'modulo.perdidas') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z" />
                            <path d="M12 9v4" />
                            <path d="M12 17h.01" />
                        </svg>
                        <span>Pérdidas</span>
                    </a>
                </li>

                {{-- Reporte Mensual --}}
                <li>
                    <a href="{{ route('modulo.reporte.index', $moduloJefe->id) }}"
                        class="sidebar-link flex items-center gap-3 px-2 py-1.5 text-body rounded-base hover:bg-blue-50 hover:text-blue-600 w-full {{ str_starts_with($rutaActual, 'modulo.reporte') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                            <path d="M15 18H9" />
                            <path d="M15 14H9" />
                            <path d="M6 22h12a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2z" />
                        </svg>
                        <span>Reporte Mensual</span>
                    </a>
                </li>
                @endif

                {{-- ═══════════════════════════════════════════════ --}}
                {{-- VISTA ADMIN / ASISTENTE --}}
                {{-- ═══════════════════════════════════════════════ --}}
                @else

                {{-- Dashboard --}}
                <li>
                    <a href="{{ route('inicio') }}"
                        class="sidebar-link flex items-center gap-3 px-2 py-1.5 text-body rounded-base hover:bg-blue-50 hover:text-blue-600 w-full {{ $rutaActual === 'inicio' ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
                            <path
                                d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        </svg>
                        <span>Menú principal</span>
                    </a>
                </li>

                {{-- Módulos Accordion --}}
                <li>
                    <button data-accordion-target="#modulos-content" aria-expanded="false"
                        class="accordion-btn flex items-center justify-between px-2 py-1.5 text-body rounded-base hover:bg-blue-50 hover:text-blue-600 w-full {{ str_starts_with($rutaActual, 'modulos') || str_starts_with($rutaActual, 'despachos') ? 'active' : '' }}">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M12 6v4" />
                                <path d="M14 14h-4" />
                                <path d="M14 18h-4" />
                                <path d="M14 8h-4" />
                                <path d="M18 12h2a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-9a2 2 0 0 1 2-2h2" />
                                <path d="M18 22V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v18" />
                            </svg>
                            <span>Módulos</span>
                        </div>
                        <svg class="w-4 h-4 shrink-0 transition-transform duration-200" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 0 1 1.414 0L10 10.586l3.293-3.293a1 1 0 1 1 1.414 1.414l-4 4a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 0-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </li>
                <li id="modulos-content" class="accordion-content hidden">
                    <ul class="py-1 space-y-0.5 pl-2">
                        <li>
                            <a href="{{ route('modulos.index') }}"
                                class="sidebar-link flex items-center gap-2.5 px-3 py-1.5 text-body text-sm rounded-base hover:bg-blue-50 hover:text-blue-600 w-full {{ $rutaActual === 'modulos.index' ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M12.35 21H5a2 2 0 0 1-2-2v-9a2 2 0 0 1 .71-1.53l7-6a2 2 0 0 1 2.58 0l7 6A2 2 0 0 1 21 10v2.35" />
                                    <path d="M14.8 12.4A1 1 0 0 0 14 12h-4a1 1 0 0 0-1 1v8" />
                                    <path d="M15 18h6" />
                                    <path d="M18 15v6" />
                                </svg>
                                Módulos Afiliados
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('despachos.index') }}"
                                class="sidebar-link flex items-center gap-2.5 px-3 py-1.5 text-body text-sm rounded-base hover:bg-blue-50 hover:text-blue-600 w-full {{ str_starts_with($rutaActual, 'despachos') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M10 10H6" />
                                    <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2" />
                                    <path
                                        d="M19 18h2a1 1 0 0 0 1-1v-3.28a1 1 0 0 0-.684-.948l-1.923-.641a1 1 0 0 1-.578-.502l-1.539-3.076A1 1 0 0 0 16.382 8H14" />
                                    <path d="M8 8v4" />
                                    <path d="M9 18h6" />
                                    <circle cx="17" cy="18" r="2" />
                                    <circle cx="7" cy="18" r="2" />
                                </svg>
                                Despacho de Vacunas
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Vacunas Accordion --}}
                <li>
                    <button data-accordion-target="#vacunas-content" aria-expanded="false"
                        class="accordion-btn flex items-center justify-between px-2 py-1.5 text-body rounded-base hover:bg-blue-50 hover:text-blue-600 w-full {{ str_starts_with($rutaActual, 'vacunas') || str_starts_with($rutaActual, 'marcas') || str_starts_with($rutaActual, 'cargas') || str_starts_with($rutaActual, 'inventario') ? 'active' : '' }}">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="m18 2 4 4" />
                                <path d="m17 7 3-3" />
                                <path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5" />
                                <path d="m9 11 4 4" />
                                <path d="m5 19-3 3" />
                                <path d="m14 4 6 6" />
                            </svg>
                            <span>Vacunas</span>
                        </div>
                        <svg class="w-4 h-4 shrink-0 transition-transform duration-200" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 0 1 1.414 0L10 10.586l3.293-3.293a1 1 0 1 1 1.414 1.414l-4 4a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 0-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </li>
                <li id="vacunas-content" class="accordion-content hidden">
                    <ul class="py-1 space-y-0.5 pl-2">
                        <li>
                            <a href="{{ route('inventario.index') }}"
                                class="sidebar-link flex items-center gap-2.5 px-3 py-1.5 text-body text-sm rounded-base hover:bg-blue-50 hover:text-blue-600 w-full {{ str_starts_with($rutaActual, 'inventario') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z" />
                                    <path d="M12 22V12" />
                                    <path d="m3.3 7 7.703 4.734a2 2 0 0 0 1.994 0L20.7 7" />
                                    <path d="m7.5 4.27 9 5.15" />
                                </svg>
                                Inventario General
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('vacunas.index') }}"
                                class="sidebar-link flex items-center gap-2.5 px-3 py-1.5 text-body text-sm rounded-base hover:bg-blue-50 hover:text-blue-600 w-full {{ str_starts_with($rutaActual, 'vacunas') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="m18 2 4 4" />
                                    <path d="m17 7 3-3" />
                                    <path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5" />
                                    <path d="m9 11 4 4" />
                                    <path d="m5 19-3 3" />
                                    <path d="m14 4 6 6" />
                                </svg>
                                Catalogo de Vacunas
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('cargas.index') }}"
                                class="sidebar-link flex items-center gap-2.5 px-3 py-1.5 text-body text-sm rounded-base hover:bg-blue-50 hover:text-blue-600 w-full {{ str_starts_with($rutaActual, 'cargas') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <rect width="8" height="4" x="8" y="2" rx="1" ry="1" />
                                    <path
                                        d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                                    <path d="M9 14h6" />
                                    <path d="M12 17v-6" />
                                </svg>
                                Carga de Vacunas
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('marcas.index') }}"
                                class="sidebar-link flex items-center gap-2.5 px-3 py-1.5 text-body text-sm rounded-base hover:bg-blue-50 hover:text-blue-600 w-full {{ str_starts_with($rutaActual, 'marcas') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M6 18h8" />
                                    <path d="M3 22h18" />
                                    <path d="M14 22a7 7 0 1 0 0-14h-1" />
                                    <path d="M9 14h2" />
                                    <path d="M9 12a2 2 0 0 1-2-2V6h6v4a2 2 0 0 1-2 2Z" />
                                    <path d="M12 6V3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3" />
                                </svg>
                                Fabricantes
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Pacientes Accordion --}}
                <li>
                    <button data-accordion-target="#pacientes-content" aria-expanded="false"
                        class="accordion-btn flex items-center justify-between px-2 py-1.5 text-body rounded-base hover:bg-blue-50 hover:text-blue-600 w-full {{ str_starts_with($rutaActual, 'pacientes') || str_starts_with($rutaActual, 'jornadas') || str_starts_with($rutaActual, 'tratamientos') || str_starts_with($rutaActual, 'representantes') ? 'active' : '' }}">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M2 6h4" />
                                <path d="M2 10h4" />
                                <path d="M2 14h4" />
                                <path d="M2 18h4" />
                                <rect width="16" height="20" x="4" y="2" rx="2" />
                                <path d="M9.5 8h5" />
                                <path d="M9.5 12H16" />
                                <path d="M9.5 16H14" />
                            </svg>
                            <span>Pacientes</span>
                        </div>
                        <svg class="w-4 h-4 shrink-0 transition-transform duration-200" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 0 1 1.414 0L10 10.586l3.293-3.293a1 1 0 1 1 1.414 1.414l-4 4a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 0-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </li>
                <li id="pacientes-content" class="accordion-content hidden">
                    <ul class="py-1 space-y-0.5 pl-2">
                        <li>
                            <a href="{{ route('pacientes.index') }}"
                                class="sidebar-link flex items-center gap-2.5 px-3 py-1.5 text-body text-sm rounded-base hover:bg-blue-50 hover:text-blue-600 w-full {{ str_starts_with($rutaActual, 'pacientes') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                                Pacientes Registrados
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('jornadas.index') }}"
                                class="sidebar-link flex items-center gap-2.5 px-3 py-1.5 text-body text-sm rounded-base hover:bg-blue-50 hover:text-blue-600 w-full {{ str_starts_with($rutaActual, 'jornadas') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M8 2v4" />
                                    <path d="M16 2v4" />
                                    <rect width="18" height="18" x="3" y="4" rx="2" />
                                    <path d="M3 10h18" />
                                    <path d="M10 16h4" />
                                    <path d="M12 14v4" />
                                </svg>
                                Jornadas
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tratamientos.index') }}"
                                class="sidebar-link flex items-center gap-2.5 px-3 py-1.5 text-body text-sm rounded-base hover:bg-blue-50 hover:text-blue-600 w-full {{ str_starts_with($rutaActual, 'tratamientos') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M12 11v4" />
                                    <path d="M14 13h-4" />
                                    <path d="M16 6V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2" />
                                    <path d="M18 6v14" />
                                    <path d="M6 6v14" />
                                    <rect width="20" height="14" x="2" y="6" rx="2" />
                                </svg>
                                Tratamientos
                            </a>
                        </li>
                        {{-- Descargo Rápido --}}
                        <li>
                            <a href="{{ route('descargo.create') }}"
                                class="sidebar-link flex items-center gap-2.5 px-3 py-1.5 text-body text-sm rounded-base hover:bg-blue-50 hover:text-blue-600 w-full {{ str_starts_with($rutaActual, 'jornadas') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z" />
                                    <path d="M13 2v7h7" />
                                    <path d="M12 12v6" />
                                    <path d="M9 15h6" />
                                </svg>
                                <span>Descargo Rápido</span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Personal Accordion --}}
                <li>
                    <button data-accordion-target="#personal-content" aria-expanded="false"
                        class="accordion-btn flex items-center justify-between px-2 py-1.5 text-body rounded-base hover:bg-blue-50 hover:text-blue-600 w-full {{ str_starts_with($rutaActual, 'personal') || str_starts_with($rutaActual, 'users') || str_starts_with($rutaActual, 'cargos') ? 'active' : '' }}">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M11 2v2" />
                                <path d="M5 2v2" />
                                <path d="M5 3H4a2 2 0 0 0-2 2v4a6 6 0 0 0 12 0V5a2 2 0 0 0-2-2h-1" />
                                <path d="M8 15a6 6 0 0 0 12 0v-3" />
                                <circle cx="20" cy="10" r="2" />
                            </svg>
                            <span>Personal</span>
                        </div>
                        <svg class="w-4 h-4 shrink-0 transition-transform duration-200" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 0 1 1.414 0L10 10.586l3.293-3.293a1 1 0 1 1 1.414 1.414l-4 4a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 0-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </li>
                <li id="personal-content" class="accordion-content hidden">
                    <ul class="py-1 space-y-0.5 pl-2">
                        <li>
                            <a href="{{ route('personal.index') }}"
                                class="sidebar-link flex items-center gap-2.5 px-3 py-1.5 text-body text-sm rounded-base hover:bg-blue-50 hover:text-blue-600 w-full {{ str_starts_with($rutaActual, 'personal') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M16 10h2" />
                                    <path d="M16 14h2" />
                                    <path d="M6.17 15a3 3 0 0 1 5.66 0" />
                                    <circle cx="9" cy="11" r="2" />
                                    <rect x="2" y="5" width="20" height="14" rx="2" />
                                </svg>
                                Personal Registrado
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                <li>
                    <a href="{{ route('sispai.index') }}"
                        class="sidebar-link flex items-center gap-3 px-2 py-1.5 text-body rounded-base hover:bg-blue-50 hover:text-blue-600 w-full {{ str_starts_with($rutaActual, 'sispai') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" /><path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" /></svg>
                        <span>Reportes</span>
                    </a>
                </li>

                @endif {{-- fin @else admin --}}

            </ul>
        </div>
    </aside>

    {{-- CONTENT --}}
    <div class="p-4 sm:ml-64 mt-14">
        @yield('content')
    </div>

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.accordion-btn.active').forEach(function (btn) {
            const targetId = btn.getAttribute('data-accordion-target');
            const target = document.querySelector(targetId);
            if (target) {
                target.classList.remove('hidden');
                btn.setAttribute('aria-expanded', 'true');
                const arrow = btn.querySelector('svg:last-child');
                if (arrow) arrow.style.transform = 'rotate(180deg)';
            }
        });
    });
    </script>
</body>

</html>