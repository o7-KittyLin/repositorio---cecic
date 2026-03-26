<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva solicitud de compra</title>
</head>
<body style="font-family: Arial, sans-serif; color:#222;">
  <h2 style="color:#8B5E3C;">Nueva solicitud de compra</h2>

  <p>Se ha registrado una nueva solicitud de compra.</p>

  <p>
    <strong>Usuario:</strong> {{ $purchaseRequest->user->name ?? 'N/D' }}<br>
    <strong>Documento:</strong> {{ $purchaseRequest->document->title ?? 'N/D' }}<br>
    <strong>Fecha:</strong> {{ $purchaseRequest->created_at?->format('d/m/Y H:i') }}
  </p>

  <p>
    <a href="{{ route('purchase-requests.index') }}"
       style="background:#8B5E3C; color:#fff; padding:10px 18px; border-radius:6px; text-decoration:none; display:inline-block;">
        Revisar solicitud
    </a>
  </p>

  <p>Ingresa al panel para aprobar o rechazar.</p>

  <p>Saludos,<br>Equipo CECIC</p>
</body>
</html>
