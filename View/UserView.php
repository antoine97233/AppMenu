<?php



namespace App\Menu\View;

use App\Menu\View\render\{
    FormLogin,
    Nav,
    UserList
};



class UserView
{
    private $page;


    public function __construct()
    {
        $this->page = $this->searchHTML('a-header');
        $this->page .= $this->searchNav();
    }



    /**
     * Affiche la liste des admin
     *
     * @param  mixed $adminList liste admin récupérée via le model
     * @return void
     */
    public function displayAdmin($adminList)
    {

        $admin = new UserList($adminList);
        $this->page .= $admin->renderAdminList($admin);

        $this->display();
    }




    /**
     * Remplace les {values} du formulaire par des valeurs d'un tableau $parameters
     *
     * @param  mixed $paramaters tableau des valeurs du formulaire
     * @return void
     */
    public function displayForm($paramaters)
    {
        $this->page .= UserList::renderFormAccount();
        $this->page = str_replace("{parm1}", $paramaters["parm1"], $this->page);
        $this->page = str_replace("{parm2}", $paramaters["parm2"], $this->page);

        if ($_GET['action'] === 'add') {
            $this->page = str_replace("{parm3}", $paramaters["parm3"], $this->page);
        }
        $this->page = str_replace("{action}", $paramaters["action"], $this->page);
        $this->page = str_replace("{buttonStyle}", $paramaters["buttonStyle"], $this->page);
        $this->page = str_replace("{readonly}", $paramaters["readonly"], $this->page);
        $this->page = str_replace("{disabled}", $paramaters["disabled"], $this->page);
        $this->page = str_replace("{libAction}", $paramaters["libAction"], $this->page);
        $this->display();
    }



    /**
     * Update des valeurs du formulaire si ajout
     *
     * @return void
     */
    public function displayAddAccount(): void
    {
        $paramaters = array(
            "parm1" => "",
            "parm2" => "",
            "parm3" => "",
            "action" => "add",
            "buttonStyle" => "success",
            "readonly" => "",
            "disabled" => "",
            "libAction" => "Ajouter"
        );

        $this->displayForm($paramaters);
    }




    /**
     * Update des valeurs du formulaire si édition
     *
     * @param  mixed $paramGet les données de l'admin passés en GET
     * @return void
     */
    public function displayEditAccount(array $paramGet): void
    {

        $paramaters = array(
            "parm0" => $paramGet['parm0'],
            "parm1" => $paramGet['parm1'],
            "parm2" => $paramGet['parm2'],
            "action" => "edit",
            "buttonStyle" => "warning",
            "readonly" => "",
            "disabled" => "disabled",
            "libAction" => "Modifier"
        );

        $this->displayForm($paramaters);
    }


    /**
     * Update des valeurs du formulaire si suppression
     *
     * @param  mixed $paramGet les données de l'admin passés en GET
     * @return void
     */
    public function displayDeleteAccount(array $paramGet): void
    {
        $paramaters = array(
            "parm0" => $paramGet['parm0'],
            "parm1" => $paramGet['parm1'],
            "parm2" => $paramGet['parm2'],
            "action" => "delete",
            "buttonStyle" => "danger",
            "readonly" => "readonly",
            "disabled" => "disabled",
            "libAction" => "Supprimer"
        );
        $this->displayForm($paramaters);
    }




    /**
     * Affiche les erreurs liés aux droit d'admin
     *
     * @return void
     */
    public function displayError(): void
    {
        $this->page .= UserList::renderAlertAdmin();
        $this->display();
    }



    /**
     * Ferme la page et l'affiche
     *
     * @return void
     */
    private function display(): void
    {
        $this->page .= $this->searchHTML('g-footer');
        echo $this->page;
    }




    /**
     * Récupère les éléments HTML statiques
     *
     * @param  mixed $block Nom du bloc HTML
     * @return string
     */
    private function searchHTML($block): string
    {
        $content = file_get_contents('View/elements/' . $block . '.php');
        return $content;
    }




    /**
     * Récupère le fichier Nav et l'affiche
     *
     * @return void
     */
    private function searchNav()
    {
        $admin = FormLogin::getVarSession();
        $content = Nav::renderNav($admin);
        return $content;
    }
}
