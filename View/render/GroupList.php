<?php

namespace App\Menu\View\render;

use App\Menu\Model\CategoryModel;
use App\Menu\View\render\CategoryList;



/**
 * Construit tous les éléments liés aux groupes
 */
class GroupList
{
    private $sideBar;
    private $title;
    private $form;
    private $groupList;

    private $groups;

    public function __construct($groups)
    {
        $this->groups = $groups;
    }



    /**
     * Construit la Sidebar contenant l'arborescence des groupes
     *
     *
     * @param  mixed $categoryList Liste des catégories appartenant au groupe
     * @return string
     */
    public function renderSidebar(?array $categoryList): string
    {
        $menuGroupList = new CategoryList($categoryList);

        $this->sideBar .= "
       <div class='col-md-2 pt-4 border'>
           <ul class='list-group list-group-flush mt-2 mb-2 sticky-top' id='displayBulletPointGroup'>
               <li class='list-group-item d-flex flex-row flex-wrap justify-content-between'><h3>Menus</h3><a class='text-decoration-none text-light btn btn-success btn-sm ' role='button'  id='addGroup' href='index.php?controller=Group&action=list'><i class='fa-solid fa-plus align-middle' style='color:white'></i></a></li>";

        // Boucle sur la liste des catégories
        foreach ($this->groups as $row) {
            $groupId = $row['groupId'];
            $groupTitle = $row['groupTitle'];


            $this->sideBar .= "
           <li class='list-group-item' id='bulletedGroupList" . $groupId . "'> 
               <a class='text-decoration-none text-dark ' href='index.php?controller=Group&action=display&parm1=" . $groupId . "&parm2=" . $groupTitle . "'>" . $groupTitle . "</a>";

            // Si le paramètre parm1 est défini, les catégories s'affichent
            if (isset($_GET["parm1"]) && $groupId == (int)$_GET["parm1"]) {

                // Affiche la liste des catégories du groupe
                $this->sideBar .= $menuGroupList->renderBulletCategoryList();
            }

            // Si le paramètre parm1 n'est pas défini, les catégories ne s'affichent pas
            $this->sideBar .= "</li>";
        }

        $this->sideBar .= "
           </ul> 
       </div>";

        return $this->sideBar;
    }



    /**
     * Construit le formulaire d'ajout d'un groupe
     *
     * @return string
     */
    public static function renderAddForm(): string
    {
        return "
           <div class='container mt-4 p-4  border col-sm-12 col-md-12 col-lg-8 col-xl-6 rounded' style='background-color: rgba(86,61,124,.1)'>
               <div class='container mb-4'>
                   <h4 class='text-center'><span id='actionGroup'>Ajouter</span> un menu</h4>
               </div>
               <form class='form-inline flex flex-column text-center' id='formGroup'>
                   <div class='input-group p-2'>
                       <input type='text' class='form-control border' id='groupTitle' name='groupTitle' placeholder='Le nom de votre restaurant' style='text-align:center' required>
                   </div>
                   <div class='input-group p-2' >
                       <input type='text' class='form-control border' id='groupDescription' name='groupDescription' placeholder='Description' style='text-align:center' required>
                   </div>
                   <div class='form-group p-2'>
                       <button type='button' class='btn btn-success m-auto' id='submitGroup'>Save</button>
                       <button type='button' class='btn btn-warning m-auto' id='updateGroup' style='display: none;'>Modify</button>
                   </div>
               </form>
           </div>";
    }




    /**
     * Renvoie le titre du groupe sélectionné en prenant le paramètre parm2 en compte
     *
     * @return string
     */
    public function renderTitle(): string
    {

        $this->title .= " <div class='container ml-4 mr-4 mt-4 mb-4 pb-2'>
                            <h1 class='text-center'>" . $_GET["parm2"] . "</h1>
                        </div>";
        return $this->title;
    }



