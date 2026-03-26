<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminación de cuenta programada</title>
</head>
<body style="font-family: Arial, sans-serif; color:#222;">
  <h2 style="color:#8B5E3C;">Tu cuenta se eliminará pronto</h2>

  <p>Hola {{ $user->name }},</p>

  <p>Solicitaste eliminar tu cuenta en <strong>CECIC</strong>. La eliminación está programada para el <strong>{{ $deletion->scheduled_for->format('d/m/Y') }}</strong> (3 días hábiles).</p>

  <p>Si cambias de opinión, entra a tu perfil y usa la opción <strong>“Recuperar cuenta”</strong> antes de esa fecha.</p>

  <p>
    <a href="{{ url('/login') }}"
       style="background:#8B5E3C; color:#fff; padding:10px 18px; border-radius:6px; text-decoration:none; display:inline-block;">
        Ir al panel
    </a>
  </p>

  <p>Si no solicitaste esto, recupera tu cuenta cuanto antes.</p>

  <p>Saludos,<br>Equipo CECIC</p>
</body>
</html>
