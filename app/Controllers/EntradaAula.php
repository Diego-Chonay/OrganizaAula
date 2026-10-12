<?php

namespace App\Controllers;

use App\Libraries\PuenteVistaAula;

class EntradaAula extends BaseController
{
    public function mostrarInicio()
    {
        $puenteVista = new PuenteVistaAula();

       $datosPortada = [
    'titulo' => 'OrganizaAula',
    'mensaje' => 'Bienvenido al organizador de tareas escolares',
    'autenticado' => session()->get('autenticado') === true,
    'nombreUsuario' => session()->get('usuario_nombre')
];

        return $puenteVista->mostrar('portada_aula', $datosPortada);
    }
}
