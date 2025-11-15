    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CECIC - Repositorio de Información del Cacao</title>

        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #f5f6fa;
            }

            /* HERO */
            .hero {
                background: linear-gradient(135deg, #4e342e, #3b2720);
                color: #fff;
                padding: 120px 20px;
                text-align: center;
                border-radius: 0 0 50px 50px;
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
            }

            .hero h1 {
                font-size: 3.2rem;
                font-weight: 700;
            }

            .hero p {
                font-size: 1.2rem;
                opacity: 0.95;
            }

            .btn-brown {
                background-color: #4e342e;
                color: #fff;
                border-radius: 10px;
                padding: 12px 28px;
            }

            .btn-brown:hover {
                background-color: #3b2720;
            }

            .section-title {
                font-weight: 700;
                color: #3b2720;
            }

            .feature-card {
                background: #fff;
                padding: 28px;
                border-radius: 16px;
                text-align: center;
                box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
                transition: 0.3s;
            }

            .feature-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
            }

            .feature-card i {
                font-size: 2.5rem;
                color: #4e342e;
                margin-bottom: 15px;
            }

            footer {
                background-color: #4e342e;
                color: #fff;
                padding: 25px 0;
                text-align: center;
                margin-top: 60px;
            }
        </style>
    </head>

    <body>

        <!-- ===========================
                HERO PRINCIPAL
        ============================ -->
        <section class="hero">
            <h1>Repositorio CECIC</h1>
            <p class="mt-3 mb-4">Centro Especializado de Investigación del Cacao</p>

            <div class="mt-4">

                @guest
                    <!-- Mostrar si NO está logueado -->
                    <a href="{{ route('login') }}" class="btn btn-light px-4 py-2 me-2">Iniciar Sesión</a>
                @endguest
                
                @auth
                    <!-- Mostrar si está logueado -->
                    <a href="{{ route('dashboard') }}" class="btn btn-light px-4 py-2 me-2">Dashboard</a>
                @endauth

                <!-- Ver repositorio siempre disponible -->
                <a href="{{ route('repository.gallery') }}" class="btn btn-brown px-4 py-2">
                    <i class="bi bi-folder2-open"></i> Ver Repositorio
                </a>
            </div>
        </section>



        <div class="container py-5">

            <!-- ===========================
                    QUIÉNES SOMOS
            ============================ -->
            <section class="mb-5 text-center">
                <h2 class="section-title mb-3">¿Qué es el CECIC?</h2>
                <p class="text-muted mx-auto" style="max-width: 750px">
                    El Centro Especializado de Investigación del Cacao es una plataforma dedicada a recopilar,
                    organizar y facilitar el acceso a información científica, técnica y académica relacionada con el
                    cacao.
                    Nuestro objetivo es impulsar la investigación y el desarrollo del sector cacaotero.
                </p>
            </section>

            <!-- ===========================
                    CARACTERÍSTICAS
            ============================ -->
            <section class="mb-5">
                <h2 class="section-title text-center mb-4">¿Qué encontrarás aquí?</h2>

                <div class="row g-4">

                    <div class="col-md-4">
                        <div class="feature-card">
                            <i class="bi bi-journal-text"></i>
                            <h5 class="fw-bold">Repositorio Digital</h5>
                            <p class="text-muted">Accede a investigaciones, documentos técnicos, artículos y material
                                académico.</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="feature-card">
                            <i class="bi bi-people"></i>
                            <h5 class="fw-bold">Acceso para Usuarios</h5>
                            <p class="text-muted">Registrarte te permitirá visualizar contenido exclusivo y gestionar
                                tus descargas.</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="feature-card">
                            <i class="bi bi-upload"></i>
                            <h5 class="fw-bold">Gestión de Documentos</h5>
                            <p class="text-muted">Los administradores pueden subir, editar y organizar documentos
                                fácilmente.</p>
                        </div>
                    </div>

                </div>
            </section>

        </div>

        <footer>
            © {{ date('Y') }} CECIC — Repositorio de Información del Cacao
        </footer>

    </body>

    </html>
