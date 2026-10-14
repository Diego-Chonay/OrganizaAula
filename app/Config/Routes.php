<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'EntradaAula::mostrarInicio');
$routes->get('/registro', 'RegistroEscolarAula::formulario');
$routes->post('/registro/guardar', 'RegistroEscolarAula::guardarCuenta');
$routes->get('/activar/(:segment)', 'RegistroEscolarAula::activarCuenta/$1');
$routes->get('/ingreso', 'SesionEscolarAula::formularioIngreso');
$routes->get('/recuperar', 'RecuperacionEscolarAula::formularioRecuperacion');
$routes->post('/recuperar/enviar', 'RecuperacionEscolarAula::enviarRecuperacion');
$routes->get('/recuperar/restablecer/(:segment)', 'RecuperacionEscolarAula::formularioRestablecimiento/$1');
$routes->post('/recuperar/actualizar', 'RecuperacionEscolarAula::actualizarContrasena');
$routes->post('/ingreso/validar', 'SesionEscolarAula::validarIngreso');
$routes->post('/salir', 'SesionEscolarAula::cerrarSesion');
$routes->get('/perfil', 'PerfilEscolarAula::mostrarPerfil');
$routes->get('/perfil/confirmar', 'PerfilEscolarAula::confirmarEdicion');
$routes->post('/perfil/confirmar', 'PerfilEscolarAula::verificarContrasena');
$routes->get('/perfil/editar', 'PerfilEscolarAula::mostrarEdicion');
$routes->post('/perfil/guardar', 'PerfilEscolarAula::guardarPerfil');
$routes->get('/perfil/contrasena', 'PerfilEscolarAula::formularioContrasena');
$routes->post('/perfil/contrasena/guardar', 'PerfilEscolarAula::guardarContrasena');
$routes->get('/perfil/imagen', 'PerfilEscolarAula::formularioImagen');
$routes->post('/perfil/imagen/guardar', 'PerfilEscolarAula::guardarImagen');
$routes->post('/perfil/imagen/eliminar', 'PerfilEscolarAula::eliminarImagen');
$routes->get('/usuarios', 'UsuariosEscolaresAula::listarUsuarios');
$routes->get('/usuarios/nuevo', 'UsuariosEscolaresAula::formularioNuevoUsuario');
$routes->post('/usuarios/guardar', 'UsuariosEscolaresAula::guardarNuevoUsuario');
$routes->get('/usuarios/(:num)', 'UsuariosEscolaresAula::verUsuario/$1');
$routes->get('/usuarios/(:num)/editar', 'UsuariosEscolaresAula::formularioEditarUsuario/$1');
$routes->post('/usuarios/(:num)/guardar', 'UsuariosEscolaresAula::guardarEdicionUsuario/$1');
$routes->post('/usuarios/(:num)/desactivar', 'UsuariosEscolaresAula::desactivarUsuario/$1');
$routes->get('/tareas', 'TareasEscolaresAula::listarTareas');
$routes->get('/tareas/nueva', 'TareasEscolaresAula::formularioNuevaTarea');
$routes->post('/tareas/guardar', 'TareasEscolaresAula::guardarNuevaTarea');
$routes->get('/tareas/(:num)', 'TareasEscolaresAula::verTarea/$1');
$routes->get('/tareas/(:num)/editar', 'TareasEscolaresAula::formularioEditarTarea/$1');
$routes->post('/tareas/(:num)/guardar', 'TareasEscolaresAula::guardarEdicionTarea/$1');
$routes->post('/tareas/(:num)/eliminar', 'TareasEscolaresAula::eliminarTarea/$1');