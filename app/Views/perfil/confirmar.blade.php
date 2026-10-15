<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }}</title>
</head>
<body>

    <h1>{{ $titulo }}</h1>

    <p>Ingresa tu contraseña actual para continuar con la edición de tu perfil.</p>

    @if (session('errorPerfil'))
        <p>{{ session('errorPerfil') }}</p>
    @endif

    <form action="/perfil/confirmar" method="post">

        {!! csrf_field() !!}

        <label for="password">Contraseña actual:</label>
        <input type="password" id="password" name="password" required>

        <br><br>

        <button type="submit">Continuar</button>

        <a href="/perfil">Cancelar</a>

    </form>

</body>
</html>
