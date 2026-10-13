<?php

namespace App\Controllers;

use App\Libraries\PuenteVistaAula;
use App\Models\DatosUsuarioAula;

class SesionEscolarAula extends BaseController
{
    public function formularioIngreso()
    {
        $puenteVista = new PuenteVistaAula();

        $datosVista = [
            'titulo' => 'Iniciar sesión'
        ];

        return $puenteVista->mostrar('acceso.ingreso', $datosVista);
    }

    public function validarIngreso()
    {
        $reglasIngreso = [
            'email' => 'required|valid_email',
            'password' => 'required'
        ];

        $puenteVista = new PuenteVistaAula();

        if (!$this->validate($reglasIngreso)) {
            return $puenteVista->mostrar('acceso.ingreso', [
                'titulo' => 'Iniciar sesión',
                'errores' => $this->validator->getErrors()
            ]);
        }

        $correo = strtolower(trim((string) $this->request->getPost('email')));
        $contrasena = (string) $this->request->getPost('password');

        $modeloUsuarios = new DatosUsuarioAula();

        $usuario = $modeloUsuarios
            ->where('email', $correo)
            ->first();

        if (
            !$usuario ||
            !password_verify($contrasena, $usuario['password']) ||
            (int) $usuario['active'] !== 1
        ) {
            return $puenteVista->mostrar('acceso.ingreso', [
                'titulo' => 'Iniciar sesión',
                'errores' => [
                    'Correo o contraseña incorrectos, o cuenta pendiente de activación.'
                ]
            ]);
        }

        session()->regenerate(true);

        session()->set([
            'usuario_id' => $usuario['id'],
            'usuario_nombre' => $usuario['name'],
            'rol_academico' => $usuario['academic_role'],
            'es_administrador' => $usuario['administrator'],
            'autenticado' => true
        ]);

                $respuesta = redirect()->to('/');

        if ($this->request->getPost('remember') === '1') {
            $claveRecuerdo = bin2hex(random_bytes(32));
            $duracionRecuerdo = 7 * 24 * 60 * 60;

            $actualizado = $modeloUsuarios->update($usuario['id'], [
                'remember_token_hash' => hash('sha256', $claveRecuerdo),
                'remember_expires_at' => date(
                    'Y-m-d H:i:s',
                    time() + $duracionRecuerdo
                )
            ]);

            if ($actualizado) {
                $respuesta->setCookie([
                    'name' => 'recuerdo_aula',
                    'value' => $usuario['id'] . ':' . $claveRecuerdo,
                    'expire' => $duracionRecuerdo,
                    'path' => '/',
                    'secure' => false,
                    'httponly' => true,
                    'samesite' => 'Lax'
                ]);
            }
                } else {
            $modeloUsuarios->update($usuario['id'], [
                'remember_token_hash' => null,
                'remember_expires_at' => null
            ]);

            $respuesta->deleteCookie('recuerdo_aula');
        }

        return $respuesta;
    }

    public function cerrarSesion()
{
    $idUsuario = session()->get('usuario_id');

    if ($idUsuario !== null) {
        $modeloUsuarios = new DatosUsuarioAula();

        $modeloUsuarios->update($idUsuario, [
            'remember_token_hash' => null,
            'remember_expires_at' => null
        ]);
    }

    session()->destroy();

    $respuesta = redirect()->to('/ingreso');
    $respuesta->deleteCookie('recuerdo_aula');

    return $respuesta;
}

}