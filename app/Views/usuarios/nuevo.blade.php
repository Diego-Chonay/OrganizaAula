<!DOCTYPE html>
<html lang="{{ service('language')->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ lang('InterfazAula.nuevoUsuario') }}</title>
    @include('componentes.estilos')
</head>
<body>

    @include('componentes.navegacion')

    <h1>{{ lang('InterfazAula.nuevoUsuario') }}</h1>

    @if (!empty($errores))
        <ul>
            @foreach ($errores as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="/usuarios/guardar" method="post">

        {!! csrf_field() !!}

        <label for="name">{{ lang('InterfazAula.nombre') }}:</label>
        <input type="text" id="name" name="name" value="{{ $datosPrevios['name'] ?? '' }}" required>

        <br><br>

        <label for="email">{{ lang('InterfazAula.correoElectronico') }}:</label>
        <input type="email" id="email" name="email" value="{{ $datosPrevios['email'] ?? '' }}" required>

        <br><br>

        <label for="password">{{ lang('InterfazAula.contrasena') }}:</label>
        <input type="password" id="password" name="password" required>

        <br><br>

        <label for="password_repeat">{{ lang('InterfazAula.repetirContrasena') }}:</label>
        <input type="password" id="password_repeat" name="password_repeat" required>

        <br><br>

        <label for="academic_role">{{ lang('InterfazAula.rolAcademico') }}:</label>

<select id="academic_role" name="academic_role">
    <option value="student" {{ ($datosPrevios['academic_role'] ?? 'student') === 'student' ? 'selected' : '' }}>
        {{ lang('InterfazAula.estudiante') }}
    </option>

    <option value="teacher" {{ ($datosPrevios['academic_role'] ?? 'student') === 'teacher' ? 'selected' : '' }}>
        {{ lang('InterfazAula.profesor') }}
    </option>
</select>

        <br><br>

        <label>
            <input type="checkbox" name="active" value="1"
    {{ (int) ($datosPrevios['active'] ?? 1) === 1 ? 'checked' : '' }}>
            {{ lang('InterfazAula.usuarioActivo') }}
        </label>

        <br><br>

        <label>
            <input type="checkbox" name="administrator" value="1"
    {{ (int) ($datosPrevios['administrator'] ?? 0) === 1 ? 'checked' : '' }}>
            {{ lang('InterfazAula.administrador') }}
        </label>

        <br><br>

        <button type="submit">{{ lang('InterfazAula.guardarUsuario') }}</button>

        <a href="/usuarios">{{ lang('InterfazAula.cancelar') }}</a>

    </form>

    <script>
    const formularioUsuario = document.querySelector('form[action="/usuarios/guardar"]');
    const formularioIdioma = document.querySelector('form[action="/idioma/cambiar"]');

    const nombreUsuario = formularioUsuario.querySelector('[name="name"]');
    const correoUsuario = formularioUsuario.querySelector('[name="email"]');
    const rolUsuario = formularioUsuario.querySelector('[name="academic_role"]');
    const activoUsuario = formularioUsuario.querySelector('[name="active"]');
    const administradorUsuario = formularioUsuario.querySelector('[name="administrator"]');

    const rutaUsuario = 'nuevo';
    const identificadorUsuario = '{{ (int) session('usuario_id') }}';

    const claveAnterior = 'borradorUsuariosAula:' + identificadorUsuario + ':' + rutaUsuario;
    const claveIdioma = 'idiomaTemporalUsuariosAula:' + identificadorUsuario + ':' + rutaUsuario;

    sessionStorage.removeItem(claveAnterior);

    const borrador = sessionStorage.getItem(claveIdioma);
    sessionStorage.removeItem(claveIdioma);

    if (borrador !== null) {
        try {
            const datos = JSON.parse(borrador);

            nombreUsuario.value = datos.nombre;
            correoUsuario.value = datos.correo;
            rolUsuario.value = datos.rol;
            activoUsuario.checked = datos.activo;
            administradorUsuario.checked = datos.administrador;
        } catch (error) {
            sessionStorage.removeItem(claveIdioma);
        }
    }

    if (formularioIdioma) {
        formularioIdioma.addEventListener('submit', function () {
            const datos = {
                nombre: nombreUsuario.value,
                correo: correoUsuario.value,
                rol: rolUsuario.value,
                activo: activoUsuario.checked,
                administrador: administradorUsuario.checked
            };

            sessionStorage.setItem(claveIdioma, JSON.stringify(datos));
        });
    }
</script>

</body>
</html>