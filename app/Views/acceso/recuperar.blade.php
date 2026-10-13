<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }}</title>
</head>
<body>

    <h1>{{ $titulo }}</h1>

    <p>Introduce tu correo electrónico para recuperar tu contraseña.</p>

    @if (!empty($errores))
        <ul>
            @foreach ($errores as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    @if (session('mensajeRecuperacion'))
        <p>{{ session('mensajeRecuperacion') }}</p>
    @endif

    <form action="/recuperar/enviar" method="post">

        {!! csrf_field() !!}

        <label for="email">Correo electrónico:</label>
        <input type="email" id="email" name="email" required>

        <br><br>

        <button type="submit">Enviar enlace de recuperación</button>

    </form>

    <br>

    <a href="/ingreso">Volver al inicio de sesión</a>

</body>
</html>