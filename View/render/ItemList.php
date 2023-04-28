<?php

namespace App\Menu\View\render;


/**
 * Construit tous les blocs en lien avec les items
 */
class ItemList
{
    private $list;
    private $items;


    public function __construct($items)
    {
        $this->items = $items;
    }



    /**
     * construit un tableau des items d'une catégorie 
     *
     * @param  mixed $categoryId Id de la catégorie
     * @return string
     */
    public function renderItemList(?string $categoryId): ?string
    {

        // Boucle sur la liste des items
        foreach ($this->items as $row) {
            $itemId = $row['itemId'];
            $itemTitle = $row['itemTitle'];
            $itemDescription = $row['itemDescription'];
            $itemPrice = $row['itemPrice'];


            $this->list .= " <tr class='d-flex pl-2 pr-2 ' id='pushItem" . $itemId . "''>
                     <td class='col-3'><span id='itemTitle" . $itemId . "'><small>" . $itemTitle . "</small></span></th>
                     <td class='col-5'><span id='itemDescription" . $itemId . "'><small>" . $itemDescription . "</small></span></td>
                     <td class='col-2'><span id='itemPrice" . $itemId . "'><small>" . $itemPrice . "</small></span> €</td>
                     <td class='col-1'><button type='submit'  class='btn btn-sm btn-primary editItem' data-id='" . $itemId . "' data-categoryid='" . $categoryId . "'><i class='fa-solid fa-pen-to-square' style='color:white'></i></button></td>
                     <td class='col-1'><button type='submit' class='btn btn-sm btn-danger deleteItem' data-id='" . $itemId . "' data-categoryid='" . $categoryId . "'  id='deleteItem" . $categoryId . "'><i class='fa-solid fa-trash' style='color:white'></button></td>
                 </tr>";
        }
        return $this->list;
    }



    /**
     * Renvoie une ligne de tableau contenant un item d'une catégorie en JS de manière asynchrone
     *
     * @param  mixed $itemId Id de Item
     * @param  mixed $itemTitle Titre de l'item
     * @param  mixed $itemDescription Description de l'item
     * @param  mixed $itemPrice Prix de l'item
     * @param  mixed $categoryId Id de la category
     * @return string
     */
    public static function push_renderItemList(string $itemId, string $itemTitle, string $itemDescription, string $itemPrice, string $categoryId): string
    {
        return " <tr class='d-flex pl-2 pr-2 ' id='pushItem" . $itemId . "''>
                     <td class='col-3'><span id='itemTitle" . $itemId . "'><small>" . $itemTitle . "</small></span></th>
                     <td class='col-5'><span id='itemDescription" . $itemId . "'><small>" . $itemDescription . "</small></span></td>
                     <td class='col-2'><span id='itemPrice" . $itemId . "'><small>" . $itemPrice . "</small></span> €</td>
                     <td class='col-1'><button type='submit' class='btn btn-sm btn-primary editItem' data-id='" . $itemId . "' data-categoryid='" . $categoryId . "'><i class='fa-solid fa-pen-to-square' style='color:white'></i></button></td>
                     <td class='col-1'><button type='submit' class='btn btn-sm btn-danger deleteItem' data-id='" . $itemId . "' data-categoryid='" . $categoryId . "'  id='deleteItem" . $categoryId . "'><i class='fa-solid fa-trash' style='color:white'></button></td>
                 </tr>";
    }




    /**
     * Construit le formulaire d'ajout d'un nouvel item
     *
     * @param  mixed $categoryId Id de la category
     * @return string
     */
    public static function renderItemForm(string $categoryId): string
    {
        return "<form class='form-inline' id='formItem'>
        <table class='table rounded border table-bordered mt-4' style='background-color: white;'>
            <tbody>
                <tr class='d-flex pl-2 pr-2'>
                    <td class='col-3'><input type='text' class='form-control inputItemTitle{$categoryId}' id='itemTitle{$categoryId}' name='itemTitle{$categoryId}' data-id='{$categoryId}' required></td>
                    <td class='col-5'><input type='text' class='form-control inputItemDescription{$categoryId}' id='itemDescription{$categoryId}' name='itemDescription{$categoryId}' required></td>
                    <td class='col-2'><input type='text' class='form-control inputItemPrice{$categoryId}' id='itemPrice{$categoryId}' name='itemPrice{$categoryId}' required></td>
                    <td class='col-1' style='display: none;'><input type='text' class='form-control' id='categoryId{$categoryId}' name='categoryId{$categoryId}' value='{$categoryId}'></td>
                    <td class='col-2'>
                        <button type='button' class='btn btn-success addItem' id='addItemButton{$categoryId}' data-id='{$categoryId}'><i class='fa-solid fa-plus' style='color:white'></i></button>
                        <button type='button' class='btn btn-warning updateItem' id='updateItemButton{$categoryId}' data-id='{$categoryId}' style='display: none;'><i class='fa-solid fa-check' style='color:white'></i></button>
                    </td> 
                </tr>
                <tr class='d-flex pl-2 pr-2' style='height: 150px;'>
                    <td class='col-8'><input type='file' class='inputImage' id='inputImage{$categoryId}' data-id='{$categoryId}'></td>
                    <td class='col-4' style='overflow:hidden' id='imgPreview{$categoryId}'>
                        <i class='fa-solid fa-image' id='imgDefault{$categoryId}' style='color:rgba(86,61,124,.1);font-size:50px;display:block;'></i>
                        <img id='itemImagePreview{$categoryId}' style='height:150px;width:auto;'>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>";
    }
}
