<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'EntradaAula::mostrarInicio');
$routes->get('/registro', 'RegistroEscolarAula::formulario');
$routes->post('/registro/guardar', 'RegistroEscolarAula::guardarCuenta');
$routes->get('/activar/(:segment)', 'RegistroEscolarAula::activarCuenta/$1');
$routes->get('/ingreso', 'SesionEscolarAula::formularioIngreso');
$routes->post('/ingreso/validar', 'SesionEscolarAula::validarIngreso');
$routes->post('/salir', 'SesionEscolarAula::cerrarSesion');
