<!DOCTYPE html>
<html lang="{{ service('language')->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ lang('InterfazAula.tituloRegistro') }}</title>
</head>
<body>

    @include('componentes.navegacion')

    <h1>{{ lang('InterfazAula.tituloRegistro') }}</h1>

    @if (session('mensajeRegistro'))
        <p>{{ session('mensajeRegistro') }}</p>
    @endif

    @if (!empty($errores))
        <ul>
            @foreach ($errores as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="/registro/guardar" method="post">

        {!! csrf_field() !!}

        <label for="name">{{ lang('InterfazAula.nombre') }}:</label>
        <input type="text" id="name" name="name" value="{{ $nombreRegistro ?? '' }}">

        <br><br>

        <label for="email">{{ lang('InterfazAula.correoElectronico') }}:</label>
        <input type="email" id="email" name="email" value="{{ $correoRegistro ?? '' }}">

        <br><br>

        <label for="password">{{ lang('InterfazAula.contrasena') }}:</label>
        <input type="password" id="password" name="password">

        <br><br>

        <label for="password_repeat">{{ lang('InterfazAula.repetirContrasena') }}:</label>
        <input type="password" id="password_repeat" name="password_repeat">

        <br><br>

        <button type="submit">{{ lang('InterfazAula.crearCuenta') }}</button>

    </form>

    <script>
    const nombreRegistro = document.getElementById('name');
    const correoRegistro = document.getElementById('email');
    const formularioRegistro = nombreRegistro.form;

    const claveNombre = 'registroNombreAula';
    const claveCorreo = 'registroCorreoAula';

    if (nombreRegistro.value === '') {
        nombreRegistro.value = sessionStorage.getItem(claveNombre) || '';
    }

    if (correoRegistro.value === '') {
        correoRegistro.value = sessionStorage.getItem(claveCorreo) || '';
    }

    function conservarRegistro() {
        sessionStorage.setItem(claveNombre, nombreRegistro.value);
        sessionStorage.setItem(claveCorreo, correoRegistro.value);
    }

    conservarRegistro();

    nombreRegistro.addEventListener('input', conservarRegistro);
    correoRegistro.addEventListener('input', conservarRegistro);

    formularioRegistro.addEventListener('submit', function () {
        sessionStorage.removeItem(claveNombre);
        sessionStorage.removeItem(claveCorreo);
    });
</script>

</body>
</html>