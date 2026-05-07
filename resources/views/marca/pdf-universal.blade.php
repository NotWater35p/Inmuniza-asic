<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Universal de Marcas</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin: 30px; }
        h1 { color: #1e3a8a; }
        .marca { margin-bottom: 40px; page-break-inside: avoid; }
        .marca h2 { color: #2563eb; border-bottom: 1px solid #ccc; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f3f4f6; }
        .footer { margin-top: 30px; font-size: 12px; color: #6b7280; text-align: center; }
    </style>
</head>
<body>
    <h1>Reporte Universal de Marcas</h1>
    @foreach($marcas as $marca)
    <div class="marca">
        <h2>{{ $marca->nombre }}</h2>
        <p><strong>Descripción:</strong> {{ $marca->descripcion ?: '—' }}</p>
        @if($marca->vacunas->count())
        <table>
            <thead><tr><th>Vacuna</th><th>Enfermedad</th><th>Presentación</th></tr></thead>
            <tbody>
                @foreach($marca->vacunas as $vacuna)
                <tr>
                    <td>{{ $vacuna->nombre }}</td>
                    <td>{{ $vacuna->enfermedad ?? '—' }}</td>
                    <td>{{ $vacuna->presentacion ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>Sin vacunas asociadas.</p>
        @endif
    </div>
    @endforeach
    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i') }} - InmuNiza
    </div>
</body>
</html>