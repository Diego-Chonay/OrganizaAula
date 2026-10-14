<?php

namespace App\Controllers;

use App\Libraries\PuenteVistaAula;
use App\Models\DatosUsuarioAula;

class UsuariosEscolaresAula extends BaseController
{
    public function listarUsuarios()
    {
        if (session()->get('autenticado') !== true) {
            return redirect()->to('/ingreso');
        }

        $modeloUsuarios = new DatosUsuarioAula();

        $administrador = $modeloUsuarios->find(
            (int) session()->get('usuario_id')
        );

        if (
            !$administrador ||
            (int) $administrador['active'] !== 1 ||
            (int) $administrador['administrator'] !== 1
        ) {
            return $this->response
                ->setStatusCode(403)
                ->setBody('No tienes permiso para administrar usuarios.');
        }

        $orden = $this->request->getGet('orden') === 'antiguos'
            ? 'antiguos'
            : 'nuevos';

        $direccion = $orden === 'antiguos' ? 'ASC' : 'DESC';

        $usuarios = $modeloUsuarios
            ->orderBy('created_at', $direccion)
            ->orderBy('id', $direccion)
            ->findAll();

        $puenteVista = new PuenteVistaAula();

        return $puenteVista->mostrar('usuarios.lista', [
            'titulo' => 'Administración de usuarios',
            'usuarios' => $usuarios
        ]);
    }

    public function verUsuario(int $id)
{
    if (session()->get('autenticado') !== true) {
        return redirect()->to('/ingreso');
    }

    $modeloUsuarios = new DatosUsuarioAula();

    $administrador = $modeloUsuarios->find(
        (int) session()->get('usuario_id')
    );

    if (
        !$administrador ||
        (int) $administrador['active'] !== 1 ||
        (int) $administrador['administrator'] !== 1
    ) {
        return $this->response
            ->setStatusCode(403)
            ->setBody('No tienes permiso para administrar usuarios.');
    }

    $usuario = $modeloUsuarios->find($id);

    if (!$usuario) {
        return $this->response
            ->setStatusCode(404)
            ->setBody('El usuario solicitado no existe.');
    }

    $puenteVista = new PuenteVistaAula();

    return $puenteVista->mostrar('usuarios.detalle', [
        'titulo' => 'Información del usuario',
        'usuario' => $usuario
    ]);
}

    public function formularioNuevoUsuario()
{
    if (session()->get('autenticado') !== true) {
        return redirect()->to('/ingreso');
    }

    $modeloUsuarios = new DatosUsuarioAula();

    $administrador = $modeloUsuarios->find(
        (int) session()->get('usuario_id')
    );

    if (
        !$administrador ||
        (int) $administrador['active'] !== 1 ||
        (int) $administrador['administrator'] !== 1
    ) {
        return $this->response
            ->setStatusCode(403)
            ->setBody('No tienes permiso para crear usuarios.');
    }

    $puenteVista = new PuenteVistaAula();

    return $puenteVista->mostrar('usuarios.nuevo', [
        'titulo' => 'Nuevo usuario'
    ]);
}

