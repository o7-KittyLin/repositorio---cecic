<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer contraseña</title>
</head>
<body style="font-family: Arial, sans-serif; color:#222;">
  <h2 style="color:#8B5E3C;">Restablecer tu contraseña</h2>

  <p>Hola {{ $user->name }},</p>

  <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta en <strong>CECIC</strong>.</p>

  <p>Si fuiste tú, haz clic en el siguiente botón para crear una nueva contraseña:</p>

  <p>
    <a href="{{ $resetUrl }}"
       style="background:#8B5E3C; color:#fff; padding:10px 18px; border-radius:6px; text-decoration:none;">
        Restablecer contraseña
    </a>
  </p>

  <p>Este enlace es válido por un tiempo limitado. Si tú no solicitaste este cambio, puedes ignorar este mensaje y tu contraseña seguirá siendo la misma.</p>

  <p>Saludos,<br>Equipo CECIC</p>
</body>
</html>
