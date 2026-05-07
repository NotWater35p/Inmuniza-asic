<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- ============================ ESTILOS =================================================================== -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <!-- ============================ ICONOS ==================================================================== -->
    <link rel="icon" href="{{ asset('img/svg/logo_alter.svg') }}" type="image/svg+xml">
    <title>LOGIN</title>
</head>

<body>
    <main>
        <!-- ========================== CONTENEDOR ================================================================ -->
        <div class="box">
            <div class="inner-box">
                <!-- ====================== FORMULARIOS =============================================================== -->
                <div class="forms-wrap">
                    <!-- ========================= FORMULARIO DE LOGIN ================================================== -->
                    <form action="{{ route('login.submit') }}" autocomplete="off" class="login" method="post">
                        @csrf
                        <!-- ================== LOGO ====================================================================== -->
                        <div class="logo">
                            <img src="{{ asset('img/svg/logo_alter.svg') }}" alt="">
                            <h4>INMU<b>NIZA</b></h4>
                        </div>

                        <div class="heading">
                            <h1>INICIO SESION</h1>
                            <h6>Bienvenidos sistema de gestion de vacunas del ASIC Ilapeca</h6>
                        </div>

                        @if ($errors->any())
                            <div class="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <!-- =============================== INPUTS LOGIN ================================================= -->
                        <div class="actual-form">
                            <div class="input-wrap">
                                <input type="text" id="cedula" minlength="4" class="input-field"
                                    autocomplete="off" name="cedula" required autofocus value="{{ old('cedula') }}" inputmode="numeric" pattern="\d+">
                                <label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17"
                                        viewBox="0 0 24 24" fill="none" stroke="Currentcolor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="svg">
                                        <path d="M16 10h2"></path>
                                        <path d="M16 14h2"></path>
                                        <path d="M6.17 15a3 3 0 0 1 5.66 0"></path>
                                        <circle cx="9" cy="11" r="2"></circle>
                                        <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                                    </svg>
                                    <span>Cedula</span>
                                </label>
                            </div>

                            <div class="input-wrap">
                                <input type="password" minlength="4" class="input-field" autocomplete="off"
                                    name="password" required id="password">
                                <label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="16" r="1"></circle>
                                        <rect x="3" y="10" width="18" height="12" rx="2"></rect>
                                        <path d="M7 10V7a5 5 0 0 1 10 0v3"></path>
                                    </svg>
                                    <span>Contraseña</span>
                                </label>
                                <img src="{{ asset('img/svg/ojo-gri.svg') }}" class="showPass" id="eyeicon">
                            </div>

                            <!-- ============================= BOTON LOGIN ================================================== -->
                            <button type="submit" value="Ingresar" class="login-btn">
                                <span>INGRESAR</span>
                            </button>
                            <br>
                            <p class="text">
                                Olvidaste tu <a href="#">Contraseña?</a>
                            </p>
                        </div>
                    </form>

                    <!-- ========================= FORMULARIO DE REGISTRO ================================================== -->
                    <form action="index.html" autocomplete="off" class="register">
                </div>

                <!-- ========================= CARRUSEL DE IMAGENES ================================================== -->
                <div class="carousel">
                    <div class="images-wrapper">
                        <img src="{{ asset('img/irasutoya/medic-medicamentos.png') }}" alt=""
                            class="show image img-1">
                        <img src="{{ asset('img/irasutoya/medic-mama.png') }}" alt="" class="image img-2">
                        <img src="{{ asset('img/irasutoya/medic-registro.png') }}" alt="" class="image img-3">
                        <img src="{{ asset('img/irasutoya/medic-kit.png') }}" alt="" class="image img-4">
                    </div>

                    <!-- ============================= TEXTO DEL CARRUSEL ================================================== -->
                    <div class="text-slider">
                        <div class="text-wrap">
                            <div class="text-group">
                                <h2>Inventario de Inmunizacion</h2>
                                <h2>Registro de Vacunados</h2>
                                <h2>Gestion Interconectada</h2>
                                <h2>Reportes Digital</h2>
                            </div>
                        </div>

                        <!-- ================================ PAGINACION DEL CARRUSEL ================================================ -->
                        <div class="bullets">
                            <span class="active" data-value="1"></span>
                            <span data-value="2"></span>
                            <span data-value="3"></span>
                            <span data-value="4"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <!-- ============================ SCRIPTS JS ================================================================ -->
    <script src="{{ asset('js/responsive.js') }}"></script>
</body>

</html>
