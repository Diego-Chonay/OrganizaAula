<?php

namespace App\Controllers;

use App\Libraries\PuenteVistaAula;
use App\Models\DatosUsuarioAula;

class PerfilEscolarAula extends BaseController
{
    public function mostrarPerfil()
    {
        if (session()->get('autenticado') !== true) {
            return redirect()->to('/ingreso');
        }

        $modeloUsuarios = new DatosUsuarioAula();

        $usuario = $modeloUsuarios->find(
            (int) session()->get('usuario_id')
        );

        if (!$usuario || (int) $usuario['active'] !== 1) {
            session()->destroy();

            return redirect()->to('/ingreso');
        }

        $datosVista = [
            'titulo' => 'Mi perfil',
            'nombre' => $usuario['name'],
            'correo' => $usuario['email'],
            'imagen' => $usuario['profile_image']
        ];

        $puenteVista = new PuenteVistaAula();

        return $puenteVista->mostrar('perfil.inicio', $datosVista);
    }

        public function confirmarEdicion()
    {
        if (session()->get('autenticado') !== true) {
            return redirect()->to('/ingreso');
        }

        $modeloUsuarios = new DatosUsuarioAula();

        $usuario = $modeloUsuarios->find(
            (int) session()->get('usuario_id')
        );

        if (!$usuario || (int) $usuario['active'] !== 1) {
            session()->destroy();

            return redirect()->to('/ingreso');
        }

        $puenteVista = new PuenteVistaAula();

        return $puenteVista->mostrar('perfil.confirmar', [
            'titulo' => 'Confirmar contraseña'
        ]);
    }

        public function verificarContrasena()
    {
        if (session()->get('autenticado') !== true) {
            return redirect()->to('/ingreso');
        }

        $modeloUsuarios = new DatosUsuarioAula();

        $usuario = $modeloUsuarios->find(
            (int) session()->get('usuario_id')
        );

        if (!$usuario || (int) $usuario['active'] !== 1) {
            session()->destroy();

            return redirect()->to('/ingreso');
        }

        $contrasenaActual = (string) $this->request->getPost('password');

        if (!password_verify($contrasenaActual, $usuario['password'])) {
            session()->remove('edicion_permitida_hasta');
            session()->remove('edicion_usuario_id');

            return redirect()
                ->to('/perfil/confirmar')
                ->with('errorPerfil', lang('InterfazAula.perfilConfirmacionIncorrecta'));
        }

        session()->set([
            'edicion_usuario_id' => (int) $usuario['id'],
            'edicion_permitida_hasta' => time() + 600
        ]);

        return redirect()->to('/perfil/editar');
    }

        public function mostrarEdicion()
    {
        if (session()->get('autenticado') !== true) {
            return redirect()->to('/ingreso');
        }

        $modeloUsuarios = new DatosUsuarioAula();

        $usuario = $modeloUsuarios->find(
            (int) session()->get('usuario_id')
        );

        if (!$usuario || (int) $usuario['active'] !== 1) {
            session()->destroy();

            return redirect()->to('/ingreso');
        }

        if (
            (int) session()->get('edicion_usuario_id') !== (int) $usuario['id'] ||
            (int) session()->get('edicion_permitida_hasta') <= time()
        ) {
            return redirect()->to('/perfil/confirmar');
        }

        $puenteVista = new PuenteVistaAula();

        return $puenteVista->mostrar('perfil.editar', [
    'titulo' => lang('InterfazAula.editarPerfil'),
    'nombre' => session('nombrePerfilTemporal') ?? $usuario['name'],
    'correo' => session('correoPerfilTemporal') ?? $usuario['email'],
    'errores' => session('erroresPerfil') ?? []
]);
    }

        public function guardarPerfil()
    {
        if (session()->get('autenticado') !== true) {
            return redirect()->to('/ingreso');
        }

        $idUsuario = (int) session()->get('usuario_id');

        if (
            (int) session()->get('edicion_usuario_id') !== $idUsuario ||
            (int) session()->get('edicion_permitida_hasta') <= time()
        ) {
            return redirect()->to('/perfil/confirmar');
        }

        $modeloUsuarios = new DatosUsuarioAula();
        $usuario = $modeloUsuarios->find($idUsuario);

        if (!$usuario || (int) $usuario['active'] !== 1) {
            session()->destroy();
            return redirect()->to('/ingreso');
        }

        $nombre = trim((string) $this->request->getPost('name'));
        $correo = strtolower(trim((string) $this->request->getPost('email')));

        $reglas = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|max_length[150]'
        ];

        $mensajesPerfil = [
    'name' => [
        'required' => lang('InterfazAula.registroNombreObligatorio'),
        'min_length' => lang('InterfazAula.registroNombreCorto'),
        'max_length' => lang('InterfazAula.registroNombreLargo')
    ],
    'email' => [
        'required' => lang('InterfazAula.registroCorreoObligatorio'),
        'valid_email' => lang('InterfazAula.registroCorreoInvalido'),
        'max_length' => lang('InterfazAula.perfilCorreoLargo')
    ]
];

