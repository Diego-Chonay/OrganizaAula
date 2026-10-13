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

    <form action="/recuperar/actualizar" method="post">

        {!! csrf_field() !!}

        <input type="hidden" name="llave" value="{{ $llave }}">

        <label for="password">Nueva contraseña:</label>
        <input type="password" id="password" name="password" required>

        <br><br>

        <label for="password_repeat">Repetir contraseña:</label>
        <input type="password" id="password_repeat" name="password_repeat" required>

        <br><br>

        <button type="submit">Restablecer contraseña</button>

    </form>

</body>
</html>