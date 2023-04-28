<?php

namespace App\Menu\Controller;

use App\Menu\Controller\BaseController;
use App\Menu\Model\{
    GroupModel,
    CategoryModel,
    ItemModel
};

use App\Menu\View\GroupView;
use App\Menu\View\render\FormLogin;

/**
 * Gère les actions sur les items
 */
class ItemController extends BaseController
{
    private $view;
    private $groupModel;
    private $categoryModel;
    private $itemModel;

    public function __construct()
    {
        $this->view = new GroupView();
        $this->groupModel = new GroupModel();
        $this->categoryModel = new CategoryModel();
        $this->itemModel = new ItemModel();
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
        $this->itemModel->add();
    }

    public function deleteAction()
    {
        $this->itemModel->delete();
    }

    public function editAction()
    {
        $this->itemModel->edit();
    }
}