        $datosVista = [
            'titulo' => 'Editar perfil',
            'nombre' => $nombre,
            'correo' => $correo
        ];

        $puenteVista = new PuenteVistaAula();

        if (!$this->validateData([
    'name' => $nombre,
    'email' => $correo
], $reglas, $mensajesPerfil)) {
    return redirect()
        ->to('/perfil/editar')
        ->with('nombrePerfilTemporal', $nombre)
        ->with('correoPerfilTemporal', $correo)
        ->with('erroresPerfil', $this->validator->getErrors());
}

        $correoExistente = $modeloUsuarios
            ->where('email', $correo)
            ->where('id !=', $idUsuario)
            ->first();

        if ($correoExistente) {
            $datosVista['errores'] = [
                'El correo electrónico ya está registrado por otro usuario.'
            ];

            return $puenteVista->mostrar('perfil.editar', $datosVista);
        }

        $resultado = $modeloUsuarios->update($idUsuario, [
            'name' => $nombre,
            'email' => $correo
        ]);

        if ($resultado === false) {
    return redirect()
        ->to('/perfil/editar')
        ->with('nombrePerfilTemporal', $nombre)
        ->with('correoPerfilTemporal', $correo)
        ->with('erroresPerfil', [
            lang('InterfazAula.perfilErrorGuardar')
        ]);
}

        session()->set('usuario_nombre', $nombre);
        session()->remove('edicion_usuario_id');
        session()->remove('edicion_permitida_hasta');

        return redirect()
            ->to('/perfil')
            ->with('mensajePerfil', lang('InterfazAula.perfilDatosActualizados'));
    }

        public function formularioContrasena()
    {
        if (session()->get('autenticado') !== true) {
            return redirect()->to('/ingreso');
        }

        $modeloUsuarios = new DatosUsuarioAula();

        $usuario = $modeloUsuarios->find(
            (int) session()->get('usuario_id')
        );

        if (!$usuario || (int) $usuario['active'] !== 1) {
            session()->destroy();

            return redirect()->to('/ingreso');
        }

        $puenteVista = new PuenteVistaAula();

        return $puenteVista->mostrar('perfil.contrasena', [
    'titulo' => lang('InterfazAula.cambiarContrasena'),
    'errores' => session('erroresContrasena') ?? []
]);
    }

        public function guardarContrasena()
    {
        if (session()->get('autenticado') !== true) {
            return redirect()->to('/ingreso');
        }

        $modeloUsuarios = new DatosUsuarioAula();

        $usuario = $modeloUsuarios->find(
            (int) session()->get('usuario_id')
        );

        if (!$usuario || (int) $usuario['active'] !== 1) {
            session()->destroy();
            return redirect()->to('/ingreso');
        }

        $reglas = [
            'password_current' => 'required',
            'password_new' => 'required|min_length[8]',
            'password_repeat' => 'required|matches[password_new]'
        ];

        $mensajesContrasena = [
    'password_current' => [
        'required' => lang('InterfazAula.perfilContrasenaActualObligatoria')
    ],
    'password_new' => [
        'required' => lang('InterfazAula.registroContrasenaObligatoria'),
        'min_length' => lang('InterfazAula.registroContrasenaCorta')
    ],
    'password_repeat' => [
        'required' => lang('InterfazAula.registroRepeticionObligatoria'),
        'matches' => lang('InterfazAula.registroContrasenasDistintas')
    ]
];

        $puenteVista = new PuenteVistaAula();

        if (!$this->validate($reglas, $mensajesContrasena)) {
    return redirect()
        ->to('/perfil/contrasena')
        ->with('erroresContrasena', $this->validator->getErrors());
}

        $contrasenaActual = (string) $this->request->getPost('password_current');
        $contrasenaNueva = (string) $this->request->getPost('password_new');

        if (!password_verify($contrasenaActual, $usuario['password'])) {
    return redirect()
        ->to('/perfil/contrasena')
        ->with('erroresContrasena', [
            lang('InterfazAula.perfilContrasenaActualIncorrecta')
        ]);
}

        $resultado = $modeloUsuarios->update($usuario['id'], [
            'password' => password_hash($contrasenaNueva, PASSWORD_DEFAULT),
            'remember_token_hash' => null,
            'remember_expires_at' => null
        ]);

        if ($resultado === false) {
    return redirect()
        ->to('/perfil/contrasena')
        ->with('erroresContrasena', [
            lang('InterfazAula.perfilErrorActualizarContrasena')
        ]);
}

        session()->remove('edicion_usuario_id');
        session()->remove('edicion_permitida_hasta');

        return redirect()
            ->to('/perfil')
            ->with('mensajePerfil', lang('InterfazAula.perfilContrasenaActualizada'));
    }

    public function formularioImagen()
{
    if (session()->get('autenticado') !== true) {
        return redirect()->to('/ingreso');
    }

    $modeloUsuarios = new DatosUsuarioAula();

    $usuario = $modeloUsuarios->find(
        (int) session()->get('usuario_id')
    );

    if (!$usuario || (int) $usuario['active'] !== 1) {
        session()->destroy();
        return redirect()->to('/ingreso');
    }

    $puenteVista = new PuenteVistaAula();

    return $puenteVista->mostrar('perfil.imagen', [
    'titulo' => lang('InterfazAula.cambiarImagenPerfil'),
    'errores' => session('erroresImagen') ?? []
]);
}

    public function guardarImagen()
{
    if (session()->get('autenticado') !== true) {
        return redirect()->to('/ingreso');
    }

    $modeloUsuarios = new DatosUsuarioAula();

    $usuario = $modeloUsuarios->find(
        (int) session()->get('usuario_id')
    );

    if (!$usuario || (int) $usuario['active'] !== 1) {
        session()->destroy();
        return redirect()->to('/ingreso');
    }

    $reglas = [
    'imagen' => [
        'rules' => 'uploaded[imagen]|max_size[imagen,2048]|is_image[imagen]|mime_in[imagen,image/jpeg,image/png,image/webp]',
        'errors' => [
            'uploaded' => lang('InterfazAula.imagenObligatoria'),
            'max_size' => lang('InterfazAula.imagenTamanoMaximo'),
            'is_image' => lang('InterfazAula.imagenArchivoInvalido'),
            'mime_in' => lang('InterfazAula.imagenFormatoInvalido')
        ]
    ]
];

    $puenteVista = new PuenteVistaAula();

    if (!$this->validate($reglas)) {
    return redirect()
        ->to('/perfil/imagen')
        ->with('erroresImagen', $this->validator->getErrors());
}

    $archivo = $this->request->getFile('imagen');

    if (!$archivo || !$archivo->isValid() || $archivo->hasMoved()) {
    return redirect()
        ->to('/perfil/imagen')
        ->with('erroresImagen', [
            lang('InterfazAula.imagenErrorRecepcion')
        ]);
}

    $extensiones = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp'
    ];

    $tipo = $archivo->getMimeType();

