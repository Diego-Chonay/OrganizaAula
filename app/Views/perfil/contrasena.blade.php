<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }}</title>
</head>
<body>

    @include('componentes.navegacion')

    <h1>{{ $titulo }}</h1>

    @if (!empty($errores))
        <ul>
            @foreach ($errores as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="/perfil/contrasena/guardar" method="post">

        {!! csrf_field() !!}

        <label for="password_current">Contraseña actual:</label>
        <input type="password" id="password_current" name="password_current" required>

        <br><br>

        <label for="password_new">Nueva contraseña:</label>
        <input type="password" id="password_new" name="password_new" required>

        <br><br>

        <label for="password_repeat">Repetir nueva contraseña:</label>
        <input type="password" id="password_repeat" name="password_repeat" required>

        <br><br>

        <button type="submit">Guardar contraseña</button>

        <a href="/perfil">Cancelar</a>

    </form>

</body>
</html>
