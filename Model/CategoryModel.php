<?php

namespace App\Menu\Model;

use App\Menu\Model\Model;
use App\Menu\View\render\CategoryList;

use PDOException;
use PDO;
use Exception;


/**
 * Actions sur les catégories de la BDD
 */
class CategoryModel extends Model
{
    public function __construct()
    {
        $this->getConnection();
        $this->table = "apm_category_list";
    }


    /**
     * Retourne la liste des catégories de la table
     *
     * @return array|null tableau associatif contenant les catégories, ou null si $_GET["parm1"] n'est pas défini
     */
    public function getList(): ?array
    {
        if (isset($_GET["parm1"])) {

            // Permet d'afficher les catégories appartenant à un groupe en particulier
            $groupId = intval($_GET["parm1"]);
            $sql = "SELECT * FROM $this->table WHERE groupId = ? ORDER BY categoryRank ASC";
            $request = $this->connection->prepare($sql);
            try {
                $request->execute([$groupId]);
                return $request->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $msg = 'Error: ' . $e->getMessage();
                die($msg);
            }
        } else {
            return null;
        }
    }

    /**
     * Ajoute une catégorie dans la table 
     * 
     * @return bool false si le paramètre 'save' n'est pas défini, true si l'ajout a été effectué
     */
    public function add(): bool
    {
        if (isset($_POST['save'])) {
            $categoryTitle = htmlspecialchars($_POST['categoryTitle']);
            $categoryDescription = htmlspecialchars($_POST['categoryDescription']);
            $groupId = htmlspecialchars($_POST['groupId']);
            $categoryRank = $_POST['categoryRank'];
            $sql = "INSERT INTO $this->table (categoryTitle, categoryDescription, groupId, categoryRank) VALUES (?, ?, ?, ?)";
            $request = $this->connection->prepare($sql);
            try {
                $request->execute([$categoryTitle, $categoryDescription, $groupId, $categoryRank]);
                $categoryId = $this->connection->lastInsertId();
            } catch (PDOException $e) {
                echo $e->getMessage();
            }

            // Construction d'un bloc catégorie (bloc principal) pour être affiché en JS de manière asynchrone
            $pushCategoryList = CategoryList::push_renderCategoryList($categoryTitle, $categoryDescription, $categoryId, $categoryRank);

            // Construction d'une puce catégorie (sidebar) pour être affiché en JS de manière asynchrone
            $pushBulletCategoryList = CategoryList::push_renderBulletCategoryList($categoryTitle, $categoryId);

            //Renvoie un tableau JSON pour les valeurs de retours en JS
            $response = array(
                "pushCategoryList" => $pushCategoryList,
                "pushBulletCategoryList" => $pushBulletCategoryList,
                "categoryId" => $categoryId,
                "categoryRank" => $categoryRank
            );
            echo json_encode($response);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Edite une catégorie de la table
     *
     * @return bool false si le paramètre 'update' n'est pas défini, true si l'édit a été effectué
     */
    public function edit(): bool
    {
        if (isset($_POST['update'])) {
            $categoryId = $_POST['categoryId'];
            $categoryTitle = $_POST['categoryTitle'];
            $categoryDescription = $_POST['categoryDescription'];
            $sql = "UPDATE $this->table SET categoryTitle=:categoryTitle, categoryDescription=:categoryDescription WHERE categoryId=:categoryId";
            $request = $this->connection->prepare($sql);
            $request->bindParam(':categoryTitle', $categoryTitle);
            $request->bindParam(':categoryDescription', $categoryDescription);
            $request->bindParam(':categoryId', $categoryId);
            try {
                $request->execute();
            } catch (Exception $e) {
                echo $e->getMessage();
            }

            // Construction d'une puce catégorie (sidebar) pour être affiché en JS de manière asynchrone
            $pushBulletCategoryList = CategoryList::push_renderBulletCategoryList($categoryTitle, $categoryId);

            //Renvoie un tableau JSON pour les valeurs de retours en JS
            $response = array(
                "categoryTitle" => $categoryTitle,
                "categoryId" => $categoryId,
                "categoryDescription" => $categoryDescription,
                "pushBulletCategoryList" => $pushBulletCategoryList,
            );
            echo json_encode($response);
            return true;
        } else {
            return false;
        }
    }


    /**
     * Modifie le rank d'une catégorie de la table
     *
     * @return bool false si le paramètre 'upRank' n'est pas défini, true si l'édit a été effectué
     */
    public function editRank(): bool
    {
        if (isset($_POST['upRank'])) {
            $thisCategoryId = $_POST['thisCategoryId'];
            $thisCategoryRankValue = $_POST['thisCategoryRankValue'];
            $action = $_POST['action'];

            // Modifie le rank de la category sélectionnée
            $sql = "UPDATE $this->table SET categoryRank=:thisCategoryRankValue WHERE categoryId=:thisCategoryId";
            $request = $this->connection->prepare($sql);
            $request->bindParam(':thisCategoryId', $thisCategoryId);
            $request->bindParam(':thisCategoryRankValue', $thisCategoryRankValue);

            try {
                $request->execute();
            } catch (Exception $e) {
                echo $e->getMessage();
            }

            $response = array(
                "thisCategoryId" => $thisCategoryId,
                "thisCategoryRankValue" => $thisCategoryRankValue,
                "action" => $action
            );


            // Modifie le rank de la category suivante si on augmente le rank
            if (isset($_POST['nextCategoryRankValue']) && isset($_POST['nextCategoryBlocId'])) {
                $nextCategoryRankValue = $_POST['nextCategoryRankValue'];
                $nextCategoryBlocId = $_POST['nextCategoryBlocId'];

                $sql = "UPDATE $this->table SET categoryRank=:nextCategoryRankValue WHERE categoryId=:nextCategoryBlocId";
                $request = $this->connection->prepare($sql);
                $request->bindParam(':nextCategoryBlocId', $nextCategoryBlocId);
                $request->bindParam(':nextCategoryRankValue', $nextCategoryRankValue);

                $response["nextCategoryRankValue"] = $nextCategoryRankValue;
            }
            // Modifie le rank de la category précédente si on baisse le rank
            elseif (isset($_POST['previousCategoryRankValue']) && isset($_POST['prevCategoryBlocId'])) {
                $previousCategoryRankValue = $_POST['previousCategoryRankValue'];
                $prevCategoryBlocId = $_POST['prevCategoryBlocId'];

                $sql = "UPDATE $this->table SET categoryRank=:previousCategoryRankValue WHERE categoryId=:prevCategoryBlocId";
                $request = $this->connection->prepare($sql);
                $request->bindParam(':prevCategoryBlocId', $prevCategoryBlocId);
                $request->bindParam(':previousCategoryRankValue', $previousCategoryRankValue);

                $response["previousCategoryRankValue"] = $previousCategoryRankValue;
            }

            try {
                $request->execute();
            } catch (Exception $e) {
                echo $e->getMessage();
            }

            //Renvoie un tableau JSON pour les valeurs de retours en JS
            echo json_encode($response);
            return true;
        } else {
            return false;
        }
    }


    /**
     * Supprime une catégorie de la table
     *
     * 
     * @return bool false si le paramètre 'delete' n'est pas défini, true si la suppression a été effectuée
     */
    public function delete(): bool
    {
        if (isset($_POST['delete'])) {
            $categoryId = intval($_POST['categoryId']);

            // Récupère le categoryRank de la catégorie à supprimer
            $sql = "SELECT categoryRank FROM $this->table WHERE categoryId=?";
            $request = $this->connection->prepare($sql);
            $request->execute([$categoryId]);
            $categoryRank = intval($request->fetch(PDO::FETCH_ASSOC)['categoryRank']);

            // Supprime la catégorie
            $sql = "DELETE FROM $this->table WHERE categoryId=?";
            $request = $this->connection->prepare($sql);
            try {
                $request->execute([$categoryId]);
            } catch (PDOException $e) {
                $message = $e->getMessage();
                echo $message;
            }

            // Met à jour les categoryRank de toutes les catégories dont le categoryRank est supérieur à celui de la catégorie supprimée
            $sql = "UPDATE $this->table SET categoryRank = categoryRank - 1 WHERE categoryRank > ?";
            $request = $this->connection->prepare($sql);
            try {
                $request->execute([$categoryRank]);
            } catch (PDOException $e) {
                $message = $e->getMessage();
                echo $message;
            }

            //Renvoie un tableau JSON pour les valeurs de retours en JS
            $response = array("categoryId" => $categoryId);
            echo json_encode($response);
            return true;
        } else {
            return false;
        }
    }



    /**
     * Récupère le categoryRank le plus élevé de la catégorie d'un groupe en particulier
     *
     * @return array|null tableau associatif contenant les rank, ou null si $_GET["parm1"] n'est pas défini
     */
    public function getMaxCategoryRank(): array
    {
        if (isset($_GET["parm1"])) {
            $groupId = intval($_GET["parm1"]);

            $sql = "SELECT MAX(categoryRank) FROM $this->table WHERE groupId = ?";
            $request = $this->connection->prepare($sql);

            try {
                $request->execute([$groupId]);
                return $request->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $msg = 'Error: ' . $e->getMessage();
                die($msg);
            }
        } else {
            return null;
        }
    }
}
