<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Despachos por Vacuna</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size:10px; color:#1f2937; }
        .header { padding:18px 22px 14px; border-bottom:3px solid #2563eb; margin-bottom:14px; display:flex; justify-content:space-between; }
        .org-name { font-size:15px; font-weight:700; color:#1e40af; }
        .org-sub  { font-size:8.5px; color:#6b7280; margin-top:2px; }
        .doc-info { text-align:right; }
        .doc-info h1 { font-size:13px; font-weight:700; }
        .doc-info p  { font-size:8px; color:#9ca3af; margin-top:2px; }

        .vacuna-banner { background:#f0fdf4; border:1px solid #bbf7d0; border-radius:6px; padding:10px 14px; margin-bottom:12px; display:flex; justify-content:space-between; align-items:center; }
        .vacuna-nombre { font-size:14px; font-weight:700; color:#15803d; }
        .vacuna-sub    { font-size:8.5px; color:#16a34a; margin-top:2px; }
        .stock-badge   { text-align:right; }
        .stock-label   { font-size:7.5px; color:#15803d; font-weight:600; text-transform:uppercase; }
        .stock-valor   { font-size:18px; font-weight:700; color:#15803d; }

        .meta-bar { background:#f1f5f9; border:1px solid #e2e8f0; border-radius:6px; padding:8px 14px; margin-bottom:12px; display:flex; justify-content:space-between; }
        .meta-item { text-align:center; }
        .meta-label { font-size:7.5px; color:#64748b; text-transform:uppercase; letter-spacing:.5px; font-weight:600; }
        .meta-value { font-size:13px; font-weight:700; color:#1e3a5f; margin-top:2px; }

        .section-title { font-size:8px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#64748b; border-bottom:1px solid #e2e8f0; padding-bottom:4px; margin-bottom:8px; margin-top:12px; }

        .resumen-grid { display:flex; gap:0; border:1px solid #e2e8f0; border-radius:6px; overflow:hidden; margin-bottom:12px; }
        .resumen-item { flex:1; border-right:1px solid #e2e8f0; padding:8px 10px; text-align:center; background:#f9fafb; }
        .resumen-item:last-child { border-right:none; }
        .resumen-label { font-size:7.5px; color:#64748b; text-transform:uppercase; font-weight:600; }
        .resumen-value { font-size:11px; font-weight:700; color:#15803d; margin-top:2px; }
        .resumen-sub   { font-size:7.5px; color:#9ca3af; margin-top:1px; }

        table { width:100%; border-collapse:collapse; }
        thead tr { background:#15803d; color:#fff; }
        thead th { padding:6px 8px; text-align:left; font-size:8px; font-weight:600; text-transform:uppercase; }
        thead th.center { text-align:center; }
        tbody tr:nth-child(even) { background:#f0fdf4; }
        tbody tr { border-bottom:1px solid #e2e8f0; }
        tbody td { padding:5px 8px; font-size:9px; vertical-align:middle; }
        tbody td.center { text-align:center; }
        .totals-row td { background:#15803d; color:#fff; font-weight:700; font-size:9.5px; padding:6px 8px; }

        .footer { border-top:1px solid #e2e8f0; padding-top:8px; margin-top:10px; display:flex; justify-content:space-between; }
        .footer-text { font-size:7.5px; color:#9ca3af; }
    </style>
</head>
<body>

    <div class="header">
        <div>
            <div class="org-name">{{ $asic->nombre }}</div>
            <div class="org-sub">{{ $asic->direccion }} &bull; RIF: {{ $asic->rif }}</div>
        </div>
        <div class="doc-info">
            <h1>Reporte de Despachos por Vacuna</h1>
            <p>Generado: {{ $generadoEn }}</p>
        </div>
    </div>

    {{-- Banner vacuna --}}
    <div class="vacuna-banner">
        <div>
            <div class="vacuna-nombre">{{ $vacuna?->nombre ?? 'Todas las Vacunas' }}</div>
            @if($vacuna)
            <div class="vacuna-sub">
                {{ $vacuna->enfermedad ?? '' }}
                @if($vacuna->marca) &bull; {{ $vacuna->marca->nombre }} @endif
            </div>
            @endif
        </div>
        @if($stockActual !== null)
        <div class="stock-badge">
            <div class="stock-label">Stock actual ASIC</div>
            <div class="stock-valor">{{ number_format($stockActual) }} dosis</div>
        </div>
        @endif
    </div>

    {{-- Métricas --}}
    <div class="meta-bar">
        <div class="meta-item">
            <div class="meta-label">Total Despachos</div>
            <div class="meta-value">{{ $despachos->count() }}</div>
        </div>
        <div class="meta-item">
            <div class="meta-label">Total Dosis</div>
            <div class="meta-value">{{ number_format($totalDosis) }}</div>
        </div>
        <div class="meta-item">
            <div class="meta-label">Módulos Alcanzados</div>
            <div class="meta-value">{{ $resumenModulos->count() }}</div>
        </div>
    </div>

    {{-- Distribución por módulo --}}
    @if($resumenModulos->count() > 0)
    <div class="section-title">Distribución por Módulo</div>
    <div class="resumen-grid">
        @foreach($resumenModulos as $rm)
        <div class="resumen-item">
            <div class="resumen-label">{{ Str::limit($rm['nombre'], 18) }}</div>
            <div class="resumen-value">{{ number_format($rm['cantidad']) }}</div>
            <div class="resumen-sub">{{ $rm['registros'] }} despacho(s)</div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Tabla --}}
    <div class="section-title">Historial de Despachos</div>
    @if($despachos->isEmpty())
    <p style="text-align:center;color:#9ca3af;font-style:italic;padding:20px;">No hay despachos para los filtros seleccionados.</p>
    @else
    <table>
        <thead>
            <tr>
                <th style="width:4%">#</th>
                <th style="width:14%" class="center">Fecha</th>
                <th style="width:28%">Módulo Destino</th>
                <th style="width:12%" class="center">Cantidad</th>
                <th style="width:28%">Responsable</th>
                <th style="width:14%">Cédula</th>
            </tr>
        </thead>
        <tbody>
            @foreach($despachos as $i => $d)
            <tr>
                <td class="center" style="color:#9ca3af;">{{ $i+1 }}</td>
                <td class="center">{{ \Carbon\Carbon::parse($d->fecha_envio)->format('d/m/Y') }}</td>
                <td style="font-weight:600;">{{ $d->modulo?->nombre ?? '—' }}</td>
                <td class="center" style="font-weight:700;">{{ number_format($d->cantidad) }}</td>
                <td>{{ $d->responsable ? $d->responsable->nombre . ' ' . $d->responsable->apellido : '—' }}</td>
                <td style="font-family:monospace;font-size:8.5px;">{{ $d->responsable_envio }}</td>
            </tr>
            @endforeach
            <tr class="totals-row">
                <td colspan="3" style="text-align:right;">TOTAL:</td>
                <td class="center">{{ number_format($totalDosis) }}</td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>
    @endif

    <div class="footer">
        <div class="footer-text">Sistema de Gestión de Vacunas &bull; {{ $asic->nombre }}</div>
        <div class="footer-text">{{ $generadoEn }} &bull; Uso interno</div>
    </div>
</body>
</html>