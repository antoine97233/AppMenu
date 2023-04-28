<?php


namespace App\Menu\View\render;

use App\Menu\Model\UserModel;

/**
 * Construit tous les éléments liés à l'inscription et la connexion
 */
class FormLogin
{


    /**
     * Renvoie le formulaire de connexion d'admin
     *
     * @return string
     */
    public static function renderFormLogin(): string
    {
        return "
        <div id='connexionStatus' class='alert alert-danger' style='display:none'>
           <p class='text-center'>Email ou mot de passe incorrect<p>
        </div>
        <div class='position-absolute top-50 start-50 translate-middle'>
            <form class='row g-3' id='formLogin'>
                <div class='col-auto'>
                    <label for='emailLogin' class='visually-hidden'>Email</label>
                    <input type='text' class='form-control' id='emailLogin' placeholder='email@example.com'>
                </div>
                <div class='col-auto'>
                    <label for='passwordLogin' class='visually-hidden'>Password</label>
                    <input type='password' class='form-control' id='passwordLogin' placeholder='Password'>
                </div>
                <div class='col-auto'>
                    <button type='button' class='btn btn-primary mb-3' id='logButton'>Connexion</button>
                </div>
            </form>
        </div>";
    }



    /**
     * Renvoie le formulaire de connexion à la BDD
     *
     * @return string
     */
    public static function renderFormNewDb(): string
    {
        $form = "";
        $form .= "
        <div class='position-absolute top-50 start-50 translate-middle'>
            <h1 class='pb-4 text-center'>Bienvenue !</h4>

            <form class='justify-content' id='addDatabase' method='post' action='index.php?controller=login&action=createDb'>
                <div class='row align-items-start p-4 border'>


                    <div class='col p-2' >
                       <h4 class='p-2'>Base de données</h4>
                       <div class='col-auto p-2'>
                           <label for='dbServer'>Server</label>
                           <input type='text' class='form-control' name='dbServer' id='dbServer' required>
                       </div>
                       <div class='col-auto p-2'>
                           <label for='dbUser'>User</label>
                           <input type='text' class='form-control' name='dbUser' id='dbUser' required>
                       </div>
                       <div class='col-auto p-2'>
                            <label for='dbPassword'>Password</label>
                            <input type='Password' class='form-control' name='dbPassword' id='dbPassword'>
                       </div>
                       <div class='col-auto p-2'>
                           <label for='dbName'>Database Name</label>
                           <input type='text' class='form-control' name='dbName' id='dbName' required>
                       </div>
                   </div>

                </div>

                <div class='col-auto p-4 text-center'>
                    <button type='submit' class='btn btn-success mb-3' style='width:100px'>Valider</button>
                </div>
            </form>
        </div>";

        return $form;
    }



    /**
     * Renvoie le formulaire d'ajout d'admin
     *
     * @return string
     */
    public static function renderFormNewAccount(): string
    {
        $form = "";
        $form .= "
        <div class='position-absolute top-50 start-50 translate-middle'>
            <h1 class='pb-4 text-center'>Bienvenue !</h4>

            <form class='justify-content' id='addAccount' method='post' action='index.php?controller=login&action=createAcccount'>
            <div class='col p-2' style='background-color: #e3f2fd;'>
            <h4 class='p-2'>Informations</h4>

            <div class='col-auto p-2'>
                <label for='adminName'>Name</label>
                <input type='text' class='form-control' name='adminName' id='adminName' required>
            </div>

            <div class='col-auto p-2'>
                <label for='adminEmail'>Email</label>
                <input type='email' class='form-control' name='adminEmail' id='adminEmail' required>
            </div>

            <div class='col-auto p-2'>
                <label for='adminPassword'>Password</label>
                <input type='password' class='form-control' name='adminPassword' id='adminPassword' required>
            </div>
        </div>

                <div class='col-auto p-4 text-center'>
                   <button type='submit' class='btn btn-success mb-3' style='width:100px'>Valider</button>
                </div>
            </form>

            
        </div>";

        return $form;
    }




    /**
     * Vérifie la session et renvoie vers la page de connexion si admin déconnecté
     *
     * @return void
     */
    public static function checkAdminSession(): void
    {
        if (!isset($_SESSION['adminEmail'])) {
            header('Location: index.php?controller=login');
            exit;
        }
    }


    /**
     * Vérifie si admin a la statut superAdmin
     *
     * @return void
     */
    public static function checkIfSuperAdmin(): void
    {
        self::checkAdminSession();

        $userModel = new UserModel;
        $user = $userModel->getUserByEmail($_SESSION['adminEmail']);

        if (!isset($user['adminSuper']) || !$user['adminSuper']) {
            header('Location:index.php?controller=user&action=errorAccount');
            exit;
        }
    }




    /**
     * Retourne la valeur de l'email de l'admin connecté
     *
     * @return string
     */
    public static function getVarSession(): ?string
    {
        if (isset($_SESSION['adminEmail'])) {
            return $_SESSION['adminEmail'];
        }else {
            return null;
 
        }
    }
}
