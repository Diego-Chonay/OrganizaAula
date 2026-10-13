<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        // $this->helpers = ['form', 'url'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);
                if (session()->get('autenticado') === true) {
            return;
        }

        $valorRecuerdo = $this->request->getCookie('recuerdo_aula');

        if (!is_string($valorRecuerdo)) {
            return;
        }

        $partesRecuerdo = explode(':', $valorRecuerdo, 2);

        if (
            count($partesRecuerdo) !== 2 ||
            !ctype_digit($partesRecuerdo[0]) ||
            (int) $partesRecuerdo[0] < 1 ||
            strlen($partesRecuerdo[1]) !== 64 ||
            !ctype_xdigit($partesRecuerdo[1])
        ) {
            return;
        }

        $modeloUsuarios = new \App\Models\DatosUsuarioAula();

        $usuario = $modeloUsuarios->find((int) $partesRecuerdo[0]);

        if (
            !$usuario ||
            (int) $usuario['active'] !== 1 ||
            empty($usuario['remember_token_hash']) ||
            empty($usuario['remember_expires_at'])
        ) {
            return;
        }

        $fechaVencimiento = strtotime($usuario['remember_expires_at']);

        if ($fechaVencimiento === false || $fechaVencimiento <= time()) {
            return;
        }

        $hashRecibido = hash('sha256', $partesRecuerdo[1]);

        if (!hash_equals($usuario['remember_token_hash'], $hashRecibido)) {
            return;
        }

        session()->regenerate(true);

        session()->set([
            'usuario_id' => $usuario['id'],
            'usuario_nombre' => $usuario['name'],
            'rol_academico' => $usuario['academic_role'],
            'es_administrador' => $usuario['administrator'],
            'autenticado' => true
        ]);

        // Preload any models, libraries, etc, here.
        // $this->session = service('session');
    }
}
