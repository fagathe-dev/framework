<?php
namespace Fagathe\Framework\Env;

use Fagathe\Framework\Exception\Exception;

final class EnvException extends Exception
{
    public function __construct(?string $message = '')
    {
        $this->message = $message;
        $this->code = 500;
        $this->statusText = "Not Found environment variable exception";
        $this->name = 'ENVException';
    }

}