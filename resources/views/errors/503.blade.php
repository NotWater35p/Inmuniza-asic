<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema en Mantenimiento · INMUNIZA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --blue-deep:   #1a237e;
            --blue-mid:    #3e3af2;
            --blue-bright: #7788ff;
            --blue-light:  #e8eaff;
            --white:       #ffffff;
            --gray-soft:   #f0f2ff;
            --gray-text:   #6b7280;
            --dark:        #111827;
        }

        html, body {
            height: 100%;
            overflow: hidden;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--gray-soft);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            position: relative;
        }

        /* Fondo animado con burbujas */
        .bg-blobs {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.18;
            animation: floatBlob 12s ease-in-out infinite alternate;
        }

        .blob-1 {
            width: 500px; height: 500px;
            background: var(--blue-mid);
            top: -150px; left: -100px;
            animation-delay: 0s;
        }
        .blob-2 {
            width: 350px; height: 350px;
            background: var(--blue-bright);
            bottom: -100px; right: -80px;
            animation-delay: 3s;
        }
        .blob-3 {
            width: 250px; height: 250px;
            background: var(--blue-deep);
            top: 40%; left: 60%;
            animation-delay: 6s;
        }

        @keyframes floatBlob {
            0%   { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, -40px) scale(1.08); }
        }

        /* Grid decorativo de fondo */
        .bg-grid {
            position: fixed;
            inset: 0;
            z-index: 0;
            background-image:
                linear-gradient(rgba(62,58,242,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(62,58,242,0.04) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        /* Card principal */
        .card {
            position: relative;
            z-index: 10;
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(119,136,255,0.2);
            border-radius: 28px;
            padding: 52px 56px;
            max-width: 560px;
            width: 90%;
            text-align: center;
            box-shadow:
                0 4px 6px rgba(62,58,242,0.04),
                0 24px 60px rgba(26,35,126,0.1),
                0 0 0 1px rgba(255,255,255,0.6) inset;
            animation: slideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(32px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Logo */
        .logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 32px;
            animation: slideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both;
        }

        .logo-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--blue-mid), var(--blue-deep));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-text {
            font-family: 'Syne', sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: var(--blue-bright);
            letter-spacing: -0.5px;
        }

        .logo-text b {
            color: var(--blue-mid);
        }

        /* Imagen personaje */
        .mascot-wrapper {
            position: relative;
            display: inline-block;
            margin-bottom: 28px;
            animation: slideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.15s both;
        }

        .mascot-wrapper img {
            width: 170px;
            height: 170px;
            object-fit: contain;
            filter: drop-shadow(0 8px 24px rgba(62,58,242,0.15));
            animation: bobble 3.5s ease-in-out infinite;
        }

        @keyframes bobble {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-8px); }
        }

        /* Badge de estado */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: var(--blue-light);
            color: var(--blue-mid);
            font-family: 'Syne', sans-serif;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 6px 14px;
            border-radius: 100px;
            margin-bottom: 20px;
            border: 1px solid rgba(62,58,242,0.15);
            animation: slideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            background: var(--blue-mid);
            border-radius: 50%;
            animation: pulse-dot 1.5s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.5; transform: scale(0.75); }
        }

        /* Título */
        h1 {
            font-family: 'Syne', sans-serif;
            font-size: 32px;
            font-weight: 800;
            color: var(--dark);
            line-height: 1.15;
            letter-spacing: -0.5px;
            margin-bottom: 14px;
            animation: slideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.25s both;
        }

        h1 span {
            color: var(--blue-mid);
        }

        /* Descripción */
        .desc {
            font-size: 15px;
            color: var(--gray-text);
            line-height: 1.65;
            margin-bottom: 32px;
            font-weight: 400;
            animation: slideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.3s both;
        }

        /* Divider con icono */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
            animation: slideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.35s both;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(62,58,242,0.15), transparent);
        }

        .divider-icon {
            color: var(--blue-bright);
            font-size: 18px;
        }

        /* Info boxes */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 32px;
            animation: slideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.4s both;
        }

        .info-box {
            background: var(--gray-soft);
            border: 1px solid rgba(62,58,242,0.08);
            border-radius: 14px;
            padding: 16px;
            text-align: left;
        }

        .info-box-label {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--blue-bright);
            margin-bottom: 4px;
            font-family: 'Syne', sans-serif;
        }

        .info-box-value {
            font-size: 13px;
            font-weight: 500;
            color: var(--dark);
        }

        /* Barra de progreso animada */
        .progress-wrapper {
            margin-bottom: 32px;
            animation: slideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.45s both;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: var(--gray-text);
            margin-bottom: 8px;
            font-weight: 500;
        }

        .progress-track {
            height: 6px;
            background: var(--blue-light);
            border-radius: 100px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--blue-bright), var(--blue-mid));
            border-radius: 100px;
            animation: fillBar 2.5s cubic-bezier(0.4, 0, 0.2, 1) 0.8s both,
                       shimmer 2s linear 3.3s infinite;
            width: 72%;
        }

        @keyframes fillBar {
            from { width: 0%; }
            to   { width: 72%; }
        }

        @keyframes shimmer {
            0%   { filter: brightness(1); }
            50%  { filter: brightness(1.2); }
            100% { filter: brightness(1); }
        }

        /* Footer note */
        .footer-note {
            font-size: 12px;
            color: var(--gray-text);
            animation: slideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.5s both;
        }

        .footer-note a {
            color: var(--blue-mid);
            text-decoration: none;
            font-weight: 500;
        }

        .footer-note a:hover {
            text-decoration: underline;
        }

        /* Partículas decorativas */
        .particle {
            position: fixed;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--blue-bright);
            opacity: 0.25;
            z-index: 1;
            animation: floatParticle linear infinite;
        }

        @keyframes floatParticle {
            0%   { transform: translateY(100vh) scale(0); opacity: 0; }
            10%  { opacity: 0.25; }
            90%  { opacity: 0.25; }
            100% { transform: translateY(-10vh) scale(1); opacity: 0; }
        }
    </style>