    /**
     * Construit le formulaire d'ajout d'une catégorie dans un groupe
     *
     * @return string
     */
    public function renderAddCategoryForm(): string
    {

        // Instanciation du model des catégories pour récupéré la valeur max des rank et les mettre à jour dans le formulaire
        $categoryModel = new CategoryModel;

        $select = "";
        $disabled = "";

        $this->form .= "<div class='container mt-4 p-4  border col-sm-12 col-md-12 col-lg-8 col-xl-6 rounded' style='background-color: rgba(86,61,124,.1)'>
           <div class='container mb-4'>
              <h4 class='text-center'><span id='actionCategory'>Ajouter</span> une catégorie</h4>
           </div>
           <form class='form-inline flex flex-column text-center' id='formCategory'>
   
              <div class='input-group p-2'>
                 <input type='text' class='form-control border' id='categoryTitle' name='categoryTitle' placeholder='Entrées, boissons, plat du jour...' style='text-align:center' required>
              </div>
   
              <div class='input-group p-2' >
                 <input type='text' class='form-control border' id='categoryDescription' name='categoryDescription' placeholder='Description' style='text-align:center' required>
              </div>

              <div class='mb-3 p-2 d-flex justify-content-center row' >
                 <label for='categoryRanking' class='form-label'>Ordre d'apparition</label>
                 <div class='col-sx-4 col-sm-2 col-lg-2'>
                    <input type='number' class='form-control border text-center' id='categoryRank' name='categoryRank' style='text-align:center' value='" . (!empty($categoryModel->getMaxCategoryRank()) ? $categoryModel->getMaxCategoryRank()[0]['MAX(categoryRank)'] + 1 : 1) . "' readonly required>
                 </div>
              </div>


   
              <div class='input-group p-2' style='display:none'>
                 <select class='form-control text-center' id='groupId' name='groupId' readonly >";

        // Input caché qui permet d'ajouter la clé étrangère du groupe dont la catégorie fera partie
        foreach ($this->groups as $row) {
            if ($row['groupId'] == (int)$_GET["parm1"]) {
                $select = "selected";
                $disabled = "";
            } else {
                $select = "";
                $disabled = "disabled";
            }
            $this->form .= "<option value='" . (int)$row['groupId'] . "'" . $select . " " . $disabled . " >" . $row['groupTitle'] . "</option> ";
        }
        $this->form .= "</select>
                </div>
   
              <div class='form-group p-2'>
                 <button type='button' class='btn btn-success m-auto' id='addCategory'>Add</button>
                 <button type='button' class='btn btn-warning m-auto' id='updateCategory' style='display: none;'>Modify</button>
              </div>
           </form>";

        $this->form .= "</div>";
        return $this->form;
    }



    /**
     * Renvoie une puce groupe dans la Sidebar en JS de manière asynchrone
     *
     * @param  mixed $groupTitle Titre du group
     * @param  mixed $groupId Id du group
     * @return string
     */
    public static function push_renderBulletGroupList(string $groupTitle, string $groupId): string
    {

        return "<li class='list-group-item' id='bulletedGroupList" . $groupId . "'> 
                 <a class='text-decoration-none text-dark ' href='index.php?controller=Group&action=display&parm1=$groupId&parm2=$groupTitle'>" . $groupTitle . "</a>
              </li>";
    }



    /** 
     * Renvoie la nouvelle URL qui dirige vers le groupe sélectionné en JS de manière asynchrone
     *
     * @param  mixed $groupTitle Nom du group
     * @param  mixed $groupId Id du group
     * @return string
     */
    public static function newUrl(string $groupTitle, string $groupId): string
    {
        return "index.php?controller=Group&action=display&parm1=$groupId&parm2=$groupTitle";
    }


