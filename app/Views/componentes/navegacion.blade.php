<nav>
    <a href="/">{{ lang('InterfazAula.inicio') }}</a>

    @if (session()->get('autenticado') === true)

        | <a href="/perfil">{{ lang('InterfazAula.perfil') }}</a>
        | <a href="/tareas">{{ lang('InterfazAula.tareas') }}</a>

        @if ((int) session()->get('es_administrador') === 1)
            | <a href="/usuarios">{{ lang('InterfazAula.usuarios') }}</a>
        @endif

        | {{ lang('InterfazAula.hola') }}, {{ session()->get('usuario_nombre') }}

        <form action="/salir" method="post" style="display: inline;">
            {!! csrf_field() !!}
            <button type="submit">{{ lang('InterfazAula.cerrarSesion') }}</button>
        </form>

    @else

        | <a href="/ingreso">{{ lang('InterfazAula.iniciarSesion') }}</a>
        | <a href="/registro">{{ lang('InterfazAula.registrarse') }}</a>

    @endif

    <form action="/idioma/cambiar" method="post" style="display: inline;">
        {!! csrf_field() !!}

        <button type="submit" name="idioma" value="es">Español</button>
        <button type="submit" name="idioma" value="en">English</button>
    </form>
</nav>