if (!isset($extensiones[$tipo])) {
    return redirect()
        ->to('/perfil/imagen')
        ->with('erroresImagen', [
            lang('InterfazAula.imagenErrorFormato')
        ]);
}

$carpeta = FCPATH . 'uploads/perfiles/';

if (!is_dir($carpeta) && !mkdir($carpeta, 0755, true) && !is_dir($carpeta)) {
    return redirect()
        ->to('/perfil/imagen')
        ->with('erroresImagen', [
            lang('InterfazAula.imagenErrorCarpeta')
        ]);
}

$nombreImagen = bin2hex(random_bytes(16)) . '.' . $extensiones[$tipo];
    try {
    $archivo->move($carpeta, $nombreImagen);
} catch (\Exception $error) {
    return redirect()
        ->to('/perfil/imagen')
        ->with('erroresImagen', [
            lang('InterfazAula.imagenErrorGuardar')
        ]);
}

    $resultado = $modeloUsuarios->update($usuario['id'], [
        'profile_image' => $nombreImagen
    ]);

    if ($resultado === false) {
    unlink($carpeta . $nombreImagen);

    return redirect()
        ->to('/perfil/imagen')
        ->with('erroresImagen', [
            lang('InterfazAula.imagenErrorActualizar')
        ]);
}

    if (
        !empty($usuario['profile_image']) &&
        preg_match('/^[a-f0-9]{32}\.(jpg|png|webp)$/', $usuario['profile_image'])
    ) {
        $imagenAnterior = $carpeta . $usuario['profile_image'];

        if (is_file($imagenAnterior)) {
            unlink($imagenAnterior);
        }
    }

    return redirect()
        ->to('/perfil')
        ->with('mensajePerfil', lang('InterfazAula.imagenActualizada'));
}

public function eliminarImagen()
{
    if (session()->get('autenticado') !== true) {
        return redirect()->to('/ingreso');
    }

    $modeloUsuarios = new DatosUsuarioAula();

    $usuario = $modeloUsuarios->find(
        (int) session()->get('usuario_id')
    );

    if (!$usuario || (int) $usuario['active'] !== 1) {
        session()->destroy();

        return redirect()->to('/ingreso');
    }

    if (empty($usuario['profile_image'])) {
        return redirect()
            ->to('/perfil')
            ->with('mensajePerfil', lang('InterfazAula.imagenNoExiste'));
    }

    $nombreImagen = $usuario['profile_image'];

    $resultado = $modeloUsuarios->update($usuario['id'], [
        'profile_image' => null
    ]);

    if ($resultado === false) {
        return redirect()
            ->to('/perfil')
            ->with('mensajePerfil', lang('InterfazAula.imagenErrorEliminar'));
    }

    if (preg_match('/^[a-f0-9]{32}\.(jpg|png|webp)$/', $nombreImagen)) {
        $rutaImagen = FCPATH . 'uploads/perfiles/' . $nombreImagen;

        if (is_file($rutaImagen) && !unlink($rutaImagen)) {
            log_message('warning', 'No se pudo eliminar un archivo de imagen de perfil.');
        }
    }

    return redirect()
        ->to('/perfil')
        ->with('mensajePerfil', lang('InterfazAula.imagenEliminada'));
}

}