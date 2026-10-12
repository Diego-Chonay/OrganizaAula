<?php

namespace App\Controllers;

use App\Libraries\PuenteVistaAula;

class RegistroEscolarAula extends BaseController
{
    public function formulario()
    {
        $puenteVista = new PuenteVistaAula();

        $datosVista = [
            'titulo' => 'Crear cuenta'
        ];

        return $puenteVista->mostrar('acceso.registro', $datosVista);
    }

    public function guardarCuenta()
    {
        $reglasRegistro = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'password_repeat' => 'required|matches[password]'
        ];

        if (!$this->validate($reglasRegistro)) {
            $puenteVista = new PuenteVistaAula();

            $datosVista = [
                'titulo' => 'Crear cuenta',
                'errores' => $this->validator->getErrors()
            ];

            return $puenteVista->mostrar('acceso.registro', $datosVista);
        }

        $modeloUsuarios = new \App\Models\DatosUsuarioAula();

        $datosCuenta = [
            'name' => trim($this->request->getPost('name')),
            'email' => strtolower(trim($this->request->getPost('email'))),

            'password' => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),

            'academic_role' => 'student',
            'active' => 0,
            'administrator' => 0,

            'verification_key' => bin2hex(random_bytes(32))
        ];

        $resultado = $modeloUsuarios->insert($datosCuenta);

if ($resultado === false) {
    $puenteVista = new PuenteVistaAula();

    return $puenteVista->mostrar('acceso.registro', [
        'titulo' => 'Crear cuenta',
        'errores' => ['No se pudo registrar la cuenta.']
    ]);
}

return redirect()
    ->to('/registro')
    ->with('mensajeRegistro', 'Cuenta registrada correctamente. Pendiente de activación.');
    }

}
