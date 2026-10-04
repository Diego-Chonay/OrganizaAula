<?php

namespace App\Libraries;

use eftec\bladeone\BladeOne;

class PuenteVistaAula
{
    private BladeOne $motorVista;

    public function __construct()
    {
        $rutaVistas = APPPATH . 'Views';
        $rutaCache = WRITEPATH . 'cache/blade';

        $this->motorVista = new BladeOne(
            $rutaVistas,
            $rutaCache,
            BladeOne::MODE_DEBUG
        );
    }

    public function mostrar(string $vista, array $datos = []): string
    {
        return $this->motorVista->run($vista, $datos);
    }
}
