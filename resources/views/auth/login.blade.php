<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - CECIC</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f6fa;
        }

        .login-container {
            max-width: 1000px; /* AÚN MÁS ANCHA */
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

            <h4 class="text-center mb-4 fw-bold">Iniciar Sesión</h4>

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
            @if (session('status'))
                <div class="alert alert-success small">
                    {{ session('status') }}
                </div>
            @endif

            <!-- FORMULARIO -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Correo Electrónico</label>
                    <input id="email" type="email" name="email"
                        value="{{ old('email') }}"
                        class="form-control" placeholder="ejemplo@correo.com"
                        required autofocus>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Contraseña</label>
                    <input id="password" type="password" name="password"
                        class="form-control" required>
                </div>

                <div class="form-check mb-3">
                    <input id="remember_me" type="checkbox" name="remember" class="form-check-input">
                    <label for="remember_me" class="form-check-label">Recordarme</label>
                </div>

                <button type="submit" class="btn btn-brown w-100 py-2 fw-semibold">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Ingresar
                </button>

                @if (Route::has('password.request'))
                    <div class="text-center mt-3">
                        <a href="{{ route('password.request') }}" class="small">¿Olvidaste tu contraseña?</a>
                    </div>
                @endif

                 <div class="text-center mt-3">
                    <span class="small">¿No tienes cuenta?</span>
                    <a href="{{ route('register') }}" class="small fw-bold">Regístrate aquí</a>
                </div>

            </form>

        </div>
    </div>

</body>

</html>
