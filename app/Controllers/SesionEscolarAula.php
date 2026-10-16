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
        'titulo' => lang('InterfazAula.iniciarSesion'),
        'correoIngreso' => session('correoIngreso') ?? '',
        'recordarIngreso' => session('recordarIngreso') === '1',
        'errores' => session('erroresIngreso') ?? []
    ];

    return $puenteVista->mostrar('acceso.ingreso', $datosVista);
}
    public function validarIngreso()
    {
        $reglasIngreso = [
    'email' => 'required|valid_email',
    'password' => 'required'
];

$mensajesIngreso = [
    'email' => [
        'required' => lang('InterfazAula.ingresoCorreoObligatorio'),
        'valid_email' => lang('InterfazAula.ingresoCorreoInvalido')
    ],
    'password' => [
        'required' => lang('InterfazAula.ingresoContrasenaObligatoria')
    ]
];

$correo = strtolower(trim((string) $this->request->getPost('email')));
$contrasena = (string) $this->request->getPost('password');
$recordarIngreso = $this->request->getPost('remember') === '1';

if (!$this->validate($reglasIngreso, $mensajesIngreso)) {
    return redirect()
        ->to('/ingreso')
        ->with('correoIngreso', $correo)
        ->with('recordarIngreso', $recordarIngreso ? '1' : '0')
        ->with('erroresIngreso', $this->validator->getErrors());
}
        $modeloUsuarios = new DatosUsuarioAula();

        $usuario = $modeloUsuarios
            ->where('email', $correo)
            ->first();

        if (
    !$usuario ||
    !password_verify($contrasena, $usuario['password']) ||
    (int) $usuario['active'] !== 1
) {
    return redirect()
        ->to('/ingreso')
        ->with('correoIngreso', $correo)
        ->with('recordarIngreso', $recordarIngreso ? '1' : '0')
        ->with('erroresIngreso', [
            lang('InterfazAula.ingresoCredencialesInvalidas')
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