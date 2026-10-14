<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }}</title>
</head>
<body>

    <h1>{{ $titulo }}</h1>

    <p><strong>ID:</strong> {{ $tarea['id'] }}</p>

    <p><strong>Descripción:</strong> {{ $tarea['description'] }}</p>

    <p><strong>Fecha de creación:</strong> {{ $tarea['created_at'] }}</p>

    <p><strong>Última modificación:</strong> {{ $tarea['updated_at'] }}</p>

    <a href="/tareas">Volver al listado</a>

</body>
</html>