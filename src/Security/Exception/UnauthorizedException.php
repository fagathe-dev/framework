<?php
namespace Fagathe\Framework\Security\Exception;

use Fagathe\Framework\Exception\Exception;

final class UnauthorizedException extends Exception
{

    private const DEFAULT_UNAUTHORIZED_MESSAGE = 'You are not allowed to access this resource.';

    public function __construct(private ?string $message = null)
    {
        $this->message = $message ?? self::DEFAULT_UNAUTHORIZED_MESSAGE;
        $this->code = 401;
        $this->statusText = "Unauthorized access";
        $this->name = 'UnauthorizedException';
    }

}