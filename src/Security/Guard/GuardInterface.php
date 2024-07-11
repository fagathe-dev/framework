<?php
namespace Fagathe\Framework\Security\Guard;

use Fagathe\Framework\Security\Badge\Badge;
use Symfony\Component\HttpFoundation\Response;

interface GuardInterface
{
    public function authenticate(): Badge;

    public function supports(): bool;

    public function getLoginUrl(): string;

    public function onAuthenticationFailure(): Response;

    public function onAuthenticationSuccess(): Response;
}