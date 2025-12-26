<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mensaje de contacto</title>
</head>
<body style="font-family: Arial, sans-serif; color:#222;">
  <h2 style="color:#8B5E3C;">Nuevo mensaje de contacto</h2>

  <p><strong>Nombre:</strong> {{ $data['name'] }}</p>
  <p><strong>Correo:</strong> {{ $data['email'] }}</p>
  <p><strong>Mensaje:</strong></p>
  <p>{{ $data['message'] }}</p>

  <p>Saludos,<br>CECIC</p>
</body>
</html>