    /** 
     * Construit la liste des groupes
     *
     * @return string
     */
    public function renderGroupList(): string
    {
        $this->groupList .= "<div id='displayGroup'>";

        // Boucle sur la liste des group
        foreach ($this->groups as $row) {
            $groupId = $row['groupId'];
            $groupTitle = $row['groupTitle'];
            $groupDescription = $row['groupDescription'];

            // Liste des boutons d'action sur le group
            $this->groupList .= "<div class='container mt-4 pt-4 pb-2 border col-12 col-sm-12 col-md-12 col-lg-10 col-xl-8 rounded' style='background-color: #e3f2fd;' id='pushGroup" . $groupId . "'>
                <table class='table table-borderless rounded'>
                    <tbody>
                        <tr>
                            <td class='col-4'><h4 id='title" . $groupId . "'>" . $groupTitle . "</h4></th>
                            <td class='col-5 align-middle'><p id='desc" . $groupId . "'>" . $groupDescription . "</p></td>
                            <td class='col-1 text-center '><button type='button' data-toggle='tooltip' title='Renommer' data-placement='top' class='btn btn-primary btn-sm editGroup' data-id='" . $groupId . "'><i class='fa-solid fa-pen-to-square' style='color:white'></i></button></td>
                            <td class='col-1 text-center '><a class='btn btn-warning btn-sm' role='button' data-toggle='tooltip' title='Editer' data-placement='top' id='redirect" . $groupId . "' data-id='" .  $groupId . "' href='index.php?controller=Group&action=display&parm1=" . $groupId . "&parm2=" . $groupTitle . "'><i class='fa-solid fa-eye' style='color:white'></i></a></td>
                            <td class='col-1 text-center '><button type='button' class='btn btn-info btn-sm exportGroup' data-toggle='tooltip' title='Exporter' data-placement='top' data-id='" .  $groupId . "' ><i class='fa-solid fa-file-export' style='color:white';></i></button></td>
                            <td class='col-1 text-center '><button type='button' class='btn btn-danger btn-sm deleteGroup' data-toggle='tooltip' title='Supprimer' data-placement='top' data-id='" .  $groupId . "' ><i class='fa-solid fa-trash' style='color:white'></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>";
        }
        $this->groupList .= '</div>';
        return $this->groupList;
    }



    /**
     * Renvoie un bloc groupe en JS de manière asynchrone
     *
     * @param  mixed $groupId Id du group
     * @param  mixed $groupTitle Titre du group
     * @param  mixed $groupDescription Description du group
     * @return string
     */
    public static function push_renderGroupList(string $groupId, string $groupTitle, string $groupDescription): string
    {
        return "<div class='container mt-4  pt-4 pb-2 border col-12 col-sm-12 col-md-12 col-lg-10 col-xl-8 rounded  ' style='background-color: #e3f2fd;' id='pushGroup" . $groupId . "'>
            <table class='table table-borderless rounded'>
               <tbody>
                 <tr >
                     <td class='col-4'><h4 id='title" . $groupId . "'>" . $groupTitle . "</h4></th>
                     <td class='col-5 align-middle'><p id='desc" . $groupId . "'>" . $groupDescription . "</p></td>
                     <td class='col-1 text-center '><button type='button' data-toggle='tooltip' title='Renommer' data-placement='top' class='btn btn-primary btn-sm editGroup' data-id='" . $groupId . "'><i class='fa-solid fa-pen-to-square' style='color:white'></i></button></td>
                     <td class='col-1 text-center '><a class='btn btn-warning btn-sm' role='button' data-toggle='tooltip' title='Editer' data-placement='top' id='redirect" . $groupId . "' data-id='" .  $groupId . "' href='index.php?controller=Group&action=display&parm1=" . $groupId . "&parm2=" . $groupTitle . "'><i class='fa-solid fa-eye' style='color:white'></i></a></td>
                     <td class='col-1 text-center '><button type='button' class='btn btn-info btn-sm exportGroup' data-toggle='tooltip' title='Exporter' data-placement='top' data-id='" .  $groupId . "' ><i class='fa-solid fa-file-export' style='color:white';></i></button></td>
                     <td class='col-1 text-center '><button type='button' class='btn btn-danger btn-sm deleteGroup' data-toggle='tooltip' title='Supprimer' data-placement='top' data-id='" .  $groupId . "' ><i class='fa-solid fa-trash' style='color:white'></i></button></td>                
                 </tr>
               </tbody>
            </table>
            </div>";
    }
}
