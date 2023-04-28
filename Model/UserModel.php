<?php

namespace App\Menu\Model;

use App\Menu\Model\Model;

use PDOException;
use PDO;
use Exception;



/**
 * Actions sur les admin de la BDD
 */
class UserModel extends Model
{



    public function __construct()
    {
        $this->getConnection();
        $this->table = "apm_admin_list";
    }



    /**
     * Retourne la liste des admin de la table
     *
     * @return array tableau associatif contenant les admin
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
     * Retourne un admin de la table à partir de son email
     *
     * @param string $email Email de l'administrateur
     * @return array|bool Un tableau associatif contenant l'admin sélectionné si un enregistrement est trouvé, sinon false
     */
    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM $this->table WHERE adminEmail = :adminEmail";
        $request = $this->connection->prepare($sql);
        $request->bindParam(":adminEmail", $email);

        try {
            $request->execute();
            return $request->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $msg = 'Error: ' . $e->getMessage();
            die($msg);
        }
    }




    /**
     * Vérifie les logs de connexion dans la table
     * 
     * @return bool false si le paramètre 'connect' n'est pas défini, true si l'ajout a été effectué
     */
    public function checkLogin()
    {
        if (isset($_POST['connect'])) {

            $emailAdminValue = $_POST['emailAdminValue'];
            $mdpAdminValue = $_POST['mdpAdminValue'];

            $emailAdminValue = htmlspecialchars($emailAdminValue);
            $mdpAdminValue = htmlspecialchars($mdpAdminValue);

            $sql = "SELECT * FROM $this->table WHERE adminEmail = :adminEmail";
            $request = $this->connection->prepare($sql);

            $request->bindParam(":adminEmail", $emailAdminValue);

            try {
                $request->execute();
            } catch (PDOException $e) {
                echo $e->getMessage();
            }

            $resultat = $request->fetch(PDO::FETCH_ASSOC);

            // Vérfie la concordence du mot de passe dans la base de donnée
            if ($resultat && password_verify($mdpAdminValue, $resultat['adminPassword'])) {

                //Attribution des valeurs de la table aux superglobales Session ;
                $_SESSION['adminEmail'] = $resultat['adminEmail'];

                $response = array(
                    "connexion" => true,
                );
            } else {
                $response = array(
                    "connexion" => false,
                );
            }
            echo json_encode($response);
            return true;
        } else {
            return false;
        }
    }



    /**
     * Ajoute un admin dans la table
     *
     * @param  mixed $parmPost paramètres du formulaire d'ajout passés en POST
     * @param  mixed $isSuperAdmin booléen pour définir statut Admin
     * @return bool false les paramètres en POST ne sont pas définis, true si l'ajout a été effectué
     */
    public function add($parmPost, $isSuperAdmin): bool
    {
        if (isset($parmPost['adminEmail']) && isset($parmPost['adminPassword']) && isset($parmPost['adminName'])) {


            $adminEmail = $parmPost['adminEmail'];
            $adminPassword = $parmPost['adminPassword'];
            $adminName = $parmPost['adminName'];


            // Vérification du paramètre isSuperAdmin pour attribuer la valeur dans la colonne 
            if ($isSuperAdmin === 1) {
                $adminSuper = 1;
            } else {
                $adminSuper = 0;
            }
            $passwordHash = password_hash($adminPassword, PASSWORD_DEFAULT);

            $sql = "INSERT INTO $this->table (adminEmail, adminPassword, adminName, adminSuper) VALUES (?, ?, ?, ?)";
            $request = $this->connection->prepare($sql);

            try {
                $request->execute([$adminEmail, $passwordHash, $adminName, $adminSuper]);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }

            $message = "";
            if ($isSuperAdmin === 1) {
                $message = "<div class='alert alert-success'>
                     <p class='text-center'>Compte créé avec succès !<p>
                  </div>";
            } else {
                $message = "<div class='alert alert-success'>
                <p class='text-center'>L'admin a été ajouté avec succès!<p>
                <p class='text-center'><a href='index.php?controller=user&action=list'>Revenir à la liste</a></p>
             </div>";
            }
            echo $message;
            return true;
        } else {
            return false;
        }
    }





    /**
     * Supprime un admin de la liste
     *
     * @param  mixed $parmGet paramètres du formulaire d'ajout passés en GET
     * @return bool false les paramètres en POST ne sont pas définis, true si la suppression a été effectuée
     */
    public function delete($parmGet): bool
    {

        if (isset($parmGet['parm0']) && isset($parmGet['parm1']) && isset($parmGet['parm2'])) {

            // Vérifie que l'email lié à l'admin sélectionné ne soit pas le même que celui connecté
            // Empeche donc la suppression de son propre compte
            if ($parmGet['parm2'] !== $_SESSION["adminEmail"]) {
                $adminId = $parmGet['parm0'];

                $sql = "DELETE FROM $this->table WHERE adminId=?";
                $request = $this->connection->prepare($sql);

                try {
                    $request->execute([$adminId]);
                } catch (PDOException $e) {
                    $message = $e->getMessage();
                    echo $message;
                }

                echo "<div class='alert alert-success'>
                         <p class='text-center'>L'admin a été supprimé avec succès!<p>
                         <p class='text-center'><a href='index.php?controller=user&action=list'>Revenir à la liste</a></p>
                      </div>";
            } else {
                echo "<div class='alert alert-danger'>
                <p class='text-center'>Opération impossible<p>
                <p class='text-center'><a href='index.php?controller=user&action=list'>Revenir à la liste</a></p>
             </div>";
            }
            return true;
        } else {
            return false;
        }
    }


    /**
     * Edite un admin de la liste
     *
     * @param  mixed $parmPost paramètres du formulaire d'ajout passés en POST
     * @return bool false les paramètres en POST ne sont pas définis, true si l'édition a été effectuée
     */
    public function edit($parmPost): bool
    {

        if (isset($parmPost['adminEmail']) && isset($parmPost['adminName']) && isset($_GET['parm0'])) {

            
            $adminEmail = htmlspecialchars($parmPost['adminEmail'], ENT_QUOTES, 'UTF-8');
            $adminName = htmlspecialchars($parmPost['adminName'], ENT_QUOTES, 'UTF-8');
            $adminId = $_GET['parm0'];

            $sql = "UPDATE $this->table SET adminEmail=:adminEmail, adminName=:adminName WHERE adminId=:adminId";
            $request = $this->connection->prepare($sql);
            $request->bindParam(':adminEmail', $adminEmail);
            $request->bindParam(':adminName', $adminName);
            $request->bindParam(':adminId', $adminId);

            try {
                $request->execute();
            } catch (Exception $e) {
                echo $e->getMessage();
            }

            echo "<div class='alert alert-success'>
                     <p class='text-center'>L'admin a été modifié avec succès !<p>
                     <p class='text-center'><a href='index.php?controller=user&action=list'>Revenir à la liste</a></p>
                  </div>";
            return true;
        } else {
            return false;
        }
    }
}
