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

}