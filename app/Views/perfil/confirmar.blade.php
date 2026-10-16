<!DOCTYPE html>
<html lang="{{ service('language')->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ lang('InterfazAula.confirmarPerfil') }}</title>
</head>
<body>

    @include('componentes.navegacion')

    <h1>{{ lang('InterfazAula.confirmarPerfil') }}</h1>

    <p>{{ lang('InterfazAula.indicacionConfirmacion') }}</p>

    @if (session('errorPerfil'))
        <p>{{ session('errorPerfil') }}</p>
    @endif

    <form action="/perfil/confirmar" method="post">

        {!! csrf_field() !!}

        <label for="password">{{ lang('InterfazAula.contrasenaActual') }}:</label>
        <input type="password" id="password" name="password" required>

        <br><br>

        <button type="submit">{{ lang('InterfazAula.continuar') }}</button>

        <a href="/perfil">{{ lang('InterfazAula.cancelar') }}</a>

    </form>

</body>
</html>