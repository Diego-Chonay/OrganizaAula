<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }}</title>
</head>
<body>

    <h1>{{ $titulo }}</h1>

    @if (session('mensajeTareas'))
    <p>{{ session('mensajeTareas') }}</p>
@endif

@if ($esProfesor)
    <p>
        <a href="/tareas/nueva">Nueva tarea</a>
    </p>
@endif

    <a href="/">Inicio</a>

    <p>
        <a href="/tareas?orden=nuevos">Newer</a>
        |
        <a href="/tareas?orden=antiguos">Older</a>

        <p>

        <label for="buscarTareas">Buscar tarea:</label>
        <input
            type="search"
            id="buscarTareas"
            placeholder="Escribe una palabra clave"
    >
    </p>

    </p>

    @if (empty($tareas))
        <p>Todavía no hay tareas registradas.</p>
    @else

        <table id="tablaTareas" border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Fecha de creación</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tareas as $tarea)
                    <tr>
                        <td>
                        <a href="/tareas/{{ $tarea['id'] }}">
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
    No se encontraron tareas con esa palabra.
</p>

<script>
    const buscarTareas = document.getElementById('buscarTareas');
    const filasTareas = document.querySelectorAll('#tablaTareas tbody tr');
    const mensajeBusqueda = document.getElementById('mensajeBusqueda');

    buscarTareas.addEventListener('input', function () {
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
    });
</script>

</body>
</html>