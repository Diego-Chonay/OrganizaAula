<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }}</title>
</head>
<body>

    @include('componentes.navegacion')

    <h1>{{ $titulo }}</h1>

    <form action="/perfil/imagen/guardar" method="post" enctype="multipart/form-data">

        {!! csrf_field() !!}

        <label for="imagen">Seleccionar fotografía:</label>
        <input type="file" id="imagen" name="imagen" accept="image/jpeg,image/png,image/webp" required>

        <p>Formatos permitidos: JPG, PNG y WEBP. Tamaño máximo: 2 MB.</p>

        <button type="submit">Guardar imagen</button>

        <a href="/perfil">Cancelar</a>

    </form>

</body>
</html>
