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

        return redirect()->to('/');
    }

    public function cerrarSesion()
{
    session()->destroy();

    return redirect()->to('/ingreso');
}

}