<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CECIC - Repositorio de Información del Cacao</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pangolin&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cecic.css') }}">
    <link rel="shortcut icon" href="{{ asset('logos/logo3.png') }}" type="image/x-icon">
</head>
<body>
<main>
    {{-- NAVBAR --}}
    <section class="nav">
        <nav>
            <div class="logo">
                <a href="#inicio">
                    <img src="{{ asset('img/logos/LogoCecic.png') }}" alt="Logo CECIC">
                </a>
            </div>
            <ul class="menu">
                <li><a href="#inicio">Inicio</a></li>
                <li class="dropdown">
                    <a href="javascript:void(0)" class="dropdown-toggle-custom">CECIC <span class="flecha">▼</span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#que-es">¿Quiénes somos?</a></li>
                        <li><a href="#mision-vision">Misión y Visión</a></li>
                        <li><a href="#lugares">Dónde estamos</a></li>
                        <li><a href="#valores">Valores</a></li>
                    </ul>
                </li>
                <li><a href="#areas">Áreas</a></li>
                <li><a href="{{ route('multimedia.index') }}">Multimedia</a></li>
                <li><a href="#politicas">Políticas</a></li>
                <li><a href="#aliados">Aliados</a></li>
                <li><a href="{{ route('repository.gallery') }}">Observatorio</a></li>
            </ul>
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

    {{-- HERO --}}
    <section class="hero-landing" id="inicio">
        <div class="hero-inner">
            <div class="hero-text">
                <span class="hero-tag">Centro Especializado de Investigación del Cacao</span>
                <h1>Repositorio CECIC</h1>
                <p>
                    Un espacio digital para consultar, organizar y compartir información científica, técnica y académica sobre el cacao, pensado para investigadores, estudiantes, productores y tomadores de decisiones.
                </p>
                <div class="hero-badges">
                    <div class="hero-badge"><i class="bi bi-mortarboard-fill me-1"></i> Investigación y academia</div>
                    <div class="hero-badge"><i class="bi bi-tree-fill me-1"></i> Cadena de valor del cacao</div>
                    <div class="hero-badge"><i class="bi bi-cloud-arrow-down-fill me-1"></i> Repositorio especializado</div>
                </div>
                <div class="hero-actions">
                    @guest
                        <a href="{{ route('login') }}" class="btn-cta">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar sesión
                        </a>
                        @if(Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-cta-secondary">Registrarme</a>
                        @endif
                    @else
                        <a href="{{ route('dashboard') }}" class="btn-cta">
                            <i class="bi bi-speedometer2 me-1"></i> Ir al Dashboard
                        </a>
                    @endguest
                    <a href="{{ route('repository.gallery') }}" class="btn-hero-repo">
                        <i class="bi bi-folder2-open me-1"></i> Ver Repositorio
                    </a>
                </div>
            </div>
            <div class="hero-image-wrapper">
                <img src="{{ asset('img/banner-cacao.png') }}" alt="Imagen Cacao" class="hero-image">
            </div>
        </div>
    </section>

    {{-- ¿Qué es el CECIC? --}}
    <section class="nosotros" id="que-es">
        <h2 class="titulo">¿Qué es el CECIC?</h2>
        <div class="info-img-sobre-nosotros">
            <div class="info-texto-sobre-nosotros">
                <img class="img-nosotros" src="{{ asset('img/logos/logo3.png') }}" alt="Sobre Nosotros">
                <div class="info-nosotros">
                    El <strong>Centro Especializado de Investigación del Cacao (CECIC)</strong> es una plataforma dedicada a recopilar, organizar y facilitar el acceso a información científica, técnica y académica relacionada con el cacao.
                    <br><br>
                    Buscamos fortalecer la investigación, apoyar la toma de decisiones informadas y generar oportunidades para toda la cadena de valor del cacao, desde el productor hasta el consumidor.
                </div>
            </div>
        </div>
    </section>

    {{-- Lugar --}}
    <section class="lugar" id="lugares">
        <h2 class="titulo">Descripción del Lugar</h2>
        <div class="carrusel-lugar">
            <button class="prev" aria-label="Anterior">
                <img src="{{ asset('img/cacao.png') }}" alt="Anterior">
            </button>
            <div class="imagenes-lugar">
                <div class="slide">
                    <img src="{{ asset('img/gato.gif') }}" alt="Lugar 1">
                    <p class="descripcion-lugar">
                        Espacio dedicado a la investigación aplicada al cacao, donde convergen ciencia, tecnología e innovación.
                    </p>
                </div>
                <div class="slide">
                    <img src="{{ asset('img/no-disponible.gif') }}" alt="Lugar 2">
                    <p class="descripcion-lugar">
                        Laboratorios y áreas técnicas para el desarrollo de proyectos, análisis de calidad y estudios especializados.
                    </p>
                </div>
                <div class="slide">
                    <img src="{{ asset('img/cacao-gif.gif') }}" alt="Lugar 3">
                    <p class="descripcion-lugar">
                        Espacios de interacción con productores, aliados estratégicos y comunidad académica.
                    </p>
                </div>
            </div>
            <button class="next" aria-label="Siguiente">
                <img src="{{ asset('img/cacao.png') }}" alt="Siguiente">
            </button>
        </div>
    </section>

    {{-- Misión y Visión --}}
    <section class="mision-vision" id="mision-vision">
        <h2 class="titulo">Misión y Visión</h2>
        <div class="mision-vision-contenedor">
            <div class="card-mv">
                <div class="mv-img" style="--bg:url('{{ asset('img/mision.png') }}')">
                    <div class="mv-piece"></div><div class="mv-piece"></div><div class="mv-piece"></div><div class="mv-piece"></div>
                </div>
                <div class="mv-texto">
                    <h4>Misión</h4>
                    <p>Promover la investigación, innovación y transferencia tecnológica en el cultivo y la agroindustria del cacao colombiano, fortaleciendo su productividad, sostenibilidad y valor agregado.</p>
                </div>
            </div>
            <div class="card-mv">
                <div class="mv-img" style="--bg:url('{{ asset('img/vision.png') }}')">
                    <div class="mv-piece"></div><div class="mv-piece"></div><div class="mv-piece"></div><div class="mv-piece"></div>
                </div>
                <div class="mv-texto">
                    <h4>Visión</h4>
                    <p>Ser un centro líder que impulsa la innovación y el conocimiento para que el cacao colombiano crezca de forma sostenible y mejore la vida de quienes lo cultivan.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Valores --}}
    <section class="valores" id="valores">
        <h2 class="titulo">Valores</h2>
        <div class="valores-grid">
            <div class="valor-item bubble-card">
                <div class="cacao-float"></div><div class="cacao-float"></div><div class="cacao-float"></div>
                <img src="{{ asset('img/unidos.png') }}" alt="Pertinencia">
                <div class="valor-contenido">
                    <h4>Pertinencia</h4>
                    <p>Trabajamos con propósito, enfocando nuestras acciones en las verdaderas necesidades del sector cacaotero.</p>
                </div>
            </div>
            <div class="valor-item bubble-card">
                <div class="cacao-float"></div><div class="cacao-float"></div><div class="cacao-float"></div>
                <img src="{{ asset('img/innovar.jpg') }}" alt="Innovación">
                <div class="valor-contenido">
                    <h4>Innovación</h4>
                    <p>Impulsamos la creatividad, la ciencia y la tecnología para transformar el cultivo y la industria del cacao.</p>
                </div>
            </div>
            <div class="valor-item bubble-card">
                <div class="cacao-float"></div><div class="cacao-float"></div><div class="cacao-float"></div>
                <img src="{{ asset('img/trabajo.jpg') }}" alt="Corporatividad">
                <div class="valor-contenido">
                    <h4>Corporatividad</h4>
                    <p>Unimos esfuerzos con productores, instituciones y aliados para un desarrollo sostenible y compartido.</p>
                </div>
            </div>
            <div class="valor-item bubble-card">
                <div class="cacao-float"></div><div class="cacao-float"></div><div class="cacao-float"></div>
                <img src="{{ asset('img/sostenible.png') }}" alt="Sostenibilidad">
                <div class="valor-contenido">
                    <h4>Sostenibilidad</h4>
                    <p>Promovemos prácticas responsables que protejan el medio ambiente y aseguren un futuro mejor.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Áreas --}}
    <section class="areas" id="areas">
        <h2 class="titulo">Información de las Áreas</h2>
        <div class="areas-grid">
            @for($i=1; $i<=5; $i++)
                <div class="area-item">
                    <div class="area-inner">
                        <div class="area-front">
                            <img src="{{ asset($i === 2 ? 'img/cacao-gif.gif' : ($i === 4 ? 'img/no-disponible.gif' : 'img/gato.gif')) }}" alt="Área {{ $i }}">
                            <h4>Área {{ $i }}</h4>
                        </div>
                        <div class="area-back">
                            <h4>Área {{ $i }}</h4>
                            <p>Descripción breve del área {{ $i }}...</p>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </section>

    {{-- Políticas --}}
    <section class="politicas" id="politicas">
        <h2 class="titulo">Políticas</h2>
        <div class="politicas-contenido">
            <div class="politica-box">
                <h4>Política Institucional</h4>
                <p>Desarrollamos y transferimos agendas de I+D+I para generar oportunidades y atender los retos de la cadena de valor del cacao colombiano sostenible.</p>
            </div>
            <div class="politica-box">
                <h4>Principios Rectores</h4>
                <ul>
                    <li>Manejo óptimo del suelo.</li>
                    <li>Conservación de la biodiversidad.</li>
                    <li>Aseguramiento de la inocuidad y calidad.</li>
                </ul>
            </div>
        </div>
    </section>

    {{-- Aliados --}}
    <section class="aliados" id="aliados">
        <h2 class="titulo">Aliados</h2>
        <div class="carrusel-aliados">
            <button class="prev-aliado" aria-label="Anterior aliado">
                <img src="{{ asset('img/cacao.png') }}" alt="Anterior">
            </button>
            <div class="imagenes-aliados">
                <div class="slide-aliado">
                    <img src="{{ asset('img/tolima.png') }}" alt="Aliado 1">
                    <p class="descripcion-aliado">Universidad del Tolima</p>
                </div>
                <div class="slide-aliado">
                    <img src="{{ asset('img/nariño.jpg') }}" alt="Aliado 2">
                    <p class="descripcion-aliado">Universidad de Nariño</p>
                </div>
                <div class="slide-aliado">
                    <img src="{{ asset('img/red.jpg') }}" alt="Aliado 3">
                    <p class="descripcion-aliado">Red cacotera de Colombia</p>
                </div>
                <div class="slide-aliado">
                    <img src="{{ asset('img/mesa.jpg') }}" alt="Aliado 4">
                    <p class="descripcion-aliado">Mesa nacional de cacao</p>
                </div>
                <div class="slide-aliado">
                    <img src="{{ asset('img/fet.jpg') }}" alt="Aliado 5">
                    <p class="descripcion-aliado">Fundación Escuela Tecnológica de Neiva</p>
                </div>
            </div>
            <button class="next-aliado" aria-label="Siguiente aliado">
                <img src="{{ asset('img/cacao.png') }}" alt="Siguiente">
            </button>
        </div>
    </section>
</main>

<footer class="pie cacao-footer" id="contacto">
    <div class="footer-container">
        <div class="footer-top">
            <span class="footer-chip">Observatorio del Cacao · CECIC</span>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/cecic.js') }}"></script>
</body>
</html>
