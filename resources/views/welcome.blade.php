<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CECIC - Repositorio de Información del Cacao</title>

    {{-- Bootstrap (por si luego quieres usarlo en otros lados) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Fuentes --}}
    <link href="https://fonts.googleapis.com/css2?family=Pangolin&family=Poppins:wght@300;400;600;700&display=swap"
        rel="stylesheet">

    {{-- CSS unificado --}}
    <link rel="stylesheet" href="{{ asset('css/cecic.css') }}">

    <link rel="shortcut icon" href="{{ asset('logos/logo3.png') }}" type="image/x-icon">
</head>

<body>
    <main>

        {{-- NAVBAR SUPERIOR --}}
        <section class="nav">
            <nav>
                {{-- Logo --}}
                <div class="logo">
                    <a href="#inicio">
                        <img src="{{ asset('img/logos/logo2.png') }}" alt="Logo CECIC">
                    </a>
                </div>

                {{-- Menú principal --}}
                <ul class="menu">
                    <li><a href="#inicio">Inicio</a></li>
                    <li><a href="#que-es">¿Qué es el CECIC?</a></li>
                    <li><a href="#que-encontraras">Contenido</a></li>
                    <li><a href="#lugares">Lugar</a></li>
                    <li><a href="#mision-vision">Misión y Visión</a></li>
                    <li><a href="#valores">Valores</a></li>
                    <li><a href="#politicas">Políticas</a></li>
                    <li><a href="#aliados">Aliados</a></li>
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

        {{-- HERO (estilo welcome, integrado) --}}
        <section class="hero-landing" id="inicio">
            <div class="hero-inner">

                {{-- TEXTO IZQUIERDA --}}
                <div class="hero-text">
                    <span class="hero-tag">Centro Especializado de Investigación del Cacao</span>
                    <h1>Repositorio CECIC</h1>
                    <p>
                        Un espacio digital para consultar, organizar y compartir información científica, técnica y
                        académica sobre el cacao, pensado para investigadores, estudiantes, productores y tomadores de decisiones.
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

                {{-- IMAGEN A LA DERECHA --}}
                <div class="hero-image-wrapper">
                    <img src="{{ asset('img/banner-cacao.png') }}" alt="Imagen Cacao" class="hero-image">
                </div>

            </div>
        </section>


        {{-- SECCIÓN: ¿Qué es el CECIC? (fusión welcome + principal) --}}
        <section class="nosotros" id="que-es">
            <h2 class="titulo">¿Qué es el CECIC?</h2>
            <div class="info-img-sobre-nosotros">
                <div class="info-texto-sobre-nosotros">
                    <img class="img-nosotros" src="{{ asset('img/gato.gif') }}" alt="Sobre Nosotros">
                    <div class="info-nosotros">
                        El <strong>Centro Especializado de Investigación del Cacao (CECIC)</strong> es una plataforma
                        dedicada a recopilar, organizar y facilitar el acceso a información científica, técnica y académica
                        relacionada con el cacao.
                        <br><br>
                        Buscamos fortalecer la investigación, apoyar la toma de decisiones informadas y generar
                        oportunidades para toda la cadena de valor del cacao, desde el productor hasta el consumidor.
                    </div>
                </div>
            </div>
        </section>

        {{-- SECCIÓN: ¿Qué encontrarás aquí? (tarjetas tipo welcome) --}}
        <section class="que-encontraras" id="que-encontraras">
            <h2 class="titulo">¿Qué encontrarás aquí?</h2>
            <div class="contenedor-cards">
                <div class="feature-card">
                    <i class="bi bi-journal-text"></i>
                    <h5>Repositorio Digital</h5>
                    <p>
                        Accede a investigaciones, documentos técnicos, artículos, trabajos académicos y otros recursos
                        especializados sobre el cacao, centralizados en un solo lugar.
                    </p>
                </div>

                <div class="feature-card">
                    <i class="bi bi-people"></i>
                    <h5>Acceso para Usuarios</h5>
                    <p>
                        Crea tu cuenta para visualizar contenido ampliado, guardar favoritos, gestionar descargas y llevar
                        un historial de consulta personalizado.
                    </p>
                </div>

                <div class="feature-card">
                    <i class="bi bi-upload"></i>
                    <h5>Gestión de Documentos</h5>
                    <p>
                        Los administradores pueden subir, editar, clasificar y mantener actualizado el repositorio de forma
                        ágil, segura y organizada.
                    </p>
                </div>
            </div>
        </section>

        {{-- SECCIÓN: DESCRIPCIÓN DEL LUGAR + CARRUSEL --}}
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
                            📍 Espacio dedicado a la investigación aplicada al cacao, donde convergen ciencia, tecnología e innovación.
                        </p>
                    </div>
                    <div class="slide">
                        <img src="{{ asset('img/no-disponible.gif') }}" alt="Lugar 2">
                        <p class="descripcion-lugar">
                            🧪 Laboratorios y áreas técnicas para el desarrollo de proyectos, análisis de calidad y estudios especializados.
                        </p>
                    </div>
                    <div class="slide">
                        <img src="{{ asset('img/cacao-gif.gif') }}" alt="Lugar 3">
                        <p class="descripcion-lugar">
                            🤝 Espacios de interacción con productores, aliados estratégicos y comunidad académica.
                        </p>
                    </div>
                </div>

                <button class="next" aria-label="Siguiente">
                    <img src="{{ asset('img/cacao.png') }}" alt="Siguiente">
                </button>
            </div>
        </section>

        {{-- SECCIÓN: MISIÓN Y VISIÓN --}}
        <section class="mision-vision" id="mision-vision">
            <h2 class="titulo">Misión y Visión</h2>
            <div class="mision-vision-contenedor">
                {{-- MISIÓN --}}
                <div class="card-mv">
                    <div class="mv-img" style="--bg:url('{{ asset('img/no-disponible.gif') }}')">
                        <div class="mv-piece"></div>
                        <div class="mv-piece"></div>
                        <div class="mv-piece"></div>
                        <div class="mv-piece"></div>
                    </div>
                    <div class="mv-texto">
                        <h4>Misión</h4>
                        <p>
                            Desarrollar, organizar y transferir información y conocimiento sobre el cacao, facilitando la
                            toma de decisiones y promoviendo la innovación en toda la cadena de valor.
                        </p>
                    </div>
                </div>

                {{-- VISIÓN --}}
                <div class="card-mv">
                    <div class="mv-img" style="--bg:url('{{ asset('img/gato.gif') }}')">
                        <div class="mv-piece"></div>
                        <div class="mv-piece"></div>
                        <div class="mv-piece"></div>
                        <div class="mv-piece"></div>
                    </div>
                    <div class="mv-texto">
                        <h4>Visión</h4>
                        <p>
                            Ser un referente nacional e internacional en la gestión de información y conocimiento del
                            cacao, apoyando el desarrollo sostenible y competitivo del sector.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- SECCIÓN: VALORES --}}
        <section class="valores" id="valores">
            <h2 class="titulo">Valores</h2>
            <div class="valores-grid">
                <div class="valor-item bubble-card">
                    <div class="cacao-float"></div>
                    <div class="cacao-float"></div>
                    <div class="cacao-float"></div>
                    <img src="{{ asset('img/cacao-gif.gif') }}" alt="Compromiso">
                    <div class="valor-contenido">
                        <h4>Compromiso</h4>
                        <p>Trabajamos con dedicación para apoyar el fortalecimiento del sector cacaotero.</p>
                    </div>
                </div>

                <div class="valor-item bubble-card">
                    <div class="cacao-float"></div>
                    <div class="cacao-float"></div>
                    <div class="cacao-float"></div>
                    <img src="{{ asset('img/gato.gif') }}" alt="Innovación">
                    <div class="valor-contenido">
                        <h4>Innovación</h4>
                        <p>Buscamos constantemente nuevas formas de organizar, analizar y compartir información.</p>
                    </div>
                </div>

                <div class="valor-item bubble-card">
                    <div class="cacao-float"></div>
                    <div class="cacao-float"></div>
                    <div class="cacao-float"></div>
                    <img src="{{ asset('img/no-disponible.gif') }}" alt="Colaboración">
                    <div class="valor-contenido">
                        <h4>Colaboración</h4>
                        <p>Promovemos el trabajo conjunto entre instituciones, productores y academia.</p>
                    </div>
                </div>

                <div class="valor-item bubble-card">
                    <div class="cacao-float"></div>
                    <div class="cacao-float"></div>
                    <div class="cacao-float"></div>
                    <img src="{{ asset('img/cacao-gif.gif') }}" alt="Sostenibilidad">
                    <div class="valor-contenido">
                        <h4>Sostenibilidad</h4>
                        <p>Impulsamos prácticas que respeten el ambiente y la biodiversidad.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- SECCIÓN: POLÍTICAS --}}
        <section class="politicas" id="politicas">
            <h2 class="titulo">Políticas</h2>
            <div class="politicas-contenido">
                <div class="politica-box">
                    <h4>Política Institucional</h4>
                    <p>
                        Desarrollamos y transferimos agendas de I+D+I para generar oportunidades y atender los retos de la
                        cadena de valor del cacao colombiano sostenible, articulando conocimiento, tecnología e innovación.
                    </p>
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

        {{-- SECCIÓN: ALIADOS --}}
        <section class="aliados" id="aliados">
            <h2 class="titulo">Aliados</h2>
            <div class="carrusel-aliados">
                <button class="prev-aliado" aria-label="Anterior aliado">
                    <img src="{{ asset('img/cacao.png') }}" alt="Anterior">
                </button>

                <div class="imagenes-aliados">
                    <div class="slide-aliado">
                        <img src="{{ asset('img/cacao-gif.gif') }}" alt="Aliado 1">
                        <p class="descripcion-aliado">🤝 Aliado 1</p>
                    </div>
                    <div class="slide-aliado">
                        <img src="{{ asset('img/gato.gif') }}" alt="Aliado 2">
                        <p class="descripcion-aliado">🤝 Aliado 2</p>
                    </div>
                    <div class="slide-aliado">
                        <img src="{{ asset('img/no-disponible.gif') }}" alt="Aliado 3">
                        <p class="descripcion-aliado">🤝 Aliado 3</p>
                    </div>
                </div>

                <button class="next-aliado" aria-label="Siguiente aliado">
                    <img src="{{ asset('img/cacao.png') }}" alt="Siguiente">
                </button>
            </div>
        </section>

    </main>

    {{-- FOOTER --}}
    <footer class="pie" id="contacto">
        <section class="texto-footer">
            <p class="by"><strong>Contacto:</strong></p>
            <p><a href="mailto:cecic@garmi.com">cecic@garmi.com</a></p>
            <p class="raya"> - </p>
            <p>+57-5788643771</p>
            <p class="copy">© {{ date('Y') }} CECIC — Repositorio de Información del Cacao</p>
        </section>
    </footer>

    {{-- Bootstrap JS (opcional) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- JS del sitio --}}
    <script src="{{ asset('js/cecic.js') }}"></script>
</body>

</html>
