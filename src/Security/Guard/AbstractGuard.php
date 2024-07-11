<?php
namespace Fagathe\Framework\Security\Guard;

use Fagathe\Framework\Security\Badge\Badge;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractGuard implements GuardInterface
{

    protected const LOGIN_URL = '/login';

    /**
     * @return Badge
     */
    public abstract function authenticate(): Badge;

    /**
     * @return bool
     */
    public abstract function supports(): bool;

    /**
     * @return string
     */
    public function getLoginUrl(): string
    {
        return static::LOGIN_URL;
    }

    /**
     * @return Response
     */
    public abstract function onAuthenticationFailure(): Response;

    /**
     * @return Response
     */
    public abstract function onAuthenticationSuccess(): Response;

}