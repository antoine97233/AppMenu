<?php

namespace App\Menu\Controller;

require_once 'config/init.php';

use App\Menu\Controller\BaseController;
use App\Menu\Model\UserModel;
use App\Menu\View\UserView;
use App\Menu\View\render\UserList;
use App\Menu\View\render\FormLogin;

/**
 * Gère les actions sur les admin
 */
class UserController extends BaseController
{
    private $view;
    private $model;
    private $paramGet;
    private $paramPost;


    public function __construct()
    {
        $this->view = new UserView();
        $this->model = new UserModel();
        $this->paramGet = $_GET;
        $this->paramPost = $_POST;
    }

    //-------------Affichage des données----------------//


    public function listAction()
    {
        FormLogin::checkAdminSession();
        $listAdmin = $this->model->getList();
        $this->view->displayAdmin($listAdmin);
    }

    public function displayAction()
    {
        FormLogin::checkAdminSession();
    }


    //--------------Actions sur les éléments-------------//


    public function addAction()
    {
        // Vérifie si Admin connecté
        FormLogin::checkAdminSession();

        // Vérifie si Admin principal connecté
        FormLogin::checkIfSuperAdmin();

        // Affiche formulaire d'ajout Admin
        $this->view->displayAddAccount();

        // Vérifie les paramètres POST pour identifier l'action à effectuer
        if (isset($this->paramPost["action"]) && $this->paramPost["action"] === 'add') {

            //Vérifie si Email n'est pas déjà existant;
            if (!$this->model->getUserByEmail($this->paramPost["adminEmail"])) {

                // Ajout d'un Admin secondaire
                $this->model->add($this->paramPost, 0);
            } else {

                // Affiche erreur si Email existant
                echo UserList::renderAlertEmail();
            }
        }
    }

    public function editAction()
    {
        // Vérifie si Admin connecté
        FormLogin::checkAdminSession();

        // Vérifie si Admin principal connecté
        FormLogin::checkIfSuperAdmin();

        // Affiche formulaire d'edition Admin avec les valeurs passées en GET
        $this->view->displayEditAccount($this->paramGet);

        // Vérifie les paramètres POST pour identifier l'action à effectuer
        if (isset($this->paramPost["action"]) && $this->paramPost["action"] === 'edit') {

            // Edition admin 
            $this->model->edit($this->paramPost);
        }
    }

    public function deleteAction()
    {
        // Vérifie si Admin connecté
        FormLogin::checkAdminSession();

        // Vérifie si Admin principal connecté
        FormLogin::checkIfSuperAdmin();

        // Affiche formulaire d'edition Admin avec les valeurs passées en GET
        $this->view->displayDeleteAccount($this->paramGet);

        // Vérifie les paramètres POST pour identifier l'action à effectuer
        if (isset($this->paramPost["action"]) && $this->paramPost["action"] === 'delete') {

            // Suppression admin
            $this->model->delete($this->paramGet);
        }
    }


    /**
     * Renvoie les erreurs lorsque Admin secondaire n'a pas les droits
     *
     * @return void
     */
    public function errorAccountAction()
    {
        FormLogin::checkAdminSession();
        $this->view->displayError();
    }
}
