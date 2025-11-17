<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - CECIC</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Fuentes -->
    <link href="https://fonts.googleapis.com/css2?family=Pangolin&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --cecic-brown-dark: #3E2723;
            --cecic-brown: #5D4037;
            --cecic-gold: #D4AF37;
            --cecic-gold-soft: #FFE082;
            --cecic-bg: #F5F4F0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--cecic-bg);
            margin: 0;
        }

        /* ===== NAVBAR (MISMO QUE LA PRINCIPAL) ===== */
        .nav {
          background: linear-gradient(90deg, var(--cecic-brown), var(--cecic-brown-dark));
          padding: 12px 0;
          box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
          width: 100%;
          position: sticky;
          top: 0;
          z-index: 100;
        }

        .nav nav {
          display: flex;
          justify-content: space-between;
          align-items: center;
          max-width: 1200px;
          margin: 0 auto;
          padding: 0 20px;
          flex-wrap: wrap;
          gap: 12px;
        }

        .nav .logo img {
          width: 120px;
        }

        .menu {
          list-style: none;
          display: flex;
          gap: 16px;
          flex-wrap: wrap;
          justify-content: center;
          align-items: center;
        }

        .menu li a {
          color: #fff;
          text-decoration: none;
          font-weight: 600;
          font-family: 'Pangolin', cursive;
          font-size: 0.95rem;
          padding: 6px 10px;
          border-radius: 999px;
          transition: background-color 0.3s ease, color 0.3s ease, transform 0.2s ease;
        }

        .menu li a:hover {
          color: var(--cecic-gold-soft);
          background-color: rgba(255, 248, 225, 0.18);
          transform: translateY(-1px);
        }

        .acciones {
          display: flex;
          align-items: center;
          gap: 12px;
        }

        .acciones a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            background-color: #D4AF37;
            border-radius: 50%;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .acciones a img {
          width: 18px;
          height: 18px;
        }

        .acciones a:hover {
          background-color: var(--cecic-gold-soft);
          transform: translateY(-1px);
        }

        /* ===== REGISTRO BOX ===== */
        .page-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .login-wrapper {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 16px;
        }

        .login-container {
            max-width: 540px;
            width: 100%;
            background: #fff;
            padding: 40px 32px;
            border-radius: 18px;
            box-shadow: 0 8px 22px rgba(0, 0, 0, 0.12);
        }

        .title-brown {
            color: var(--cecic-brown-dark);
            font-weight: 700;
        }

        .btn-brown {
            background-color: var(--cecic-brown-dark);
            color: #fff;
            border-radius: 10px;
        }

        .btn-brown:hover {
            background-color: #2A1B17;
            color: #fff;
        }

        a {
            color: var(--cecic-brown-dark);
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .logo-box {
            text-align: center;
            margin-bottom: 10px;
        }

        .logo-box img {
            max-width: 90px;
            height: auto;
            display: block;
            margin: 0 auto 6px;
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 28px 20px;
            }
        }

        /* ===== FOOTER (MISMO QUE LOGIN) ===== */
        footer.pie.cacao-footer {
            background-color: #2A1B17;
            color: #EEE7DB;
            padding: 28px 20px;
            font-family: 'Poppins', sans-serif;
            margin-top: 40px;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-top {
            text-align: center;
            margin-bottom: 18px;
        }

        .footer-chip {
            background-color: rgba(212, 175, 55, 0.15);
            border: 1px solid rgba(212, 175, 55, 0.5);
            padding: 6px 16px;
            border-radius: 999px;
            font-size: 0.88rem;
            color: #EEE7DB;
            display: inline-block;
        }

        .footer-bottom {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 30px;
        }

        .footer-left,
        .footer-right {
            display: flex;
            flex-direction: column;
            gap: 6px;
            font-size: 0.95rem;
        }

        .footer-left i,
        .footer-right i {
            color: #D4AF37;
            margin-right: 6px;
        }

        .footer-divider-vertical {
            width: 2px;
            height: 55px;
            background: linear-gradient(
                to bottom,
                transparent,
                #D4AF37,
                transparent
            );
            border-radius: 3px;
            margin-top: 5px;
        }

        @media (max-width: 700px) {
            .footer-bottom {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .footer-divider-vertical {
                height: 2px;
                width: 80px;
                margin: 10px 0;
                background: linear-gradient(90deg, transparent, #D4AF37, transparent);
            }
        }
    </style>
</head>

<body>

<div class="page-wrapper">

    {{-- NAV CECIC --}}
    <section class="nav">
        <nav>
            {{-- Logo --}}
            <div class="logo">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('img/logos/logo2.png') }}" alt="Logo CECIC">
                </a>
            </div>

            {{-- Menú principal --}}
            <ul class="menu">
                <li><a href="{{ url('/') }}#inicio">Inicio</a></li>
                <li><a href="{{ url('/') }}#que-es">¿Qué es el CECIC?</a></li>
                <li><a href="{{ url('/') }}#que-encontraras">Contenido</a></li>
                <li><a href="{{ url('/') }}#lugares">Lugar</a></li>
                <li><a href="{{ url('/') }}#mision-vision">Misión y Visión</a></li>
                <li><a href="{{ url('/') }}#valores">Valores</a></li>
                <li><a href="{{ url('/') }}#politicas">Políticas</a></li>
                <li><a href="{{ url('/') }}#aliados">Aliados</a></li>
                <li><a href="{{ route('repository.gallery') }}">Observatorio</a></li>
            </ul>

            {{-- Acciones: redes + login/dashboard --}}
            <div class="acciones">
                <a href="https://www.facebook.com/hover.suarezpuentes" target="_blank" title="Facebook">
                    <img src="{{ asset('img/logos/facebook.png') }}" alt="Facebook">
                </a>

                @guest
                    <a href="{{ route('login') }}" title="Iniciar sesión">
                        <img src="{{ asset('img/logos/usuario.png') }}" alt="Login">
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" title="Ir al Dashboard">
                        <img src="{{ asset('img/logos/usuario.png') }}" alt="Dashboard">
                    </a>
                @endguest
            </div>
        </nav>
    </section>

    {{-- CONTENIDO REGISTRO --}}
    <div class="login-wrapper">
        <div class="login-container">

            <!-- Logo / Título -->
            <div class="logo-box">
                {{-- <img src="{{ asset('img/logos/logo2.png') }}" alt="Logo CECIC"> --}}
                <h3 class="title-brown mt-2">CECIC</h3>
            </div>

            <h4 class="text-center mb-4 fw-bold">Crear Cuenta</h4>

            <!-- Errores -->
            @if ($errors->any())
                <div class="alert alert-danger py-2">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li class="small">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- FORMULARIO -->
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Nombre Completo</label>
                    <input id="name" type="text" name="name"
                           class="form-control"
                           value="{{ old('name') }}" autofocus>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Correo Electrónico</label>
                    <input id="email" type="email" name="email"
                           class="form-control"
                           placeholder="ejemplo@correo.com"
                           value="{{ old('email') }}">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Contraseña</label>
                    <input id="password" type="password" name="password"
                           class="form-control">
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label fw-semibold">Confirmar Contraseña</label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                           class="form-control">
                </div>

                <button type="submit" class="btn btn-brown w-100 py-2 fw-semibold">
                    <i class="bi bi-person-plus me-1"></i> Registrarse
                </button>

                <div class="text-center mt-3">
                    <span class="small">¿Ya tienes una cuenta?</span>
                    <a href="{{ route('login') }}" class="small fw-bold">Inicia sesión aquí</a>
                </div>

            </form>

        </div>
    </div>

    {{-- FOOTER --}}
    <footer class="pie cacao-footer" id="contacto">
        <div class="footer-container">

            <div class="footer-top">
                <span class="footer-chip">🍫 Observatorio del Cacao · CECIC</span>
            </div>

            <div class="footer-bottom">

                <div class="footer-left">
                    <p><i class="bi bi-envelope-fill"></i> cecic@garmi.com</p>
                    <p><i class="bi bi-telephone-fill"></i> +57 578 864 377</p>
                </div>

                <div class="footer-divider-vertical"></div>

                <div class="footer-right">
                    <p>© {{ date('Y') }} CECIC</p>
                    <p>Repositorio del Cacao</p>
                </div>

            </div>

        </div>
    </footer>

</div>

</body>
</html>
