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

    <form action="/perfil/guardar" method="post">

        {!! csrf_field() !!}

        <label for="name">Nombre:</label>
        <input type="text" id="name" name="name" value="{{ $nombre }}" required>

        <br><br>

        <label for="email">Correo electrónico:</label>
        <input type="email" id="email" name="email" value="{{ $correo }}" required>

        <br><br>

        <button type="submit">Guardar cambios</button>

        <a href="/perfil">Cancelar</a>

    </form>

</body>
</html>