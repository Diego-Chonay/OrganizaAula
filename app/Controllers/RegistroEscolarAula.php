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

        $configuracionCorreo = config('Email');
        $correoActivacion = service('email');

        $enlaceActivacion = site_url(
            'activar/' . $datosCuenta['verification_key']
        );

        $mensajeCorreo = "Hola {$datosCuenta['name']},\n\n"
            . "Para activar tu cuenta de OrganizaAula, abre el siguiente enlace:\n\n"
            . $enlaceActivacion
            . "\n\nOrganizaAula";

        $correoActivacion->setFrom(
            $configuracionCorreo->fromEmail,
            $configuracionCorreo->fromName
        );

        $correoActivacion->setTo($datosCuenta['email']);
        $correoActivacion->setSubject('Activa tu cuenta de OrganizaAula');
        $correoActivacion->setMessage($mensajeCorreo);

        if (!$correoActivacion->send()) {
            return redirect()
                ->to('/registro')
                ->with(
                    'mensajeRegistro',
                    'La cuenta se guardó, pero no se pudo enviar el correo de activación.'
                );
        }

        return redirect()
            ->to('/registro')
            ->with(
                'mensajeRegistro',
                'Cuenta registrada correctamente. Revisa tu correo para activarla.'
            );

    }

      public function activarCuenta(string $llave)
    {
        if (strlen($llave) !== 64 || !ctype_xdigit($llave)) {
        return redirect()->to('/ingreso')
            ->with('errorActivacion', 'El enlace de activación no es válido.');
    }

    $modeloUsuarios = new \App\Models\DatosUsuarioAula();

    $usuario = $modeloUsuarios
        ->where('verification_key', $llave)
        ->first();

    if (!$usuario || (int) $usuario['active'] !== 0) {
        return redirect()->to('/ingreso')
            ->with('errorActivacion', 'El enlace de activación no es válido o ya fue utilizado.');
    }

    $resultado = $modeloUsuarios->update($usuario['id'], [
        'active' => 1,
        'verification_key' => null
    ]);

    if ($resultado === false) {
        return redirect()->to('/ingreso')
            ->with('errorActivacion', 'No se pudo activar la cuenta.');
    }

    return redirect()->to('/ingreso')
        ->with('mensajeActivacion', 'Cuenta activada correctamente. Ya puedes iniciar sesión.');
}

}
