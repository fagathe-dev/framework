<?php
namespace Fagathe\Framework\Env;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidFileException;
use Fagathe\Framework\Exception\Exception;

final class Env
{
    
    public function __construct()
    {
    }

    /**
     * @return Dotenv
     */
    public static function load(): Dotenv
    {
        try {
            if (!file_exists(DOCUMENT_ROOT . '/.env')) {
                throw new InvalidFileException("Unable to find the .env file");
            }
            $dotenv = Dotenv::createImmutable(DOCUMENT_ROOT);
            $dotenv->load();
        } catch (Exception $e) {
            return $e->render();
        }

        return $dotenv;
    }

    /**
     * @param string $key
     * 
     * @return string
     */
    public static function getEnv(string $key, bool $raiseException = false): string
    {
        try {
            if ($raiseException && !array_key_exists($key, $_ENV)) {
                throw new EnvException("Unable to find environment variable '$key'");
            }
            return $_ENV[$key];
        } catch (EnvException $e) {
            return $e->render();
        }
    }

    public static function setEnv(string $key, string $value): void
    {
        $_ENV[$key] = $value;
    }
}