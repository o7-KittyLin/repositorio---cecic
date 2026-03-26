<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Compra rechazada</title>
</head>
<body style="font-family: Arial, sans-serif; color:#222;">
  <h2 style="color:#8B5E3C;">Compra rechazada</h2>

  <p>Hola {{ $purchaseRequest->user->name ?? 'usuario' }},</p>

  <p>Tu solicitud de compra del documento <strong>{{ $purchaseRequest->document->title ?? '' }}</strong> fue rechazada.</p>

  @if($purchaseRequest->admin_note)
  <p><strong>Nota del administrador:</strong> {{ $purchaseRequest->admin_note }}</p>
  @endif

  <p>Si tienes dudas, por favor comunicate con el administrador.</p>

  <p>Gracias,<br>Equipo CECIC</p>
</body>
</html>
