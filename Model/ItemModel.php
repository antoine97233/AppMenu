<?php

namespace App\Menu\Model;

use App\Menu\Model\Model;
use App\Menu\View\render\ItemList;

use PDOException;
use PDO;
use Exception;


/**
 * Actions sur les items de la BDD
 */
class ItemModel extends Model
{

    public function __construct()
    {
        $this->getConnection();
        $this->table = "apm_item_list";
    }



    /**
     * Retourne la liste des items d'une catégorie
     *
     * @param  mixed $categoryId clé étrangère de la catégorie
     * @return array tableau associatif contenant les items
     */
    public function getList(int $categoryId): array
    {
        $sql = "SELECT * FROM $this->table WHERE categoryId = " . $categoryId;
        $request = $this->connection->prepare($sql);

        try {
            $request->execute();
            return $request->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $msg = 'Error: ' . $e->getMessage();
            die($msg);
        }
    }




    /**
     * Ajoute un item dans la table 
     * 
     * @return bool false si le paramètre 'save' n'est pas défini, true si l'ajout a été effectué
     */
    public function add(): bool
    {
        if (isset($_POST['save2'])) {
            $itemTitle = htmlspecialchars($_POST['itemTitle']);
            $itemDescription = htmlspecialchars($_POST['itemDescription']);
            $itemPrice = htmlspecialchars($_POST['itemPrice']);
            $categoryId = htmlspecialchars($_POST['categoryId']);

            // Si image ajoutée, enregistrement dans BDD
            if (!empty($_FILES['inputImage']) && $_FILES['inputImage']['error'] == 0) {
                $itemImage = $_FILES['inputImage'];

                // insertion d'un slug image vide
                $sql = "INSERT INTO $this->table (itemTitle, itemDescription, itemPrice, categoryId, itemImagePath) VALUES (?, ?, ?, ?, ?)";
                $request = $this->connection->prepare($sql);
                try {
                    $request->execute([$itemTitle, $itemDescription, $itemPrice, $categoryId, '']);
                    $itemId = $this->connection->lastInsertId();
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }

                // Une fois id créé, construction du slug image à partir de celui-ci
                $itemImagePath = 'itemImages/' . $categoryId . "-" . $itemId . ".jpg";
                move_uploaded_file($itemImage['tmp_name'], $itemImagePath);


                // Modification du slug image
                $sql = "UPDATE $this->table SET itemImagePath = ? WHERE itemId = ?";
                $request = $this->connection->prepare($sql);
                try {
                    $request->execute([$itemImagePath, $itemId]);
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }
            } else {

                // Si aucune image ajoutée, attribution de l'image par défaut
                $itemImagePath = 'itemImages/no-image-avalaible.jpg';

                $sql = "INSERT INTO $this->table (itemTitle, itemDescription, itemPrice, categoryId, itemImagePath) VALUES (?, ?, ?, ?, ?)";
                $request = $this->connection->prepare($sql);
                try {
                    $request->execute([$itemTitle, $itemDescription, $itemPrice, $categoryId, $itemImagePath]);
                    $itemId = $this->connection->lastInsertId();
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }
            }

            // Construction d'une ligne tableau contenant un item pour être affiché en JS de manière asynchrone
            $pushItemList = ItemList::push_renderItemList($itemId, $itemTitle, $itemDescription, $itemPrice, $categoryId);

            //Renvoie un tableau JSON pour les valeurs de retours en JS
            $response = array("itemId" => $itemId, "pushItemList" => $pushItemList, "categoryId" => $categoryId);
            echo json_encode($response);
            return true;
        } else {
            return false;
        }
    }



    /**
     * Supprime un item de la table
     *
     * 
     * @return bool false si le paramètre 'delete' n'est pas défini, true si la suppression a été effectuée
     */
    public function delete(): bool
    {
        if (isset($_POST['delete'])) {
            $itemId = intval($_POST['itemId']);
            $categoryId = intval($_POST['categoryId']);

            // Supression de l'image lié à l'item dans la BDD
            $sql = "SELECT itemImagePath FROM $this->table WHERE itemId=?";
            $request = $this->connection->prepare($sql);
            $request->execute([$itemId]);
            $item = $request->fetch();
            $itemImagePath = $item['itemImagePath'];

            if (!empty($itemImagePath) && file_exists($itemImagePath)) {
                unlink($itemImagePath);
            }


            // Supression de l'item
            $sql = "DELETE FROM $this->table WHERE itemId=?";
            $request = $this->connection->prepare($sql);

            try {
                $request->execute([$itemId]);
            } catch (PDOException $e) {
                $message = $e->getMessage();
                echo $message;
            }

            //Renvoie un tableau JSON pour les valeurs de retours en JS
            $response = array("itemId" => $itemId, "categoryId" => $categoryId);
            echo json_encode($response);
            return true;
        } else {
            return false;
        }
    }


    /**
     * Edite un item dans la table
     *
     * 
     * @return bool false si le paramètre 'update' n'est pas défini, true si l'édit a été effectué
     */
    public function edit(): bool
    {
        if (isset($_POST['update2'])) {
            $itemId = $_POST['itemId'];
            $itemTitle = $_POST['itemTitle'];
            $itemDescription = $_POST['itemDescription'];
            $itemPrice = $_POST['itemPrice'];
            $categoryId = $_POST['categoryId'];

            // Ajoute une image si aucune image n'était lié à l'item
            if (isset($_FILES['inputImage']) && $_FILES['inputImage']['error'] == 0) {
                $itemImagePath = 'itemImages/' . $categoryId . "-" . $itemId . ".jpg";
                move_uploaded_file($_FILES['inputImage']['tmp_name'], $itemImagePath);
            } else {

                // Sélectionne l'image liée à l'item
                $sql = "SELECT itemImagePath FROM $this->table WHERE itemId=?";
                $request = $this->connection->prepare($sql);
                $request->execute([$itemId]);
                $item = $request->fetch();
                $itemImagePath = $item['itemImagePath'];
            }


            // Edite l'item
            $sql = "UPDATE $this->table SET itemTitle=:itemTitle, itemDescription=:itemDescription, itemPrice=:itemPrice, itemImagePath=:itemImagePath  WHERE itemId=:itemId";
            $request = $this->connection->prepare($sql);
            $request->bindParam(':itemId', $itemId);
            $request->bindParam(':itemTitle', $itemTitle);
            $request->bindParam(':itemDescription', $itemDescription);
            $request->bindParam(':itemPrice', $itemPrice);
            $request->bindParam(':itemImagePath', $itemImagePath);

            try {
                $request->execute();
            } catch (Exception $e) {
                echo $e->getMessage();
            }

            //Renvoie un tableau JSON pour les valeurs de retours en JS
            $response = array(
                "itemId" => $itemId,
                "itemTitle" => $itemTitle,
                "itemDescription" => $itemDescription,
                "itemPrice" => $itemPrice,
                "categoryId" => $categoryId
            );

            echo json_encode($response);
            return true;
        } else {
            return false;
        }
    }
}