    public function guardarNuevoUsuario()
{
    if (session()->get('autenticado') !== true) {
        return redirect()->to('/ingreso');
    }

    $modeloUsuarios = new DatosUsuarioAula();

    $administrador = $modeloUsuarios->find(
        (int) session()->get('usuario_id')
    );

    if (
        !$administrador ||
        (int) $administrador['active'] !== 1 ||
        (int) $administrador['administrator'] !== 1
    ) {
        return $this->response
            ->setStatusCode(403)
            ->setBody('No tienes permiso para crear usuarios.');
    }

    $nombre = trim((string) $this->request->getPost('name'));
    $correo = strtolower(trim((string) $this->request->getPost('email')));
    $contrasena = (string) $this->request->getPost('password');
    $repetirContrasena = (string) $this->request->getPost('password_repeat');
    $rolAcademico = (string) $this->request->getPost('academic_role');

    $datos = [
        'name' => $nombre,
        'email' => $correo,
        'password' => $contrasena,
        'password_repeat' => $repetirContrasena,
        'academic_role' => $rolAcademico
    ];

    $reglas = [
        'name' => 'required|min_length[3]|max_length[100]',
        'email' => 'required|valid_email|max_length[150]|is_unique[users.email]',
        'password' => 'required|min_length[8]',
        'password_repeat' => 'required|matches[password]',
        'academic_role' => 'required|in_list[student,teacher]'
    ];

    $puenteVista = new PuenteVistaAula();

    if (!$this->validateData($datos, $reglas)) {
        return $puenteVista->mostrar('usuarios.nuevo', [
            'titulo' => 'Nuevo usuario',
            'errores' => $this->validator->getErrors()
        ]);
    }

    $activo = $this->request->getPost('active') === '1' ? 1 : 0;
    $esAdministrador = $this->request->getPost('administrator') === '1' ? 1 : 0;

    $resultado = $modeloUsuarios->insert([
        'name' => $nombre,
        'email' => $correo,
        'password' => password_hash($contrasena, PASSWORD_DEFAULT),
        'academic_role' => $rolAcademico,
        'active' => $activo,
        'administrator' => $esAdministrador
    ]);

    if ($resultado === false) {
        return $puenteVista->mostrar('usuarios.nuevo', [
            'titulo' => 'Nuevo usuario',
            'errores' => ['No fue posible guardar el usuario.']
        ]);
    }

    return redirect()
        ->to('/usuarios')
        ->with('mensajeUsuarios', 'El usuario se registró correctamente.');
}

public function formularioEditarUsuario(int $id)
{
    if (session()->get('autenticado') !== true) {
        return redirect()->to('/ingreso');
    }

    $modeloUsuarios = new DatosUsuarioAula();

    $administrador = $modeloUsuarios->find(
        (int) session()->get('usuario_id')
    );

    if (
        !$administrador ||
        (int) $administrador['active'] !== 1 ||
        (int) $administrador['administrator'] !== 1
    ) {
        return $this->response
            ->setStatusCode(403)
            ->setBody('No tienes permiso para editar usuarios.');
    }

    $usuario = $modeloUsuarios->find($id);

    if (!$usuario) {
        return $this->response
            ->setStatusCode(404)
            ->setBody('El usuario solicitado no existe.');
    }

    $puenteVista = new PuenteVistaAula();

    return $puenteVista->mostrar('usuarios.editar', [
        'titulo' => 'Editar usuario',
        'usuario' => $usuario
    ]);
}

public function guardarEdicionUsuario(int $id)
{
    if (session()->get('autenticado') !== true) {
        return redirect()->to('/ingreso');
    }

    $modeloUsuarios = new DatosUsuarioAula();

    $administrador = $modeloUsuarios->find(
        (int) session()->get('usuario_id')
    );

    if (
        !$administrador ||
        (int) $administrador['active'] !== 1 ||
        (int) $administrador['administrator'] !== 1
    ) {
        return $this->response
            ->setStatusCode(403)
            ->setBody('No tienes permiso para editar usuarios.');
    }

    $usuario = $modeloUsuarios->find($id);

    if (!$usuario) {
        return $this->response
            ->setStatusCode(404)
            ->setBody('El usuario solicitado no existe.');
    }

    $nombre = trim((string) $this->request->getPost('name'));
    $correo = strtolower(trim((string) $this->request->getPost('email')));
    $contrasena = (string) $this->request->getPost('password');
    $repetirContrasena = (string) $this->request->getPost('password_repeat');
    $rolAcademico = (string) $this->request->getPost('academic_role');

    $activo = $this->request->getPost('active') === '1' ? 1 : 0;
    $esAdministrador = $this->request->getPost('administrator') === '1' ? 1 : 0;

    $datosEntrada = [
        'name' => $nombre,
        'email' => $correo,
        'academic_role' => $rolAcademico
    ];

    $reglas = [
        'name' => 'required|min_length[3]|max_length[100]',
        'email' => 'required|valid_email|max_length[150]',
        'academic_role' => 'required|in_list[student,teacher]'
    ];

    if ($contrasena !== '' || $repetirContrasena !== '') {
        $datosEntrada['password'] = $contrasena;
        $datosEntrada['password_repeat'] = $repetirContrasena;

        $reglas['password'] = 'required|min_length[8]';
        $reglas['password_repeat'] = 'required|matches[password]';
    }

    $errores = [];

    if (!$this->validateData($datosEntrada, $reglas)) {
        $errores = array_values($this->validator->getErrors());
    }

    $correoExistente = $modeloUsuarios
        ->where('email', $correo)
        ->where('id !=', $id)
        ->first();

    if ($correoExistente) {
        $errores[] = 'El correo electrónico ya está registrado.';
    }

    if (
        $id === (int) $administrador['id'] &&
        ($activo !== 1 || $esAdministrador !== 1)
    ) {
        $errores[] = 'No puedes desactivar tu propia cuenta ni quitarte los permisos de administrador.';
    }

    $usuario['name'] = $nombre;
    $usuario['email'] = $correo;
    $usuario['academic_role'] = $rolAcademico;
    $usuario['active'] = $activo;
    $usuario['administrator'] = $esAdministrador;

    $puenteVista = new PuenteVistaAula();

    if (!empty($errores)) {
        return $puenteVista->mostrar('usuarios.editar', [
            'titulo' => 'Editar usuario',
            'usuario' => $usuario,
            'errores' => $errores
        ]);
    }

    $cambios = [
        'name' => $nombre,
        'email' => $correo,
        'academic_role' => $rolAcademico,
        'active' => $activo,
        'administrator' => $esAdministrador
    ];

    if ($contrasena !== '') {
        $cambios['password'] = password_hash($contrasena, PASSWORD_DEFAULT);
        $cambios['remember_token_hash'] = null;
        $cambios['remember_expires_at'] = null;
    }

    $resultado = $modeloUsuarios->update($id, $cambios);

    if ($resultado === false) {
        return $puenteVista->mostrar('usuarios.editar', [
            'titulo' => 'Editar usuario',
            'usuario' => $usuario,
            'errores' => ['No fue posible actualizar el usuario.']
        ]);
    }

    if ($id === (int) $administrador['id']) {
        session()->set([
            'usuario_nombre' => $nombre,
            'rol_academico' => $rolAcademico,
            'es_administrador' => true
        ]);
    }

    return redirect()
        ->to('/usuarios/' . $id)
        ->with('mensajeUsuarios', 'Los datos del usuario se actualizaron correctamente.');
}

public function desactivarUsuario(int $id)
{
    if (session()->get('autenticado') !== true) {
        return redirect()->to('/ingreso');
    }

    $modeloUsuarios = new DatosUsuarioAula();

    $administrador = $modeloUsuarios->find(
        (int) session()->get('usuario_id')
    );

    if (
        !$administrador ||
        (int) $administrador['active'] !== 1 ||
        (int) $administrador['administrator'] !== 1
    ) {
        return $this->response
            ->setStatusCode(403)
            ->setBody('No tienes permiso para desactivar usuarios.');
    }

    if ($id === (int) $administrador['id']) {
        return redirect()
            ->to('/usuarios/' . $id)
            ->with('mensajeUsuarios', 'No puedes desactivar tu propia cuenta.');
    }

    $usuario = $modeloUsuarios->find($id);

    if (!$usuario) {
        return $this->response
            ->setStatusCode(404)
            ->setBody('El usuario solicitado no existe.');
    }

    if ((int) $usuario['active'] === 0) {
        return redirect()
            ->to('/usuarios/' . $id)
            ->with('mensajeUsuarios', 'Este usuario ya se encuentra inactivo.');
    }

    $resultado = $modeloUsuarios->update($id, [
        'active' => 0,
        'remember_token_hash' => null,
        'remember_expires_at' => null
    ]);

    if ($resultado === false) {
        return redirect()
            ->to('/usuarios/' . $id)
            ->with('mensajeUsuarios', 'No fue posible desactivar el usuario.');
    }

    return redirect()
        ->to('/usuarios/' . $id)
        ->with('mensajeUsuarios', 'El usuario se desactivó correctamente.');
}

}