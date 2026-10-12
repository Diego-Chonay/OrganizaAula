<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'EntradaAula::mostrarInicio');
$routes->get('/registro', 'RegistroEscolarAula::formulario');
$routes->post('/registro/guardar', 'RegistroEscolarAula::guardarCuenta');

