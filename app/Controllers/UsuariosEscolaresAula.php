<?php

namespace App\Controllers;

use App\Libraries\PuenteVistaAula;
use App\Models\DatosUsuarioAula;

class UsuariosEscolaresAula extends BaseController
{
    public function listarUsuarios()
    {
        if (session()->get('autenticado') !== true) {
            return redirect()->to('/ingreso');
        }

        $modeloUsuarios = new DatosUsuarioAula();

        $administrador = $modeloUsuarios->find(
            (int) session()->get('usuario_id')
        );

        if (
            !$administrador ||
            (int) $administrador['active'] !== 1 ||
            (int) $administrador['administrator'] !== 1
        ) {
            return $this->response
                ->setStatusCode(403)
                ->setBody(lang('InterfazAula.usuariosPermisoAdministrar'));
        }

        $orden = $this->request->getGet('orden') === 'antiguos'
            ? 'antiguos'
            : 'nuevos';

        $direccion = $orden === 'antiguos' ? 'ASC' : 'DESC';

        $usuarios = $modeloUsuarios
            ->orderBy('created_at', $direccion)
            ->orderBy('id', $direccion)
            ->findAll();

        $puenteVista = new PuenteVistaAula();

        return $puenteVista->mostrar('usuarios.lista', [
            'titulo' => lang('InterfazAula.administracionUsuarios'),
            'usuarios' => $usuarios
        ]);
    }

    public function verUsuario(int $id)
{
    if (session()->get('autenticado') !== true) {
        return redirect()->to('/ingreso');
    }

    $modeloUsuarios = new DatosUsuarioAula();

    $administrador = $modeloUsuarios->find(
        (int) session()->get('usuario_id')
    );

    if (
        !$administrador ||
        (int) $administrador['active'] !== 1 ||
        (int) $administrador['administrator'] !== 1
    ) {
        return $this->response
            ->setStatusCode(403)
            ->setBody(lang('InterfazAula.usuariosPermisoAdministrar'));
    }

    $usuario = $modeloUsuarios->find($id);

    if (!$usuario) {
        return $this->response
            ->setStatusCode(404)
            ->setBody(lang('InterfazAula.usuariosNoExiste'));
    }

    $puenteVista = new PuenteVistaAula();

    return $puenteVista->mostrar('usuarios.detalle', [
        'titulo' => lang('InterfazAula.informacionUsuario'),
        'usuario' => $usuario
    ]);
}

    public function formularioNuevoUsuario()
{
    if (session()->get('autenticado') !== true) {
        return redirect()->to('/ingreso');
    }

    $modeloUsuarios = new DatosUsuarioAula();

    $administrador = $modeloUsuarios->find(
        (int) session()->get('usuario_id')
    );

    if (
        !$administrador ||
        (int) $administrador['active'] !== 1 ||
        (int) $administrador['administrator'] !== 1
    ) {
        return $this->response
            ->setStatusCode(403)
            ->setBody(lang('InterfazAula.usuariosPermisoCrear'));
    }

    $puenteVista = new PuenteVistaAula();

    return $puenteVista->mostrar('usuarios.nuevo', [
    'titulo' => lang('InterfazAula.nuevoUsuario'),
    'errores' => session('erroresUsuarioNuevo') ?? [],
    'datosPrevios' => session('datosUsuarioNuevo') ?? []
]);
}

