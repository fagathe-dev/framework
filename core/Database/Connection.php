<?php
namespace Fagathe\Framework\Database;

use Fagathe\Framework\Env\Env;
use \PDO;
use PDOException;

final class Connection
{
    public function __construct()
    {
    }
    public static function getConnection(): PDO
    {
        try {
            $pdo = new PDO('mysql:host=' . Env::getEnv('DB_HOST') . ';port=' . Env::getEnv('DB_PORT') . ';dbname=' . Env::getEnv('DB_NAME') . ';charset=utf8mb4;', Env::getEnv('DB_USER'), Env::getEnv('DB_PASS'));
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOEXCEPTION $e) {
            (new DatabaseException($e->getMessage()))->render();
        }

        return $pdo;
    }

}