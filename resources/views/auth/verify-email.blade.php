<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('img/logos/Logo.png') }}" type="image/x-icon">
    <title>Verificar correo - CECIC</title>

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

        /* ============ NAV ============ */
        .nav {
            background: linear-gradient(90deg, var(--cecic-brown), var(--cecic-brown-dark));
            padding: 12px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.25);
            width: 100%;
            top: 0;
            position: sticky;
            z-index: 100;
        }

        .nav nav {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .nav .logo img {
            width: 120px;
        }

        /* ============ FORM BOX ============ */
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
            width: 100%;
            max-width: 500px;
            background: #fff;
            padding: 40px 32px;
            border-radius: 18px;
            box-shadow: 0 8px 22px rgba(0,0,0,0.12);
        }

        .logo-box {
            text-align: center;
            margin-bottom: 12px;
        }

        .logo-box img {
            max-width: 120px;
            display: block;
            margin: 0 auto;
        }

        .title-brown {
            font-weight: 700;
            color: var(--cecic-brown-dark);
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

        /* ============ FOOTER ============ */
        footer.pie.cacao-footer {
            background-color: #2A1B17;
            color: #EEE7DB;
            padding: 28px 20px;
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
            background-color: rgba(212,175,55,0.15);
            border: 1px solid rgba(212,175,55,0.5);
            padding: 6px 16px;
            border-radius: 999px;
            color: #EEE7DB;
            font-size: 0.88rem;
        }

        .footer-bottom {
            display: flex;
            justify-content: center;
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
            color: var(--cecic-gold);
        }

        .footer-divider-vertical {
            width: 2px;
            height: 55px;
            background: linear-gradient(to bottom, transparent, var(--cecic-gold), transparent);
            border-radius: 3px;
            margin-top: 5px;
        }

        @media(max-width:700px){
            .footer-bottom {flex-direction:column;text-align:center;}
            .footer-divider-vertical {
                height:2px;width:80px;
                background:linear-gradient(90deg,transparent,var(--cecic-gold),transparent);
                margin:10px 0;
            }
        }
    </style>
</head>

<body>

<div class="page-wrapper">

    {{-- NAV --}}
    <section class="nav">
        <nav>
            <div class="logo">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('img/logos/logo2.png') }}" alt="CECIC">
                </a>
            </div>
        </nav>
    </section>

    {{-- CONTENIDO --}}
    <div class="login-wrapper">
        <div class="login-container">

            <div class="logo-box">
                <img src="{{ asset('img/logos/logo3.png') }}" alt="Logo CECIC">
            </div>

            <h4 class="text-center mb-3 fw-bold title-brown">Verifica tu correo electrónico</h4>

            <p class="text-center mb-4" style="color:#555;">
                Gracias por registrarte. Antes de comenzar, debes verificar tu correo electrónico.<br>
                Te enviamos un enlace de verificación.
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success small">
                    Se ha enviado un nuevo enlace de verificación a tu correo.
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <button type="submit" class="btn btn-brown w-100 mb-3">
                    <i class="bi bi-envelope-paper me-1"></i>
                    Reenviar enlace de verificación
                </button>
            </form>

            <div class="text-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-link small" style="color:#3E2723;">
                        Cerrar sesión
                    </button>
                </form>
            </div>

        </div>
    </div>

    {{-- FOOTER --}}
    <footer class="pie cacao-footer">
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
