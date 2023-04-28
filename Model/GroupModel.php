<?php

namespace App\Menu\Model;

use App\Menu\Model\Model;
use App\Menu\View\render\GroupList;

use PDOException;
use PDO;
use Exception;


/**
 * Actions sur les groupes de la BDD
 */
class GroupModel extends Model
{

    public function __construct()
    {
        $this->getConnection();
        $this->table = "apm_group_list";
    }


    /**
     * Retourne la liste des groupes de la table
     *
     * 
     * @return array tableau associatif contenant les groupes
     */
    public function getList(): array
    {
        $sql = "SELECT * FROM $this->table";
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
     * Ajoute un groupe dans la table
     *
     * 
     * @return bool false si le paramètre 'save' n'est pas défini, true si l'ajout a été effectué
     */
    public function add(): bool
    {
        if (isset($_POST['save3'])) {
            $groupTitle = htmlspecialchars($_POST['groupTitle']);
            $groupDescription = htmlspecialchars($_POST['groupDescription']);

            $sql = "INSERT INTO $this->table (groupTitle, groupDescription) VALUES (?, ?)";
            $request = $this->connection->prepare($sql);

            try {
                $request->execute([$groupTitle, $groupDescription]);
                $groupId = $this->connection->lastInsertId();
            } catch (PDOException $e) {
                echo $e->getMessage();
            }

            // Construction d'un bloc groupe (bloc principal) pour être affiché en JS de manière asynchrone
            $pushGroupList = GroupList::push_renderGroupList($groupId, $groupTitle, $groupDescription);

            // Construction d'une puce groupe (sidebar) pour être affiché en JS de manière asynchrone
            $pushBulletGroupList = GroupList::push_renderBulletGroupList($groupTitle, $groupId);

            //Renvoie un tableau JSON pour les valeurs de retours en JS
            $response = array("pushBulletGroupList" => $pushBulletGroupList, "pushGroupList" => $pushGroupList);
            echo json_encode($response);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Edite un groupe dans la table
     *
     * 
     * @return bool false si le paramètre 'update' n'est pas défini, true si l'édit a été effectué
     */
    public function edit(): bool
    {
        if (isset($_POST['update3'])) {
            $groupId = $_POST['groupId'];
            $groupTitle = $_POST['groupTitle'];
            $groupDescription = $_POST['groupDescription'];

            $sql = "UPDATE $this->table SET groupTitle=:groupTitle, groupDescription=:groupDescription WHERE groupId=:groupId";
            $request = $this->connection->prepare($sql);
            $request->bindParam(':groupTitle', $groupTitle);
            $request->bindParam(':groupDescription', $groupDescription);
            $request->bindParam(':groupId', $groupId);

            try {
                $request->execute();
            } catch (Exception $e) {
                echo $e->getMessage();
            }

            // Construction d'une puce groupe (sidebar) pour être affiché en JS de manière asynchrone
            $pushBulletGroupList = GroupList::push_renderBulletGroupList($groupTitle, $groupId);

            // Construction d'une nouvelle URL (bloc principal) pour être affiché en JS de manière asynchrone
            $groupUrl = GroupList::newUrl($groupTitle, $groupId);

            //Renvoie un tableau JSON pour les valeurs de retours en JS
            $response = array(
                "groupTitle" => $groupTitle,
                "groupId" => $groupId,
                "groupDescription" => $groupDescription,
                "pushBulletGroupList" => $pushBulletGroupList,
                "groupUrl" => $groupUrl
            );
            echo json_encode($response);
            return true;
        } else {
            return false;
        }
    }


    /**
     * Supprime un groupe dans la table
     *
     * 
     * @return bool false si le paramètre 'delete' n'est pas défini, true si la suppression a été effectuée
     */
    public function delete(): bool
    {
        if (isset($_POST['delete3'])) {
            $groupId = intval($_POST['groupId']);

            $sql = "DELETE FROM $this->table WHERE groupId=?";
            $request = $this->connection->prepare($sql);

            try {
                $request->execute([$groupId]);
            } catch (PDOException $e) {
                $message = $e->getMessage();
                echo $message;
            }

            $response = array("groupId" => $groupId);
            echo json_encode($response);
            return true;
        } else {
            return false;
        }
    }


    /**
     * Construit et renvoie un flux structuré par groupe
     *
     * 
     * @return bool false si le paramètre 'export' n'est pas défini, true si la suppression a été effectuée
     */
    public function export(): bool
    {
        if (isset($_POST['export'])) {
            $groupId = intval($_POST['groupId']);

            $sql = "SELECT e.groupTitle AS groupTitle, c.categoryTitle AS categoryTitle, i.itemTitle AS itemTitle, i.itemDescription AS itemDescription, i.itemPrice AS itemPrice, i.itemImagePath AS itemImagePath
                FROM $this->table e
                JOIN apm_category_list c ON c.groupId = e.groupId
                JOIN apm_item_list i ON i.categoryId = c.categoryId
                WHERE e.groupId = :groupId";

            $request = $this->connection->prepare($sql);

            try {
                $request->execute(['groupId' => $groupId]);
            } catch (PDOException $e) {
                $message = $e->getMessage();
                echo $message;
            }

            $result = $request->fetchAll();

            header('Content-Type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename="group_' . $groupId . '.csv"');

            $csvFile = fopen('php://output', 'w');

            fputcsv($csvFile, ['Entité', 'Catégorie', 'Item_Nom', 'Item_Description', 'Item_Price', 'Item_Image'], ';');

            foreach ($result as $row) {
                $groupTitle = str_replace('"', '', mb_convert_encoding($row['groupTitle'], 'UTF-8'));
                $categoryTitle = str_replace('"', '', mb_convert_encoding($row['categoryTitle'], 'UTF-8'));
                $itemTitle = str_replace('"', '', mb_convert_encoding($row['itemTitle'], 'UTF-8'));
                $itemDescription = str_replace('"', '', mb_convert_encoding($row['itemDescription'], 'UTF-8'));
                $itemPrice = str_replace('"', '', mb_convert_encoding($row['itemPrice'], 'UTF-8'));
                $itemImage = str_replace('"', '', mb_convert_encoding('http://mesapplications/perso/AppMenuFonctionnel/' . $row['itemImagePath'], 'UTF-8'));

                fputcsv($csvFile, [$groupTitle, $categoryTitle, $itemTitle, $itemDescription, $itemPrice, $itemImage], ';');
            }

            fclose($csvFile);
            return true;
            exit;
        } else {
            return false;
        }
    }
}
