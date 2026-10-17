<!DOCTYPE html>
<html lang="{{ service('language')->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ lang('InterfazAula.tituloTareas') }}</title>
    @include('componentes.estilos')
</head>
<body>

    @include('componentes.navegacion')

    <h1>{{ lang('InterfazAula.tituloTareas') }}</h1>

    @if (session('mensajeTareas'))
        <p>{{ session('mensajeTareas') }}</p>
    @endif

    <div class="acciones-aula">

    @if ($esProfesor)
        <a href="/tareas/nueva" class="btn-aula">
            {{ lang('InterfazAula.nuevaTarea') }}
        </a>
    @endif

    <a href="/tareas?orden=nuevos" class="btn-aula">
        {{ lang('InterfazAula.masRecientes') }}
    </a>

    <a href="/tareas?orden=antiguos" class="btn-aula">
        {{ lang('InterfazAula.masAntiguos') }}
    </a>

</div>
    <p>
        <label for="buscarTareas">{{ lang('InterfazAula.buscarTarea') }}:</label>

        <input
            type="search"
            id="buscarTareas"
            placeholder="{{ lang('InterfazAula.buscarPlaceholder') }}"
        >
    </p>

    @if (empty($tareas))
        <p>{{ lang('InterfazAula.sinTareas') }}</p>
    @else

        <table id="tablaTareas" border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>{{ lang('InterfazAula.descripcion') }}</th>
                    <th>{{ lang('InterfazAula.fechaCreacion') }}</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($tareas as $tarea)
                    <tr>
                        <td>
                            <a href="/tareas/{{ $tarea['id'] }}" class="btn-aula">
    {{ $tarea['description'] }}
</a>
                        </td>

                        <td>{{ $tarea['created_at'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @endif

    <p id="mensajeBusqueda" style="display: none;">
        {{ lang('InterfazAula.sinResultadosTareas') }}
    </p>

    <script>
    const buscarTareas = document.getElementById('buscarTareas');
    const filasTareas = document.querySelectorAll('#tablaTareas tbody tr');
    const mensajeBusqueda = document.getElementById('mensajeBusqueda');

    const claveBusqueda = 'busquedaTareasAula';

    function filtrarTareas() {
        const palabra = buscarTareas.value.toLowerCase().trim();
        let encontradas = 0;

        filasTareas.forEach(function (fila) {
            const descripcion = fila.cells[0].textContent.toLowerCase();

            if (descripcion.includes(palabra)) {
                fila.style.display = '';
                encontradas++;
            } else {
                fila.style.display = 'none';
            }
        });

        mensajeBusqueda.style.display =
            encontradas === 0 && filasTareas.length > 0 ? 'block' : 'none';
    }

    buscarTareas.value = sessionStorage.getItem(claveBusqueda) || '';

    filtrarTareas();

    buscarTareas.addEventListener('input', function () {
        sessionStorage.setItem(claveBusqueda, buscarTareas.value);

        filtrarTareas();
    });
</script>

</body>
</html>