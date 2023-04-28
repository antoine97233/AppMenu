<?php

namespace App\Menu\Controller;

abstract class BaseController
{
    /**
     * Affiche la liste des éléments
     * 
     * @return void
     */
    abstract public function listAction();

    /**
     * Affiche le contenu  de la liste
     *
     * @return void
     */
    abstract public function displayAction();

    /**
     * Ajout d'un nouvel élément
     *
     * @return void
     */
    abstract public function addAction();

    /**
     * Modification d'un élément
     *
     * @return void
     */
    abstract public function editAction();

    /**
     * Supression d'un élément
     *
     * @return void
     */
    abstract public function deleteAction();
}
