@extends('layouts.app')
@section('title', 'Menú principal')

@section('content')
<div class="bg-white/90 p-3 shadow-sm rounded-lg backdrop-blur-lg max-w-6xl xl:mx-auto">
  <div class="px-4 py-8 mx-auto bg-white rounded-lg shadow">

    {{-- Alertas --}}
    @if(session('success'))
    <div class="flex items-center gap-3 p-4 mb-6 text-green-800 bg-green-50 border border-green-200 rounded-lg">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
        class="lucide lucide-x-icon lucide-x">
        <path d="M18 6 6 18" />
        <path d="m6 6 12 12" />
      </svg>
      <span class="text-sm font-medium">{{ session('success') }}</span>
      <button onclick="this.parentElement.remove()" class="ml-auto"><i data-lucide="x" class="w-4 h-4"></i></button>
    </div>
    @endif
    @if(session('error'))
    <div class="flex items-center gap-3 p-4 mb-6 text-red-800 bg-red-50 border border-red-200 rounded-lg">
      <i data-lucide="alert-circle" class="w-5 h-5 shrink-0"></i>
      <span class="text-sm font-medium">{{ session('error') }}</span>
      <button onclick="this.parentElement.remove()" class="ml-auto"><i data-lucide="x" class="w-4 h-4"></i></button>
    </div>
    @endif

    @if(!$asic)
    <div class="text-center py-24">
      <div class="p-4 bg-gray-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
        <i data-lucide="building-2" class="w-10 h-10 text-gray-400"></i>
      </div>
      <h2 class="text-lg font-semibold text-gray-700 mb-1">Sin ASIC configurado</h2>
      <p class="text-sm text-gray-500">Contacta al administrador del sistema.</p>
    </div>
    @else

    @php
    $nivelUsuario = auth()->user()?->personal?->cargo?->nivel_acceso ?? 0;
    $esAdmin = $nivelUsuario >= 5;
    @endphp


    <div class="flex items-center justify-between md:flex-cols-2 md:flex-row gap-2">
      <h1 class="mb-4 text-xl font-semibold text-indigo-700 sm:text-2xl md:mb-6 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          class="lucide lucide-layout-dashboard-icon lucide-layout-dashboard">
          <rect width="7" height="9" x="3" y="3" rx="1" />
          <rect width="7" height="5" x="14" y="3" rx="1" />
          <rect width="7" height="9" x="14" y="12" rx="1" />
          <rect width="7" height="5" x="3" y="16" rx="1" />
        </svg>
        Panel de Control
      </h1>

      {{-- Alerta vencimientos --}}
      @if($proxVencer->count() > 0)
      <div class="relative inline-flex mb-4 md:mb-6">
        <button id="dropdownVencimientosButton" data-dropdown-toggle="dropdownVencimientos"
          class="inline-flex items-center justify-center text-warning bg-warning-soft box-border border border-warning-subtle hover:bg-warning hover:text-white focus:ring-4 focus:ring-warning-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none"
          type="button">
          <i data-lucide="alarm-clock" class="w-4 h-4 mr-1.5"></i>
          {{ $proxVencer->count() }} lote(s) por vencer
          <svg class="w-4 h-4 ms-1.5 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
            height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m19 9-7 7-7-7" />
          </svg>
        </button>

        <div id="dropdownVencimientos"
          class="z-10 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-72">
          <ul class="p-2 text-sm text-body font-medium" aria-labelledby="dropdownVencimientosButton">
            @foreach($proxVencer as $c)
            @php $d = now()->diffInDays(\Carbon\Carbon::parse($c->fecha_vencimiento), false); @endphp
            <li>
              <div class="flex items-center w-full p-2 hover:bg-warning-soft hover:text-heading rounded">
                <i data-lucide="syringe" class="w-3.5 h-3.5 mr-2 text-orange-500"></i>
                <span class="flex-1">{{ $c->vacuna?->nombre }} · {{ $c->lote }}</span>
                <span class="text-xs font-mono text-gray-500">{{ (int)$d }}d</span>
              </div>
            </li>
            @endforeach
            <li class="border-t border-default-medium my-1"></li>
            <li>
              <a href="{{ route('cargas.index', ['proximos_vencer' => 30]) }}"
                class="flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded text-xs">
                Ver todas
              </a>
            </li>
          </ul>
        </div>
      </div>
      @endif
    </div>

    {{-- ── TARJETAS DE ESTADÍSTICAS ───────────────────────────── --}}
    <div class="grid grid-cols-2 gap-6 border-b border-t border-gray-200 py-6 md:py-8 lg:grid-cols-4 xl:gap-16 xl:ml-6">

      {{-- Pacientes --}}
      <div>
        <i data-lucide="book-user" class="mb-2 h-8 w-8 text-yellow-500"></i>
        <h3 class="mb-2 text-gray-500">Pacientes registrados</h3>
        <span class="flex items-center text-2xl font-bold text-gray-900">
          {{ $totalPacientes }}
          @if($pacientesActivos > 0)
          <span
            class="ms-2 inline-flex items-center rounded bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
            <i data-lucide="circle-check-big" class="w-3 h-3 -ms-0.5 me-1"></i>
            {{ $pacientesActivos }} activos
          </span>
          @endif
        </span>
        <p class="mt-2 text-sm text-gray-500">
          <a href="{{ route('pacientes.index') }}" class="text-yellow-600 hover:underline">Ver todos →</a>
        </p>
      </div>

      {{-- Vacunas en catálogo --}}
      <div>
        <i data-lucide="syringe" class="mb-2 h-8 w-8 text-green-500"></i>
        <h3 class="mb-2 text-gray-500">Vacunas en catálogo</h3>
        <span class="flex items-center text-2xl font-bold text-gray-900">
          {{ \App\Models\Vacuna::count() }}
        </span>
        <p class="mt-2 text-sm text-gray-500">
          <a href="{{ route('vacunas.index') }}" class="text-green-600 hover:underline">Ver catálogo →</a>
        </p>
      </div>

      {{-- Personal --}}
      <div>
        <i data-lucide="stethoscope" class="mb-2 h-8 w-8 text-purple-600"></i>
        <h3 class="mb-2 text-gray-500">Personal registrado</h3>
        <span class="flex items-center text-2xl font-bold text-gray-900">
          {{ $stats['total_personal'] }}
        </span>
        <p class="mt-2 text-sm text-gray-500">
          <a href="{{ route('personal.index') }}" class="text-purple-600 hover:underline">Gestionar →</a>
        </p>
      </div>

      {{-- Dosis disponibles --}}
      <div>
        <i data-lucide="package-2" class="mb-2 h-8 w-8 text-teal-500"></i>
        <h3 class="mb-2 text-gray-500">Dosis disponibles</h3>
        <span class="flex items-center text-2xl font-bold text-gray-900">
          {{ number_format($totalDosisDisponibles) }}
          {{-- @if($proxVencer->count() > 0)
          <span
            class="ms-2 inline-flex items-center rounded bg-orange-100 px-2.5 py-0.5 text-xs font-medium text-orange-800">
            <i data-lucide="alarm-clock" class="w-3 h-3 -ms-0.5 me-1"></i>
            {{ $proxVencer->count() }} por vencer
          </span>
          @endif --}}
        </span>
        <p class="mt-2 text-sm text-gray-500">
          <a href="{{ route('cargas.index') }}" class="text-teal-600 hover:underline">Ver cargas →</a>
        </p>
      </div>
    </div>

    {{-- ── DATOS DEL ASIC ──────────────────────── --}}
    <div class="py-6 md:py-8">
      <div class="mb-6 grid gap-6 sm:grid-cols-2 lg:gap-12 ">

        {{-- Info del ASIC --}}
        <div class="space-y-4 bg-linear-to-r from-red-700 via-red-800 to-red-900 rounded-xl p-6 mb-6 text-white">
          <div class="flex items-center gap-4">
            <div class="p-3 bg-white/20 rounded-xl shrink-0">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-hospital-icon lucide-hospital text-white">
                <path d="M12 7v4" />
                <path d="M14 21v-3a2 2 0 0 0-4 0v3" />
                <path d="M14 9h-4" />
                <path d="M18 11h2a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-9a2 2 0 0 1 2-2h2" />
                <path d="M18 21V5a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16" />
              </svg>
            </div>
            <div>
              <p class="text-primary-200 text-xs font-semibold uppercase tracking-widest mb-1">Centro de Salud Principal
              </p>
              <h1 class="text-2xl font-bold">{{ $asic->nombre }}</h1>
            </div>
          </div>

          <dl>
            <dt class="font-semibold text-red-200">RIF</dt>
            <dd class="text-white font-mono">{{ $asic->rif }}</dd>
          </dl>
          <dl>
            <dt class="font-semibold text-red-200">Dirección</dt>
            <dd class="flex items-center gap-1 text-white">
              <i data-lucide="map-pin" class="hidden h-5 w-5 shrink-0 lg:inline"></i>
              {{ $asic->direccion }}
            </dd>
          </dl>
          <dl>
            <dt class="font-semibold text-red-100">Teléfono</dt>
            <dd class="flex items-center gap-1 text-white">
              <i data-lucide="phone" class="hidden h-5 w-5 shrink-0 lg:inline"></i>
              {{ $asic->telefono }}
            </dd>
          </dl>
          <dl>
            @if($esAdmin)
            <dd class="items-center bg-white/20 rounded-xl lg:inline-flex px-1 py-1 font-semibold text-white hover:bg-fg-danger-strong focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2">    
              <a href="{{ route('asic.edit', $asic->id) }}"
                class="inline-flex items-center justify-center rounded p-2 text-xs font-medium text-white">
                <i data-lucide="Square-pen" class="w-4 h-4 -ms-0.5 me-1.5"></i>
                Modificar
              </a>
              @endif
            </dd>
          </dl>
        </div>

        {{-- Distribución de módulos y personal --}}
        <div class="space-y-4">
          <dl>
            <dt class="font-semibold text-gray-900">Módulos afiliados</dt>
            @if($asic->modulos->count() > 0)
            <dd class="mt-2 space-y-1.5">
              @foreach($asic->modulos as $modulo)
              <div class="flex items-center gap-2 text-gray-500">
                <i data-lucide="building" class="h-4 w-4 shrink-0 text-gray-400"></i>
                {{ $modulo->nombre }}
              </div>
              @endforeach
            </dd>
            @else
            <dd class="text-gray-400 text-sm mt-1">Sin módulos registrados</dd>
            @endif
          </dl>
          <dl>
            <dt class="mb-2 font-semibold text-gray-900">Personal por cargo</dt>
            @php
            $personalPorCargo = $asic->personal->groupBy(fn($p) => $p->cargo?->nombre ?? 'Sin cargo');
            $cfgCargos = [
            'Administrador' => 'bg-red-100 text-red-800',
            'Asistente Administrativo' => 'bg-blue-100 text-blue-800',
            'Jefe de Módulo' => 'bg-yellow-100 text-yellow-800',
            'Vacunador' => 'bg-green-100 text-green-800',
            ];
            @endphp
            <dd class="flex flex-wrap gap-2">
              @forelse($personalPorCargo as $cargo => $personas)
              @php $badgeClass = $cfgCargos[$cargo] ?? 'bg-gray-100 text-gray-700'; @endphp
              <span
                class="inline-flex items-center gap-1.5 rounded px-2.5 py-0.5 text-xs font-medium {{ $badgeClass }}">
                {{ $cargo }}: <strong>{{ $personas->count() }}</strong>
              </span>
              @empty
              <span class="text-gray-400 text-sm">Sin personal registrado</span>
              @endforelse
            </dd>
          </dl>
          <dl>
            <dt class="mb-2 font-semibold text-gray-900">Estadísticas de operación</dt>
            <dd class="flex flex-wrap gap-2 text-sm text-gray-500">
              <span class="flex items-center gap-1.5">
                <i data-lucide="package-2" class="w-4 h-4 text-green-500"></i>
                <strong class="text-gray-900">{{ number_format($stats['dosis_recibidas']) }}</strong> dosis recibidas
              </span>
              <span class="text-gray-300">·</span>
              <span class="flex items-center gap-1.5">
                <i data-lucide="send" class="w-4 h-4 text-orange-500"></i>
                <strong class="text-gray-900">{{ number_format($stats['dosis_despachadas']) }}</strong> despachadas
              </span>
            </dd>
          </dl>
        </div>
      </div>

      {{-- Botón editar — solo admins --}}
      {{-- @if($esAdmin)
      <a href="{{ route('asic.edit', $asic->id) }}"
        class="inline-flex items-center justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300">
        <i data-lucide="pencil" class="w-4 h-4 -ms-0.5 me-1.5"></i>
        Editar datos del ASIC
      </a>
      @endif
    </div> --}}

    {{-- ── ACTIVIDAD RECIENTE ──────────────────────────────────── --}}
    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 md:p-8">
      <h3 class="mb-5 text-xl font-semibold text-gray-900">Actividad reciente</h3>

      {{-- Accesos rápidos --}}
      <div class="flex flex-wrap gap-2 mb-5 pb-5 border-b border-gray-200">
        <a href="{{ route('pacientes.create') }}"
          class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors">
          <i data-lucide="user-plus" class="w-4 h-4"></i> Nuevo paciente
        </a>
        <a href="{{ route('cargas.create') }}"
          class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors">
          <i data-lucide="package-plus" class="w-4 h-4"></i> Registrar carga
        </a>
        <a href="{{ route('despachos.create') }}"
          class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-orange-50 text-orange-700 rounded-lg hover:bg-orange-100 transition-colors">
          <i data-lucide="send" class="w-4 h-4"></i> Nuevo despacho
        </a>
        <a href="{{ route('cargas.reporte.general') }}"
          class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
          <i data-lucide="file-down" class="w-4 h-4"></i> Reporte de cargas
        </a>
      </div>

      {{-- Últimas cargas --}}
      @forelse($ultimasCargas as $carga)
      @php
      $dias = now()->diffInDays(\Carbon\Carbon::parse($carga->fecha_vencimiento), false);
      if ($dias < 0) $badge=['bg-red-100 text-red-800', 'Vencida' ]; elseif($dias <=30) $badge=['bg-orange-100
        text-orange-800', 'Próx. vencer' ]; elseif($dias <=90) $badge=['bg-yellow-100 text-yellow-800', 'Por vencer' ];
        else $badge=['bg-green-100 text-green-800', 'Vigente' ]; @endphp <div class="flex flex-wrap items-center gap-y-4
            {{ !$loop->last ? 'border-b border-gray-200 pb-4 mb-4 md:pb-5' : 'pt-4' }}">

        <dl class="w-1/2 sm:w-48">
          <dt class="text-base font-medium text-gray-500">Vacuna:</dt>
          <dd class="mt-1 text-base font-semibold text-gray-900">
            {{ $carga->vacuna?->nombre ?? '—' }}
          </dd>
        </dl>

        <dl class="w-1/2 sm:w-1/4 md:flex-1 lg:w-auto">
          <dt class="text-base font-medium text-gray-500">Lote:</dt>
          <dd class="mt-1 text-base font-semibold text-gray-900 font-mono">{{ $carga->lote }}</dd>
        </dl>

        <dl class="w-1/2 sm:w-1/5 md:flex-1 lg:w-auto">
          <dt class="text-base font-medium text-gray-500">Cantidad:</dt>
          <dd class="mt-1 text-base font-semibold text-gray-900">{{ number_format($carga->cantidad) }} dosis</dd>
        </dl>

        <dl class="w-1/2 sm:w-1/4 sm:flex-1 lg:w-auto">
          <dt class="text-base font-medium text-gray-500">Estado:</dt>
          <dd
            class="me-2 mt-1 inline-flex shrink-0 items-center rounded {{ $badge[0] }} px-2.5 py-0.5 text-xs font-medium">
            <i data-lucide="{{ $dias < 0 ? 'alert-circle' : ($dias <= 30 ? 'alarm-clock' : 'check-circle') }}"
              class="me-1 h-3 w-3"></i>
            {{ $badge[1] }}
            @if($dias >= 0) · {{ (int)$dias }}d @endif
          </dd>
        </dl>

        <div class="w-full sm:flex sm:w-32 sm:items-center sm:justify-end sm:gap-4">
          <div class="relative">
            <button id="cargaAccBtn{{ $carga->id }}" data-dropdown-toggle="cargaAccDd{{ $carga->id }}"
              data-dropdown-placement="left" type="button"
              class="flex w-full items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 md:w-auto">
              Acciones
              <i data-lucide="chevron-down" class="w-4 h-4 -me-0.5 ms-1.5"></i>
            </button>
            <div id="cargaAccDd{{ $carga->id }}"
              class="hidden z-10 w-44 divide-y divide-gray-100 rounded-lg bg-white shadow">
              <ul class="p-2 text-sm text-gray-700">
                <li>
                  <a href="{{ route('cargas.show', $carga->id) }}"
                    class="flex items-center gap-2 rounded-md px-3 py-2 hover:bg-gray-100 hover:text-gray-900">
                    <i data-lucide="eye" class="w-4 h-4 text-blue-500"></i> Ver detalle
                  </a>
                </li>
                <li>
                  <a href="{{ route('cargas.edit', $carga->id) }}"
                    class="flex items-center gap-2 rounded-md px-3 py-2 hover:bg-gray-100 hover:text-gray-900">
                    <i data-lucide="pencil" class="w-4 h-4 text-yellow-500"></i> Editar
                  </a>
                </li>
                {{-- <li>
                  <a href="{{ route('cargas.clone', $carga->id) }}"
                    class="flex items-center gap-2 rounded-md px-3 py-2 hover:bg-gray-100 hover:text-gray-900">
                    <i data-lucide="copy" class="w-4 h-4 text-purple-500"></i> Clonar
                  </a>
                </li> --}}
                <li>
                  <a href="{{ route('cargas.reporte.individual', $carga->id) }}"
                    class="flex items-center gap-2 rounded-md px-3 py-2 hover:bg-gray-100 hover:text-gray-900">
                    <i data-lucide="file-down" class="w-4 h-4 text-green-500"></i> PDF
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
    </div>
    @empty
    <div class="text-center py-8 text-gray-400">
      <i data-lucide="package-x" class="w-10 h-10 mx-auto mb-2 text-gray-300"></i>
      <p class="text-sm">Sin cargas registradas</p>
    </div>
    @endforelse

    <div class="mt-4 pt-4 border-t border-gray-200 flex items-center justify-between">
      <a href="{{ route('cargas.index') }}" class="text-sm text-primary-600 hover:underline flex items-center gap-1">
        Ver todas las cargas <i data-lucide="arrow-right" class="w-4 h-4"></i>
      </a>
    </div>
  </div>
</div>


@endif {{-- /if $asic --}}
</div>

@push('scripts')
<script>
  lucide.createIcons();
</script>
@endpush
@endsection