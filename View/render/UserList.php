<?php

namespace App\Menu\View\render;


/**
 * Construit tous les éléments liés à la gestion des Admin
 */
class UserList
{
    private $list;
    private $admins;


    public function __construct($admins)
    {
        $this->admins = $admins;
    }



    /**
     * Construit un tableau contenant tous les admin
     *
     * @param  mixed $categoryId Id de la catégorie
     * @return string
     */
    public function renderAdminList(): string
    {
        $this->list .= "<div class='container ml-4 mr-4 mt-4 mb-4 pb-2 pt-2'>
                            <h1 class='text-center'>Administrateurs</h1>
                        </div>
    
                        <div class='container mt-4 pt-2 pb-2 col-12 col-sm-10 col-md-10 col-lg-8 col-xl-6'>
                            <div class='col-auto p-4 text-center'>
                                <a href='index.php?controller=user&action=add' type='button' class='btn btn-success mb-4 text-center'>New account</a>
                            </div>
    
                            <table class='table table-striped'>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Account</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>";

        // Boucle sur la liste des items
        foreach ($this->admins as $row) {
            $adminId = $row['adminId'];
            $adminEmail = $row['adminEmail'];
            $adminName = $row['adminName'];
            $adminSuper = $row['adminSuper'];

            $adminUser = $this->isSuperAdmin($adminSuper);

            //Construit l'url avec les parametres 
            $editUrl = "index.php?controller=user&action=edit"
                . "&parm0=" . urlencode($adminId)
                . "&parm1=" . urlencode($adminName)
                . "&parm2=" . urlencode($adminEmail);

            //Construit l'url avec les parametres 
            $deleteUrl = "index.php?controller=user&action=delete"
                . "&parm0=" . urlencode($adminId)
                . "&parm1=" . urlencode($adminName)
                . "&parm2=" . urlencode($adminEmail);

            $this->list .= "<tr id='adminId$adminId'>
                                <td><span>$adminId</span></td>
                                <td><span id='adminName$adminId'>$adminName</span></td>
                                <td><span id='adminEmail$adminId'>$adminEmail</span></td>
                                <td><span id='adminSuper$adminId'>$adminUser</span></td>
                                <td>
                                    <a href='$editUrl' type='button' class='btn btn-sm btn-primary editAdmin'>
                                        <i class='fa-solid fa-pen-to-square' style='color:white'></i>
                                    </a>
                                </td>
                                <td>
                                    <a href='$deleteUrl' type='button' class='btn btn-sm btn-danger deleteAdmin'>
                                        <i class='fa-solid fa-trash' style='color:white'></i>
                                    </a>
                                </td>
                            </tr>";
        }

        $this->list .= "</tbody></table></div>";

        return $this->list;
    }


    /**
     * Construit le formulaire d'ajout d'admin
     *
     * @return string
     */
    public static function renderFormAccount(): string
    {
        $form = '';

        $form .= "
        <div class='position-absolute top-50 start-50 translate-middle'>
            <h4 class='pb-4'>Renseignez vos informations</h4>

            <form class=' justify-content' id='newAccountLogin'  method='post' action='#'>
 
                <div class='col-auto p-2'>
                    <label for='adminName'>Name</label>
                    <input type='text' class='form-control' name='adminName' id='adminName' value='{parm1}' {readonly} required>
                </div>
                <div class='col-auto p-2'>
                    <label for='adminEmail'>Email</label>
                    <input type='email' class='form-control' name='adminEmail' id='adminEmail' value='{parm2}' {readonly} required>
                </div>";

        // Si c'est un nouveau compte, affichage de l'input password
        if ($_GET['action'] === 'add') {
            $form .= "<div class='col-auto p-2'>
                    <label for='adminPassword''>Password</label>
                    <input type='password' class='form-control' name='adminPassword' id='adminPassword' value='{parm3}' {readonly} required>
                </div>";
        }
        $form .= "<div class='col-auto p-4 text-center'>
                    <a href='index.php?controller=user&action=list' type='button' class='btn btn-primary mb-3' style='width:100px'>Précédent</a>
                    <button type='submit' class='btn btn-{buttonStyle} mb-3' name='action' style='width:100px' value='{action}' >{libAction}</button>
                </div>
            </form>

        </div>";

        return $form;
    }


    /**
     * Construit le bloc d'erreur si l'admin n'a pas les accès
     *
     * @return string
     */
    public static function renderAlertAdmin(): string
    {
        return "<div class='alert alert-danger' role='alert'>
                   <p class='text-center'>Vous n'avez pas accès<p>
                   <p class='text-center'><a href='index.php?controller=user&action=list'>Revenir à la liste</a></p>
               </div>";
    }


    /**
     * Construit l'erreur si l'admin existe déjà
     *
     * @return string
     */
    public static function renderAlertEmail(): string
    {
        return "<div class='alert alert-danger' role='alert'>
                   <p class='text-center'>L'adresse email est déjà utilisée<p>
                   <p class='text-center'><a href='index.php?controller=user&action=list'>Revenir à la liste</a></p>
               </div>";
    }



    /**
     * Convertit le booléen de la colonne adminSuper
     *
     * @param  mixed $boolen valeur adminSuper de la table connexion
     * @return string
     */
    private function isSuperAdmin(int $isSuperAdmin): string
    {
        $adminProfile = "";

        if ($isSuperAdmin == 1) {
            $adminProfile = "SuperAdmin";
        } else {
            $adminProfile = "Admin";
        }
        return $adminProfile;
    }
}
