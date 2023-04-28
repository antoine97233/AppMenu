<?php

namespace App\Menu\View\render;

use App\Menu\Model\ItemModel;
use App\Menu\View\render\ItemList;


/**
 * Construit tous les blocs liés aux catégories
 */
class CategoryList
{
   private $categoryList;
   private $bulletedList;
   private $categories;

   public function __construct($categories)
   {
      $this->categories = $categories;
   }



   /**
    * Construit la liste des blocs catégorie
    *
    *  
    * @return string
    */
   public function renderCategoryList(): string
   {

      // Est la zone parente de tous les blocs
      $this->categoryList .= "<div id='displayCategory'>";

      // Boucle sur la liste des category
      foreach ($this->categories as $row) {
         $categoryId = $row['categoryId'];
         $categoryTitle = $row['categoryTitle'];
         $categoryDescription = $row['categoryDescription'];
         $categoryRank = $row['categoryRank'];

         //Récupère la liste des items pour les afficher dans le bloc
         $itemModel = new ItemModel;
         $itemList = $itemModel->getList($categoryId);
         $items = new ItemList($itemList);

         $this->categoryList .= "<div class='container mt-4 pt-4 pb-2 border col-12 col-sm-12 col-md-12 col-lg-12 col-xl-8 rounded' style='background-color: #e3f2fd;' id='pushCategory{$categoryId}' data-id='{$categoryId}'>
               <div class='container mb-4'>
                   <h4 class='text-center' id='title{$categoryId}'>{$categoryTitle}</h4>
               </div>
               <div class='container mb-4'>
                   <p class='text-center' id='desc{$categoryId}'>{$categoryDescription}</p>
               </div>
               <div class='container'>
                   <table class='table rounded border' style='background-color: white;'>
                       <thead>
                           <tr class='d-flex pl-2 pr-2'>
                               <th class='col-3'>Nom</th>
                               <th class='col-5'>Description</th>
                               <th class='col-2'>Prix</th>
                               <th class='col-1'></th>
                               <th class='col-1'></th>
                           </tr>
                       </thead>
                       <tbody id='displayItem{$categoryId}'>{$items->renderItemList($categoryId)}</tbody>
                   </table>
                   {$items::renderItemForm($categoryId)}
               </div>
               <div class='form-group p-2 d-flex justify-content-between'>

                  <div>
                     <button type='button' class='btn btn-primary edit' data-id='{$categoryId}'>Edit</button>
                     <button type='button' class='btn btn-danger deleteCategory' data-id='{$categoryId}'>Delete</button>
                  </div>

                  <div class='d-flex'>
                     <div class='input-group' style='width:40px'>
                        <input type='text' id='inputRank{$categoryId}' class='form-control inputRank' value='{$categoryRank}'  readonly>
                     </div>
                     <div class='p-1 '>
                        <button type='button' class='btn btn-sm btn-primary downRank' data-id='{$categoryId}'><i class='fa-solid fa-arrow-up'></i></button>
                     </div>
                     <div class='p-1'>
                        <button type='button' class='btn btn-sm btn-primary upRank' data-id='{$categoryId}'><i class='fa-solid fa-arrow-down'></i></button>
                     </div>   
                  </div>

               </div>
           </div>";
      }
      $this->categoryList .= "</div>";
      return $this->categoryList;
   }


   /**
    * Renvoie un bloc catégorie en JS de manière asynchrone 
    *
    * @param  mixed $categoryTitle Titre d'une category 
    * @param  mixed $categoryDescription Description d'une category
    * @param  mixed $categoryId Id de la category
    * @return string
    */
   public static function push_renderCategoryList(string $categoryTitle, string $categoryDescription, string $categoryId, string $categoryRank): string
   {
      $category = "";
      $category .= "<div class='container mt-4 pt-4 pb-2 border col-12 col-sm-12 col-md-12 col-lg-12 col-xl-8 rounded' style='background-color: #e3f2fd;' id='pushCategory" . $categoryId . "' data-id='" . $categoryId . "'>
           <div class='container mb-4'>
               <h4 class='text-center' id='title" . $categoryId . "'>" . $categoryTitle . "</h4>
           </div>
           <div class='container mb-4'>
               <p class='text-center' id='desc" . $categoryId . "'>" . $categoryDescription . "</p>
           </div>
           <div class='container '>
               <table class='table rounded border ' style='background-color: white;'>
                   <thead class>
                       <tr class='d-flex pl-2 pr-2'>
                           <th class='col-3'>Nom</th>
                           <th class='col-5'>Description</th>
                           <th class='col-2'>Prix</th>
                           <th class='col-1'></th>
                           <th class='col-1'></th>
                       </tr>
                   </thead>
                   <tbody id='displayItem" . $categoryId . "'>
                   </tbody>
               </table>";

      // Récupère le formulaire d'ajout d'item
      $category .= ItemList::renderItemForm($categoryId);

      $category .= "</div>
              <div class='form-group p-2 d-flex justify-content-between'>
               
                  <div>
                     <button type='button' class='btn btn-primary edit' data-id='" . $categoryId . "'>Edit</button>
                     <button type='button' class='btn btn-danger deleteCategory' data-id='" . $categoryId . "'>Delete</button>
                  </div>

                  <div class='d-flex'>
                     <div class='input-group' style='width:40px'>
                        <input type='text' id='inputRank" . $categoryId . "' class='form-control inputRank' value='" . $categoryRank . "' readonly>
                     </div>
                     <div class='p-1 '>
                        <button type='button' class='btn btn-sm btn-primary downRank' data-id='" . $categoryId . "'><i class='fa-solid fa-arrow-up'></i></button>
                     </div>
                     <div class='p-1'>
                        <button type='button' class='btn btn-sm btn-primary upRank' data-id='" . $categoryId . "'><i class='fa-solid fa-arrow-down'></i></button>
                     </div>   
                  </div>

               </div>
         </div>";

      return $category;
   }


   /** 
    * Construit la liste des puces catégorie affichée dans la Sidebar
    *
    *
    * @return string
    */
   public function renderBulletCategoryList(): string
   {
      $this->bulletedList = "<div class='list-group mt-2 mb-2' id='displayBulletPointCategory'>";

      // Boucle les catégories pour afficher la list à puces
      foreach ($this->categories as $row) {
         $this->bulletedList .= "<a href='#pushCategory" . $row["categoryId"] . "' class='list-group-item list-group-item-action text-secondary' id='category" . $row["categoryId"] . "'>" . $row['categoryTitle'] . "</a>";
      }
      $this->bulletedList .= "</div>";

      return $this->bulletedList;
   }




   /** 
    * Renvoie une  puce catégorie en JS de manière asynchrone pour l'afficher dans la Sidebar
    *
    * @param  mixed $categoryTitle Titre d'une category 
    * @param  mixed $categoryId Id d'une category
    * @return string
    */
   public static function push_renderBulletCategoryList(string $categoryTitle, string $categoryId): string
   {
      return "<a href='#pushCategory" . $categoryId . "' class='list-group-item list-group-item-action text-secondary' id='category" . $categoryId . "'>" . $categoryTitle . "</a>";
   }
}
