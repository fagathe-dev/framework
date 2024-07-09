<?php
namespace Fagathe\Framework\Form\Exception;

use Fagathe\Framework\Exception\Exception;

final class NotAllowedMethodException extends Exception
{
    public function __construct(?string $message = '')
    {
        $this->message = $message;
        $this->code = 500;
        $this->statusText = "Not Allowed Method Exception";
        $this->name = 'NotAllowedMethodException';
    }

}