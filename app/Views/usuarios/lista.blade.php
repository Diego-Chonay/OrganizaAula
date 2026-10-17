<!DOCTYPE html>
<html lang="{{ service('language')->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ lang('InterfazAula.administracionUsuarios') }}</title>
    @include('componentes.estilos')
</head>
<body>

    @include('componentes.navegacion')

    <h1>{{ lang('InterfazAula.administracionUsuarios') }}</h1>

    @if (session('mensajeUsuarios'))
        <p>{{ session('mensajeUsuarios') }}</p>
    @endif

    <div class="acciones-aula">

    <a href="/usuarios/nuevo" class="btn-aula">
        {{ lang('InterfazAula.nuevoUsuario') }}
    </a>

    <a href="/usuarios?orden=nuevos" class="btn-aula">
        {{ lang('InterfazAula.masRecientes') }}
    </a>

    <a href="/usuarios?orden=antiguos" class="btn-aula">
        {{ lang('InterfazAula.masAntiguos') }}
    </a>

</div>
    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>{{ lang('InterfazAula.nombre') }}</th>
                <th>{{ lang('InterfazAula.correoElectronico') }}</th>
                <th>{{ lang('InterfazAula.activo') }}</th>
                <th>{{ lang('InterfazAula.administrador') }}</th>
                <th>{{ lang('InterfazAula.fechaCreacion') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usuarios as $usuario)
                <tr>
                    <td>
                        <a href="/usuarios/{{ $usuario['id'] }}" class="btn-aula">
    {{ $usuario['name'] }}
</a>
                    </td>

                    <td>{{ $usuario['email'] }}</td>

                    <td>
                        {{ (int) $usuario['active'] === 1 ? lang('InterfazAula.si') : lang('InterfazAula.no') }}
                    </td>

                    <td>
                        {{ (int) $usuario['administrator'] === 1 ? lang('InterfazAula.si') : lang('InterfazAula.no') }}
                    </td>

                    <td>{{ $usuario['created_at'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>