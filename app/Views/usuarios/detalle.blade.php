<!DOCTYPE html>
<html lang="{{ service('language')->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ lang('InterfazAula.informacionUsuario') }}</title>
    @include('componentes.estilos')
</head>
<body>

    @include('componentes.navegacion')

    <h1>{{ lang('InterfazAula.informacionUsuario') }}</h1>

    @if (session('mensajeUsuarios'))
        <p>{{ session('mensajeUsuarios') }}</p>
    @endif

    <p>
        <strong>{{ lang('InterfazAula.nombre') }}:</strong>
        {{ $usuario['name'] }}
    </p>

    <p>
        <strong>{{ lang('InterfazAula.correoElectronico') }}:</strong>
        {{ $usuario['email'] }}
    </p>

    <p>
        <strong>{{ lang('InterfazAula.activo') }}:</strong>
        {{ (int) $usuario['active'] === 1 ? lang('InterfazAula.si') : lang('InterfazAula.no') }}
    </p>

    <p>
        <strong>{{ lang('InterfazAula.administrador') }}:</strong>
        {{ (int) $usuario['administrator'] === 1 ? lang('InterfazAula.si') : lang('InterfazAula.no') }}
    </p>

    <p>
        <strong>{{ lang('InterfazAula.fechaCreacion') }}:</strong>
        {{ $usuario['created_at'] }}
    </p>

    <p>
        <strong>{{ lang('InterfazAula.ultimaModificacion') }}:</strong>
        {{ $usuario['updated_at'] }}
    </p>

    <a href="/usuarios/{{ $usuario['id'] }}/editar" class="btn-aula">
        {{ lang('InterfazAula.editarUsuario') }}
    </a>

    @if ((int) $usuario['active'] === 1)
        <form action="/usuarios/{{ $usuario['id'] }}/desactivar"
              method="post"
              onsubmit="return confirm('{{ lang('InterfazAula.confirmacionDesactivarUsuario') }}');">

            {!! csrf_field() !!}

            <button type="submit">
                {{ lang('InterfazAula.eliminarUsuario') }}
            </button>

        </form>
@endif
    <br><br>

    <a href="/usuarios" class="btn-aula">{{ lang('InterfazAula.volverListado') }}</a>

</body>
</html>