<!DOCTYPE html>
<html lang="{{ service('language')->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ lang('InterfazAula.nuevaTarea') }}</title>
</head>
<body>

    @include('componentes.navegacion')

    <h1>{{ lang('InterfazAula.nuevaTarea') }}</h1>

    @if (!empty($errores))
        <ul>
            @foreach ($errores as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="/tareas/guardar" method="post">

        {!! csrf_field() !!}

        <label for="description">{{ lang('InterfazAula.descripcion') }}:</label>

        <br>

        <textarea id="description" name="description" rows="4" cols="45" maxlength="255" required>{{ $descripcion ?? '' }}</textarea>

        <br><br>

        <button type="submit">{{ lang('InterfazAula.guardarTarea') }}</button>

        <a href="/tareas">{{ lang('InterfazAula.cancelar') }}</a>

    </form>

<script>
    const descripcionTarea = document.querySelector('textarea[name="description"]');
    const formularioTarea = descripcionTarea.form;

    const rutaTarea = window.location.pathname
        .replace(/^\/index\.php(?=\/|$)/, '')
        .replace(/\/$/, '');

    const claveBorrador = 'borradorTareaAula:' + rutaTarea;
    const borradorGuardado = sessionStorage.getItem(claveBorrador);

    if (borradorGuardado !== null) {
        descripcionTarea.value = borradorGuardado;
    }

    descripcionTarea.addEventListener('input', function () {
        sessionStorage.setItem(claveBorrador, descripcionTarea.value);
    });

    formularioTarea.addEventListener('submit', function () {
        sessionStorage.removeItem(claveBorrador);
    });

    const cancelarTarea = formularioTarea.querySelector('a');

    if (cancelarTarea) {
        cancelarTarea.addEventListener('click', function () {
            sessionStorage.removeItem(claveBorrador);
        });
    }
</script>

</body>
</html>