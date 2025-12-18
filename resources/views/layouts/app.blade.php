<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CECIC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cecic.css') }}">
    <style>
        body { background-color: var(--cecic-bg); }
        .cecic-sidebar {
            width: 260px;
            background: #fff;
            border-right: 1px solid #eaeaea;
        }
        .cecic-sidebar a { color: #3e2723; }
        .cecic-sidebar a:hover { color: #5d4037; }
        .cecic-active { color: #5d4037 !important; font-weight: 700 !important; }
        .cecic-logout { border-color: #e0d3c0; color: #3e2723; }
        .cecic-logout:hover { background: var(--cecic-gold-soft); color: #3e2723; }
    </style>
    @stack('styles')
</head>
@php
    $user = auth()->user();
@endphp
<body>
<div class="d-flex min-vh-100">
    <aside class="d-flex flex-column cecic-sidebar">
        <div class="p-3 border-bottom">
            <h5 class="mb-0 text-brown fw-bold">CECIC Panel</h5>
            <small class="text-muted">Gestión</small>
        </div>

        <div class="flex-grow-1 overflow-auto p-3">
            <div class="mb-3">
                <a class="d-flex align-items-center gap-2 text-decoration-none {{ request()->routeIs('dashboard') ? 'cecic-active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
                </a>
            </div>

            <div class="mb-4">
                <div class="text-uppercase text-muted small mb-2">Contenido</div>
                <div class="d-flex flex-column gap-2 ps-2">
                    <a class="text-decoration-none {{ request()->routeIs('repository.index') ? 'cecic-active' : '' }}" href="{{ route('repository.index') }}">
                        <i class="bi bi-archive"></i> Repositorio
                    </a>
                    <a class="text-decoration-none {{ request()->routeIs('multimedia.index') ? 'cecic-active' : '' }}" href="{{ route('multimedia.index') }}">
                        <i class="bi bi-camera-video"></i> Multimedia
                    </a>
                    <a class="text-decoration-none {{ request()->routeIs('repository.gallery') ? 'cecic-active' : '' }}" href="{{ route('repository.gallery') }}">
                        <i class="bi bi-collection"></i> Observatorio
                    </a>
                </div>
            </div>

            @hasrole('Administrador')
            <div class="mb-4">
                <div class="text-uppercase text-muted small mb-2">Gestión</div>
                <div class="d-flex flex-column gap-2 ps-2">
                    <a class="text-decoration-none {{ request()->routeIs('users.*') ? 'cecic-active' : '' }}" href="{{ route('users.index') }}">
                        <i class="bi bi-people"></i> Usuarios
                    </a>
                </div>
            </div>

            <div class="mb-4">
                <div class="text-uppercase text-muted small mb-2">Ventas</div>
                <div class="d-flex flex-column gap-2 ps-2">
                    <a class="text-decoration-none {{ request()->routeIs('purchase-requests.*') ? 'cecic-active' : '' }}" href="{{ route('purchase-requests.index') }}">
                        <i class="bi bi-hourglass-split"></i> Solicitudes compra
                    </a>
                    <a class="text-decoration-none {{ request()->routeIs('payment-settings.*') ? 'cecic-active' : '' }}" href="{{ route('payment-settings.edit') }}">
                        <i class="bi bi-qr-code"></i> Config. pagos
                    </a>
                    <a class="text-decoration-none {{ request()->routeIs('sales.*') || request()->routeIs('sales.byDocument') ? 'cecic-active' : '' }}" href="{{ route('sales.index') }}">
                        <i class="bi bi-graph-up"></i> Ventas
                    </a>
                </div>
            </div>

            <div class="mb-4">
                <div class="text-uppercase text-muted small mb-2">Comunicación</div>
                <div class="d-flex flex-column gap-2 ps-2">
                    <a class="text-decoration-none {{ request()->routeIs('announcements.*') ? 'cecic-active' : '' }}" href="{{ route('announcements.index') }}">
                        <i class="bi bi-megaphone"></i> Anuncios
                    </a>
                </div>
            </div>
            @endhasrole
        </div>

        <div class="border-top p-3">
            @if($user)
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="rounded-circle bg-brown text-white d-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                        {{ strtoupper(substr($user->name,0,1)) }}
                    </div>
                    <div>
                        <div class="fw-semibold">{{ $user->name }}</div>
                        <small class="text-muted">{{ $user->email }}</small>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn w-100 cecic-logout">
                        <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                    </button>
                </form>
            @endif
        </div>
    </aside>

    <main class="flex-grow-1">
        <div class="p-4">
            @yield('content')
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
