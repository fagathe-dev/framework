<?php
namespace Fagathe\Framework\Database;

use \PDO, \PDOException;
use Fagathe\Framework\Database\Connection;

class Database 
{

    public function __construct() {
        
    }

    public static function create():bool {
        try {
            $conn = new PDO('mysql:host=' . DB_HOST . ';port='. DB_PORT .';', DB_USER, DB_PASS);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = 'CREATE DATABASE IF NOT EXISTS ' . DB_NAME . ' CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;';
            // use exec() because no results are returned
            $conn->exec($sql);
            echo "Database created successfully<br>";
        } catch(PDOException $e) {
            (new DatabaseException($sql . "<br>" . $e->getMessage()))->render();
        }

        return true;
    }

}