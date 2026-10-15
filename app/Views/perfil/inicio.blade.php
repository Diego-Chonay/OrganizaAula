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

    @if (session('mensajePerfil'))
    <p>{{ session('mensajePerfil') }}</p>
@endif

@if (!empty($imagen))
    <img src="{{ base_url('uploads/perfiles/' . $imagen) }}" alt="Imagen de perfil" width="140">
@else
    <div style="font-size: 70px;">👤</div>
@endif

<br>

<a href="/perfil/imagen">Cambiar imagen de perfil</a>

@if (!empty($imagen))
    <form action="/perfil/imagen/eliminar" method="post"
          onsubmit="return confirm('¿Estás seguro de que deseas eliminar tu fotografía de perfil?');">

        {!! csrf_field() !!}

        <button type="submit">Eliminar imagen de perfil</button>

    </form>
@endif

<br><br>

    <h3>Información personal</h3>

    <p><strong>Nombre:</strong> {{ $nombre }}</p>

    <p><strong>Correo electrónico:</strong> {{ $correo }}</p>

    <a href="/perfil/confirmar">Editar perfil</a>

    <a href="/perfil/contrasena">Cambiar contraseña</a>

<br><br>

<br><br>



</body>
</html>
