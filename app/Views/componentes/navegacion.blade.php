<nav>
    <a href="/">Inicio</a>

    @if (session()->get('autenticado') === true)

        | <a href="/perfil">Mi perfil</a>
        | <a href="/tareas">Tareas</a>

        @if ((int) session()->get('es_administrador') === 1)
            | <a href="/usuarios">Usuarios</a>
        @endif

        | Hola, {{ session()->get('usuario_nombre') }}

        <form action="/salir" method="post" style="display: inline;">
            {!! csrf_field() !!}
            <button type="submit">Cerrar sesión</button>
        </form>

    @else

        | <a href="/ingreso">Iniciar sesión</a>
        | <a href="/registro">Registrarse</a>

    @endif
</nav>