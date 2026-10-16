<?php

namespace App\Controllers;

use App\Libraries\PuenteVistaAula;

class RecuperacionEscolarAula extends BaseController
{
    public function formularioRecuperacion()
{
    $puenteVista = new PuenteVistaAula();

    $datosVista = [
        'titulo' => lang('InterfazAula.tituloRecuperacion'),
        'correoRecuperacion' => session('correoRecuperacion') ?? '',
        'errores' => session('erroresRecuperacion') ?? []
    ];

    return $puenteVista->mostrar('acceso.recuperar', $datosVista);
}

        public function formularioRestablecimiento(string $llave)
    {
        if (strlen($llave) !== 64 || !ctype_xdigit($llave)) {
            return redirect()
                ->to('/recuperar')
                ->with('mensajeRecuperacion', lang('InterfazAula.recuperacionEnlaceInvalido'));
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
                ->with('mensajeRecuperacion', lang('InterfazAula.recuperacionEnlaceVencido'));
        }

        $puenteVista = new PuenteVistaAula();

        return $puenteVista->mostrar('acceso.restablecer', [
    'titulo' => lang('InterfazAula.tituloRestablecimiento'),
    'llave' => $llave,
    'errores' => session('erroresRestablecimiento') ?? []
]);
    }

        public function enviarRecuperacion()
    {
        $reglasRecuperacion = [
    'email' => 'required|valid_email'
];

$mensajesRecuperacion = [
    'email' => [
        'required' => lang('InterfazAula.ingresoCorreoObligatorio'),
        'valid_email' => lang('InterfazAula.ingresoCorreoInvalido')
    ]
];

if (!$this->validate($reglasRecuperacion, $mensajesRecuperacion)) {
    return redirect()
        ->to('/recuperar')
        ->with('correoRecuperacion', trim((string) $this->request->getPost('email')))
        ->with('erroresRecuperacion', $this->validator->getErrors());
}

        $correo = strtolower(trim((string) $this->request->getPost('email')));

        $modeloUsuarios = new \App\Models\DatosUsuarioAula();

        $usuario = $modeloUsuarios
            ->where('email', $correo)
            ->where('active', 1)
            ->first();

        $mensaje = lang('InterfazAula.recuperacionMensajeGeneral');

        if ($usuario) {
            $llave = bin2hex(random_bytes(32));

            $actualizado = $modeloUsuarios->update($usuario['id'], [
                'recovery_key' => hash('sha256', $llave),
                'recovery_expires_at' => date('Y-m-d H:i:s', time() + 900)
            ]);

            if ($actualizado) {
                $enlace = site_url('recuperar/restablecer/' . $llave);

                $contenido = sprintf(
    lang('InterfazAula.correoRecuperacionSaludo'),
    $usuario['name']
)
    . "\n\n"
    . lang('InterfazAula.correoRecuperacionSolicitud')
    . "\n\n"
    . lang('InterfazAula.correoRecuperacionInstruccion')
    . "\n\n"
    . $enlace
    . "\n\n"
    . lang('InterfazAula.correoRecuperacionVigencia')
    . "\n\n"
    . lang('InterfazAula.correoRecuperacionAviso')
    . "\n\n"
    . lang('InterfazAula.correoRecuperacionDespedida')
    . "\n"
    . lang('InterfazAula.correoRecuperacionEquipo');

                $configuracionCorreo = config('Email');
                $correoRecuperacion = service('email');

                $correoRecuperacion->setFrom(
                    $configuracionCorreo->fromEmail,
                    $configuracionCorreo->fromName
                );

                $correoRecuperacion->setTo($usuario['email']);
                $correoRecuperacion->setSubject(lang('InterfazAula.correoRecuperacionAsunto'));
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
                ->with('mensajeRecuperacion', lang('InterfazAula.recuperacionEnlaceInvalido'));
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
                ->with('mensajeRecuperacion', lang('InterfazAula.recuperacionEnlaceVencido'));
        }

        $reglasContrasena = [
            'password' => 'required|min_length[8]',
            'password_repeat' => 'required|matches[password]'
        ];

        $mensajesContrasena = [
    'password' => [
        'required' => lang('InterfazAula.registroContrasenaObligatoria'),
        'min_length' => lang('InterfazAula.registroContrasenaCorta')
    ],
    'password_repeat' => [
        'required' => lang('InterfazAula.registroRepeticionObligatoria'),
        'matches' => lang('InterfazAula.registroContrasenasDistintas')
    ]
];

        if (!$this->validate($reglasContrasena, $mensajesContrasena)) {
    return redirect()
        ->to('/recuperar/restablecer/' . $llave)
        ->with('erroresRestablecimiento', $this->validator->getErrors());
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
                ->with('mensajeRecuperacion', lang('InterfazAula.recuperacionErrorActualizar'));
        }

        return redirect()
            ->to('/ingreso')
            ->with('mensajeRestablecimiento', lang('InterfazAula.recuperacionActualizada'));
    }

}