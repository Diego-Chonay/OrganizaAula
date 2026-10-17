<!DOCTYPE html>
<html lang="{{ service('language')->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ lang('InterfazAula.editarPerfil') }}</title>
    @include('componentes.estilos')
</head>
<body>

    @include('componentes.navegacion')

    <h1>{{ lang('InterfazAula.editarPerfil') }}</h1>

    @if (!empty($errores))
        <ul>
            @foreach ($errores as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="/perfil/guardar" method="post">

        {!! csrf_field() !!}

        <label for="name">{{ lang('InterfazAula.nombre') }}:</label>
        <input type="text" id="name" name="name" value="{{ $nombre }}" required>

        <br><br>

        <label for="email">{{ lang('InterfazAula.correoElectronico') }}:</label>
        <input type="email" id="email" name="email" value="{{ $correo }}" required>

        <br><br>

        <button type="submit">{{ lang('InterfazAula.guardarCambios') }}</button>

        <a href="/perfil">{{ lang('InterfazAula.cancelar') }}</a>

    </form>

    <script>
    const nombrePerfil = document.getElementById('name');
    const correoPerfil = document.getElementById('email');
    const formularioPerfil = nombrePerfil.form;

    const clavePerfil = 'borradorPerfilAula:' + {{ (int) session('usuario_id') }};

    const borradorGuardado = sessionStorage.getItem(clavePerfil);

    if (borradorGuardado !== null) {
        const datosGuardados = JSON.parse(borradorGuardado);

        nombrePerfil.value = datosGuardados.nombre;
        correoPerfil.value = datosGuardados.correo;
    }

    function conservarDatosPerfil() {
        const datosPerfil = {
            nombre: nombrePerfil.value,
            correo: correoPerfil.value
        };

        sessionStorage.setItem(clavePerfil, JSON.stringify(datosPerfil));
    }

    nombrePerfil.addEventListener('input', conservarDatosPerfil);
    correoPerfil.addEventListener('input', conservarDatosPerfil);

    formularioPerfil.addEventListener('submit', conservarDatosPerfil);

    const cancelarPerfil = formularioPerfil.querySelector('a[href="/perfil"]');

    if (cancelarPerfil) {
        cancelarPerfil.addEventListener('click', function () {
            sessionStorage.removeItem(clavePerfil);
        });
    }
</script>

</body>
</html>