<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Ficha de Personal - {{ $personal->nombre }} {{ $personal->apellido }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; color: #1f2937; margin: 40px; }
        .header { border-bottom: 2px solid #2563eb; padding-bottom: 15px; margin-bottom: 25px; }
        .header h1 { color: #1e3a8a; margin: 0 0 5px 0; font-size: 28px; }
        .header .subtitle { color: #4b5563; font-size: 14px; }
        .info-grid { display: table; width: 100%; border-collapse: collapse; }
        .info-row { display: table-row; }
        .info-label { display: table-cell; font-weight: bold; padding: 10px 15px 10px 0; width: 35%; border-bottom: 1px solid #e5e7eb; color: #374151; }
        .info-value { display: table-cell; padding: 10px 0; border-bottom: 1px solid #e5e7eb; color: #111827; }
        .footer { margin-top: 40px; padding-top: 15px; border-top: 1px solid #d1d5db; text-align: center; font-size: 12px; color: #6b7280; }
        .badge { display: inline-block; background-color: #dbeafe; color: #1e40af; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 500; }
    </style>
</head>
<body>
    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td style="width: 70%;">
                    <h1>Ficha de Personal</h1>
                    <div class="subtitle">Sistema InmuNiza - ASIC Ilapeca</div>
                </td>
                <td style="text-align: right;">
                    <span class="badge">Cédula: {{ $personal->cedula }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Nombre completo</div>
            <div class="info-value"><strong>{{ $personal->nombre }} {{ $personal->apellido }}</strong></div>
        </div>
        <div class="info-row">
            <div class="info-label">ASIC</div>
            <div class="info-value">{{ $personal->asic->nombre ?? 'No especificado' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Cargo</div>
            <div class="info-value">{{ $personal->cargo->nombre ?? 'No especificado' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Teléfono</div>
            <div class="info-value">{{ $personal->telefono ?: '—' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Correo electrónico</div>
            <div class="info-value">{{ $personal->correo ?: '—' }}</div>
        </div>
    </div>

    <div class="footer">
        <p>Reporte generado el {{ now()->format('d/m/Y H:i') }}</p>
        <p>InmuNiza - Sistema de Gestión de Vacunación</p>
    </div>
</body>
</html>