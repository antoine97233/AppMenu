<?php

namespace App\Menu\Model;

require_once "config/config.php";

use PDOException;
use PDO;




/**
 * Class parente de connexion à la BDD
 */
abstract class Model
{
    protected $hostname = DB_HOST;
    protected $dbname = DB_NAME;
    protected $username = DB_USER;
    protected $password = DB_PASS;

    protected $request;
    protected $connection;

    protected $table;



    /**
     * Connexion à la BDD
     *
     * @return PDO  instance de PDO représentant la connexion à la base de données.
     */
    public function getConnection(): PDO
    {
        try {
            $this->connection = new PDO("mysql:host=$this->hostname;dbname=$this->dbname;charset=UTF8", $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->connection;
        } catch (PDOException $e) {
            $msg = 'Error: ' . $e->getMessage();
            die($msg);
        }
    }
}
