<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Ficha de Módulo - {{ $modulo->nombre }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; color: #1f2937; margin: 40px; }
        .header { border-bottom: 2px solid #4f46e5; padding-bottom: 15px; margin-bottom: 25px; }
        .header h1 { color: #4338ca; margin: 0 0 5px 0; font-size: 28px; }
        .info-grid { display: table; width: 100%; border-collapse: collapse; }
        .info-row { display: table-row; }
        .info-label { display: table-cell; font-weight: bold; padding: 10px 15px 10px 0; width: 35%; border-bottom: 1px solid #e5e7eb; }
        .info-value { display: table-cell; padding: 10px 0; border-bottom: 1px solid #e5e7eb; }
        .footer { margin-top: 40px; padding-top: 15px; border-top: 1px solid #d1d5db; text-align: center; font-size: 12px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Ficha de Módulo Afiliado</h1>
        <div>Sistema InmuNiza - ASIC Ilapeca</div>
    </div>
    <div class="info-grid">
        <div class="info-row"><div class="info-label">Nombre</div><div class="info-value">{{ $modulo->nombre }}</div></div>
        <div class="info-row"><div class="info-label">RIF</div><div class="info-value">{{ $modulo->rif }}</div></div>
        <div class="info-row"><div class="info-label">ASIC</div><div class="info-value">{{ $modulo->asic->nombre ?? '—' }}</div></div>
        <div class="info-row"><div class="info-label">Dirección</div><div class="info-value">{{ $modulo->direccion ?? '—' }}</div></div>
        <div class="info-row"><div class="info-label">Teléfono</div><div class="info-value">{{ $modulo->telefono ?? '—' }}</div></div>
        <div class="info-row"><div class="info-label">Responsable</div><div class="info-value">{{ $modulo->responsable ?? '—' }}</div></div>
    </div>
    <div class="footer">
        <p>Reporte generado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>