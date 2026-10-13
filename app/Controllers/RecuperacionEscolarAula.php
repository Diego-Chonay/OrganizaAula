<?php

namespace App\Controllers;

use App\Libraries\PuenteVistaAula;

class RecuperacionEscolarAula extends BaseController
{
    public function formularioRecuperacion()
    {
        $puenteVista = new PuenteVistaAula();

        $datosVista = [
            'titulo' => 'Recuperar contraseña'
        ];

        return $puenteVista->mostrar('acceso.recuperar', $datosVista);
    }

        public function formularioRestablecimiento(string $llave)
    {
        if (strlen($llave) !== 64 || !ctype_xdigit($llave)) {
            return redirect()
                ->to('/recuperar')
                ->with('mensajeRecuperacion', 'El enlace de recuperación no es válido.');
        }

        $modeloUsuarios = new \App\Models\DatosUsuarioAula();

        $usuario = $modeloUsuarios
            ->where('recovery_key', hash('sha256', $llave))
            ->first();

        if (
            !$usuario ||
            (int) $usuario['active'] !== 1 ||
            empty($usuario['recovery_expires_at']) ||
            strtotime($usuario['recovery_expires_at']) <= time()
        ) {
            return redirect()
                ->to('/recuperar')
                ->with('mensajeRecuperacion', 'El enlace de recuperación no es válido o ha vencido.');
        }

        $puenteVista = new PuenteVistaAula();

        return $puenteVista->mostrar('acceso.restablecer', [
            'titulo' => 'Restablecer contraseña',
            'llave' => $llave
        ]);
    }

        public function enviarRecuperacion()
    {
        $reglasRecuperacion = [
            'email' => 'required|valid_email'
        ];

        if (!$this->validate($reglasRecuperacion)) {
            $puenteVista = new PuenteVistaAula();

            return $puenteVista->mostrar('acceso.recuperar', [
                'titulo' => 'Recuperar contraseña',
                'errores' => $this->validator->getErrors()
            ]);
        }

        $correo = strtolower(trim((string) $this->request->getPost('email')));

        $modeloUsuarios = new \App\Models\DatosUsuarioAula();

        $usuario = $modeloUsuarios
            ->where('email', $correo)
            ->where('active', 1)
            ->first();

        $mensaje = 'Si existe una cuenta activa con ese correo, recibirás instrucciones para recuperar tu contraseña.';

        if ($usuario) {
            $llave = bin2hex(random_bytes(32));

            $actualizado = $modeloUsuarios->update($usuario['id'], [
                'recovery_key' => hash('sha256', $llave),
                'recovery_expires_at' => date('Y-m-d H:i:s', time() + 900)
            ]);

            if ($actualizado) {
                $enlace = site_url('recuperar/restablecer/' . $llave);

                $contenido = "¡Hola, {$usuario['name']}!\n\n"
    . "Recibimos una solicitud para cambiar la contraseña de tu cuenta en OrganizaAula.\n\n"
    . "Para continuar, ingresa desde el siguiente enlace:\n\n"
    . $enlace
    . "\n\nEste enlace estará disponible durante 15 minutos y dejará de funcionar después de utilizarlo.\n\n"
    . "Si no solicitaste este cambio, puedes ignorar el mensaje y continuar utilizando tu contraseña actual.\n\n"
    . "Saludos,\n"
    . "Equipo OrganizaAula";

                $configuracionCorreo = config('Email');
                $correoRecuperacion = service('email');

                $correoRecuperacion->setFrom(
                    $configuracionCorreo->fromEmail,
                    $configuracionCorreo->fromName
                );

                $correoRecuperacion->setTo($usuario['email']);
                $correoRecuperacion->setSubject('OrganizaAula | Solicitud de cambio de contraseña');
                $correoRecuperacion->setMessage($contenido);

                if (!$correoRecuperacion->send()) {
                    $modeloUsuarios->update($usuario['id'], [
                        'recovery_key' => null,
                        'recovery_expires_at' => null
                    ]);

                    log_message('error', 'No se pudo enviar el correo de recuperación.');
                }
            }
        }

        return redirect()
            ->to('/recuperar')
            ->with('mensajeRecuperacion', $mensaje);
    }

        public function actualizarContrasena()
    {
        $llave = (string) $this->request->getPost('llave');

        if (strlen($llave) !== 64 || !ctype_xdigit($llave)) {
            return redirect()
                ->to('/recuperar')
                ->with('mensajeRecuperacion', 'El enlace de recuperación no es válido.');
        }

        $modeloUsuarios = new \App\Models\DatosUsuarioAula();

        $usuario = $modeloUsuarios
            ->where('recovery_key', hash('sha256', $llave))
            ->first();

        if (
            !$usuario ||
            (int) $usuario['active'] !== 1 ||
            empty($usuario['recovery_expires_at']) ||
            strtotime($usuario['recovery_expires_at']) <= time()
        ) {
            return redirect()
                ->to('/recuperar')
                ->with('mensajeRecuperacion', 'El enlace de recuperación no es válido o ha vencido.');
        }

        $reglasContrasena = [
            'password' => 'required|min_length[8]',
            'password_repeat' => 'required|matches[password]'
        ];

        if (!$this->validate($reglasContrasena)) {
            $puenteVista = new PuenteVistaAula();

            return $puenteVista->mostrar('acceso.restablecer', [
                'titulo' => 'Restablecer contraseña',
                'llave' => $llave,
                'errores' => $this->validator->getErrors()
            ]);
        }

        $resultado = $modeloUsuarios->update($usuario['id'], [
            'password' => password_hash(
                (string) $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
            'recovery_key' => null,
            'recovery_expires_at' => null,
            'remember_token_hash' => null,
            'remember_expires_at' => null
        ]);

        if ($resultado === false) {
            return redirect()
                ->to('/recuperar')
                ->with('mensajeRecuperacion', 'No se pudo actualizar la contraseña.');
        }

        return redirect()
            ->to('/ingreso')
            ->with('mensajeRestablecimiento', 'Contraseña actualizada correctamente. Ya puedes iniciar sesión.');
    }

}