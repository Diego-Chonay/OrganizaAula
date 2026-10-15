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

    @if (session('mensajeRestablecimiento'))
    <p>{{ session('mensajeRestablecimiento') }}</p>
@endif

    @if (session('mensajeActivacion'))
    <p>{{ session('mensajeActivacion') }}</p>
@endif

@if (session('errorActivacion'))
    <p>{{ session('errorActivacion') }}</p>
@endif

    @if (!empty($errores))
    <ul>
        @foreach ($errores as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

    <form action="/ingreso/validar" method="post">

        <?= csrf_field() ?>

        <label for="email">Correo electrónico:</label>
        <input type="email" id="email" name="email" required>

        <br><br>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>

        <br><br>

        <label>
            <input type="checkbox" name="remember" value="1">
            Recordarme
        </label>

        <br><br>

        <a href="/recuperar">¿Olvidaste tu contraseña?</a>

        <br><br>

        <button type="submit">Iniciar sesión</button>

    </form>

</body>
</html>