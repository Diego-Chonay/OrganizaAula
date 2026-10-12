<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>OrganizaAula</title>
</head>
<body>

    <h1>{{ $titulo }}</h1>

    <p>{{ $mensaje }}</p>

@if ($autenticado)
    <p>Hola, {{ $nombreUsuario }}</p>

    <form action="/salir" method="post">
        {!! csrf_field() !!}
        <button type="submit">Cerrar sesión</button>
    </form>
@endif

</body>
</html>
