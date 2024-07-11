<?php
namespace Fagathe\Core\Security;

use Fagathe\Framework\Security\Roles;
use Fagathe\Framework\Security\Security;

class RoleManager
{

    private static Security $security;

    public static function acl(string $permittedRole): bool
    {
        static::$security = new Security();
        $user = static::$security->getUser();
        $actionPermissionLevel = Roles::level($permittedRole);
        $userPermissionLevel = Roles::level($user->getRole());

        if ($userPermissionLevel !== null && $actionPermissionLevel !== null) {
            return $userPermissionLevel >= $actionPermissionLevel;
        }

        return false;
    }

}