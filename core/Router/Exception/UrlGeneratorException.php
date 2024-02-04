<?php 
namespace Fagathe\Framework\Router\Exception;

use Fagathe\Framework\Exception\Exception;

final class UrlGeneratorException extends Exception
{

    public function __construct(private string $name) {
        $this->message = sprintf("Unable to generate route \"%s\". ", $name, $this->getRequest()->getPathInfo());
        $this->code = 500;
        $this->statusText = "Url Generator";
    }

}