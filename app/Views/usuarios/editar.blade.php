<!DOCTYPE html>
<html lang="{{ service('language')->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ lang('InterfazAula.editarUsuario') }}</title>
</head>
<body>

    @include('componentes.navegacion')

    <h1>{{ lang('InterfazAula.editarUsuario') }}</h1>

    @if (!empty($errores))
        <ul>
            @foreach ($errores as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="/usuarios/{{ $usuario['id'] }}/guardar" method="post">

        {!! csrf_field() !!}

        <label for="name">{{ lang('InterfazAula.nombre') }}:</label>
        <input type="text" id="name" name="name" value="{{ $usuario['name'] }}" required>

        <br><br>

        <label for="email">{{ lang('InterfazAula.correoElectronico') }}:</label>
        <input type="email" id="email" name="email" value="{{ $usuario['email'] }}" required>

        <br><br>

        <label for="password">{{ lang('InterfazAula.nuevaContrasena') }}:</label>
        <input type="password" id="password" name="password">

        <br><br>

        <label for="password_repeat">{{ lang('InterfazAula.repetirContrasena') }}:</label>
        <input type="password" id="password_repeat" name="password_repeat">

        <p>{{ lang('InterfazAula.contrasenasOpcionales') }}</p>

        <label for="academic_role">{{ lang('InterfazAula.rolAcademico') }}:</label>

        <select id="academic_role" name="academic_role">
            <option value="student" {{ $usuario['academic_role'] === 'student' ? 'selected' : '' }}>
                {{ lang('InterfazAula.estudiante') }}
            </option>

            <option value="teacher" {{ $usuario['academic_role'] === 'teacher' ? 'selected' : '' }}>
                {{ lang('InterfazAula.profesor') }}
            </option>
        </select>

        <br><br>

        <label>
            <input type="checkbox" name="active" value="1"
                {{ (int) $usuario['active'] === 1 ? 'checked' : '' }}>
            {{ lang('InterfazAula.usuarioActivo') }}
        </label>

        <br><br>

        <label>
            <input type="checkbox" name="administrator" value="1"
                {{ (int) $usuario['administrator'] === 1 ? 'checked' : '' }}>
            {{ lang('InterfazAula.administrador') }}
        </label>

        <br><br>

        <button type="submit">{{ lang('InterfazAula.guardarCambios') }}</button>

        <a href="/usuarios/{{ $usuario['id'] }}">{{ lang('InterfazAula.cancelar') }}</a>

    </form>

</body>
</html>