<?php

final class PasswordHasher
{

    public const REGEX = '#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]){8,}#';

    public function __construct() {
    }

    public function isVadid(string $plainPassword):bool 
    {
        return preg_match(self::REGEX,$plainPassword);
    }

    public function verify(string $plainPassword, string $hash): bool {
        return password_verify($plainPassword, $hash);
    }

    public static function hash(string $password): string {
        return password_hash($password, PASSWORD_ARGON2I);
    }
}