<!DOCTYPE html>
<html lang="{{ service('language')->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ lang('InterfazAula.informacionTarea') }}</title>
    @include('componentes.estilos')
</head>
<body>

    @include('componentes.navegacion')

    <h1>{{ lang('InterfazAula.informacionTarea') }}</h1>

    @if (session('mensajeTareas'))
        <p>{{ session('mensajeTareas') }}</p>
    @endif

    <p><strong>ID:</strong> {{ $tarea['id'] }}</p>

    <p>
        <strong>{{ lang('InterfazAula.descripcion') }}:</strong>
        {{ $tarea['description'] }}
    </p>

    <p>
        <strong>{{ lang('InterfazAula.fechaCreacion') }}:</strong>
        {{ $tarea['created_at'] }}
    </p>

    <p>
        <strong>{{ lang('InterfazAula.ultimaModificacion') }}:</strong>
        {{ $tarea['updated_at'] }}
    </p>

    @if ($esProfesor)

        <a href="/tareas/{{ $tarea['id'] }}/editar">
            {{ lang('InterfazAula.editarTarea') }}
        </a>

        <br><br>

        <form action="/tareas/{{ $tarea['id'] }}/eliminar" method="post"
              onsubmit="return confirm('{{ lang('InterfazAula.confirmarEliminarTarea') }}');">

            {!! csrf_field() !!}

            <button type="submit">
                {{ lang('InterfazAula.eliminarTarea') }}
            </button>

        </form>

        <br>

    @endif

   <a href="/tareas">{{ lang('InterfazAula.volverListaTareas') }}</a>

</body>
</html>