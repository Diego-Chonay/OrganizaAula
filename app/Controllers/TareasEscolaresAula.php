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
            'titulo' => 'Tareas escolares',
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
            ->setBody('Solo los profesores pueden crear tareas.');
    }

    $puenteVista = new PuenteVistaAula();

    return $puenteVista->mostrar('tareas.nueva', [
        'titulo' => 'Nueva tarea'
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
            ->setBody('Solo los profesores pueden crear tareas.');
    }

    $descripcion = trim((string) $this->request->getPost('description'));

    $reglas = [
        'description' => 'required|max_length[255]'
    ];

    $puenteVista = new PuenteVistaAula();

    if (!$this->validateData(['description' => $descripcion], $reglas)) {
        return $puenteVista->mostrar('tareas.nueva', [
            'titulo' => 'Nueva tarea',
            'descripcion' => $descripcion,
            'errores' => $this->validator->getErrors()
        ]);
    }

    $modeloTareas = new DatosTareaAula();

    $resultado = $modeloTareas->insert([
        'creator_id' => (int) $usuario['id'],
        'description' => $descripcion
    ]);

    if ($resultado === false) {
        return $puenteVista->mostrar('tareas.nueva', [
            'titulo' => 'Nueva tarea',
            'descripcion' => $descripcion,
            'errores' => ['No fue posible guardar la tarea.']
        ]);
    }

    return redirect()
        ->to('/tareas')
        ->with('mensajeTareas', 'La tarea se registró correctamente.');
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
            ->setBody('La tarea solicitada no existe.');
    }

    $puenteVista = new PuenteVistaAula();

    return $puenteVista->mostrar('tareas.detalle', [
    'titulo' => 'Información de la tarea',
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
            ->setBody('Solo los profesores pueden editar tareas.');
    }

    $modeloTareas = new DatosTareaAula();

    $tarea = $modeloTareas->find($id);

    if (!$tarea) {
        return $this->response
            ->setStatusCode(404)
            ->setBody('La tarea solicitada no existe.');
    }

    $puenteVista = new PuenteVistaAula();

    return $puenteVista->mostrar('tareas.editar', [
        'titulo' => 'Editar tarea',
        'tarea' => $tarea
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
            ->setBody('Solo los profesores pueden editar tareas.');
    }

    $modeloTareas = new DatosTareaAula();

    $tarea = $modeloTareas->find($id);

    if (!$tarea) {
        return $this->response
            ->setStatusCode(404)
            ->setBody('La tarea solicitada no existe.');
    }

    $descripcion = trim((string) $this->request->getPost('description'));

    $puenteVista = new PuenteVistaAula();

    $reglas = [
        'description' => 'required|max_length[255]'
    ];

    if (!$this->validateData(['description' => $descripcion], $reglas)) {
        $tarea['description'] = $descripcion;

        return $puenteVista->mostrar('tareas.editar', [
            'titulo' => 'Editar tarea',
            'tarea' => $tarea,
            'errores' => $this->validator->getErrors()
        ]);
    }

    $resultado = $modeloTareas->update($id, [
        'description' => $descripcion
    ]);

    if ($resultado === false) {
        $tarea['description'] = $descripcion;

        return $puenteVista->mostrar('tareas.editar', [
            'titulo' => 'Editar tarea',
            'tarea' => $tarea,
            'errores' => ['No fue posible actualizar la tarea.']
        ]);
    }

    return redirect()
        ->to('/tareas/' . $id)
        ->with('mensajeTareas', 'La tarea se actualizó correctamente.');
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
            ->setBody('Solo los profesores pueden eliminar tareas.');
    }

    $modeloTareas = new DatosTareaAula();

    $tarea = $modeloTareas->find($id);

    if (!$tarea) {
        return $this->response
            ->setStatusCode(404)
            ->setBody('La tarea solicitada no existe.');
    }

    $resultado = $modeloTareas->delete($id);

    if ($resultado === false) {
        return redirect()
            ->to('/tareas/' . $id)
            ->with('mensajeTareas', 'No fue posible eliminar la tarea.');
    }

    return redirect()
        ->to('/tareas')
        ->with('mensajeTareas', 'La tarea se eliminó correctamente.');
}

}