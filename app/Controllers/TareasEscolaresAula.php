<?php

namespace App\Controllers;

use App\Libraries\PuenteVistaAula;
use App\Models\DatosTareaAula;
use App\Models\DatosUsuarioAula;

class TareasEscolaresAula extends BaseController
{
    public function listarTareas()
    {
        if (session()->get('autenticado') !== true) {
            return redirect()->to('/ingreso');
        }

        $modeloUsuarios = new DatosUsuarioAula();

        $usuario = $modeloUsuarios->find(
            (int) session()->get('usuario_id')
        );

        if (!$usuario || (int) $usuario['active'] !== 1) {
            session()->destroy();

            return redirect()->to('/ingreso');
        }

        $orden = $this->request->getGet('orden') === 'antiguos'
            ? 'antiguos'
            : 'nuevos';

        $direccion = $orden === 'antiguos' ? 'ASC' : 'DESC';

        $modeloTareas = new DatosTareaAula();

        $tareas = $modeloTareas
            ->orderBy('created_at', $direccion)
            ->orderBy('id', $direccion)
            ->findAll();

        $puenteVista = new PuenteVistaAula();

        return $puenteVista->mostrar('tareas.lista', [
            'titulo' => lang('InterfazAula.tituloTareas'),
            'tareas' => $tareas,
            'esProfesor' => $usuario['academic_role'] === 'teacher'
        ]);
    }

    public function formularioNuevaTarea()
{
    if (session()->get('autenticado') !== true) {
        return redirect()->to('/ingreso');
    }

    $modeloUsuarios = new DatosUsuarioAula();

    $usuario = $modeloUsuarios->find(
        (int) session()->get('usuario_id')
    );

    if (!$usuario || (int) $usuario['active'] !== 1) {
        session()->destroy();
        return redirect()->to('/ingreso');
    }

    if ($usuario['academic_role'] !== 'teacher') {
        return $this->response
            ->setStatusCode(403)
            ->setBody(lang('InterfazAula.permisoCrearTarea'));
    }

    $puenteVista = new PuenteVistaAula();

    return $puenteVista->mostrar('tareas.nueva', [
    'titulo' => lang('InterfazAula.nuevaTarea'),
    'descripcion' => session('descripcion') ?? '',
    'errores' => session('errores') ?? []
]);

}

    public function guardarNuevaTarea()
{
    if (session()->get('autenticado') !== true) {
        return redirect()->to('/ingreso');
    }

    $modeloUsuarios = new DatosUsuarioAula();

    $usuario = $modeloUsuarios->find(
        (int) session()->get('usuario_id')
    );

    if (!$usuario || (int) $usuario['active'] !== 1) {
        session()->destroy();
        return redirect()->to('/ingreso');
    }

    if ($usuario['academic_role'] !== 'teacher') {
        return $this->response
            ->setStatusCode(403)
            ->setBody(lang('InterfazAula.permisoCrearTarea'));
    }

    $descripcion = trim((string) $this->request->getPost('description'));

    $reglas = [
        'description' => 'required|max_length[255]'
    ];

    $mensajes = [
    'description' => [
        'required' => lang('InterfazAula.descripcionObligatoria'),
        'max_length' => lang('InterfazAula.descripcionMuyLarga')
    ]
];

    $puenteVista = new PuenteVistaAula();

    if (!$this->validateData(['description' => $descripcion], $reglas, $mensajes)) {
    return redirect()
        ->to('/tareas/nueva')
        ->with('descripcion', $descripcion)
        ->with('errores', $this->validator->getErrors());
}

    $modeloTareas = new DatosTareaAula();

    $resultado = $modeloTareas->insert([
        'creator_id' => (int) $usuario['id'],
        'description' => $descripcion
    ]);

    if ($resultado === false) {
    return redirect()
        ->to('/tareas/nueva')
        ->with('descripcion', $descripcion)
        ->with('errores', [lang('InterfazAula.errorGuardarTarea')]);
}

    return redirect()
        ->to('/tareas')
        ->with('mensajeTareas', lang('InterfazAula.tareaRegistrada'));
}

    public function verTarea(int $id)
{
    if (session()->get('autenticado') !== true) {
        return redirect()->to('/ingreso');
    }

    $modeloUsuarios = new DatosUsuarioAula();

    $usuario = $modeloUsuarios->find(
        (int) session()->get('usuario_id')
    );

    if (!$usuario || (int) $usuario['active'] !== 1) {
        session()->destroy();

        return redirect()->to('/ingreso');
    }

    $modeloTareas = new DatosTareaAula();

    $tarea = $modeloTareas->find($id);

    if (!$tarea) {
        return $this->response
            ->setStatusCode(404)
            ->setBody(lang('InterfazAula.tareaNoExiste'));
    }

    $puenteVista = new PuenteVistaAula();

    return $puenteVista->mostrar('tareas.detalle', [
    'titulo' => lang('InterfazAula.informacionTarea'),
    'tarea' => $tarea,
    'esProfesor' => $usuario['academic_role'] === 'teacher'
]);
}

