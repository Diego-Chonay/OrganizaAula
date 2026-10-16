<?php

namespace App\Controllers;

use App\Libraries\PuenteVistaAula;

class RegistroEscolarAula extends BaseController
{
    public function formulario()
{
    $puenteVista = new PuenteVistaAula();

    $datosVista = [
        'titulo' => lang('InterfazAula.tituloRegistro'),
        'nombreRegistro' => session('nombreRegistro') ?? '',
        'correoRegistro' => session('correoRegistro') ?? '',
        'errores' => session('erroresRegistro') ?? []
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

        $mensajesRegistro = [
        'name' => [
        'required' => lang('InterfazAula.registroNombreObligatorio'),
        'min_length' => lang('InterfazAula.registroNombreCorto'),
        'max_length' => lang('InterfazAula.registroNombreLargo')
    ],
    'email' => [
        'required' => lang('InterfazAula.registroCorreoObligatorio'),
        'valid_email' => lang('InterfazAula.registroCorreoInvalido'),
        'is_unique' => lang('InterfazAula.registroCorreoExistente')
    ],
    'password' => [
        'required' => lang('InterfazAula.registroContrasenaObligatoria'),
        'min_length' => lang('InterfazAula.registroContrasenaCorta')
    ],
    'password_repeat' => [
        'required' => lang('InterfazAula.registroRepeticionObligatoria'),
        'matches' => lang('InterfazAula.registroContrasenasDistintas')
    ]
];

        if (!$this->validate($reglasRegistro, $mensajesRegistro)) {
    return redirect()
        ->to('/registro')
        ->with('nombreRegistro', trim((string) $this->request->getPost('name')))
        ->with('correoRegistro', trim((string) $this->request->getPost('email')))
        ->with('erroresRegistro', $this->validator->getErrors());
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
    return redirect()
        ->to('/registro')
        ->with('nombreRegistro', trim((string) $this->request->getPost('name')))
        ->with('correoRegistro', trim((string) $this->request->getPost('email')))
        ->with('erroresRegistro', [lang('InterfazAula.registroFallido')]);
}

        $configuracionCorreo = config('Email');
        $correoActivacion = service('email');

        $enlaceActivacion = site_url(
            'activar/' . $datosCuenta['verification_key']
        );

        $mensajeCorreo = lang('InterfazAula.correoActivacionSaludo')
    . " {$datosCuenta['name']},\n\n"
    . lang('InterfazAula.correoActivacionInstruccion')
    . "\n\n"
    . $enlaceActivacion
    . "\n\nOrganizaAula";

        $correoActivacion->setFrom(
            $configuracionCorreo->fromEmail,
            $configuracionCorreo->fromName
        );

        $correoActivacion->setTo($datosCuenta['email']);
        $correoActivacion->setSubject(lang('InterfazAula.correoActivacionAsunto'));
        $correoActivacion->setMessage($mensajeCorreo);

        if (!$correoActivacion->send()) {
            return redirect()
                ->to('/registro')
                ->with(
                    'mensajeRegistro',
                    lang('InterfazAula.registroCorreoFallido')
                );
        }

        return redirect()
            ->to('/registro')
            ->with(
                'mensajeRegistro',
                lang('InterfazAula.registroExitoso')
            );

    }

      public function activarCuenta(string $llave)
    {
        if (strlen($llave) !== 64 || !ctype_xdigit($llave)) {
        return redirect()->to('/ingreso')
            ->with('errorActivacion', lang('InterfazAula.activacionInvalida'));
    }

    $modeloUsuarios = new \App\Models\DatosUsuarioAula();

    $usuario = $modeloUsuarios
        ->where('verification_key', $llave)
        ->first();

    if (!$usuario || (int) $usuario['active'] !== 0) {
        return redirect()->to('/ingreso')
            ->with('errorActivacion', lang('InterfazAula.activacionUtilizada'));
    }

    $resultado = $modeloUsuarios->update($usuario['id'], [
        'active' => 1,
        'verification_key' => null
    ]);

    if ($resultado === false) {
        return redirect()->to('/ingreso')
            ->with('errorActivacion', lang('InterfazAula.activacionFallida'));
    }

    return redirect()->to('/ingreso')
        ->with('mensajeActivacion', lang('InterfazAula.activacionExitosa'));
}

}
