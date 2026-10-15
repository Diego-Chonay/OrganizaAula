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

    <a href="/usuarios/nuevo">Nuevo usuario</a>

<br><br>



    <p>
        <a href="/usuarios?orden=nuevos">Newer</a>
        |
        <a href="/usuarios?orden=antiguos">Older</a>
    </p>

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Correo electrónico</th>
                <th>Activo</th>
                <th>Administrador</th>
                <th>Fecha de creación</th>
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
                    <td>{{ (int) $usuario['active'] === 1 ? 'Sí' : 'No' }}</td>
                    <td>{{ (int) $usuario['administrator'] === 1 ? 'Sí' : 'No' }}</td>
                    <td>{{ $usuario['created_at'] }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>

</body>
</html>
