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

    <form action="/usuarios/{{ $usuario['id'] }}/guardar" method="post">

        {!! csrf_field() !!}

        <label for="name">Nombre:</label>
        <input type="text" id="name" name="name" value="{{ $usuario['name'] }}" required>

        <br><br>

        <label for="email">Correo electrónico:</label>
        <input type="email" id="email" name="email" value="{{ $usuario['email'] }}" required>

        <br><br>

        <label for="password">Nueva contraseña:</label>
        <input type="password" id="password" name="password">

        <br><br>

        <label for="password_repeat">Repetir contraseña:</label>
        <input type="password" id="password_repeat" name="password_repeat">

        <p>Deja las contraseñas vacías si no deseas cambiarlas.</p>

        <label for="academic_role">Rol académico:</label>
        <select id="academic_role" name="academic_role">
            <option value="student" {{ $usuario['academic_role'] === 'student' ? 'selected' : '' }}>Estudiante</option>
            <option value="teacher" {{ $usuario['academic_role'] === 'teacher' ? 'selected' : '' }}>Profesor</option>
        </select>

        <br><br>

        <label>
            <input type="checkbox" name="active" value="1"
                {{ (int) $usuario['active'] === 1 ? 'checked' : '' }}>
            Usuario activo
        </label>

        <br><br>

        <label>
            <input type="checkbox" name="administrator" value="1"
                {{ (int) $usuario['administrator'] === 1 ? 'checked' : '' }}>
            Administrador
        </label>

        <br><br>

        <button type="submit">Guardar cambios</button>

        <a href="/usuarios/{{ $usuario['id'] }}">Cancelar</a>

    </form>

</body>
</html>
