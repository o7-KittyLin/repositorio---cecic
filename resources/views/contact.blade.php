<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contáctanos - CECIC</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Fuentes -->
    <link href="https://fonts.googleapis.com/css2?family=Pangolin&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cecic.css') }}">
    <link rel="shortcut icon" href="{{ asset('img/logos/Logo.png') }}" type="image/x-icon">

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

        /* ===== LOGIN BOX ===== */
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
            max-width: 500px;
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
            max-width: 110px;
            width: 100%;
            height: auto;
            display: block;
            margin: 0 auto 10px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.15));
        }

        /* Responsive */
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

        @media (max-width: 576px) {
            .login-container {
                padding: 28px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="page-wrapper">

        {{-- NAVBAR (igual login) --}}
        <section class="nav">
            <nav>
                <div class="logo">
                    <a href="{{ url('/') }}#inicio">
                        <img src="{{ asset('img/logos/LogoCecic.png') }}" alt="Logo CECIC">
                    </a>
                </div>
                <ul class="menu">
                    <li><a href="{{ url('/') }}#inicio">Inicio</a></li>
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropdown-toggle-custom">CECIC <span class="flecha">▼</span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url('/') }}#que-es">¿Quiénes somos?</a></li>
                            <li><a href="{{ url('/') }}#mision-vision">Misión y Visión</a></li>
                            <li><a href="{{ url('/') }}#lugares">Dónde estamos</a></li>
                            <li><a href="{{ url('/') }}#valores">Valores</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ url('/') }}#areas">Áreas</a></li>
                    <li><a href="{{ route('multimedia.index') }}">Multimedia</a></li>
                    <li><a href="{{ url('/') }}#politicas">Políticas</a></li>
                    <li><a href="{{ url('/') }}#aliados">Aliados</a></li>
                    <li><a href="{{ route('repository.gallery') }}">Observatorio</a></li>
                </ul>
                <div class="acciones">
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

        {{-- CONTENIDO --}}
        <div class="login-wrapper">
            <div class="login-container">

                <!-- Logo / Título -->
                <div class="logo-box">
                    <img src="{{ asset('img/logos/logo3.png') }}" alt="Logo CECIC">
                </div>

                <h4 class="text-center mb-1 fw-bold">Contáctanos</h4>
                <p class="text-center text-muted mb-4">Déjanos tu mensaje y te responderemos pronto.</p>

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

                <!-- Estado -->
                @if (session('contact_success'))
                    <div class="alert alert-success small text-center">
                        {{ session('contact_success') }}
                    </div>
                @endif

                <!-- FORMULARIO -->
                <form method="POST" action="{{ route('contact.store') }}" novalidate>
                    @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nombre</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" maxlength="120" required>
                    @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Correo Electrónico</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" maxlength="255" required>
                    @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Mensaje</label>
                    <textarea name="message" class="form-control @error('message') is-invalid @enderror with-counter" rows="4" maxlength="300" data-max="300" required placeholder="Cuéntanos cómo podemos ayudarte">{{ old('message') }}</textarea>
                    <div class="form-text text-end"><small class="counter">0/300</small></div>
                    @error('message')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                    <button type="submit" class="btn btn-brown w-100 py-2 fw-semibold">
                        <i class="bi bi-send me-1"></i> Enviar
                    </button>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/cecic.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.with-counter').forEach(el => {
        const max = parseInt(el.dataset.max || el.getAttribute('maxlength'), 10);
        const counter = el.parentElement.querySelector('.counter');
        const update = () => { if (counter) counter.textContent = `${el.value.length}/${max}`; };
        el.addEventListener('input', update);
        update();
    });
});
</script>
</body>

</html>
