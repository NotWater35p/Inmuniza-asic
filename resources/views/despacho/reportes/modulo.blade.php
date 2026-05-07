<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Despachos – {{ $modulo->nombre }}</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size:10px; color:#1f2937; }

        .header { padding:18px 22px 14px; border-bottom:3px solid #2563eb; margin-bottom:14px; display:flex; justify-content:space-between; align-items:flex-start; }
        .org-name { font-size:15px; font-weight:700; color:#1e40af; }
        .org-sub  { font-size:8.5px; color:#6b7280; margin-top:2px; }
        .doc-info { text-align:right; }
        .doc-info h1 { font-size:13px; font-weight:700; }
        .doc-info p  { font-size:8px; color:#9ca3af; margin-top:2px; }

        .modulo-banner { background:#f5f3ff; border:1px solid #ddd6fe; border-radius:6px; padding:10px 14px; margin-bottom:12px; display:flex; justify-content:space-between; align-items:center; }
        .modulo-nombre { font-size:14px; font-weight:700; color:#5b21b6; }
        .modulo-dir    { font-size:8.5px; color:#7c3aed; margin-top:2px; }
        .periodo-badge { background:#ede9fe; color:#6d28d9; border-radius:4px; padding:3px 10px; font-size:9px; font-weight:700; }

        .meta-bar { background:#f1f5f9; border:1px solid #e2e8f0; border-radius:6px; padding:8px 14px; margin-bottom:12px; display:flex; justify-content:space-between; }
        .meta-item { text-align:center; }
        .meta-label { font-size:7.5px; color:#64748b; text-transform:uppercase; letter-spacing:.5px; font-weight:600; }
        .meta-value { font-size:13px; font-weight:700; color:#1e3a5f; margin-top:2px; }

        .section-title { font-size:8px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#64748b; border-bottom:1px solid #e2e8f0; padding-bottom:4px; margin-bottom:8px; margin-top:12px; }

        table { width:100%; border-collapse:collapse; margin-bottom:12px; }
        thead tr { background:#5b21b6; color:#fff; }
        thead th { padding:6px 8px; text-align:left; font-size:8px; font-weight:600; text-transform:uppercase; }
        thead th.center { text-align:center; }
        tbody tr:nth-child(even) { background:#faf5ff; }
        tbody tr { border-bottom:1px solid #e2e8f0; }
        tbody td { padding:5px 8px; font-size:9px; vertical-align:middle; }
        tbody td.center { text-align:center; }
        tbody td.bold { font-weight:700; }

        .totals-row td { background:#5b21b6; color:#fff; font-weight:700; font-size:9.5px; padding:6px 8px; }

        .resumen-grid { display:flex; gap:0; margin-bottom:12px; }
        .resumen-item { flex:1; border-right:1px solid #e2e8f0; padding:8px 12px; text-align:center; background:#f9fafb; }
        .resumen-item:last-child { border-right:none; }
        .resumen-item-label { font-size:7.5px; color:#64748b; text-transform:uppercase; font-weight:600; }
        .resumen-item-value { font-size:11px; font-weight:700; color:#4c1d95; margin-top:3px; }
        .resumen-item-sub   { font-size:7.5px; color:#9ca3af; margin-top:1px; }

        .footer { border-top:1px solid #e2e8f0; padding-top:8px; margin-top:8px; display:flex; justify-content:space-between; }
        .footer-text { font-size:7.5px; color:#9ca3af; }

        .firma-section { margin-top:28px; display:flex; justify-content:space-between; }
        .firma-box { text-align:center; width:45%; }
        .firma-line { border-top:1px solid #374151; padding-top:5px; margin-top:35px; }
        .firma-label { font-size:8.5px; font-weight:600; color:#374151; }
        .firma-sub   { font-size:7.5px; color:#9ca3af; margin-top:1px; }
    </style>
</head>
<body>

    <div class="header">
        <div>
            <div class="org-name">{{ $asic->nombre }}</div>
            <div class="org-sub">{{ $asic->direccion }}</div>
            <div class="org-sub">RIF: {{ $asic->rif }} &bull; Tel: {{ $asic->telefono }}</div>
        </div>
        <div class="doc-info">
            <h1>Reporte de Despachos por Módulo</h1>
            <p>Generado: {{ $generadoEn }}</p>
        </div>
    </div>

    {{-- Módulo banner --}}
    <div class="modulo-banner">
        <div>
            <div class="modulo-nombre">{{ $modulo->nombre }}</div>
            <div class="modulo-dir">{{ $modulo->direccion }}
                @if($modulo->telefono) &bull; Tel: {{ $modulo->telefono }} @endif
            </div>
        </div>
        <div class="periodo-badge">Período: {{ $periodo }}</div>
    </div>

    {{-- Resumen estadístico --}}
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
            <div class="meta-label">Vacunas distintas</div>
            <div class="meta-value">{{ $resumenVacunas->count() }}</div>
        </div>
        <div class="meta-item">
            <div class="meta-label">Último despacho</div>
            <div class="meta-value" style="font-size:10px;">
                {{ $despachos->isNotEmpty() ? \Carbon\Carbon::parse($despachos->first()->fecha_envio)->format('d/m/Y') : '—' }}
            </div>
        </div>
    </div>

    {{-- Resumen por vacuna --}}
    @if($resumenVacunas->count() > 1)
    <div class="section-title">Resumen por Vacuna</div>
    <div class="resumen-grid">
        @foreach($resumenVacunas as $rv)
        <div class="resumen-item">
            <div class="resumen-item-label">{{ Str::limit($rv['nombre'], 20) }}</div>
            <div class="resumen-item-value">{{ number_format($rv['cantidad']) }}</div>
            <div class="resumen-item-sub">{{ $rv['registros'] }} despacho(s)</div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Tabla detallada --}}
    <div class="section-title">Detalle de Despachos</div>

    @if($despachos->isEmpty())
    <p style="text-align:center; color:#9ca3af; font-style:italic; padding:20px;">
        No se encontraron despachos para este módulo en el período seleccionado.
    </p>
    @else
    <table>
        <thead>
            <tr>
                <th style="width:4%">#</th>
                <th style="width:22%">Vacuna</th>
                <th style="width:14%" class="center">Fecha Envío</th>
                <th style="width:12%" class="center">Cantidad</th>
                <th style="width:30%">Responsable</th>
                <th style="width:18%">Cédula</th>
            </tr>
        </thead>
        <tbody>
            @foreach($despachos as $i => $d)
            <tr>
                <td class="center" style="color:#9ca3af;">{{ $i + 1 }}</td>
                <td class="bold">{{ $d->vacuna?->nombre ?? '—' }}</td>
                <td class="center">{{ \Carbon\Carbon::parse($d->fecha_envio)->format('d/m/Y') }}</td>
                <td class="center bold">{{ number_format($d->cantidad) }}</td>
                <td>{{ $d->responsable ? $d->responsable->nombre . ' ' . $d->responsable->apellido : '—' }}</td>
                <td style="font-family: monospace; font-size:8.5px;">{{ $d->responsable_envio }}</td>
            </tr>
            @endforeach
            <tr class="totals-row">
                <td colspan="3" style="text-align:right;">TOTAL DOSIS DESPACHADAS:</td>
                <td class="center">{{ number_format($totalDosis) }}</td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>

    <div class="firma-section">
        <div class="firma-box">
            <div class="firma-line">
                <div class="firma-label">Coordinador ASIC</div>
                <div class="firma-sub">{{ $asic->nombre }}</div>
            </div>
        </div>
        <div class="firma-box">
            <div class="firma-line">
                <div class="firma-label">Responsable del Módulo</div>
                <div class="firma-sub">{{ $modulo->nombre }}</div>
            </div>
        </div>
    </div>
    @endif

    <div class="footer">
        <div class="footer-text">Sistema de Gestión de Vacunas &bull; {{ $asic->nombre }}</div>
        <div class="footer-text">Generado: {{ $generadoEn }} &bull; Uso interno</div>
    </div>
</body>
</html>