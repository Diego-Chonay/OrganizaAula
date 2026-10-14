<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }}</title>
</head>
<body>

    <h1>{{ $titulo }}</h1>

    @if (session('mensajeTareas'))
    <p>{{ session('mensajeTareas') }}</p>
@endif

    <p><strong>ID:</strong> {{ $tarea['id'] }}</p>

    <p><strong>Descripción:</strong> {{ $tarea['description'] }}</p>

    <p><strong>Fecha de creación:</strong> {{ $tarea['created_at'] }}</p>

    <p><strong>Última modificación:</strong> {{ $tarea['updated_at'] }}</p>

    @if ($esProfesor)
    <a href="/tareas/{{ $tarea['id'] }}/editar">Editar tarea</a>

    <br><br>

    <form action="/tareas/{{ $tarea['id'] }}/eliminar" method="post"
          onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta tarea?');">

        {!! csrf_field() !!}

        <button type="submit">Eliminar tarea</button>

    </form>

    <br>
@endif

    <a href="/tareas">Volver al listado</a>

</body>
</html>