<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }}</title>
</head>
<body>

    <h1>{{ $titulo }}</h1>

    <p><strong>Nombre:</strong> {{ $usuario['name'] }}</p>

    <p><strong>Correo electrónico:</strong> {{ $usuario['email'] }}</p>

    <p><strong>Activo:</strong> {{ (int) $usuario['active'] === 1 ? 'Sí' : 'No' }}</p>

    <p><strong>Administrador:</strong> {{ (int) $usuario['administrator'] === 1 ? 'Sí' : 'No' }}</p>

    <p><strong>Fecha de creación:</strong> {{ $usuario['created_at'] }}</p>

    <p><strong>Última modificación:</strong> {{ $usuario['updated_at'] }}</p>

    <a href="/usuarios">Volver al listado</a>

</body>
</html>