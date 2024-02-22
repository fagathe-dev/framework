<?php 
namespace Fagathe\Framework\Database;

use \PDO;
use PDOException;

final class Connection {

    public static function getConnection(): PDO 
    {
        try
        {
            $pdo    = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4;port=' . DB_PORT .';', DB_USER , DB_PASS );
            $pdo    ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $pdo    ->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        catch (PDOEXCEPTION $e) {
            (new DatabaseException($e->getMessage()))->render();
        }

        return $pdo;
    }

}