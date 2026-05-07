<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1f2937; }
        .header { background: #1e40af; color: white; padding: 16px 20px; margin-bottom: 16px; }
        .header h1 { font-size: 16px; font-weight: bold; }
        .header p { font-size: 9px; opacity: 0.85; margin-top: 2px; }
        .meta { display: flex; gap: 20px; margin-bottom: 14px; padding: 0 4px; }
        .meta-item { flex: 1; }
        .meta-item .label { font-size: 8px; color: #6b7280; text-transform: uppercase; }
        .meta-item .val { font-size: 11px; font-weight: bold; color: #111827; }
        .section-title { font-size: 9px; font-weight: bold; color: #374151;
            text-transform: uppercase; letter-spacing: 0.05em;
            border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; margin: 12px 0 8px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #1e40af; color: white; padding: 5px 8px; text-align: left; font-size: 8px; }
        td { padding: 4px 8px; border-bottom: 1px solid #f3f4f6; font-size: 9px; }
        tr:nth-child(even) td { background: #f9fafb; }
        .total-row td { font-weight: bold; background: #eff6ff; color: #1e40af; }
        .jornada-header { background: #f3f4f6; padding: 5px 8px; font-weight: bold;
            font-size: 9px; border-top: 2px solid #e5e7eb; margin-top: 8px; }
        .footer { margin-top: 20px; text-align: center; font-size: 8px; color: #9ca3af;
            border-top: 1px solid #e5e7eb; padding-top: 8px; }
    </style>
</head>
<body>

<div class="header">
    <h1>Reporte Mensual de Vacunación</h1>
    <p>{{ $modulo->nombre }} · {{ $modulo->asic->nombre ?? 'ASIC ILAPECA' }} · {{ ucfirst($nombreMes) }} {{ $anio }}</p>
</div>

<div class="meta">
    <div class="meta-item">
        <div class="label">Módulo</div>
        <div class="val">{{ $modulo->nombre }}</div>
    </div>
    <div class="meta-item">
        <div class="label">RIF</div>
        <div class="val">{{ $modulo->rif }}</div>
    </div>
    <div class="meta-item">
        <div class="label">Período</div>
        <div class="val">{{ ucfirst($nombreMes) }} {{ $anio }}</div>
    </div>
    <div class="meta-item">
        <div class="label">Total Pacientes</div>
        <div class="val">{{ $totalPacientes }}</div>
    </div>
    <div class="meta-item">
        <div class="label">Total Dosis</div>
        <div class="val">{{ $totalDosis }}</div>
    </div>
</div>

<div class="section-title">Resumen de dosis aplicadas</div>
<table>
    <thead>
        <tr>
            <th>Vacuna</th>
            <th style="text-align:center; width:120px">Dosis Aplicadas</th>
        </tr>
    </thead>
    <tbody>
        @foreach($resumenVacunas as $v)
        <tr>
            <td>{{ $v->nombre }}</td>
            <td style="text-align:center">{{ $v->dosis_aplicadas }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td>TOTAL</td>
            <td style="text-align:center">{{ $totalDosis }}</td>
        </tr>
    </tbody>
</table>

<div class="section-title">Detalle por jornada</div>
@foreach($jornadas as $jornada)
<div class="jornada-header">
    {{ $jornada->fecha_jornada->format('d/m/Y') }}
    @if($jornada->responsable)
     · Resp: {{ $jornada->responsable->nombre }} {{ $jornada->responsable->apellido }}
    @endif
     · {{ $jornada->tratamientos->count() }} tratamientos
</div>
<table>
    <thead>
        <tr>
            <th>CI Paciente</th>
            <th>Vacuna</th>
            <th style="text-align:center">Dosis N°</th>
            <th>Observaciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($jornada->tratamientos as $t)
        <tr>
            <td style="font-family: monospace">{{ $t->paciente?->cedula ?? 'S/C' }}</td>
            <td>{{ optional($t->vacuna)->nombre ?? '—' }}</td>
            <td style="text-align:center">{{ $t->dosis_aplicada }}</td>
            <td>{{ $t->observaciones ?? '—' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endforeach

<div class="footer">
    Generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }} · Sistema INMUNIZA · ASIC ILAPECA
</div>

</body>
</html>