    public function formularioEditarTarea(int $id)
{
    if (session()->get('autenticado') !== true) {
        return redirect()->to('/ingreso');
    }

    $modeloUsuarios = new DatosUsuarioAula();

    $usuario = $modeloUsuarios->find(
        (int) session()->get('usuario_id')
    );

    if (!$usuario || (int) $usuario['active'] !== 1) {
        session()->destroy();

        return redirect()->to('/ingreso');
    }

    if ($usuario['academic_role'] !== 'teacher') {
        return $this->response
            ->setStatusCode(403)
            ->setBody(lang('InterfazAula.permisoEditarTarea'));
    }

    $modeloTareas = new DatosTareaAula();

    $tarea = $modeloTareas->find($id);

    if (!$tarea) {
        return $this->response
            ->setStatusCode(404)
            ->setBody(lang('InterfazAula.tareaNoExiste'));
    }

    $descripcionTemporal = session('descripcionEdicion');

if ($descripcionTemporal !== null) {
    $tarea['description'] = $descripcionTemporal;
}

$puenteVista = new PuenteVistaAula();

return $puenteVista->mostrar('tareas.editar', [
    'titulo' => lang('InterfazAula.editarTarea'),
    'tarea' => $tarea,
    'errores' => session('erroresEdicion') ?? []
]);
}

    public function guardarEdicionTarea(int $id)
{
    if (session()->get('autenticado') !== true) {
        return redirect()->to('/ingreso');
    }

    $modeloUsuarios = new DatosUsuarioAula();

    $usuario = $modeloUsuarios->find(
        (int) session()->get('usuario_id')
    );

    if (!$usuario || (int) $usuario['active'] !== 1) {
        session()->destroy();
        return redirect()->to('/ingreso');
    }

    if ($usuario['academic_role'] !== 'teacher') {
        return $this->response
            ->setStatusCode(403)
            ->setBody(lang('InterfazAula.permisoEditarTarea'));
    }

    $modeloTareas = new DatosTareaAula();

    $tarea = $modeloTareas->find($id);

    if (!$tarea) {
        return $this->response
            ->setStatusCode(404)
            ->setBody(lang('InterfazAula.tareaNoExiste'));
    }

    $descripcion = trim((string) $this->request->getPost('description'));

    $puenteVista = new PuenteVistaAula();

    $reglas = [
        'description' => 'required|max_length[255]'
    ];

    $mensajes = [
    'description' => [
        'required' => lang('InterfazAula.descripcionObligatoria'),
        'max_length' => lang('InterfazAula.descripcionMuyLarga')
    ]
];

    if (!$this->validateData(['description' => $descripcion], $reglas, $mensajes)) {
    return redirect()
        ->to('/tareas/' . $id . '/editar')
        ->with('descripcionEdicion', $descripcion)
        ->with('erroresEdicion', $this->validator->getErrors());
}

    $resultado = $modeloTareas->update($id, [
        'description' => $descripcion
    ]);

    if ($resultado === false) {
    return redirect()
        ->to('/tareas/' . $id . '/editar')
        ->with('descripcionEdicion', $descripcion)
        ->with('erroresEdicion', [lang('InterfazAula.errorActualizarTarea')]);
}

    return redirect()
        ->to('/tareas/' . $id)
        ->with('mensajeTareas', lang('InterfazAula.tareaActualizada'));
}

    public function eliminarTarea(int $id)
{
    if (session()->get('autenticado') !== true) {
        return redirect()->to('/ingreso');
    }

    $modeloUsuarios = new DatosUsuarioAula();

    $usuario = $modeloUsuarios->find(
        (int) session()->get('usuario_id')
    );

    if (!$usuario || (int) $usuario['active'] !== 1) {
        session()->destroy();

        return redirect()->to('/ingreso');
    }

    if ($usuario['academic_role'] !== 'teacher') {
        return $this->response
            ->setStatusCode(403)
            ->setBody(lang('InterfazAula.permisoEliminarTarea'));
    }

    $modeloTareas = new DatosTareaAula();

    $tarea = $modeloTareas->find($id);

    if (!$tarea) {
        return $this->response
            ->setStatusCode(404)
            ->setBody(lang('InterfazAula.tareaNoExiste'));
    }

    $resultado = $modeloTareas->delete($id);

    if ($resultado === false) {
        return redirect()
            ->to('/tareas/' . $id)
            ->with('mensajeTareas', lang('InterfazAula.errorEliminarTarea'));
    }

    return redirect()
        ->to('/tareas')
        ->with('mensajeTareas', lang('InterfazAula.tareaEliminada'));
}

}