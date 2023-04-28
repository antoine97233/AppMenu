<?php

namespace App\Menu\Controller;

require_once 'config/init.php';

use App\Menu\View\HomeView;
use App\Menu\View\render\FormLogin;

/**
 * Page d'accueil de l'application après connexion
 */
class HomeController
{
    private $view;

    public function __construct()
    {
        $this->view = new HomeView();
    }

    /**
     * S'active si aucune action n'est renseignée en controller
     *
     * @return void
     */
    public function showAction()
    {
        FormLogin::checkAdminSession();
        $this->view->displayHome();
    }
}