    public function guardarNuevoUsuario()
{
    if (session()->get('autenticado') !== true) {
        return redirect()->to('/ingreso');
    }

    $modeloUsuarios = new DatosUsuarioAula();

    $administrador = $modeloUsuarios->find(
        (int) session()->get('usuario_id')
    );

    if (
        !$administrador ||
        (int) $administrador['active'] !== 1 ||
        (int) $administrador['administrator'] !== 1
    ) {
        return $this->response
            ->setStatusCode(403)
            ->setBody(lang('InterfazAula.usuariosPermisoCrear'));
    }

    $nombre = trim((string) $this->request->getPost('name'));
    $correo = strtolower(trim((string) $this->request->getPost('email')));
    $contrasena = (string) $this->request->getPost('password');
    $repetirContrasena = (string) $this->request->getPost('password_repeat');
    $rolAcademico = (string) $this->request->getPost('academic_role');

    $datos = [
        'name' => $nombre,
        'email' => $correo,
        'password' => $contrasena,
        'password_repeat' => $repetirContrasena,
        'academic_role' => $rolAcademico
    ];

    $reglas = [
    'name' => [
        'rules' => 'required|min_length[3]|max_length[100]',
        'errors' => [
            'required' => lang('InterfazAula.registroNombreObligatorio'),
            'min_length' => lang('InterfazAula.registroNombreCorto'),
            'max_length' => lang('InterfazAula.registroNombreLargo')
        ]
    ],
    'email' => [
        'rules' => 'required|valid_email|max_length[150]|is_unique[users.email]',
        'errors' => [
            'required' => lang('InterfazAula.registroCorreoObligatorio'),
            'valid_email' => lang('InterfazAula.registroCorreoInvalido'),
            'max_length' => lang('InterfazAula.perfilCorreoLargo'),
            'is_unique' => lang('InterfazAula.registroCorreoExistente')
        ]
    ],
    'password' => [
        'rules' => 'required|min_length[8]',
        'errors' => [
            'required' => lang('InterfazAula.registroContrasenaObligatoria'),
            'min_length' => lang('InterfazAula.registroContrasenaCorta')
        ]
    ],
    'password_repeat' => [
        'rules' => 'required|matches[password]',
        'errors' => [
            'required' => lang('InterfazAula.registroRepeticionObligatoria'),
            'matches' => lang('InterfazAula.registroContrasenasDistintas')
        ]
    ],
    'academic_role' => [
        'rules' => 'required|in_list[student,teacher]',
        'errors' => [
            'required' => lang('InterfazAula.usuariosRolObligatorio'),
            'in_list' => lang('InterfazAula.usuariosRolInvalido')
        ]
    ]
];
    $puenteVista = new PuenteVistaAula();

    if (!$this->validateData($datos, $reglas)) {
    return redirect()
        ->to('/usuarios/nuevo')
        ->with('erroresUsuarioNuevo', array_values($this->validator->getErrors()))
        ->with('datosUsuarioNuevo', [
            'name' => $nombre,
            'email' => $correo,
            'academic_role' => $rolAcademico,
            'active' => $this->request->getPost('active') === '1' ? 1 : 0,
            'administrator' => $this->request->getPost('administrator') === '1' ? 1 : 0
        ]);
}

    $activo = $this->request->getPost('active') === '1' ? 1 : 0;
    $esAdministrador = $this->request->getPost('administrator') === '1' ? 1 : 0;

    $resultado = $modeloUsuarios->insert([
        'name' => $nombre,
        'email' => $correo,
        'password' => password_hash($contrasena, PASSWORD_DEFAULT),
        'academic_role' => $rolAcademico,
        'active' => $activo,
        'administrator' => $esAdministrador
    ]);

    if ($resultado === false) {
    return redirect()
        ->to('/usuarios/nuevo')
        ->with('erroresUsuarioNuevo', [
            lang('InterfazAula.usuariosErrorGuardar')
        ])
        ->with('datosUsuarioNuevo', [
            'name' => $nombre,
            'email' => $correo,
            'academic_role' => $rolAcademico,
            'active' => $activo,
            'administrator' => $esAdministrador
        ]);
}

    return redirect()
        ->to('/usuarios')
        ->with('mensajeUsuarios', lang('InterfazAula.usuariosCreado'));
}

public function formularioEditarUsuario(int $id)
{
    if (session()->get('autenticado') !== true) {
        return redirect()->to('/ingreso');
    }

    $modeloUsuarios = new DatosUsuarioAula();

    $administrador = $modeloUsuarios->find(
        (int) session()->get('usuario_id')
    );

    if (
        !$administrador ||
        (int) $administrador['active'] !== 1 ||
        (int) $administrador['administrator'] !== 1
    ) {
        return $this->response
    ->setStatusCode(403)
    ->setBody(lang('InterfazAula.usuariosPermisoEditar'));
    }

    $usuario = $modeloUsuarios->find($id);

    if (!$usuario) {
        return $this->response
            ->setStatusCode(404)
            ->setBody(lang('InterfazAula.usuariosNoExiste'));
    }

    $puenteVista = new PuenteVistaAula();

    return $puenteVista->mostrar('usuarios.editar', [
    'titulo' => lang('InterfazAula.editarUsuario'),
    'usuario' => array_replace(
        $usuario,
        session('datosUsuarioEditar') ?? []
    ),
    'errores' => session('erroresUsuarioEditar') ?? []
]);
}

public function guardarEdicionUsuario(int $id)
{
    if (session()->get('autenticado') !== true) {
        return redirect()->to('/ingreso');
    }

    $modeloUsuarios = new DatosUsuarioAula();

    $administrador = $modeloUsuarios->find(
        (int) session()->get('usuario_id')
    );

    if (
        !$administrador ||
        (int) $administrador['active'] !== 1 ||
        (int) $administrador['administrator'] !== 1
    ) {
       return $this->response
    ->setStatusCode(403)
    ->setBody(lang('InterfazAula.usuariosPermisoEditar'));
    }

    $usuario = $modeloUsuarios->find($id);

    if (!$usuario) {
        return $this->response
            ->setStatusCode(404)
            ->setBody(lang('InterfazAula.usuariosNoExiste'));
    }

    $nombre = trim((string) $this->request->getPost('name'));
    $correo = strtolower(trim((string) $this->request->getPost('email')));
    $contrasena = (string) $this->request->getPost('password');
    $repetirContrasena = (string) $this->request->getPost('password_repeat');
    $rolAcademico = (string) $this->request->getPost('academic_role');

    $activo = $this->request->getPost('active') === '1' ? 1 : 0;
    $esAdministrador = $this->request->getPost('administrator') === '1' ? 1 : 0;

    $datosEntrada = [
        'name' => $nombre,
        'email' => $correo,
        'academic_role' => $rolAcademico
    ];

    $reglas = [
    'name' => [
        'rules' => 'required|min_length[3]|max_length[100]',
        'errors' => [
            'required' => lang('InterfazAula.registroNombreObligatorio'),
            'min_length' => lang('InterfazAula.registroNombreCorto'),
            'max_length' => lang('InterfazAula.registroNombreLargo')
        ]
    ],
    'email' => [
        'rules' => 'required|valid_email|max_length[150]',
        'errors' => [
            'required' => lang('InterfazAula.registroCorreoObligatorio'),
            'valid_email' => lang('InterfazAula.registroCorreoInvalido'),
            'max_length' => lang('InterfazAula.perfilCorreoLargo')
        ]
    ],
    'academic_role' => [
        'rules' => 'required|in_list[student,teacher]',
        'errors' => [
            'required' => lang('InterfazAula.usuariosRolObligatorio'),
            'in_list' => lang('InterfazAula.usuariosRolInvalido')
        ]
    ]
];

    if ($contrasena !== '' || $repetirContrasena !== '') {
        $datosEntrada['password'] = $contrasena;
        $datosEntrada['password_repeat'] = $repetirContrasena;

        $reglas['password'] = [
    'rules' => 'required|min_length[8]',
    'errors' => [
        'required' => lang('InterfazAula.registroContrasenaObligatoria'),
        'min_length' => lang('InterfazAula.registroContrasenaCorta')
    ]
];

$reglas['password_repeat'] = [
    'rules' => 'required|matches[password]',
    'errors' => [
        'required' => lang('InterfazAula.registroRepeticionObligatoria'),
        'matches' => lang('InterfazAula.registroContrasenasDistintas')
    ]
];
    }

    $errores = [];

    if (!$this->validateData($datosEntrada, $reglas)) {
        $errores = array_values($this->validator->getErrors());
    }

    $correoExistente = $modeloUsuarios
        ->where('email', $correo)
        ->where('id !=', $id)
        ->first();

    if ($correoExistente) {
        $errores[] = lang('InterfazAula.usuariosCorreoDuplicado');
    }

    if (
        $id === (int) $administrador['id'] &&
        ($activo !== 1 || $esAdministrador !== 1)
    ) {
        $errores[] = lang('InterfazAula.usuariosNoAutodesactivar');
    }

    $usuario['name'] = $nombre;
    $usuario['email'] = $correo;
    $usuario['academic_role'] = $rolAcademico;
    $usuario['active'] = $activo;
    $usuario['administrator'] = $esAdministrador;

    $puenteVista = new PuenteVistaAula();

    if (!empty($errores)) {
    return redirect()
        ->to('/usuarios/' . $id . '/editar')
        ->with('erroresUsuarioEditar', $errores)
        ->with('datosUsuarioEditar', [
            'name' => $nombre,
            'email' => $correo,
            'academic_role' => $rolAcademico,
            'active' => $activo,
            'administrator' => $esAdministrador
        ]);
}

    $cambios = [
        'name' => $nombre,
        'email' => $correo,
        'academic_role' => $rolAcademico,
        'active' => $activo,
        'administrator' => $esAdministrador
    ];

    if ($contrasena !== '') {
        $cambios['password'] = password_hash($contrasena, PASSWORD_DEFAULT);
        $cambios['remember_token_hash'] = null;
        $cambios['remember_expires_at'] = null;
    }

    $resultado = $modeloUsuarios->update($id, $cambios);

    if ($resultado === false) {
    return redirect()
        ->to('/usuarios/' . $id . '/editar')
        ->with('erroresUsuarioEditar', [
            lang('InterfazAula.usuariosErrorActualizar')
        ])
        ->with('datosUsuarioEditar', [
            'name' => $nombre,
            'email' => $correo,
            'academic_role' => $rolAcademico,
            'active' => $activo,
            'administrator' => $esAdministrador
        ]);
}

    if ($id === (int) $administrador['id']) {
        session()->set([
            'usuario_nombre' => $nombre,
            'rol_academico' => $rolAcademico,
            'es_administrador' => true
        ]);
    }

    return redirect()
        ->to('/usuarios/' . $id)
        ->with('mensajeUsuarios', lang('InterfazAula.usuariosActualizado'));
}

public function desactivarUsuario(int $id)
{
    if (session()->get('autenticado') !== true) {
        return redirect()->to('/ingreso');
    }

    $modeloUsuarios = new DatosUsuarioAula();

    $administrador = $modeloUsuarios->find(
        (int) session()->get('usuario_id')
    );

    if (
        !$administrador ||
        (int) $administrador['active'] !== 1 ||
        (int) $administrador['administrator'] !== 1
    ) {
        return $this->response
            ->setStatusCode(403)
            ->setBody(lang('InterfazAula.usuariosPermisoDesactivar'));
    }

    if ($id === (int) $administrador['id']) {
        return redirect()
            ->to('/usuarios/' . $id)
            ->with('mensajeUsuarios', lang('InterfazAula.usuariosNoDesactivarPropiaCuenta'));
    }

    $usuario = $modeloUsuarios->find($id);

    if (!$usuario) {
        return $this->response
            ->setStatusCode(404)
            ->setBody(lang('InterfazAula.usuariosNoExiste'));
    }

    if ((int) $usuario['active'] === 0) {
        return redirect()
            ->to('/usuarios/' . $id)
            ->with('mensajeUsuarios', lang('InterfazAula.usuariosYaInactivo'));
    }

    $resultado = $modeloUsuarios->update($id, [
        'active' => 0,
        'remember_token_hash' => null,
        'remember_expires_at' => null
    ]);

    if ($resultado === false) {
        return redirect()
            ->to('/usuarios/' . $id)
            ->with('mensajeUsuarios', lang('InterfazAula.usuariosErrorDesactivar'));
    }

    return redirect()
        ->to('/usuarios/' . $id)
        ->with('mensajeUsuarios', lang('InterfazAula.usuariosDesactivado'));
}

}