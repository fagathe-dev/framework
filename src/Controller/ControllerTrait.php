<?php 
namespace Fagathe\Framework\Controller;

use Symfony\Component\HttpFoundation\Request;

trait ControllerTrait 
{
    
    /**
     * getRequest
     *
     * @return Request
     */
    public function getRequest(): Request 
    {
        $request = Request::createFromGlobals();
        
        return $request;
    }
    
    /**
     * getMethod
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->getRequest()->getMethod();
    }

}