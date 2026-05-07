<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Marca {{ $marca->nombre }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin: 30px; }
        h1 { color: #1e3a8a; border-bottom: 2px solid #2563eb; padding-bottom: 10px; }
        .desc { margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f3f4f6; }
        .footer { margin-top: 30px; font-size: 12px; color: #6b7280; text-align: center; }
    </style>
</head>
<body>
    <h1>Marca: {{ $marca->nombre }}</h1>
    <div class="desc"><strong>Descripción:</strong> {{ $marca->descripcion ?: '—' }}</div>

    <h3>Vacunas asociadas ({{ $marca->vacunas->count() }})</h3>
    @if($marca->vacunas->count())
    <table>
        <thead>
            <tr><th>Nombre</th><th>Enfermedad</th><th>Presentación</th><th>Dosis</th></tr>
        </thead>
        <tbody>
            @foreach($marca->vacunas as $vacuna)
            <tr>
                <td>{{ $vacuna->nombre }}</td>
                <td>{{ $vacuna->enfermedad ?? '—' }}</td>
                <td>{{ $vacuna->presentacion ?? '—' }}</td>
                <td>{{ $vacuna->numero_dosis ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p>No hay vacunas registradas.</p>
    @endif

    <div class="footer">
        Reporte generado el {{ now()->format('d/m/Y H:i') }} - InmuNiza
    </div>
</body>
</html>