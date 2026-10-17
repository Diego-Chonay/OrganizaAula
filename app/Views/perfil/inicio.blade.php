<!DOCTYPE html>
<html lang="{{ service('language')->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ lang('InterfazAula.perfil') }}</title>
    @include('componentes.estilos')
</head>
<body>

    @include('componentes.navegacion')

    <h1>{{ lang('InterfazAula.perfil') }}</h1>

    @if (session('mensajePerfil'))
        <p>{{ session('mensajePerfil') }}</p>
    @endif

    @if (!empty($imagen))
        <img src="{{ base_url('uploads/perfiles/' . $imagen) }}"
             alt="{{ lang('InterfazAula.imagenPerfil') }}" width="140">
    @else
        <div style="font-size: 70px;">👤</div>
    @endif

    <br>

    <a href="/perfil/imagen">{{ lang('InterfazAula.cambiarImagenPerfil') }}</a>

    @if (!empty($imagen))
        <form action="/perfil/imagen/eliminar" method="post"
              onsubmit="return confirm('{{ lang('InterfazAula.confirmarEliminarImagen') }}');">

            {!! csrf_field() !!}

            <button type="submit">{{ lang('InterfazAula.eliminarImagenPerfil') }}</button>

        </form>
    @endif

    <br><br>

    <h3>{{ lang('InterfazAula.informacionPersonal') }}</h3>

    <p><strong>{{ lang('InterfazAula.nombre') }}:</strong> {{ $nombre }}</p>

    <p><strong>{{ lang('InterfazAula.correoElectronico') }}:</strong> {{ $correo }}</p>

    <a href="/perfil/confirmar">{{ lang('InterfazAula.editarPerfil') }}</a>

    <a href="/perfil/contrasena">{{ lang('InterfazAula.cambiarContrasena') }}</a>

    <script>
    sessionStorage.removeItem('borradorPerfilAula:{{ (int) session('usuario_id') }}');
</script>

</body>
</html>