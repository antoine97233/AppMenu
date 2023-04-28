<?php

namespace App\Menu\Controller;

use App\Menu\Controller\BaseController;
use App\Menu\Model\{
    GroupModel,
    CategoryModel
};

use App\Menu\View\GroupView;
use App\Menu\View\render\FormLogin;


/**
 * Gère les actions sur les catégories
 */
class CategoryController extends BaseController
{
    private $view;
    private $groupModel;
    private $categoryModel;

    public function __construct()
    {
        $this->view = new GroupView();
        $this->groupModel = new GroupModel();
        $this->categoryModel = new CategoryModel();
    }

    //-------------Affichage des données----------------//

    public function listAction()
    {
        FormLogin::checkAdminSession();
        $listGroup = $this->groupModel->getList();
        $listCategory = $this->categoryModel->getList();
        $this->view->displayList($listGroup, $listCategory);
    }

    public function displayAction()
    {
        FormLogin::checkAdminSession();
        $listGroup = $this->groupModel->getList();
        $listCategory = $this->categoryModel->getList();
        $this->view->displayAll($listGroup, $listCategory);
    }

    //--------------Actions sur les éléments-------------//

    public function addAction()
    {
        FormLogin::checkAdminSession();
        $this->categoryModel->add();
    }

    public function deleteAction()
    {
        FormLogin::checkAdminSession();
        $this->categoryModel->delete();
    }

    public function editAction()
    {
        FormLogin::checkAdminSession();
        $this->categoryModel->edit();
    }

    public function editRankAction()
    {
        FormLogin::checkAdminSession();
        $this->categoryModel->editRank();
    }
}
