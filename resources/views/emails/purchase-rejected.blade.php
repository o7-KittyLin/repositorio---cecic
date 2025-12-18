<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Compra rechazada</title>
</head>
<body style="font-family: Arial, sans-serif; color:#222;">
  <h2 style="color:#8B5E3C;">Tu compra fue rechazada</h2>

  <p>Hola {{ $purchaseRequest->user->name }},</p>

  <p>No pudimos aprobar tu solicitud de compra para <strong>{{ $purchaseRequest->document->title ?? 'documento' }}</strong>.</p>

  @if($purchaseRequest->note)
    <p>Motivo: {{ $purchaseRequest->note }}</p>
  @endif

  <p>Si crees que es un error, intenta de nuevo o contáctanos.</p>

  <p>Saludos,<br>Equipo CECIC</p>
</body>
</html>
