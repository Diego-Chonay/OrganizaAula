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

    @if (session('mensajeUsuarios'))
    <p>{{ session('mensajeUsuarios') }}</p>
@endif

    <p><strong>Nombre:</strong> {{ $usuario['name'] }}</p>

    <p><strong>Correo electrónico:</strong> {{ $usuario['email'] }}</p>

    <p><strong>Activo:</strong> {{ (int) $usuario['active'] === 1 ? 'Sí' : 'No' }}</p>

    <p><strong>Administrador:</strong> {{ (int) $usuario['administrator'] === 1 ? 'Sí' : 'No' }}</p>

    <p><strong>Fecha de creación:</strong> {{ $usuario['created_at'] }}</p>

    <p><strong>Última modificación:</strong> {{ $usuario['updated_at'] }}</p>

    <a href="/usuarios/{{ $usuario['id'] }}/editar">Editar usuario</a>

    @if ((int) $usuario['active'] === 1)
    <form action="/usuarios/{{ $usuario['id'] }}/desactivar" method="post"
          onsubmit="return confirm('¿Deseas desactivar este usuario? No se eliminará de la base de datos.');">

        {!! csrf_field() !!}

        <button type="submit">Eliminar usuario</button>

    </form>
@endif

<br><br>

    <a href="/usuarios">Volver al listado</a>

</body>
</html>
