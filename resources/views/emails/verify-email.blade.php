<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verifica tu correo</title>
</head>
<body style="font-family: Arial, sans-serif; color:#222;">
  <h2 style="color:#8B5E3C;">Verificación de correo</h2>

  <p>Hola {{ $user->name }},</p>

  <p>Gracias por registrarte en <strong>CECIC</strong>. Para activar tu cuenta y acceder al repositorio de documentos, por favor verifica tu correo electrónico.</p>

  <p>Solo tienes que hacer clic en el siguiente botón:</p>

  <p>
    <a href="{{ $verifyUrl }}"
       style="background:#8B5E3C; color:#fff; padding:10px 18px; border-radius:6px; text-decoration:none;">
        Verificar mi correo
    </a>
  </p>

  <p>Si tú no creaste esta cuenta, puedes ignorar este mensaje.</p>

  <p>Saludos,<br>Equipo CECIC</p>
</body>
</html>
