<?php 
namespace Fagathe\Framework\Router\Exception;

use Fagathe\Framework\Exception\Exception;

final class HttpMethodNotFoundException extends Exception
{
    public function __construct(private array $allowedMethods) {
        $this->message = sprintf("Unable to access route \"%s\" with %s. \n\r Only %s methods are allowed for this route.", $this->getRequest()->getPathInfo(), $this->getRequest()->getMethod(), join(', ', $allowedMethods));
        $this->code = 405;
        $this->statusText = "Method not allowed";
    }

}