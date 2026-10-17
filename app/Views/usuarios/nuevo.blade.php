<!DOCTYPE html>
<html lang="{{ service('language')->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ lang('InterfazAula.nuevoUsuario') }}</title>
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
        <input type="text" id="name" name="name" required>

        <br><br>

        <label for="email">{{ lang('InterfazAula.correoElectronico') }}:</label>
        <input type="email" id="email" name="email" required>

        <br><br>

        <label for="password">{{ lang('InterfazAula.contrasena') }}:</label>
        <input type="password" id="password" name="password" required>

        <br><br>

        <label for="password_repeat">{{ lang('InterfazAula.repetirContrasena') }}:</label>
        <input type="password" id="password_repeat" name="password_repeat" required>

        <br><br>

        <label for="academic_role">{{ lang('InterfazAula.rolAcademico') }}:</label>
        <select id="academic_role" name="academic_role">
            <option value="student">{{ lang('InterfazAula.estudiante') }}</option>
            <option value="teacher">{{ lang('InterfazAula.profesor') }}</option>
        </select>

        <br><br>

        <label>
            <input type="checkbox" name="active" value="1" checked>
            {{ lang('InterfazAula.usuarioActivo') }}
        </label>

        <br><br>

        <label>
            <input type="checkbox" name="administrator" value="1">
            {{ lang('InterfazAula.administrador') }}
        </label>

        <br><br>

        <button type="submit">{{ lang('InterfazAula.guardarUsuario') }}</button>

        <a href="/usuarios">{{ lang('InterfazAula.cancelar') }}</a>

    </form>

    <script>
    const formularioUsuario = document.querySelector('form[action^="/usuarios/"]');

    const nombreUsuario = formularioUsuario.querySelector('[name="name"]');
    const correoUsuario = formularioUsuario.querySelector('[name="email"]');
    const rolUsuario = formularioUsuario.querySelector('[name="academic_role"]');
    const activoUsuario = formularioUsuario.querySelector('[name="active"]');
    const administradorUsuario = formularioUsuario.querySelector('[name="administrator"]');

    const claveBorrador = 'borradorUsuariosAula:{{ (int) session('usuario_id') }}:' + window.location.pathname;

    const borradorGuardado = sessionStorage.getItem(claveBorrador);

    if (borradorGuardado !== null) {
        const datosGuardados = JSON.parse(borradorGuardado);

        nombreUsuario.value = datosGuardados.nombre;
        correoUsuario.value = datosGuardados.correo;
        rolUsuario.value = datosGuardados.rol;
        activoUsuario.checked = datosGuardados.activo;
        administradorUsuario.checked = datosGuardados.administrador;
    }

    function conservarDatosUsuario() {
        const datosUsuario = {
            nombre: nombreUsuario.value,
            correo: correoUsuario.value,
            rol: rolUsuario.value,
            activo: activoUsuario.checked,
            administrador: administradorUsuario.checked
        };

        sessionStorage.setItem(claveBorrador, JSON.stringify(datosUsuario));
    }

    nombreUsuario.addEventListener('input', conservarDatosUsuario);
    correoUsuario.addEventListener('input', conservarDatosUsuario);
    rolUsuario.addEventListener('change', conservarDatosUsuario);
    activoUsuario.addEventListener('change', conservarDatosUsuario);
    administradorUsuario.addEventListener('change', conservarDatosUsuario);

    formularioUsuario.addEventListener('submit', function () {
        sessionStorage.removeItem(claveBorrador);
    });

    const cancelarUsuario = formularioUsuario.querySelector('a');

    if (cancelarUsuario) {
        cancelarUsuario.addEventListener('click', function () {
            sessionStorage.removeItem(claveBorrador);
        });
    }
</script>

</body>
</html>