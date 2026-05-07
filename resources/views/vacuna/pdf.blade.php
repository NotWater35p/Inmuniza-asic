<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Ficha de Vacuna - {{ $vacuna->nombre }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #1f2937;
            margin: 40px;
        }
        .header {
            border-bottom: 2px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .header h1 {
            color: #1e3a8a;
            margin: 0 0 5px 0;
            font-size: 28px;
        }
        .header .subtitle {
            color: #4b5563;
            font-size: 14px;
        }
        .info-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            font-weight: bold;
            padding: 10px 15px 10px 0;
            width: 35%;
            border-bottom: 1px solid #e5e7eb;
            color: #374151;
        }
        .info-value {
            display: table-cell;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
            color: #111827;
        }
        .section-title {
            font-size: 18px;
            color: #1e3a8a;
            margin: 30px 0 15px 0;
            border-left: 4px solid #2563eb;
            padding-left: 12px;
        }
        .badge {
            display: inline-block;
            background-color: #dbeafe;
            color: #1e40af;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
        }
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #d1d5db;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
        .logo {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td style="width: 70%;">
                    <h1>Ficha de Vacuna</h1>
                    <div class="subtitle">Sistema InmuNiza - ASIC Ilapeca</div>
                </td>
                <td class="logo">
                    <span class="badge">ID: {{ $vacuna->id }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Nombre de la Vacuna</div>
            <div class="info-value"><strong>{{ $vacuna->nombre }}</strong></div>
        </div>
        <div class="info-row">
            <div class="info-label">Marca / Fabricante</div>
            <div class="info-value">{{ $vacuna->marca->nombre ?? 'No especificada' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Presentación</div>
            <div class="info-value">{{ $vacuna->presentacion ?: '—' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Enfermedad que previene</div>
            <div class="info-value">{{ $vacuna->enfermedad ?: '—' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Dosificación</div>
            <div class="info-value">{{ $vacuna->dosificacion ?: '—' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Vía de administración</div>
            <div class="info-value">{{ $vacuna->via_administracion ?: '—' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Intervalo entre dosis</div>
            <div class="info-value">{{ $vacuna->intervalo ?: '—' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Refuerzo</div>
            <div class="info-value">{{ $vacuna->refuerzo ?: '—' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Número de dosis</div>
            <div class="info-value">{{ $vacuna->numero_dosis ?: '—' }}</div>
        </div>
    </div>

    @if($vacuna->descripcion)
    <div class="section-title">Descripción / Notas adicionales</div>
    <p style="line-height: 1.6;">{{ $vacuna->descripcion }}</p>
    @endif

    <div class="footer">
        <p>Reporte generado el {{ now()->format('d/m/Y H:i') }}</p>
        <p>InmuNiza - Sistema de Gestión de Vacunación</p>
    </div>
</body>
</html>