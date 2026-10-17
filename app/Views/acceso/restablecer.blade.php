<!DOCTYPE html>
<html lang="{{ service('language')->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ lang('InterfazAula.tituloRestablecimiento') }}</title>
    @include('componentes.estilos')
</head>
<body>

    @include('componentes.navegacion')

    <h1>{{ lang('InterfazAula.tituloRestablecimiento') }}</h1>

    @if (!empty($errores))
        <ul>
            @foreach ($errores as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="/recuperar/actualizar" method="post">

        {!! csrf_field() !!}

        <input type="hidden" name="llave" value="{{ $llave }}">

        <label for="password">{{ lang('InterfazAula.nuevaContrasena') }}:</label>
        <input type="password" id="password" name="password" required>

        <br><br>

        <label for="password_repeat">{{ lang('InterfazAula.repetirContrasena') }}:</label>
        <input type="password" id="password_repeat" name="password_repeat" required>

        <br><br>

        <button type="submit">{{ lang('InterfazAula.restablecerContrasena') }}</button>

    </form>

</body>
</html>
