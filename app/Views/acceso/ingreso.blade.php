<!DOCTYPE html>
<html lang="{{ service('language')->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ lang('InterfazAula.iniciarSesion') }}</title>
</head>
<body>

    @include('componentes.navegacion')

    <h1>{{ lang('InterfazAula.iniciarSesion') }}</h1>

    @if (session('mensajeRestablecimiento'))
        <p>{{ session('mensajeRestablecimiento') }}</p>
    @endif

    @if (session('mensajeActivacion'))
        <p>{{ session('mensajeActivacion') }}</p>
    @endif

    @if (session('errorActivacion'))
        <p>{{ session('errorActivacion') }}</p>
    @endif

    @if (!empty($errores))
        <ul>
            @foreach ($errores as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="/ingreso/validar" method="post">

        {!! csrf_field() !!}

        <label for="email">{{ lang('InterfazAula.correoElectronico') }}:</label>
       <input type="email" id="email" name="email" value="{{ $correoIngreso ?? '' }}" required>

        <br><br>

        <label for="password">{{ lang('InterfazAula.contrasena') }}:</label>
        <input type="password" id="password" name="password" required>

        <br><br>

        <label>
            <input type="checkbox" name="remember" value="1" {{ !empty($recordarIngreso) ? 'checked' : '' }}>
            {{ lang('InterfazAula.recordarme') }}
        </label>

        <br><br>

        <a href="/recuperar">{{ lang('InterfazAula.olvidasteContrasena') }}</a>

        <br><br>

        <button type="submit">{{ lang('InterfazAula.iniciarSesion') }}</button>

    </form>

    <script>
    const correoIngreso = document.getElementById('email');
    const recordarIngreso = document.querySelector('input[name="remember"]');
    const formularioIngreso = correoIngreso.form;

    const claveCorreoIngreso = 'correoIngresoAula';
    const claveRecordarIngreso = 'recordarIngresoAula';

    if (correoIngreso.value === '') {
        correoIngreso.value = sessionStorage.getItem(claveCorreoIngreso) || '';
    }

    if (!recordarIngreso.checked) {
        recordarIngreso.checked =
            sessionStorage.getItem(claveRecordarIngreso) === '1';
    }

    function conservarIngreso() {
        sessionStorage.setItem(claveCorreoIngreso, correoIngreso.value);
        sessionStorage.setItem(
            claveRecordarIngreso,
            recordarIngreso.checked ? '1' : '0'
        );
    }

    conservarIngreso();

    correoIngreso.addEventListener('input', conservarIngreso);
    recordarIngreso.addEventListener('change', conservarIngreso);

    formularioIngreso.addEventListener('submit', function () {
        sessionStorage.removeItem(claveCorreoIngreso);
        sessionStorage.removeItem(claveRecordarIngreso);
    });
</script>

</body>
</html>