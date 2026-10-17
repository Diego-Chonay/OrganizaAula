<!DOCTYPE html>
<html lang="{{ service('language')->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ lang('InterfazAula.cambiarImagenPerfil') }}</title>
    @include('componentes.estilos')
</head>
<body>

    @include('componentes.navegacion')

    <h1>{{ lang('InterfazAula.cambiarImagenPerfil') }}</h1>

    @if (!empty($errores))
    <ul>
        @foreach ($errores as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

    <form action="/perfil/imagen/guardar" method="post" enctype="multipart/form-data">

        {!! csrf_field() !!}

        <label for="imagen">{{ lang('InterfazAula.seleccionarFotografia') }}:</label>
        <input type="file" id="imagen" name="imagen" accept="image/jpeg,image/png,image/webp" required>

        <p>{{ lang('InterfazAula.formatosImagen') }}</p>

        <button type="submit">{{ lang('InterfazAula.guardarImagen') }}</button>

        <a href="/perfil">{{ lang('InterfazAula.cancelar') }}</a>

    </form>

</body>
</html>