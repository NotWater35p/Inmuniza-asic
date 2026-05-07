<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte de Despachos por Período</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size:10px; color:#1f2937; }
        .header { padding:18px 22px 14px; border-bottom:3px solid #2563eb; margin-bottom:14px; display:flex; justify-content:space-between; }
        .org-name { font-size:15px; font-weight:700; color:#1e40af; }
        .org-sub  { font-size:8.5px; color:#6b7280; margin-top:2px; }
        .doc-info { text-align:right; }
        .doc-info h1 { font-size:13px; font-weight:700; }
        .doc-info p  { font-size:8px; color:#9ca3af; margin-top:2px; }

        .meta-bar { background:#f1f5f9; border:1px solid #e2e8f0; border-radius:6px; padding:8px 14px; margin-bottom:14px; display:flex; justify-content:space-between; }
        .meta-item { text-align:center; }
        .meta-label { font-size:7.5px; color:#64748b; text-transform:uppercase; letter-spacing:.5px; font-weight:600; }
        .meta-value { font-size:14px; font-weight:700; color:#1e3a5f; margin-top:2px; }

        .two-col { display:flex; gap:12px; margin-bottom:12px; }
        .col { flex:1; }

        .section-title { font-size:8px; font-weight:700; text-transform:uppercase; color:#64748b; border-bottom:1px solid #e2e8f0; padding-bottom:4px; margin-bottom:8px; }

        .resumen-table { width:100%; border-collapse:collapse; }
        .resumen-table thead tr { background:#1e40af; color:#fff; }
        .resumen-table thead th { padding:5px 8px; font-size:8px; font-weight:600; text-align:left; text-transform:uppercase; }
        .resumen-table thead th.right { text-align:right; }
        .resumen-table tbody tr { border-bottom:1px solid #e2e8f0; }
        .resumen-table tbody tr:nth-child(even) { background:#f8fafc; }
        .resumen-table tbody td { padding:5px 8px; font-size:9px; }
        .resumen-table tbody td.right { text-align:right; font-weight:700; }

        table.main { width:100%; border-collapse:collapse; margin-bottom:10px; }
        table.main thead tr { background:#1e40af; color:#fff; }
        table.main thead th { padding:6px 8px; text-align:left; font-size:8px; font-weight:600; text-transform:uppercase; }
        table.main thead th.center { text-align:center; }
        table.main tbody tr:nth-child(even) { background:#f8fafc; }
        table.main tbody tr { border-bottom:1px solid #e2e8f0; }
        table.main tbody td { padding:5px 8px; font-size:9px; }
        table.main tbody td.center { text-align:center; }
        .totals-row td { background:#1e40af; color:#fff; font-weight:700; font-size:9.5px; padding:6px 8px; }

        .footer { border-top:1px solid #e2e8f0; padding-top:8px; margin-top:8px; display:flex; justify-content:space-between; }
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
            <h1>Reporte General de Despachos por Período</h1>
            <p>Generado: {{ $generadoEn }}</p>
        </div>
    </div>

    {{-- Métricas --}}
    <div class="meta-bar">
        <div class="meta-item">
            <div class="meta-label">Total Registros</div>
            <div class="meta-value">{{ $despachos->count() }}</div>
        </div>
        <div class="meta-item">
            <div class="meta-label">Total Dosis</div>
            <div class="meta-value">{{ number_format($totalDosis) }}</div>
        </div>
        <div class="meta-item">
            <div class="meta-label">Módulos</div>
            <div class="meta-value">{{ $resumenModulos->count() }}</div>
        </div>
        <div class="meta-item">
            <div class="meta-label">Vacunas</div>
            <div class="meta-value">{{ $resumenVacunas->count() }}</div>
        </div>
    </div>

    {{-- Dos columnas: módulos + vacunas --}}
    <div class="two-col">
        {{-- Por módulo --}}
        <div class="col">
            <div class="section-title">Dosis por Módulo</div>
            <table class="resumen-table">
                <thead>
                    <tr>
                        <th>Módulo</th>
                        <th class="right">Dosis</th>
                        <th class="right">Despachos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resumenModulos->sortByDesc('cantidad') as $rm)
                    <tr>
                        <td>{{ $rm['nombre'] }}</td>
                        <td class="right">{{ number_format($rm['cantidad']) }}</td>
                        <td class="right">{{ $rm['registros'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Por vacuna --}}
        <div class="col">
            <div class="section-title">Dosis por Vacuna</div>
            <table class="resumen-table">
                <thead>
                    <tr>
                        <th>Vacuna</th>
                        <th class="right">Dosis</th>
                        <th class="right">Despachos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resumenVacunas->sortByDesc('cantidad') as $rv)
                    <tr>
                        <td>{{ Str::limit($rv['nombre'], 25) }}</td>
                        <td class="right">{{ number_format($rv['cantidad']) }}</td>
                        <td class="right">{{ $rv['registros'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tabla completa --}}
    <div class="section-title" style="margin-top:14px;">Detalle Completo de Despachos</div>
    @if($despachos->isEmpty())
    <p style="text-align:center;color:#9ca3af;font-style:italic;padding:20px;">Sin despachos en el período seleccionado.</p>
    @else
    <table class="main">
        <thead>
            <tr>
                <th style="width:4%">#</th>
                <th style="width:14%" class="center">Fecha</th>
                <th style="width:22%">Vacuna</th>
                <th style="width:20%">Módulo</th>
                <th style="width:10%" class="center">Dosis</th>
                <th style="width:22%">Responsable</th>
            </tr>
        </thead>
        <tbody>
            @foreach($despachos as $i => $d)
            <tr>
                <td class="center" style="color:#9ca3af;">{{ $i+1 }}</td>
                <td class="center">{{ \Carbon\Carbon::parse($d->fecha_envio)->format('d/m/Y') }}</td>
                <td style="font-weight:600;">{{ $d->vacuna?->nombre ?? '—' }}</td>
                <td>{{ $d->modulo?->nombre ?? '—' }}</td>
                <td class="center" style="font-weight:700;">{{ number_format($d->cantidad) }}</td>
                <td>{{ $d->responsable ? $d->responsable->nombre . ' ' . $d->responsable->apellido : '—' }}</td>
            </tr>
            @endforeach
            <tr class="totals-row">
                <td colspan="4" style="text-align:right;">TOTAL DOSIS:</td>
                <td class="center">{{ number_format($totalDosis) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
    @endif

    <div class="footer">
        <div class="footer-text">Sistema de Gestión de Vacunas &bull; {{ $asic->nombre }}</div>
        <div class="footer-text">{{ $generadoEn }} &bull; Documento de uso interno</div>
    </div>
</body>
</html>