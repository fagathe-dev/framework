<?php 
namespace Fagathe\Framework\Router\Exception;

use Fagathe\Framework\Exception\Exception;

final class HttpRouteNotFoundException extends Exception
{

    public function __construct() {
        $this->message = sprintf("No Route found route for \"%s %s\"", $this->getRequest()->getMethod(), $this->getRequest()->getPathInfo());
        $this->code = 404;
        $this->statusText = "Not Found";
    }

}