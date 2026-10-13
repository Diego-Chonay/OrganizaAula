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
                ->with('errorPerfil', 'La contraseña ingresada es incorrecta.');
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
            'titulo' => 'Editar perfil',
            'nombre' => $usuario['name'],
            'correo' => $usuario['email']
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

        $datosVista = [
            'titulo' => 'Editar perfil',
            'nombre' => $nombre,
            'correo' => $correo
        ];

        $puenteVista = new PuenteVistaAula();

        if (!$this->validateData([
            'name' => $nombre,
            'email' => $correo
        ], $reglas)) {
            $datosVista['errores'] = $this->validator->getErrors();
            return $puenteVista->mostrar('perfil.editar', $datosVista);
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
            $datosVista['errores'] = [
                'No fue posible guardar los cambios.'
            ];

            return $puenteVista->mostrar('perfil.editar', $datosVista);
        }

        session()->set('usuario_nombre', $nombre);
        session()->remove('edicion_usuario_id');
        session()->remove('edicion_permitida_hasta');

        return redirect()
            ->to('/perfil')
            ->with('mensajePerfil', 'Tus datos se actualizaron correctamente.');
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
            'titulo' => 'Cambiar contraseña'
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

        $puenteVista = new PuenteVistaAula();

        if (!$this->validate($reglas)) {
            return $puenteVista->mostrar('perfil.contrasena', [
                'titulo' => 'Cambiar contraseña',
                'errores' => $this->validator->getErrors()
            ]);
        }

        $contrasenaActual = (string) $this->request->getPost('password_current');
        $contrasenaNueva = (string) $this->request->getPost('password_new');

        if (!password_verify($contrasenaActual, $usuario['password'])) {
            return $puenteVista->mostrar('perfil.contrasena', [
                'titulo' => 'Cambiar contraseña',
                'errores' => ['La contraseña actual es incorrecta.']
            ]);
        }

        $resultado = $modeloUsuarios->update($usuario['id'], [
            'password' => password_hash($contrasenaNueva, PASSWORD_DEFAULT),
            'remember_token_hash' => null,
            'remember_expires_at' => null
        ]);

        if ($resultado === false) {
            return $puenteVista->mostrar('perfil.contrasena', [
                'titulo' => 'Cambiar contraseña',
                'errores' => ['No fue posible actualizar la contraseña.']
            ]);
        }

        session()->remove('edicion_usuario_id');
        session()->remove('edicion_permitida_hasta');

        return redirect()
            ->to('/perfil')
            ->with('mensajePerfil', 'Tu contraseña se actualizó correctamente.');
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
        'titulo' => 'Cambiar imagen de perfil'
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
                'uploaded' => 'Debes seleccionar una fotografía.',
                'max_size' => 'La fotografía no debe superar los 2 MB.',
                'is_image' => 'El archivo seleccionado no es una imagen válida.',
                'mime_in' => 'Solo se permiten imágenes JPG, PNG y WEBP.'
            ]
        ]
    ];

    $puenteVista = new PuenteVistaAula();

    if (!$this->validate($reglas)) {
        return $puenteVista->mostrar('perfil.imagen', [
            'titulo' => 'Cambiar imagen de perfil',
            'errores' => $this->validator->getErrors()
        ]);
    }

    $archivo = $this->request->getFile('imagen');

    if (!$archivo || !$archivo->isValid() || $archivo->hasMoved()) {
        return $puenteVista->mostrar('perfil.imagen', [
            'titulo' => 'Cambiar imagen de perfil',
            'errores' => ['No fue posible recibir la fotografía.']
        ]);
    }

    $extensiones = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp'
    ];

    $tipo = $archivo->getMimeType();

    if (!isset($extensiones[$tipo])) {
        return $puenteVista->mostrar('perfil.imagen', [
            'titulo' => 'Cambiar imagen de perfil',
            'errores' => ['El formato de la fotografía no es válido.']
        ]);
    }

    $carpeta = FCPATH . 'uploads/perfiles/';

    if (!is_dir($carpeta) && !mkdir($carpeta, 0755, true) && !is_dir($carpeta)) {
        return $puenteVista->mostrar('perfil.imagen', [
            'titulo' => 'Cambiar imagen de perfil',
            'errores' => ['No fue posible preparar la carpeta de fotografías.']
        ]);
    }

    $nombreImagen = bin2hex(random_bytes(16)) . '.' . $extensiones[$tipo];

    try {
        $archivo->move($carpeta, $nombreImagen);
    } catch (\Exception $error) {
        return $puenteVista->mostrar('perfil.imagen', [
            'titulo' => 'Cambiar imagen de perfil',
            'errores' => ['No fue posible guardar la fotografía.']
        ]);
    }

    $resultado = $modeloUsuarios->update($usuario['id'], [
        'profile_image' => $nombreImagen
    ]);

    if ($resultado === false) {
        unlink($carpeta . $nombreImagen);

        return $puenteVista->mostrar('perfil.imagen', [
            'titulo' => 'Cambiar imagen de perfil',
            'errores' => ['No fue posible actualizar la fotografía del perfil.']
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
        ->with('mensajePerfil', 'Tu fotografía se actualizó correctamente.');
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
            ->with('mensajePerfil', 'No tienes una fotografía para eliminar.');
    }

    $nombreImagen = $usuario['profile_image'];

    $resultado = $modeloUsuarios->update($usuario['id'], [
        'profile_image' => null
    ]);

    if ($resultado === false) {
        return redirect()
            ->to('/perfil')
            ->with('mensajePerfil', 'No fue posible eliminar la fotografía.');
    }

    if (preg_match('/^[a-f0-9]{32}\.(jpg|png|webp)$/', $nombreImagen)) {
        $rutaImagen = FCPATH . 'uploads/perfiles/' . $nombreImagen;

        if (is_file($rutaImagen) && !unlink($rutaImagen)) {
            log_message('warning', 'No se pudo eliminar un archivo de imagen de perfil.');
        }
    }

    return redirect()
        ->to('/perfil')
        ->with('mensajePerfil', 'Tu fotografía se eliminó correctamente.');
}

}