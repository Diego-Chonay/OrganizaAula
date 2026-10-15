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

    @if (!empty($errores))
        <ul>
            @foreach ($errores as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="/usuarios/guardar" method="post">

        {!! csrf_field() !!}

        <label for="name">Nombre:</label>
        <input type="text" id="name" name="name" required>

        <br><br>

        <label for="email">Correo electrónico:</label>
        <input type="email" id="email" name="email" required>

        <br><br>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>

        <br><br>

        <label for="password_repeat">Repetir contraseña:</label>
        <input type="password" id="password_repeat" name="password_repeat" required>

        <br><br>

        <label for="academic_role">Rol académico:</label>
        <select id="academic_role" name="academic_role">
            <option value="student">Estudiante</option>
            <option value="teacher">Profesor</option>
        </select>

        <br><br>

        <label>
            <input type="checkbox" name="active" value="1" checked>
            Usuario activo
        </label>

        <br><br>

        <label>
            <input type="checkbox" name="administrator" value="1">
            Administrador
        </label>

        <br><br>

        <button type="submit">Guardar usuario</button>

        <a href="/usuarios">Cancelar</a>

    </form>

</body>
</html>
