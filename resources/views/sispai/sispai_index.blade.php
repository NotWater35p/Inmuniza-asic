@extends('layouts.app')

@section('title', 'SISPAI — ' . \Carbon\Carbon::create($anio, $mes)->translatedFormat('F Y'))

@push('styles')
<style>
/* ── Paleta de colores por sección ──────────────────────────── */
:root {
    --s-blue:   #1d4ed8; --s-blue-bg:   #eff6ff; --s-blue-bd:   #bfdbfe;
    --s-green:  #15803d; --s-green-bg:  #f0fdf4; --s-green-bd:  #bbf7d0;
    --s-amber:  #b45309; --s-amber-bg:  #fffbeb; --s-amber-bd:  #fde68a;
    --s-purple: #7c3aed; --s-purple-bg: #f5f3ff; --s-purple-bd: #ddd6fe;
    --s-red:    #b91c1c; --s-red-bg:    #fef2f2; --s-red-bd:    #fecaca;
    --s-orange: #c2410c; --s-orange-bg: #fff7ed; --s-orange-bd: #fed7aa;
    --s-teal:   #0f766e; --s-teal-bg:   #f0fdfa; --s-teal-bd:   #99f6e4;
    --s-rose:   #be123c; --s-rose-bg:   #fff1f2; --s-rose-bd:   #fecdd3;
    --s-indigo: #4338ca; --s-indigo-bg: #eef2ff; --s-indigo-bd: #c7d2fe;
}

/* ── Botones de módulo ──────────────────────────────────────── */
.tab-btn { transition: all .15s ease; }
.tab-btn.active {
    background: #1d4ed8;
    color: #fff;
    border-color: #1d4ed8;
    box-shadow: 0 1px 4px rgba(29,78,216,.35);
}

/* ── Sección vacuna ─────────────────────────────────────────── */
.sec-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    padding: .6rem .75rem;
    border: none;
    border-radius: .5rem;
    cursor: pointer;
    font-weight: 600;
    font-size: .85rem;
    letter-spacing: .02em;
    transition: opacity .15s;
}
.sec-header:hover { opacity: .9; }
.sec-icon { font-size: 1rem; }
.sec-chevron { transition: transform .25s; font-size: .75rem; }
.sec-header[aria-expanded="true"] .sec-chevron { transform: rotate(180deg); }
.sec-body { overflow: hidden; }

