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
    'mensaje' => lang('InterfazAula.bienvenida'),
    'autenticado' => session()->get('autenticado') === true,
    'nombreUsuario' => session()->get('usuario_nombre'),
    'esAdministrador' => (int) session()->get('es_administrador') === 1
];

        return $puenteVista->mostrar('portada_aula', $datosPortada);
    }
}
