<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'CECIC Repositorio') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f6fa;
            margin: 0;
            padding: 0;
        }

        /* Sidebar */
        #sidebar {
            background-color: #2e2e2e;
            min-height: 100vh;
            width: 250px;
            position: fixed;
            left: 0;
            top: 0;
            transition: all 0.3s ease;
            color: #fff;
            display: flex;
            flex-direction: column;
        }

        #sidebar .sidebar-header {
            padding: 16px 12px;
            background: linear-gradient(135deg, #5D4037, #3E2723);
            text-align: center;
            font-weight: 600;
            font-size: 0.95rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        #sidebar .sidebar-header img {
            max-width: 170px;
            height: auto;
            display: block;
            margin-bottom: 4px;
            margin-left: -20px;
        }

        #sidebar .nav-link {
            color: #ddd;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            transition: 0.2s;
        }

        #sidebar .nav-link i {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        #sidebar .nav-link:hover,
        #sidebar .nav-link.active {
            background-color: #3b2720;
            color: #fff;
        }

        /* Main Content */
        #content {
            margin-left: 250px;
            transition: all 0.3s ease;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .navbar-brand {
            color: #4e342e !important;
            font-weight: 600;
        }

        main {
            flex: 1;
            padding: 1.5rem;
        }

        footer {
            background-color: #4e342e;
            color: #fff;
            text-align: center;
            padding: 15px 0;
        }
    </style>

    @stack('styles')
</head>
<body>
<div id="sidebar">
    <div class="sidebar-header">
        <img src="{{ asset('img/logos/logo4.png') }}" alt="Logo CECIC">
    </div>

    <ul class="nav flex-column mt-3">
        @auth
            <li>
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
        @else
            <li>
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="bi bi-house"></i> Inicio
                </a>
            </li>
        @endauth

        {{-- Enlaces comunes --}}
        <li>
            <a href="{{ route('multimedia.index') }}" class="nav-link {{ request()->routeIs('multimedia.index') ? 'active' : '' }}">
                <i class="bi bi-camera-video"></i> Multimedia
            </a>
        </li>
        <li>
            <a href="{{ route('repository.gallery') }}" class="nav-link {{ request()->routeIs('repository.gallery') ? 'active' : '' }}">
                <i class="bi bi-collection"></i> Observatorio
            </a>
        </li>
        <li>
            <a href="{{ route('repository.index') }}" class="nav-link {{ request()->routeIs('repository.index') ? 'active' : '' }}">
                <i class="bi bi-archive"></i> Repositorio
            </a>
        </li>

        {{-- Admin --}}
        @hasrole('Administrador')
            <li>
                <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Usuarios
                </a>
            </li>
            <li>
                <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.*') || request()->routeIs('sales.byDocument') ? 'active' : '' }}">
                    <i class="bi bi-cash-coin"></i> Ventas
                </a>
            </li>
            <li>
                <a href="{{ route('announcements.index') }}" class="nav-link {{ request()->routeIs('announcements.*') ? 'active' : '' }}">
                    <i class="bi bi-megaphone"></i> Anuncios
                </a>
            </li>
        @endhasrole

        {{-- Usuario --}}
        @hasrole('Usuario')
            <li>
                <a href="{{ route('purchases.my') }}" class="nav-link {{ request()->routeIs('purchases.my') ? 'active' : '' }}">
                    <i class="bi bi-bag-check"></i> Mis Compras
                </a>
            </li>
            <li>
                <a href="{{ route('favorites.my') }}" class="nav-link {{ request()->routeIs('favorites.my') ? 'active' : '' }}">
                    <i class="bi bi-heart"></i> Favoritos
                </a>
            </li>
        @endhasrole
    </ul>

    @auth
        <div class="mt-auto text-center p-3">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-outline-light w-100">
                    <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                </button>
            </form>
        </div>
    @endauth
</div>

<div id="content">
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold">Panel de Control CECIC</span>

            @auth
                <div class="d-flex align-items-center ms-auto me-3">
                    <div class="text-end me-3">
                        <div class="fw-semibold text-brown">{{ Auth::user()->name }}</div>
                        <small class="text-muted">
                            {{ Auth::user()->roles->pluck('name')->implode(', ') }}
                        </small>
                    </div>

                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=8B5E3C&color=fff"
                         alt="avatar" class="rounded-circle" width="40" height="40">
                </div>
            @endauth
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        © {{ date('Y') }} CECIC — Repositorio de Información de Cacao
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
