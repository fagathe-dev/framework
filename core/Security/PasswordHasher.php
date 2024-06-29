<?php
namespace Fagathe\Framework\Security;

final class PasswordHasher
{

    public const REGEX = '#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]){8,}#';

    public function __construct() {
    }

    public static function isValid(string $plainPassword):bool 
    {
        return preg_match(static::REGEX, $plainPassword);
    }

    public static function verify(string $plainPassword, string $hash): bool {
        return password_verify($plainPassword, $hash);
    }

    public static function hash(string $password): string {
        return password_hash($password, PASSWORD_HASH_ALGO);
    }
}