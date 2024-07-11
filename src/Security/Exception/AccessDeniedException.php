<?php
namespace Fagathe\Framework\Security\Exception;

use Fagathe\Framework\Exception\Exception;

final class AccessDeniedException extends Exception
{

    private const DEFAULT_ACCESS_DENIED_MESSAGE = 'You are not allowed to access this resource.';

    public function __construct(private ?string $message = null)
    {
        $this->message = $message ?? self::DEFAULT_ACCESS_DENIED_MESSAGE;
        $this->code = 403;
        $this->statusText = "Access denied";
        $this->name = 'AccessDeniedException';
    }

}