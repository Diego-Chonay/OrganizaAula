<?php

namespace App\Controllers;

class IdiomaEscolarAula extends BaseController
{
    public function cambiar()
    {
        $idioma = $this->request->getPost('idioma');

        if (!in_array($idioma, ['es', 'en'], true)) {
            return redirect()->back();
        }

        session()->set('idioma_aula', $idioma);

        return redirect()->back();
    }
}