<?php 
namespace Fagathe\Framework\Database;

use \PDO;
use PDOException;

final class Connection {
    private static string $host = DB_HOST;
    private static string $port = DB_PORT;
    private static string $user = DB_USER;
    private static string $pass = DB_PASS;
    private static string $name = DB_NAME;

    public function __construct() {}
    public static function getConnection(): PDO 
    {
        try
        {
            $pdo    = new PDO('mysql:host=' . static::$host . ';port='. static::$port .';dbname=' . DB_NAME . ';charset=utf8mb4;', static::$user, static::$pass);
            $pdo    ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $pdo    ->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        catch (PDOEXCEPTION $e) {
            (new DatabaseException($e->getMessage()))->render();
        }

        return $pdo;
    }

}