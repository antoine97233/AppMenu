<?php

namespace App\Menu\Model;

use App\Menu\Model\Model;
use PDOException;
use PDO;


/**
 * Actions sur les tables de la BDD
 */
class NewDbModel extends Model
{
    public function __construct()
    {
        $this->getConnection();
    }



    /**
     * Vérifie si l'utilisateur n'a pas déjà les tables configurées pour l'application
     *
     * @return bool true si les tables existent déjà, false si les tables n'existent pas
     */
    function checkIfTablesExist(): bool
    {

        $pdo = $this->getConnection();

        $tables = ['apm_category_list', 'apm_item_list', 'apm_admin_list', 'apm_group_list'];

        $result = $pdo->query('SHOW TABLES');
        $tables_in_database = $result->fetchAll(PDO::FETCH_COLUMN);

        // S'assure que chacune des tables existe 
        foreach ($tables as $table) {
            if (!in_array($table, $tables_in_database)) {
                return false;
            }
        }
        $message = "<div class='alert alert-warning'>
                       <p class='text-center'>Votre BDD est déjà paramétrée, veuillez vous connecter<p>
                    </div>";
        echo $message;
        return true;
    }




    /**
     * Création des tables nécessaires au fonctionnement de l'application
     *
     * @return void
     */
    public function createTables(): void
    {
        try {
            $pdo = $this->getConnection();
            $pdo->query("USE $this->dbname");
            $sql = file_get_contents("SQL/structure.sql");
            $pdo->exec($sql);
        } catch (PDOException $e) {
            $msg = 'Error: ' . $e->getMessage();
            die($msg);
        }
    }
}
