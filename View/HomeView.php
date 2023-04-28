<?php

namespace App\Menu\View;

use App\Menu\View\render\{
    FormLogin,
    Nav
};




class HomeView
{
    private $page;

    public function __construct()
    {

        $this->page = $this->searchHTML('a-header');
        $this->page .= $this->searchNav();
    }


    /**
     * Affiche la home de l'application
     *
     * @return void
     */
    public function displayHome(): void
    {
        $this->page .= $this->searchHTML('i-home');
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
     * @return string
     */
    private function searchNav(): string
    {
        $admin = FormLogin::getVarSession();

        $content = Nav::renderNav($admin);
        return $content;
    }
}