/* ── Grid de inputs ─────────────────────────────────────────── */
.input-row {
    display: grid;
    grid-template-columns: 1fr auto;
    align-items: center;
    gap: .5rem;
    padding: .45rem .5rem;
    border-bottom: 1px solid #f3f4f6;
}
.input-row:last-child { border-bottom: none; }
.input-row label { font-size: .78rem; color: #374151; line-height: 1.3; }
.input-row input[type=number] {
    width: 72px;
    text-align: center;
    padding: .3rem .4rem;
    border: 1px solid #d1d5db;
    border-radius: .375rem;
    font-size: .9rem;
    font-weight: 600;
    background: #fff;
    color: #111827;
    -moz-appearance: textfield;
}
.input-row input[type=number]::-webkit-inner-spin-button { display: none; }
.input-row input[type=number]:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 2px rgba(99,102,241,.2);
}
.input-row input.modified { border-color: #f59e0b; background: #fffbeb; }

/* ── Subgrupo dentro de sección ─────────────────────────────── */
.subgroup-title {
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: #6b7280;
    padding: .5rem .5rem .2rem;
    margin-top: .2rem;
    background: #f9fafb;
    border-top: 1px solid #f3f4f6;
}

/* ── Badges de estado ───────────────────────────────────────── */
.badge-sin { background:#fffbeb; color:#92400e; border:1px solid #fde68a; }
.badge-covid { background:#ede9fe; color:#5b21b6; border:1px solid #ddd6fe; }

/* ── Barra export sticky ────────────────────────────────────── */
.export-bar {
    position: sticky;
    bottom: 0;
    background: rgba(255,255,255,.95);
    backdrop-filter: blur(6px);
    border-top: 1px solid #e5e7eb;
    padding: .75rem 1rem;
    display: flex;
    gap: .5rem;
    flex-wrap: wrap;
    z-index: 40;
}

/* ── Toggle notificante ─────────────────────────────────────── */
.toggle-wrap { display: flex; align-items: center; gap: .5rem; }
.toggle-wrap input[type=checkbox] { accent-color: #16a34a; width: 1.1rem; height: 1.1rem; }

/* ── Color vars por sección ─────────────────────────────────── */
.color-blue   { background: var(--s-blue-bg);   color: var(--s-blue);   border: 1px solid var(--s-blue-bd); }
.color-green  { background: var(--s-green-bg);  color: var(--s-green);  border: 1px solid var(--s-green-bd); }
.color-amber  { background: var(--s-amber-bg);  color: var(--s-amber);  border: 1px solid var(--s-amber-bd); }
.color-purple { background: var(--s-purple-bg); color: var(--s-purple); border: 1px solid var(--s-purple-bd); }
.color-red    { background: var(--s-red-bg);    color: var(--s-red);    border: 1px solid var(--s-red-bd); }
.color-orange { background: var(--s-orange-bg); color: var(--s-orange); border: 1px solid var(--s-orange-bd); }
.color-teal   { background: var(--s-teal-bg);   color: var(--s-teal);   border: 1px solid var(--s-teal-bd); }
.color-rose   { background: var(--s-rose-bg);   color: var(--s-rose);   border: 1px solid var(--s-rose-bd); }
.color-indigo { background: var(--s-indigo-bg); color: var(--s-indigo); border: 1px solid var(--s-indigo-bd); }

@media (min-width: 640px) {
    .input-row { grid-template-columns: 1fr repeat(3, 72px); }
}
</style>
@endpush

@section('content')
<div class="max-w-2xl mx-auto px-3 pb-28">

    {{-- ══ ENCABEZADO ══════════════════════════════════════════════════ --}}
    <div class="flex items-center justify-between mt-4 mb-3">
        <div>
            <h1 class="text-lg font-bold text-gray-900">SISPAI</h1>
            <p class="text-xs text-gray-500">Reporte mensual de dosis aplicadas</p>
        </div>
        {{-- Selector mes/año --}}
        <form method="GET" action="{{ route('sispai.index') }}" class="flex gap-2 items-center">
            <select name="mes" onchange="this.form.submit()"
                    class="text-sm border border-gray-300 rounded-lg px-2 py-1.5 bg-white focus:ring-2 focus:ring-blue-500">
                @foreach (range(1, 12) as $m)
                    <option value="{{ $m }}" {{ $m == $mes ? 'selected' : '' }}>
                        {{ ['','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'][$m] }}
                    </option>
                @endforeach
            </select>
            <select name="anio" onchange="this.form.submit()"
                    class="text-sm border border-gray-300 rounded-lg px-2 py-1.5 bg-white focus:ring-2 focus:ring-blue-500">
                @foreach (range(now()->year - 1, now()->year + 1) as $a)
                    <option value="{{ $a }}" {{ $a == $anio ? 'selected' : '' }}>{{ $a }}</option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- ══ ALERTAS ══════════════════════════════════════════════════════ --}}

    {{-- Dosis sin clasificar (descargos rápidos) --}}
    @php
        $haySinClasif = collect($sinClasificar)->flatten()->sum() > 0;
        $hayCovidTotal = collect($covidTotal)->sum() > 0;
    @endphp
    @if($haySinClasif)
    <div class="mb-3 rounded-xl p-3 badge-sin flex gap-2 text-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="shrink-0 mt-0.5" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        <div>
            <p class="font-semibold mb-1">Dosis sin clasificar (descargos rápidos)</p>
            <p class="text-xs mb-2">Estas dosis no tienen paciente registrado y no se pueden asignar automáticamente a un grupo etario. Agrégalas manualmente en la sección correspondiente.</p>
            @foreach ($modulos as $modulo)
                @if (!empty($sinClasificar[$modulo->id]))
                    <p class="font-medium text-xs">{{ $modulo->nombre }}:</p>
                    <ul class="text-xs ml-3 mb-1">
                        @foreach ($sinClasificar[$modulo->id] as $vacNombre => $n)
                            <li>{{ $vacNombre }}: <strong>{{ $n }}</strong> dosis</li>
                        @endforeach
                    </ul>
                @endif
            @endforeach
        </div>
    </div>
    @endif

    {{-- COVID-19 --}}
    @if($hayCovidTotal)
    <div class="mb-3 rounded-xl p-3 badge-covid flex gap-2 text-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="shrink-0 mt-0.5" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <div>
            <p class="font-semibold mb-1">Dosis COVID-19 registradas</p>
            <p class="text-xs">COVID-19 no tiene columna en la plantilla SISPAI. Estas dosis se incluirán en el total de la vista pero <strong>no en el Excel</strong>.</p>
            @foreach ($modulos as $modulo)
                @if (!empty($covidTotal[$modulo->id]))
                    <span class="inline-block text-xs mt-1 mr-3">
                        <strong>{{ $modulo->nombre }}:</strong> {{ $covidTotal[$modulo->id] }} dosis
                    </span>
                @endif
            @endforeach
        </div>
    </div>
    @endif

    {{-- Errores de validación --}}
    @if ($errors->any())
    <div class="mb-3 rounded-xl p-3 bg-red-50 border border-red-200 text-red-800 text-sm">
        @foreach ($errors->all() as $error)
            <p>⚠️ {{ $error }}</p>
        @endforeach
    </div>
    @endif

    {{-- ══ TABS DE MÓDULOS ══════════════════════════════════════════════ --}}
    @if ($modulos->count() > 1)
    <div class="flex gap-2 overflow-x-auto pb-2 mb-3 scrollbar-hide">
        @foreach ($modulos as $idx => $modulo)
        <button type="button"
                class="tab-btn shrink-0 px-4 py-2 rounded-full border border-gray-300 text-sm font-medium text-gray-600 bg-white whitespace-nowrap {{ $idx === 0 ? 'active' : '' }}"
                onclick="switchTab({{ $modulo->id }}, this)">
            {{ $modulo->nombre }}
        </button>
        @endforeach
    </div>
    @endif

    {{-- ══ FORMULARIO PRINCIPAL ═════════════════════════════════════════ --}}
    <form method="POST" action="{{ route('sispai.excel') }}" id="sispai-form">
        @csrf
        <input type="hidden" name="mes"  value="{{ $mes }}">
        <input type="hidden" name="anio" value="{{ $anio }}">

        @foreach ($modulos as $idx => $modulo)
        <div id="modulo-{{ $modulo->id }}"
             class="{{ $idx > 0 ? 'hidden' : '' }} space-y-2">

            {{-- ── Notificante ──────────────────────────────────────── --}}
            <div class="rounded-xl border border-gray-200 bg-white px-4 py-3 flex items-center justify-between">
                <div>
                    <p class="font-semibold text-sm text-gray-800">{{ $modulo->nombre }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Fila SISPAI: <strong>{{ $modulo->sispai_fila }}</strong>
                        &nbsp;·&nbsp; {{ $modulo->tipo_establecimiento }}
                    </p>
                </div>
                <label class="toggle-wrap cursor-pointer">
                    <input type="checkbox"
                           name="v[{{ $modulo->id }}][notificante]"
                           value="1"
                           {{ isset($tuvoJornadas[$modulo->id]) ? 'checked' : '' }}>
                    <span class="text-xs font-medium {{ isset($tuvoJornadas[$modulo->id]) ? 'text-green-700' : 'text-gray-500' }}">
                        Notificante
                    </span>
                </label>
            </div>

            {{-- ── Secciones de vacunas ─────────────────────────────── --}}
            @foreach ($sections as $secNombre => $seccion)
            @php
                $color    = $seccion['color'] ?? 'blue';
                $modDatos = $datos[$modulo->id] ?? [];
                $todasLasCols = isset($seccion['cols'])
                    ? array_keys($seccion['cols'])
                    : collect($seccion['grupos'] ?? [])->flatMap(fn($c) => array_keys($c))->toArray();
                $tieneData = collect($todasLasCols)->some(fn($c) => ($modDatos[$c] ?? 0) > 0);
                $secId     = 'sec-' . $modulo->id . '-' . preg_replace('/[^a-z0-9]+/', '-', strtolower($secNombre));
            @endphp

            <div class="rounded-xl border border-gray-200 overflow-hidden bg-white">
                {{-- Header colapsable --}}
                <button type="button"
                        class="sec-header color-{{ $color }}"
                        aria-expanded="{{ $tieneData ? 'true' : 'false' }}"
                        onclick="toggleSec(this, '{{ $secId }}')">
                    <span class="sec-icon">
                        @switch($color)
                            @case('blue')   💉 @break
                            @case('green')  🟢 @break
                            @case('amber')  🟡 @break
                            @case('purple') 💜 @break
                            @case('red')    🔴 @break
                            @case('orange') 🟠 @break
                            @case('teal')   🩵 @break
                            @case('rose')   📉 @break
                            @case('indigo') 🏥 @break
                            @default        💊
                        @endswitch
                        {{ $secNombre }}
                    </span>
                    <span class="flex items-center gap-2">
                        @if($tieneData)
                        <span class="text-xs font-normal opacity-80">
                            {{ collect($todasLasCols)->sum(fn($c) => $datos[$modulo->id][$c] ?? 0) }} dosis
                        </span>
                        @endif
                        <span class="sec-chevron">▼</span>
                    </span>
                </button>

                {{-- Body --}}
                <div id="{{ $secId }}" class="sec-body {{ !$tieneData ? 'hidden' : '' }}">
                    @if(isset($seccion['cols']))
                        {{-- Sección plana --}}
                        <div class="divide-y divide-gray-50">
                        @foreach($seccion['cols'] as $col => $label)
                            <div class="input-row">
                                <label for="{{ $secId }}-{{ $col }}">{{ $label }}</label>
                                <input type="number"
                                       id="{{ $secId }}-{{ $col }}"
                                       name="v[{{ $modulo->id }}][{{ $col }}]"
                                       value="{{ $datos[$modulo->id][$col] ?? 0 }}"
                                       min="0"
                                       oninput="markModified(this)">
                            </div>
                        @endforeach
                        </div>

                    @elseif(isset($seccion['grupos']))
                        {{-- Sección agrupada --}}
                        @foreach($seccion['grupos'] as $grupoNombre => $cols)
                        <div class="subgroup-title">{{ $grupoNombre }}</div>
                        <div class="divide-y divide-gray-50 mb-1">
                            @foreach($cols as $col => $label)
                            <div class="input-row">
                                <label for="{{ $secId }}-{{ $col }}">
                                    {{ $label ?: $grupoNombre }}
                                </label>
                                <input type="number"
                                       id="{{ $secId }}-{{ $col }}"
                                       name="v[{{ $modulo->id }}][{{ $col }}]"
                                       value="{{ $datos[$modulo->id][$col] ?? 0 }}"
                                       min="0"
                                       oninput="markModified(this)">
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
            @endforeach

        </div>{{-- /modulo --}}
        @endforeach

        {{-- ══ BARRA DE EXPORTACIÓN ════════════════════════════════════ --}}
        <div class="export-bar">
            <button type="submit"
                    class="flex-1 flex items-center justify-center gap-2 bg-blue-700 hover:bg-blue-800 text-white font-semibold rounded-xl py-2.5 px-4 text-sm transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Exportar Excel SISPAI
            </button>

            {{-- Botones PDF por módulo --}}
            @foreach ($modulos as $modulo)
            <button type="button"
                    id="pdf-btn-{{ $modulo->id }}"
                    onclick="exportPDF({{ $modulo->id }})"
                    class="{{ $modulos->count() > 1 ? 'hidden' : '' }} flex items-center justify-center gap-1.5 border border-gray-300 text-gray-700 font-medium rounded-xl py-2.5 px-3 text-sm bg-white hover:bg-gray-50 transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                PDF
            </button>
            @endforeach
        </div>

    </form>{{-- /sispai-form --}}

</div>

{{-- Formulario oculto para PDF --}}
<form method="POST" action="{{ route('sispai.pdf') }}" id="pdf-form">
    @csrf
    <input type="hidden" name="mes"       id="pdf-mes"      value="{{ $mes }}">
    <input type="hidden" name="anio"      id="pdf-anio"     value="{{ $anio }}">
    <input type="hidden" name="modulo_id" id="pdf-modulo-id" value="">
</form>
@endsection

@push('scripts')
<script>
// ── Cambio de tab (módulo) ─────────────────────────────────────────────
function switchTab(moduloId, btn) {
    // Ocultar todos los paneles
    document.querySelectorAll('[id^="modulo-"]').forEach(el => el.classList.add('hidden'));
    // Quitar active de todos los tabs
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    // Mostrar panel activo
    document.getElementById('modulo-' + moduloId)?.classList.remove('hidden');
    btn.classList.add('active');

    // Mostrar/ocultar botón PDF del módulo activo
    document.querySelectorAll('[id^="pdf-btn-"]').forEach(b => b.classList.add('hidden'));
    document.getElementById('pdf-btn-' + moduloId)?.classList.remove('hidden');
}

// ── Colapso de sección vacuna ──────────────────────────────────────────
function toggleSec(btn, bodyId) {
    const body    = document.getElementById(bodyId);
    const open    = btn.getAttribute('aria-expanded') === 'true';
    btn.setAttribute('aria-expanded', !open);
    body.classList.toggle('hidden', open);
}

// ── Marcar input modificado ───────────────────────────────────────────
function markModified(input) {
    const original = input.defaultValue;
    input.classList.toggle('modified', input.value !== original);
}

// ── Export PDF ────────────────────────────────────────────────────────
function exportPDF(moduloId) {
    document.getElementById('pdf-modulo-id').value = moduloId;
    document.getElementById('pdf-form').submit();
}

// ── Auto-open secciones con datos al cambiar de tab ───────────────────
document.addEventListener('DOMContentLoaded', () => {
    // Mostrar PDF btn para el primer módulo visible
    @if($modulos->count() > 0)
    const primerModulo = {{ $modulos->first()->id }};
    const pdfBtn = document.getElementById('pdf-btn-' + primerModulo);
    if (pdfBtn) pdfBtn.classList.remove('hidden');
    @endif
});
</script>
@endpush
