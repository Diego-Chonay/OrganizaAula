<!DOCTYPE html>
<html lang="{{ service('language')->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ lang('InterfazAula.administracionUsuarios') }}</title>
</head>
<body>

    @include('componentes.navegacion')

    <h1>{{ lang('InterfazAula.administracionUsuarios') }}</h1>

    @if (session('mensajeUsuarios'))
        <p>{{ session('mensajeUsuarios') }}</p>
    @endif

    <a href="/usuarios/nuevo">{{ lang('InterfazAula.nuevoUsuario') }}</a>

    <br><br>

    <p>
        <a href="/usuarios?orden=nuevos">{{ lang('InterfazAula.masRecientes') }}</a>
        |
        <a href="/usuarios?orden=antiguos">{{ lang('InterfazAula.masAntiguos') }}</a>
    </p>

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
                        <a href="/usuarios/{{ $usuario['id'] }}">
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