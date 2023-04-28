<?php

namespace App\Menu\View;

use App\Menu\View\render\{
    GroupList,
    CategoryList,
    FormLogin,
    Nav
};


class GroupView
{
    private $page;

    public function __construct()
    {
        $this->page = $this->searchHTML('a-header');
        $this->page .= $this->searchNav();
        $this->page .= $this->searchHTML('c-openBody');
    }


    /**
     * Interface d'affichage de la liste des groupes
     *
     * @param  mixed $groupList Liste des groupes récupérés via le model
     * @param  mixed $categoryList Liste des categories récupérés via le model
     * @return void
     */
    public function displayList(?array $groupList, ?array $categoryList): void
    {
        // Affichage de la Sidebar à gauche
        $group = new GroupList($groupList);
        $this->page .= $group->renderSidebar($categoryList);

        // Affichage de la zone droite contenant les groupes
        $this->page .= $this->searchHTML('e-openBodyContentRight');
        $this->page .= GroupList::renderAddForm();
        $this->page .= $group->renderGroupList();
        $this->page .= $this->searchHTML('f-closeBodyContentRight');

        $this->display();
    }



    /**
     * Interface d'affichage du contenu d'un groupe
     *
     * @param  mixed $groupList Liste des groupes récupérés via le model
     * @param  mixed $categoryList Liste des categories récupérés via le model
     * @return void
     */
    public function displayAll(?array $groupList, ?array $categoryList): void
    {
        // Affichage de la Sidebar à gauche
        $group = new GroupList($groupList);
        $this->page .= $group->renderSidebar($categoryList);

        // Affichage de la zone droite contenant données d'un group
        $this->page .= $this->searchHTML('e-openBodyContentRight');
        $this->page .= $group->renderTitle();
        $this->page .= $group->renderAddCategoryForm();

        // Affichage des category du group
        $category = new CategoryList($categoryList);
        $this->page .= $category->renderCategoryList();
        $this->page .= $this->searchHTML('f-closeBodyContentRight');

        $this->display();
    }



    /**
     * Ferme la page et l'affiche
     *
     * @return void
     */
    private function display(): void
    {
        $this->page .= $this->searchHTML('f-closeBody');
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
