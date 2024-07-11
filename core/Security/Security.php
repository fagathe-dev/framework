<?php
namespace Fagathe\Framework\Security;

use App\Entity\User;
use App\Model\UserModel;
use Fagathe\Framework\Http\Session;
use Fagathe\Framework\Security\Exception\SecurityException;
use Fagathe\Framework\Security\Exception\UnauthorizedException;

final class Security
{
    private Session $session;
    private UserModel $userModel;

    public function __construct()
    {
        $this->session = new Session();
        $this->userModel = new UserModel();
    }

    /**
     * @param ?object $user
     * 
     * @return bool
     */
    private function checkSecurityValidity(mixed $user): bool
    {
        $classImplements = class_implements($user);
        if ($classImplements !== false && in_array(UserInterface::class, $classImplements)) {
            return true;
        }
        throw new SecurityException();
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        $token = $this->session->get(Auth::AUTH_TOKEN_KEY);
        if (!$token) {
            return null;
        }

        try {
            $decodedToken = base64_decode($token);
            $user = $this->userModel->findOneBy(['token' => $decodedToken]);
            $this->checkSecurityValidity($user);
            return $user;
        } catch (SecurityException $e) {
            $e->render();
            exit;
        }
    }

    /**
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        return $this->getUser() !== null;
    }

    /**
     * @param string $role
     * 
     * @return bool
     */
    public function isGranted(string $role): bool
    {
        $user = $this->getUser();
        if ($user === null) {
            throw new UnauthorizedException();
        } 
        
        if ($user) {
            $roles = $user->getRoles();
            return true;
        }
        if ($roles === null || !in_array($role, $roles)) {
            throw new \Exception('Access denied.');
        }
    }

}