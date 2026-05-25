<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte SISPAI — {{ $modulo->nombre }} — {{ $nombreMes }} {{ $anio }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 10px; color: #1a1a1a; background: #fff; }

        /* ── Encabezado ── */
        .header { background: #1d4ed8; color: #fff; padding: 14px 18px; margin-bottom: 14px; }
        .header-top { display: flex; justify-content: space-between; align-items: flex-start; }
        .header h1 { font-size: 15px; font-weight: 700; margin-bottom: 2px; }
        .header-sub { font-size: 9px; color: #bfdbfe; }
        .header-meta { text-align: right; font-size: 9px; color: #bfdbfe; }
        .header-meta strong { display: block; font-size: 11px; color: #fff; }

        /* ── Stats ── */
        .stats-row { display: flex; gap: 10px; margin-bottom: 14px; }
        .stat-box { flex: 1; border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px 12px; }
        .stat-box .num { font-size: 20px; font-weight: 700; color: #1d4ed8; }
        .stat-box .lbl { font-size: 8px; color: #6b7280; margin-top: 2px; text-transform: uppercase; letter-spacing: 0.5px; }

        /* ── Secciones ── */
        .section { margin-bottom: 14px; }
        .section-title { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: #374151; background: #f3f4f6; padding: 5px 10px; border-left: 3px solid #1d4ed8; margin-bottom: 0; }

        /* ── Tablas ── */
        table { width: 100%; border-collapse: collapse; }
        th { background: #f9fafb; font-size: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #6b7280; padding: 5px 8px; border-bottom: 1px solid #e5e7eb; text-align: left; }
        td { padding: 5px 8px; border-bottom: 1px solid #f3f4f6; font-size: 9px; color: #374151; }
        tr:last-child td { border-bottom: none; }
        .td-num { text-align: center; font-weight: 700; }
        .badge { display: inline-block; padding: 1px 6px; border-radius: 999px; font-size: 8px; font-weight: 700; }
        .badge-blue { background: #dbeafe; color: #1d4ed8; }
        .badge-green { background: #d1fae5; color: #065f46; }

        /* ── Barras ── */
        .bar-row { display: flex; align-items: center; gap: 8px; margin-bottom: 5px; }
        .bar-label { width: 160px; font-size: 9px; color: #374151; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .bar-track { flex: 1; background: #e5e7eb; border-radius: 999px; height: 6px; }
        .bar-fill { background: #1d4ed8; height: 6px; border-radius: 999px; }
        .bar-num { width: 30px; text-align: right; font-size: 9px; font-weight: 700; color: #1a1a1a; }

        /* ── Pie ── */
        .footer { margin-top: 20px; padding-top: 8px; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between; }
        .footer-txt { font-size: 8px; color: #9ca3af; }

        /* ── Info strip ── */
        .info-strip { display: flex; gap: 20px; font-size: 9px; color: #6b7280; margin-bottom: 14px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px 12px; }
        .info-item strong { display: block; color: #111827; font-size: 10px; }

        /* ── Nota SISPAI ── */
        .nota-sispai { background: #fffbeb; border: 1px solid #fde68a; border-radius: 6px; padding: 8px 12px; margin-bottom: 14px; font-size: 9px; color: #92400e; }
        .nota-sispai strong { display: block; margin-bottom: 2px; }
    </style>
</head>
<body>

    {{-- ENCABEZADO --}}
    <div class="header">
        <div class="header-top">
            <div>
                <h1>{{ $modulo->nombre }}</h1>
                <p class="header-sub">ASIC Ilapeca &nbsp;·&nbsp; Municipio Rosario de Perijá &nbsp;·&nbsp; Zulia</p>
                <p class="header-sub" style="margin-top:2px">Reporte Mensual de Dosis Aplicadas</p>
            </div>
            <div class="header-meta">
                <strong>{{ strtoupper($nombreMes) }} {{ $anio }}</strong>
                Generado: {{ now()->format('d/m/Y H:i') }}
                <br>Responsable: {{ $modulo->jefe?->nombres . ' ' . $modulo->jefe?->apellidos ?? 'No asignado' }}
            </div>
        </div>
    </div>

    {{-- INFO DEL MÓDULO --}}
    <div class="info-strip">
        <div class="info-item">
            <span>Tipo</span>
            <strong>{{ $modulo->tipo_establecimiento }}</strong>
        </div>
        <div class="info-item">
            <span>Municipio</span>
            <strong>{{ $modulo->municipio ?? 'Rosario de Perijá' }}</strong>
        </div>
        <div class="info-item">
            <span>Parroquia</span>
            <strong>{{ $modulo->parroquia ?? '—' }}</strong>
        </div>
        @if($modulo->sispai_fila)
        <div class="info-item">
            <span>Fila SISPAI</span>
            <strong>{{ $modulo->sispai_fila }}</strong>
        </div>
        @endif
        <div class="info-item">
            <span>Dirección</span>
            <strong>{{ $modulo->direccion ?? '—' }}</strong>
        </div>
    </div>

    {{-- ESTADÍSTICAS RÁPIDAS --}}
    <div class="stats-row">
        <div class="stat-box">
            <div class="num">{{ $jornadas->count() }}</div>
            <div class="lbl">Jornadas realizadas</div>
        </div>
        <div class="stat-box">
            <div class="num">{{ number_format($totalDosis) }}</div>
            <div class="lbl">Total dosis aplicadas</div>
        </div>
        <div class="stat-box">
            <div class="num">{{ $totalPacientes }}</div>
            <div class="lbl">Pacientes atendidos</div>
        </div>
        <div class="stat-box">
            <div class="num">{{ count($resumenVacunas) }}</div>
            <div class="lbl">Vacunas / biológicos</div>
        </div>
    </div>

    {{-- DOSIS POR VACUNA --}}
    @if(count($resumenVacunas) > 0)
    <div class="section">
        <div class="section-title">Dosis aplicadas por vacuna / biológico</div>
        <div style="padding: 10px 12px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 6px 6px;">
            @php $maxDosis = max($resumenVacunas); @endphp
            @foreach($resumenVacunas as $nombre => $cantidad)
            <div class="bar-row">
                <div class="bar-label">{{ $nombre }}</div>
                <div class="bar-track">
                    <div class="bar-fill" style="width: {{ $maxDosis > 0 ? round(($cantidad/$maxDosis)*100) : 0 }}%"></div>
                </div>
                <div class="bar-num">{{ $cantidad }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- DETALLE DE JORNADAS --}}
    @if($jornadas->count() > 0)
    <div class="section">
        <div class="section-title">Detalle de jornadas</div>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Responsable</th>
                    <th style="text-align:center">Dosis</th>
                    <th style="text-align:center">Pacientes</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jornadas as $jornada)
                <tr>
                    <td style="white-space:nowrap; font-weight:600">{{ $jornada->fecha_jornada->format('d/m/Y') }}</td>
                    <td>{{ $jornada->responsable ? $jornada->responsable->nombres . ' ' . $jornada->responsable->apellidos : '—' }}</td>
                    <td class="td-num"><span class="badge badge-blue">{{ $jornada->totalDosis() }}</span></td>
                    <td class="td-num"><span class="badge badge-green">{{ $jornada->totalPacientes() }}</span></td>
                    <td style="color:#6b7280">{{ $jornada->descripcion ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="text-align:center; padding: 24px; border: 1px solid #e5e7eb; border-radius:8px; color:#9ca3af; font-size:9px; margin-bottom:14px;">
        No se registraron jornadas en {{ $nombreMes }} {{ $anio }}.
    </div>
    @endif

    {{-- DETALLE COMPLETO DE TRATAMIENTOS --}}
    @php
        $todosLosTratamientos = $jornadas->flatMap(fn($j) => $j->tratamientos)->filter(fn($t) => $t->vacuna && $t->paciente);
    @endphp
    @if($todosLosTratamientos->count() > 0)
    <div class="section">
        <div class="section-title">Registro de dosis — Detalle de pacientes</div>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Paciente</th>
                    <th>Cédula</th>
                    <th style="text-align:center">Edad</th>
                    <th style="text-align:center">Sexo</th>
                    <th>Vacuna / Biológico</th>
                    <th style="text-align:center">Dosis N°</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jornadas as $jornada)
                    @foreach($jornada->tratamientos as $t)
                    @if($t->vacuna && $t->paciente)
                    @php
                        $edadStr = '—';
                        if ($t->paciente->fecha_nacimiento) {
                            $nacimiento = \Carbon\Carbon::parse($t->paciente->fecha_nacimiento);
                            $fechaVac   = \Carbon\Carbon::parse($t->fecha_aplicacion ?? $jornada->fecha_jornada);
                            $anios = $nacimiento->diffInYears($fechaVac);
                            $meses = $nacimiento->diffInMonths($fechaVac);
                            if ($anios >= 1) $edadStr = $anios . 'a';
                            elseif ($meses >= 1) $edadStr = $meses . 'm';
                            else $edadStr = $nacimiento->diffInDays($fechaVac) . 'd';
                        }
                    @endphp
                    <tr>
                        <td style="white-space:nowrap">{{ $jornada->fecha_jornada->format('d/m/Y') }}</td>
                        <td>{{ $t->paciente->nombres }} {{ $t->paciente->apellidos }}</td>
                        <td style="font-family:monospace">{{ $t->paciente->cedula ?? 'S/C' }}</td>
                        <td class="td-num">{{ $edadStr }}</td>
                        <td class="td-num">{{ $t->paciente->sexo === 'M' ? 'M' : 'F' }}</td>
                        <td style="font-weight:600">{{ $t->vacuna->nombre }}</td>
                        <td class="td-num"><span class="badge badge-blue">{{ $t->dosis_aplicada }}ª</span></td>
                        <td style="color:#6b7280; font-size:8px">{{ $t->observaciones ?? '—' }}</td>
                    </tr>
                    @endif
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- NOTA SISPAI --}}
    @if($modulo->sispai_fila)
    <div class="nota-sispai">
        <strong>📊 Datos SISPAI disponibles</strong>
        Este módulo tiene asignada la fila <strong>{{ $modulo->sispai_fila }}</strong> en el sistema nacional SISPAI.
        Descarga el Excel SISPAI desde la plataforma para obtener el formato oficial con todas las columnas del reporte nacional.
    </div>
    @else
    <div class="nota-sispai">
        <strong>⚠️ Fila SISPAI no configurada</strong>
        Para generar el Excel en formato SISPAI oficial, configure el número de fila de este módulo en el catálogo de módulos del sistema.
    </div>
    @endif

    {{-- PIE DE PÁGINA --}}
    <div class="footer">
        <span class="footer-txt">Sistema INMUNIZA · ASIC Ilapeca · UPTMA · {{ now()->year }}</span>
        <span class="footer-txt">Este documento es de uso interno. {{ now()->format('d/m/Y H:i') }}</span>
    </div>

</body>
</html>
