<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }}</title>
</head>
<body>

    <h1>{{ $titulo }}</h1>

    @if (!empty($errores))
        <ul>
            @foreach ($errores as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="/tareas/guardar" method="post">

        {!! csrf_field() !!}

        <label for="description">Descripción:</label>

        <br>

        <textarea id="description" name="description" rows="4" cols="45" maxlength="255" required>{{ $descripcion ?? '' }}</textarea>

        <br><br>

        <button type="submit">Guardar tarea</button>

        <a href="/tareas">Cancelar</a>

    </form>

</body>
</html>