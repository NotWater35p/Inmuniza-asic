<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Ficha de Carga – {{ $carga->lote }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1f2937; }

        .header { padding: 20px 24px 16px; border-bottom: 3px solid #2563eb; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: flex-start; }
        .org-name { font-size: 15px; font-weight: 700; color: #1e40af; }
        .org-sub { font-size: 8.5px; color: #6b7280; margin-top: 2px; }
        .doc-info { text-align: right; }
        .doc-info h1 { font-size: 13px; font-weight: 700; }
        .doc-info p { font-size: 8px; color: #9ca3af; margin-top: 2px; }
        .lote-badge { display: inline-block; background: #dbeafe; color: #1e40af; border-radius: 4px;
                      padding: 3px 10px; font-family: DejaVu Sans Mono, monospace; font-size: 10px; font-weight: 700; margin-top: 4px; }

        /* STATUS BANNER */
        .status-banner { border-radius: 8px; padding: 12px 16px; margin-bottom: 18px; display: flex; justify-content: space-between; align-items: center; }
        .status-banner.vigente  { background: #f0fdf4; border: 1px solid #bbf7d0; }
        .status-banner.vencer   { background: #fff7ed; border: 1px solid #fed7aa; }
        .status-banner.vencida  { background: #fef2f2; border: 1px solid #fecaca; }
        .status-banner.porvencer{ background: #fefce8; border: 1px solid #fef08a; }

        .banner-label { font-size: 9px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .banner-value { font-size: 22px; font-weight: 700; margin-top: 2px; }
        .banner-sub   { font-size: 8px; margin-top: 1px; }

        .banner-estado { text-align: right; }
        .badge-estado { display: inline-block; border-radius: 20px; padding: 4px 14px; font-size: 10px; font-weight: 700; }
        .badge-green  { background: #dcfce7; color: #15803d; }
        .badge-orange { background: #ffedd5; color: #c2410c; }
        .badge-red    { background: #fee2e2; color: #b91c1c; }
        .badge-yellow { background: #fef9c3; color: #a16207; }

        /* SECTION */
        .section { margin-bottom: 16px; }
        .section-title { font-size: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px;
                         color: #64748b; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px; margin-bottom: 10px; }

        /* GRID INFO */
        .info-grid { display: flex; flex-wrap: wrap; }
        .info-item { width: 50%; padding: 8px 10px 8px 0; }
        .info-item.full { width: 100%; }
        .info-label { font-size: 8px; color: #9ca3af; font-weight: 600; text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 3px; }
        .info-value { font-size: 11px; font-weight: 600; color: #111827; }
        .info-value.mono { font-family: DejaVu Sans Mono, monospace; }
        .info-value.big  { font-size: 20px; font-weight: 700; color: #1e40af; }

        /* VACUNA CARD */
        .vacuna-card { background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px; margin-bottom: 16px; }
        .vacuna-nombre { font-size: 15px; font-weight: 700; color: #1e3a5f; }
        .vacuna-marca  { font-size: 9px; color: #64748b; margin-top: 2px; }
        .vacuna-details { display: flex; gap: 0; margin-top: 10px; }
        .vd-item { flex: 1; border-right: 1px solid #e2e8f0; padding: 0 12px 0 0; margin-right: 12px; }
        .vd-item:last-child { border-right: none; margin-right: 0; padding-right: 0; }
        .vd-label { font-size: 7.5px; color: #9ca3af; text-transform: uppercase; font-weight: 600; }
        .vd-value { font-size: 9.5px; font-weight: 600; color: #374151; margin-top: 2px; }

        /* OBSERVACIONES */
        .obs-box { background: #f9fafb; border: 1px solid #e5e7eb; border-left: 3px solid #2563eb;
                   border-radius: 4px; padding: 10px 12px; font-size: 9px; color: #374151; line-height: 1.5; }

        /* FIRMA */
        .firma-section { margin-top: 30px; display: flex; justify-content: space-between; }
        .firma-box { text-align: center; width: 45%; }
        .firma-line { border-top: 1px solid #374151; padding-top: 6px; margin-top: 40px; }
        .firma-label { font-size: 8.5px; color: #374151; font-weight: 600; }
        .firma-sub { font-size: 7.5px; color: #9ca3af; margin-top: 1px; }

        /* FOOTER */
        .footer { border-top: 1px solid #e2e8f0; margin-top: 20px; padding-top: 8px; display: flex; justify-content: space-between; }
        .footer-text { font-size: 7.5px; color: #9ca3af; }
    </style>
</head>
<body>

    {{-- ENCABEZADO --}}
    <div class="header">
        <div>
            <div class="org-name">{{ $asic->nombre }}</div>
            <div class="org-sub">{{ $asic->direccion }}</div>
            <div class="org-sub">RIF: {{ $asic->rif }} &bull; Tel: {{ $asic->telefono }}</div>
        </div>
        <div class="doc-info">
            <h1>Ficha de Carga de Vacunas</h1>
            <p>Generado: {{ $generadoEn }}</p>
            <div class="lote-badge">{{ $carga->lote }}</div>
        </div>
    </div>

    @php
        $vence    = \Carbon\Carbon::parse($carga->fecha_vencimiento);
        $dias     = now()->diffInDays($vence, false);
        if ($dias < 0) {
            $bannerClass = 'vencida';  $badgeClass = 'badge-red';    $label = 'Vencida';
            $bannerColor = '#b91c1c';  $bannerSub  = 'Venció hace '.abs((int)$dias).' días';
        } elseif ($dias <= 30) {
            $bannerClass = 'vencer';   $badgeClass = 'badge-orange'; $label = 'Próx. a vencer';
            $bannerColor = '#c2410c';  $bannerSub  = (int)$dias.' días restantes';
        } elseif ($dias <= 90) {
            $bannerClass = 'porvencer';$badgeClass = 'badge-yellow'; $label = 'Por vencer';
            $bannerColor = '#a16207';  $bannerSub  = (int)$dias.' días restantes';
        } else {
            $bannerClass = 'vigente';  $badgeClass = 'badge-green';  $label = 'Vigente';
            $bannerColor = '#15803d';  $bannerSub  = (int)$dias.' días restantes';
        }
    @endphp

    {{-- BANNER DE ESTADO --}}
    <div class="status-banner {{ $bannerClass }}">
        <div>
            <div class="banner-label" style="color:{{ $bannerColor }};">Cantidad registrada</div>
            <div class="banner-value" style="color:{{ $bannerColor }};">{{ number_format($carga->cantidad) }}</div>
            <div class="banner-sub" style="color:{{ $bannerColor }};">dosis</div>
        </div>
        <div class="banner-estado">
            <div class="banner-label" style="color:#64748b;">Estado de vencimiento</div>
            <div style="margin-top:4px;"><span class="badge-estado {{ $badgeClass }}">{{ $label }}</span></div>
            <div class="banner-sub" style="color:#64748b; margin-top:4px;">{{ $bannerSub }}</div>
        </div>
    </div>

    {{-- DATOS DE VACUNA --}}
    <div class="section">
        <div class="section-title">Información de la Vacuna</div>
        <div class="vacuna-card">
            <div class="vacuna-nombre">{{ $carga->vacuna?->nombre ?? 'Sin nombre' }}</div>
            @if($carga->vacuna?->marca)
            <div class="vacuna-marca">Marca: {{ $carga->vacuna->marca->nombre }}</div>
            @endif

            @if($carga->vacuna)
            <div class="vacuna-details">
                @if($carga->vacuna->enfermedad)
                <div class="vd-item">
                    <div class="vd-label">Enfermedad</div>
                    <div class="vd-value">{{ $carga->vacuna->enfermedad }}</div>
                </div>
                @endif
                @if($carga->vacuna->via_administracion)
                <div class="vd-item">
                    <div class="vd-label">Vía de Administración</div>
                    <div class="vd-value">{{ $carga->vacuna->via_administracion }}</div>
                </div>
                @endif
                @if($carga->vacuna->numero_dosis)
                <div class="vd-item">
                    <div class="vd-label">Nº de Dosis</div>
                    <div class="vd-value">{{ $carga->vacuna->numero_dosis }}</div>
                </div>
                @endif
                @if($carga->vacuna->presentacion)
                <div class="vd-item">
                    <div class="vd-label">Presentación</div>
                    <div class="vd-value">{{ $carga->vacuna->presentacion }}</div>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    {{-- DATOS DE LA CARGA --}}
    <div class="section">
        <div class="section-title">Datos del Registro</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">ASIC Receptor</div>
                <div class="info-value">{{ $carga->asic?->nombre ?? '—' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Número de Lote</div>
                <div class="info-value mono">{{ $carga->lote }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Fecha de Llegada</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($carga->fecha_llegada)->format('d \d\e F \d\e Y') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Fecha de Vencimiento</div>
                <div class="info-value" style="color:{{ $dias < 0 ? '#b91c1c' : ($dias <= 30 ? '#c2410c' : '#111827') }}">
                    {{ \Carbon\Carbon::parse($carga->fecha_vencimiento)->format('d \d\e F \d\e Y') }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">ID de Registro</div>
                <div class="info-value mono" style="color:#9ca3af;">#{{ str_pad($carga->id, 6, '0', STR_PAD_LEFT) }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Fecha de Registro en Sistema</div>
                <div class="info-value" style="font-size:9.5px;">{{ \Carbon\Carbon::parse($carga->created_at)->format('d/m/Y H:i') }}</div>
            </div>
        </div>
    </div>

    {{-- OBSERVACIONES --}}
    <div class="section">
        <div class="section-title">Observaciones</div>
        <div class="obs-box">
            {{ $carga->observaciones ?: 'Sin observaciones registradas para esta carga.' }}
        </div>
    </div>

    {{-- FIRMAS --}}
    <div class="firma-section">
        <div class="firma-box">
            <div class="firma-line">
                <div class="firma-label">Responsable de Recepción</div>
                <div class="firma-sub">Nombre y sello</div>
            </div>
        </div>
        <div class="firma-box">
            <div class="firma-line">
                <div class="firma-label">Coordinador de Vacunación</div>
                <div class="firma-sub">Nombre y sello</div>
            </div>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        <div class="footer-text">Sistema de Gestión de Vacunas &bull; {{ $asic->nombre }}</div>
        <div class="footer-text">Documento generado: {{ $generadoEn }} &bull; Uso interno</div>
    </div>

</body>
</html>