</head>
<body>

    {{-- Fondo --}}
    <div class="bg-grid"></div>
    <div class="bg-blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>

    {{-- Partículas --}}
    <div class="particle" style="left:8%; animation-duration:8s; animation-delay:0s;"></div>
    <div class="particle" style="left:22%; animation-duration:11s; animation-delay:2s; width:4px; height:4px;"></div>
    <div class="particle" style="left:55%; animation-duration:9s; animation-delay:4s; width:8px; height:8px;"></div>
    <div class="particle" style="left:75%; animation-duration:13s; animation-delay:1s; width:5px; height:5px;"></div>
    <div class="particle" style="left:90%; animation-duration:7s; animation-delay:3s;"></div>

    {{-- Card principal --}}
    <div class="card">

        {{-- Logo --}}
        <div class="logo">
            <div class="logo-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m18 2 4 4"/><path d="m17 7 3-3"/>
                    <path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5"/>
                    <path d="m9 11 4 4"/><path d="m5 19-3 3"/><path d="m14 4 6 6"/>
                </svg>
            </div>
            <span class="logo-text">INMU<b>NIZA</b></span>
        </div>

        {{-- Personaje --}}
        <div class="mascot-wrapper">
            <img
                src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhg_XQk7L4Gs8BoY7tndswvKRrzE0j-1BP7zZPNSCD382xgW9oETQyxs_DkI6Ln9qATxautaeekQ5feQUTJx9-Hc9PeeroH8L7NngdJaq1GoiseflGFhiAFQeUwXJxHeDVw3MToHnWXZrs/s800/kouji_maintenance.png"
                alt="Sistema en mantenimiento"
                onerror="this.style.display='none'"
            >
        </div>

        {{-- Badge --}}
        <div class="status-badge">
            <div class="status-dot"></div>
            En Mantenimiento
        </div>

        {{-- Título --}}
        <h1>Estamos mejorando<br><span>el sistema</span> para ti</h1>

        {{-- Descripción --}}
        <p class="desc">
            El sistema INMUNIZA se encuentra temporalmente fuera de servicio
            mientras realizamos actualizaciones. Estaremos de vuelta muy pronto.
        </p>

        {{-- Info --}}
        <div class="info-grid">
            <div class="info-box">
                <div class="info-box-label">Sistema</div>
                <div class="info-box-value">ASIC ILAPECA</div>
            </div>
            <div class="info-box">
                <div class="info-box-label">Estado</div>
                <div class="info-box-value">Mantenimiento programado</div>
            </div>
        </div>

        {{-- Progreso --}}
        <div class="progress-wrapper">
            <div class="progress-label">
                <span>Progreso de actualización</span>
                <span>72%</span>
            </div>
            <div class="progress-track">
                <div class="progress-fill"></div>
            </div>
        </div>

        {{-- Divider --}}
        <div class="divider">
            <div class="divider-line"></div>
            <span class="divider-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
            </span>
            <div class="divider-line"></div>
        </div>

        {{-- Footer --}}
        <p class="footer-note">
            ¿Eres administrador?
            <a href="{{ route('login') }}">Acceder al sistema</a>
            &nbsp;·&nbsp; INMUNIZA &copy; {{ date('Y') }}
        </p>

    </div>

</body>
</html>