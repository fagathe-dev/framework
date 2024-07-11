<?php
namespace Fagathe\Framework\Form\Exception;

use Fagathe\Framework\Exception\Exception;

final class NotAllowedAttributeException extends Exception
{
    public function __construct(?string $message = '')
    {
        $this->message = $message;
        $this->code = 500;
        $this->statusText = "Not Allowed Attribute Exception";
        $this->name = 'NotAllowedAttributeException';
    }

}