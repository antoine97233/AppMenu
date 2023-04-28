<?php

namespace App\Menu\Controller;

use App\Menu\Model\{
    UserModel,
    NewDbModel,
};

use App\Menu\View\LoginView;

/**
 * Gère les actions de connexion et d'inscription
 */
class LoginController
{
    private $view;
    private $userModel;
    private $newDbModel;
    private $parmPost;

    public function __construct()
    {
        $this->view = new LoginView();
        $this->userModel = new UserModel();
        $this->newDbModel = new NewDbModel();
        $this->parmPost = $_POST;
    }

    /**
     * S'active si aucune action n'est renseignée en controller
     *
     * @return void
     */
    public function showAction()
    {
        // Vérifie si la BDD configurée 
        $tablesExist = $this->newDbModel->checkIfTablesExist();
        if ($tablesExist) {
            // Formulaire de connexion 
            $this->view->displayLogin();
        } else {
            // Formulaire de BDD 
            $this->view->displayNewDb();
        }
    }

    /**
     * S'active à la soumission du formulaire de connexion
     *
     * @return void
     */
    public function connectAction()
    {
        $this->userModel->checkLogin();
    }


    /**
     * S'active à la soumission du formulaire de BDD
     *
     * @return void
     */
    public function createDbAction()
    {
        // Création des tables
        $this->newDbModel->createTables();
        sleep(1);
        //formulaire de nouvel Admin
        $this->view->displayNewAccount();
    }



    /**
     * S'active à la soumission du formulaire de nouvel Admin
     *
     * @return void
     */
    public function createAcccountAction()
    {
        // Ajoute l'administrateur principal
        $this->userModel->add($this->parmPost, 1);
        sleep(1);
        // Formulaire de connexion
        $this->view->displayLogin();
    }



    /**
     * Deconnexion de l'admin
     *
     * @return void
     */
    public function logoutAction()
    {
        session_destroy();
        header("Location:index.php?controller=login");
        exit();
    }
}
