<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Compra aprobada</title>
</head>
<body style="font-family: Arial, sans-serif; color:#222;">
  <h2 style="color:#8B5E3C;">¡Tu compra fue aprobada!</h2>

  <p>Hola {{ $purchaseRequest->user->name }},</p>

  <p>Hemos aprobado tu solicitud de compra del documento <strong>{{ $purchaseRequest->document->title ?? 'documento' }}</strong>.</p>

  <p>Ya puedes acceder y descargarlo desde tu cuenta.</p>

  <p>Saludos,<br>Equipo CECIC</p>
</body>
</html>
