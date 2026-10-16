<!DOCTYPE html>
<html lang="{{ service('language')->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ lang('InterfazAula.tituloRecuperacion') }}</title>
</head>
<body>

    @include('componentes.navegacion')

    <h1>{{ lang('InterfazAula.tituloRecuperacion') }}</h1>

    <p>{{ lang('InterfazAula.indicacionRecuperacion') }}</p>

    @if (!empty($errores))
        <ul>
            @foreach ($errores as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    @if (session('mensajeRecuperacion'))
        <p>{{ session('mensajeRecuperacion') }}</p>
    @endif

    <form action="/recuperar/enviar" method="post">

        {!! csrf_field() !!}

        <label for="email">{{ lang('InterfazAula.correoElectronico') }}:</label>
        <input type="email" id="email" name="email" required>

        <br><br>

        <button type="submit">{{ lang('InterfazAula.enviarRecuperacion') }}</button>

    </form>

    <br>

    <a href="/ingreso">{{ lang('InterfazAula.volverIngreso') }}</a>

</body>
</html>
