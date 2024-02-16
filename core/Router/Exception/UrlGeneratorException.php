<?php 
namespace Fagathe\Framework\Router\Exception;

use Fagathe\Framework\Exception\Exception;

final class UrlGeneratorException extends Exception
{

    public function __construct(private string $routeName, private $parameters = []) {
        $this->message = sprintf("Unable to generate route \"%s\" ", $routeName, $this->getRequest()->getPathInfo());
        if (count($parameters) > 0) {
            $this->message .= sprintf(' with parameters { %s }', join(' => ', $parameters));
        } else {
            $this->message .= '. ';
        }
        $this->code = 500;
        $this->statusText = "Url Generator";
        $this->name = 'URLGeneratorException';
    }

}