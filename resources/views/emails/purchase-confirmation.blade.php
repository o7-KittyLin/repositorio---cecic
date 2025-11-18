<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmar compra</title>
</head>
<body style="font-family: Arial, sans-serif; color:#222;">
  <h2 style="color:#8B5E3C;">Confirmación de compra</h2>

  <p>Hola {{ $user->name }},</p>

  <p>Has solicitado la compra del siguiente documento:</p>

  <p>
    <strong>{{ $document->title }}</strong><br>
    Precio: <strong>${{ number_format($document->price, 2) }}</strong>
  </p>

  <p>Para confirmar tu compra, haz clic en el siguiente enlace:</p>

  <p>
    <a href="{{ $confirmUrl }}"
       style="background:#8B5E3C; color:#fff; padding:10px 18px; border-radius:6px; text-decoration:none;">
        Confirmar compra
    </a>
  </p>

  <p>Este enlace es válido por 15 minutos. Si tú no solicitaste esta compra, puedes ignorar este mensaje.</p>

  <p>Saludos,<br>Equipo CECIC</p>
</body>
</html>
