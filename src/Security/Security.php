<?php
namespace Fagathe\Framework\Security;

use App\Entity\User;
use App\Model\UserModel;
use Fagathe\Framework\Http\Session;
use Fagathe\Framework\Security\Exception\AccessDeniedException;
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
     * @param null|object $user
     * 
     * @return bool
     */
    private function checkSecurityValidity(?object $user): bool
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
    public function getUser(): ?UserInterface
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
        try {
            $user = $this->getUser();
            if ($user === null) {
                throw new UnauthorizedException();
            }
            $userRole = $user->getRole();

            if ($userRole === null || $userRole === $role) {
                throw new AccessDeniedException();
            }

            return true;
        } catch (SecurityException $e) {
            $e->render();
            exit(0);
        } catch (AccessDeniedException $e) {
            $e->render();
            exit(0);
        } catch (UnauthorizedException $e) {
            $e->render();
            exit(0);
        }

    }

}