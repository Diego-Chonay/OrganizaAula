<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $titulo }}</title>
</head>
<body>

    @include('componentes.navegacion')

    <h1>{{ $titulo }}</h1>

@if (session('mensajeRegistro'))
    <p>{{ session('mensajeRegistro') }}</p>
@endif
@if (!empty($errores))
    <ul>
        @foreach ($errores as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

    <form action="/registro/guardar" method="post">

        <label>Nombre:</label>
        <input type="text" name="name">

        <br><br>

        <label>Correo:</label>
        <input type="email" name="email">

        <br><br>

        <label>Contraseña:</label>
        <input type="password" name="password">

        <br><br>

        <label>Repetir contraseña:</label>
        <input type="password" name="password_repeat">

        <br><br>

        <button type="submit">Crear cuenta</button>

    </form>

</body>
</html>