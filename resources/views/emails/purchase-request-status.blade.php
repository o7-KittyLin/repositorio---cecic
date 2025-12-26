<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitud de compra</title>
</head>
<body style="font-family: Arial, sans-serif; color:#222;">
  <h2 style="color:#8B5E3C;">Solicitud de compra {{ $purchaseRequest->status === 'approved' ? 'aprobada' : 'rechazada' }}</h2>

  <p><strong>Documento:</strong> {{ $purchaseRequest->document->title ?? 'N/D' }}</p>
  <p><strong>Usuario:</strong> {{ $purchaseRequest->user->name ?? 'N/D' }}</p>
  <p><strong>Estado:</strong> {{ $purchaseRequest->status === 'approved' ? 'Aprobada' : 'Rechazada' }}</p>
  <p><strong>Revisado por:</strong> {{ $purchaseRequest->reviewer->name ?? 'N/D' }} el {{ optional($purchaseRequest->reviewed_at)->format('d/m/Y H:i') }}</p>
  @if($purchaseRequest->admin_note)
    <p><strong>Nota:</strong> {{ $purchaseRequest->admin_note }}</p>
  @endif

  <p>
    <a href="{{ route('purchase-requests.index') }}"
       style="background:#8B5E3C; color:#fff; padding:10px 18px; border-radius:6px; text-decoration:none; display:inline-block;">
        Ver solicitudes
    </a>
  </p>

  <p>Ingresa al panel si necesitas ajustar la decisión.</p>

  <p>Saludos,<br>Equipo CECIC</p>
</body>
</html>
