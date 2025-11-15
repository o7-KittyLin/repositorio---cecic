<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - CECIC</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f6fa;
        }

        .login-container {
            max-width: 1000px;
            background: #fff;
            padding: 50px;
            border-radius: 18px;
            box-shadow: 0 8px 22px rgba(0, 0, 0, 0.12);
        }

        .title-brown {
            color: #4e342e;
            font-weight: 700;
        }

        .btn-brown {
            background-color: #4e342e;
            color: #fff;
            border-radius: 10px;
        }

        .btn-brown:hover {
            background-color: #3b2720;
            color: #fff;
        }

        a {
            color: #4e342e;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .logo-box {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

<div class="d-flex justify-content-center align-items-center min-vh-100 px-3">
    <div class="login-container">

        <!-- Logo -->
        <div class="logo-box">
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
                       value="{{ old('name') }}" required autofocus>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Correo Electrónico</label>
                <input id="email" type="email" name="email"
                       class="form-control"
                       placeholder="ejemplo@correo.com"
                       value="{{ old('email') }}" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Contraseña</label>
                <input id="password" type="password" name="password"
                       class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label fw-semibold">Confirmar Contraseña</label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                       class="form-control" required>
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

</body>

</html>
