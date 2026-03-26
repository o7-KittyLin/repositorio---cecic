<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Compra aprobada</title>
</head>
<body style="font-family: Arial, sans-serif; color:#222;">
  <h2 style="color:#8B5E3C;">Compra aprobada</h2>

  <p>Hola {{ $purchaseRequest->user->name ?? 'usuario' }},</p>

  <p>Tu solicitud de compra del documento <strong>{{ $purchaseRequest->document->title ?? '' }}</strong> ha sido aprobada.</p>

  <p>Ya puedes descargar el documento desde tu seccion de compras.</p>

  @if($purchaseRequest->admin_note)
  <p><strong>Nota del administrador:</strong> {{ $purchaseRequest->admin_note }}</p>
  @endif

  <p>Gracias,<br>Equipo CECIC</p>
</body>
</html>
