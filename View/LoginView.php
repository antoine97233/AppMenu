<?php



namespace App\Menu\View;

use App\Menu\View\render\FormLogin;





class LoginView
{

    private $page;

    public function __construct()
    {
        $this->page = $this->searchHTML('a-header');
    }



    /**
     * Affiche le formulaire de connexion
     *
     * @return void
     */
    public function displayLogin(): void
    {
        $this->page .= FormLogin::renderFormLogin();

        $this->display();
    }




    /** 
     * Affiche le formulaire d'admin
     *
     * @return void
     */
    public function displayNewAccount(): void
    {
        $this->page .= FormLogin::renderFormNewAccount();
        $this->display();
    }



    /** 
     * Affiche le formulaire de BDD
     *
     * @return void
     */
    public function displayNewDb(): void
    {
        $this->page .= FormLogin::renderFormNewDb();
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
}
