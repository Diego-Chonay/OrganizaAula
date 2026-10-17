<!DOCTYPE html>
<html lang="{{ service('language')->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ lang('InterfazAula.cambiarContrasena') }}</title>
    @include('componentes.estilos')
</head>
<body>

    @include('componentes.navegacion')

    <h1>{{ lang('InterfazAula.cambiarContrasena') }}</h1>

    @if (!empty($errores))
        <ul>
            @foreach ($errores as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="/perfil/contrasena/guardar" method="post">

        {!! csrf_field() !!}

        <label for="password_current">{{ lang('InterfazAula.contrasenaActual') }}:</label>
        <input type="password" id="password_current" name="password_current" required>

        <br><br>

        <label for="password_new">{{ lang('InterfazAula.nuevaContrasena') }}:</label>
        <input type="password" id="password_new" name="password_new" required>

        <br><br>

        <label for="password_repeat">{{ lang('InterfazAula.repetirNuevaContrasena') }}:</label>
        <input type="password" id="password_repeat" name="password_repeat" required>

        <br><br>

        <button type="submit">{{ lang('InterfazAula.guardarContrasena') }}</button>

        <a href="/perfil">{{ lang('InterfazAula.cancelar') }}</a>

    </form>

</body>
</html>