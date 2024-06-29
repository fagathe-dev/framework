<?php
namespace Fagathe\Framework\Security\Guard;

use App\Entity\User;
use App\Model\UserModel;
use Fagathe\Framework\Security\Badge\Badge;
use Fagathe\Framework\Security\CustomAuthenticationMessage;
use Fagathe\Framework\Security\PasswordHasher;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class Guard extends AbstractGuard
{

    private Request $request;
    private ?User $user = null;
    private PasswordHasher $passwordHasher;

    public const AUTHENTICATION_FAILURE_URL = '/login';
    public const AUTHENTICATION_SUCCESS_URL = '/login';

    public function __construct()
    {
        $this->request = Request::createFromGlobals();
        $this->passwordHasher = new PasswordHasher();
    }

    /**
     * @return Badge
     */
    public function authenticate(): Badge
    {
        $userModel = new UserModel();
        $username = $this->request->request->get('username');
        $password = $this->request->request->get('password');

        $user = $userModel->findBy(['username' => $this->request->get('username')]);

        return new Badge();
    }

    /**
     * @return bool
     */
    public function supports(): bool
    {
        return $this->request->getPathInfo() === static::LOGIN_URL && $this->request->isMethod('POST');
    }

    /**
     * @return Response
     */
    public function onAuthenticationFailure(): Response
    {
        new CustomAuthenticationMessage('Identifiants incorrectes.', 'danger');
        return new RedirectResponse(static::AUTHENTICATION_FAILURE_URL);
    }

    /**
     * @return Response
     */
    public function onAuthenticationSuccess(): Response
    {
        new CustomAuthenticationMessage('Identifiants incorrectes.', 'danger');
        return new Response('Authentication success', 401);
    }

    public function getUser(): ?User
    {
        return $this->user;
    }
}