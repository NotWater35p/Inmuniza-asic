<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Listado de Módulos Afiliados</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; margin: 30px; }
        h1 { color: #4338ca; border-bottom: 2px solid #4f46e5; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #e0e7ff; color: #1e1b4b; padding: 10px; text-align: left; }
        td { padding: 8px 10px; border-bottom: 1px solid #cbd5e1; }
        .footer { margin-top: 30px; font-size: 12px; text-align: center; color: #64748b; }
    </style>
</head>
<body>
    <h1>Listado General de Módulos Afiliados</h1>
    <table>
        <thead>
            <tr>
                <th>RIF</th>
                <th>Nombre</th>
                <th>ASIC</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Responsable</th>
            </tr>
        </thead>
        <tbody>
            @foreach($modulos as $modulo)
            <tr>
                <td>{{ $modulo->rif }}</td>
                <td>{{ $modulo->nombre }}</td>
                <td>{{ $modulo->asic->nombre ?? '' }}</td>
                <td>{{ $modulo->direccion ?? '' }}</td>
                <td>{{ $modulo->telefono ?? '' }}</td>
                <td>{{ $modulo->responsable ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i') }} - Total: {{ $modulos->count() }} módulos
    </div>
</body>
</html>