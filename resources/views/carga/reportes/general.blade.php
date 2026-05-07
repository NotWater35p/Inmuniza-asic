<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte General de Cargas</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1f2937; background: #fff; }

        /* HEADER */
        .header { padding: 20px 24px 16px; border-bottom: 3px solid #2563eb; margin-bottom: 16px; }
        .header-top { display: flex; justify-content: space-between; align-items: flex-start; }
        .org-name { font-size: 16px; font-weight: 700; color: #1e40af; }
        .org-sub { font-size: 9px; color: #6b7280; margin-top: 2px; }
        .report-title { text-align: right; }
        .report-title h1 { font-size: 14px; font-weight: 700; color: #1f2937; }
        .report-title p { font-size: 8px; color: #9ca3af; margin-top: 2px; }

        /* META INFO */
        .meta-bar { background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 6px;
                    padding: 8px 14px; margin-bottom: 14px; display: flex; justify-content: space-between; }
        .meta-item { text-align: center; }
        .meta-label { font-size: 7.5px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; }
        .meta-value { font-size: 11px; font-weight: 700; color: #1e3a5f; margin-top: 2px; }

        /* FILTROS ACTIVOS */
        .filtros-section { margin-bottom: 12px; }
        .filtros-title { font-size: 8px; font-weight: 700; color: #64748b; text-transform: uppercase;
                         letter-spacing: 0.5px; margin-bottom: 5px; }
        .filtro-tag { display: inline-block; background: #dbeafe; color: #1d4ed8; border-radius: 4px;
                      padding: 2px 7px; font-size: 8px; font-weight: 600; margin-right: 5px; }

        /* TABLE */
        table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        thead tr { background: #1e40af; color: #fff; }
        thead th { padding: 7px 8px; text-align: left; font-size: 8px; font-weight: 600;
                   text-transform: uppercase; letter-spacing: 0.4px; }
        thead th.center { text-align: center; }

        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr { border-bottom: 1px solid #e2e8f0; }
        tbody td { padding: 6px 8px; font-size: 9px; vertical-align: middle; }
        tbody td.center { text-align: center; }
        tbody td.right { text-align: right; }
        tbody td.mono { font-family: DejaVu Sans Mono, monospace; font-size: 8px; }

        /* BADGES */
        .badge { display: inline-block; border-radius: 10px; padding: 2px 7px; font-size: 7.5px; font-weight: 700; }
        .badge-green  { background: #dcfce7; color: #15803d; }
        .badge-yellow { background: #fef9c3; color: #a16207; }
        .badge-orange { background: #ffedd5; color: #c2410c; }
        .badge-red    { background: #fee2e2; color: #b91c1c; }

        /* TOTALES */
        .totals-row { background: #1e40af !important; }
        .totals-row td { color: #fff; font-weight: 700; font-size: 9.5px; padding: 7px 8px; }

        /* FOOTER */
        .footer { border-top: 2px solid #e2e8f0; padding-top: 10px; margin-top: 6px;
                  display: flex; justify-content: space-between; align-items: center; }
        .footer-left { font-size: 7.5px; color: #9ca3af; }
        .footer-right { font-size: 7.5px; color: #9ca3af; text-align: right; }
        .page-num { font-weight: 600; color: #6b7280; }

        /* Sin resultados */
        .empty { text-align: center; padding: 30px; color: #9ca3af; font-style: italic; }
    </style>
</head>
<body>

    {{-- ENCABEZADO --}}
    <div class="header">
        <div class="header-top">
            <div>
                <div class="org-name">{{ $asic->nombre }}</div>
                <div class="org-sub">{{ $asic->direccion }} &bull; {{ $asic->telefono }}</div>
                <div class="org-sub">RIF: {{ $asic->rif }}</div>
            </div>
            <div class="report-title">
                <h1>Reporte de Cargas de Vacunas</h1>
                <p>Generado: {{ $generadoEn }}</p>
            </div>
        </div>
    </div>

    {{-- BARRA DE RESUMEN --}}
    <div class="meta-bar">
        <div class="meta-item">
            <div class="meta-label">Total Registros</div>
            <div class="meta-value">{{ $cargas->count() }}</div>
        </div>
        <div class="meta-item">
            <div class="meta-label">Total Dosis</div>
            <div class="meta-value">{{ number_format($totalDosis) }}</div>
        </div>
        <div class="meta-item">
            <div class="meta-label">Vacunas Distintas</div>
            <div class="meta-value">{{ $cargas->pluck('vacuna_id')->unique()->count() }}</div>
        </div>
        <div class="meta-item">
            <div class="meta-label">Vencidas</div>
            <div class="meta-value" style="color: #b91c1c;">
                {{ $cargas->filter(fn($c) => \Carbon\Carbon::parse($c->fecha_vencimiento)->isPast())->count() }}
            </div>
        </div>
        <div class="meta-item">
            <div class="meta-label">Próx. a Vencer (30d)</div>
            <div class="meta-value" style="color: #c2410c;">
                {{ $cargas->filter(fn($c) => !(\Carbon\Carbon::parse($c->fecha_vencimiento)->isPast()) && \Carbon\Carbon::parse($c->fecha_vencimiento)->diffInDays(now()) <= 30)->count() }}
            </div>
        </div>
    </div>

    {{-- FILTROS APLICADOS --}}
    @php
        $filtrosAplicados = array_filter([
            'Vacuna'         => request('vacuna'),
            'Lote'           => request('lote'),
            'Llegada desde'  => request('fecha_llegada_desde'),
            'Llegada hasta'  => request('fecha_llegada_hasta'),
            'Vence desde'    => request('fecha_vencimiento_desde'),
            'Vence hasta'    => request('fecha_vencimiento_hasta'),
            'Cantidad min'   => request('cantidad_min'),
            'Cantidad max'   => request('cantidad_max'),
            'Próx. vencer'   => request('proximos_vencer') ? request('proximos_vencer').' días' : null,
        ]);
    @endphp
    @if(count($filtrosAplicados))
    <div class="filtros-section">
        <div class="filtros-title">Filtros aplicados:</div>
        @foreach($filtrosAplicados as $label => $val)
            <span class="filtro-tag">{{ $label }}: {{ $val }}</span>
        @endforeach
    </div>
    @endif

    {{-- TABLA --}}
    @if($cargas->isEmpty())
        <div class="empty">No se encontraron cargas con los filtros seleccionados.</div>
    @else
    <table>
        <thead>
            <tr>
                <th style="width:4%">#</th>
                <th style="width:18%">Vacuna</th>
                <th style="width:13%">Lote</th>
                <th style="width:12%" class="center">F. Llegada</th>
                <th style="width:12%" class="center">F. Vencimiento</th>
                <th style="width:9%" class="center">Cantidad</th>
                <th style="width:11%" class="center">Estado</th>
                <th style="width:21%">Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cargas as $i => $carga)
            @php
                $vence    = \Carbon\Carbon::parse($carga->fecha_vencimiento);
                $dias     = now()->diffInDays($vence, false);
                if ($dias < 0)       { $badgeClass = 'badge-red';    $badgeLabel = 'Vencida'; }
                elseif ($dias <= 30) { $badgeClass = 'badge-orange'; $badgeLabel = 'Próx. vencer'; }
                elseif ($dias <= 90) { $badgeClass = 'badge-yellow'; $badgeLabel = 'Por vencer'; }
                else                 { $badgeClass = 'badge-green';  $badgeLabel = 'Vigente'; }
            @endphp
            <tr>
                <td class="center" style="color:#9ca3af;">{{ $i + 1 }}</td>
                <td style="font-weight:600; color:#1f2937;">{{ $carga->vacuna?->nombre ?? '—' }}</td>
                <td class="mono">{{ $carga->lote }}</td>
                <td class="center">{{ \Carbon\Carbon::parse($carga->fecha_llegada)->format('d/m/Y') }}</td>
                <td class="center {{ $dias < 0 ? 'color:#b91c1c;' : '' }}">
                    {{ \Carbon\Carbon::parse($carga->fecha_vencimiento)->format('d/m/Y') }}
                </td>
                <td class="center" style="font-weight:700;">{{ number_format($carga->cantidad) }}</td>
                <td class="center"><span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span></td>
                <td style="color:#6b7280; font-size:8px;">{{ Str::limit($carga->observaciones, 50) ?? '—' }}</td>
            </tr>
            @endforeach

            {{-- Fila de totales --}}
            <tr class="totals-row">
                <td colspan="5" style="text-align:right;">TOTAL DOSIS:</td>
                <td class="center">{{ number_format($totalDosis) }}</td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>
    @endif

    {{-- FOOTER --}}
    <div class="footer">
        <div class="footer-left">
            Sistema de Gestión de Vacunas &bull; {{ $asic->nombre }}<br>
            Documento generado el {{ $generadoEn }}
        </div>
        <div class="footer-right">
            <span class="page-num">Documento oficial &bull; Solo para uso interno</span>
        </div>
    </div>

</body>
</html>