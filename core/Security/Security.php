<?php
namespace Fagathe\Framework\Security;

use App\Entity\User;

final class Security
{
    public function __construct()
    {
    }

    /**
     * @return User|null
     */
    public function getUser():?User
    {
        if (isset($_SESSION['user'])) {
            return unserialize($_SESSION['user']);
        }
        return null;
    } 
}