<?php
namespace Fagathe\Framework\Exception;

use Exception as GlobalException;
use Fagathe\Framework\Logger\Logger;
use Fagathe\Framework\Twig\Twig;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class Exception extends GlobalException
{
    protected string $statusText = 'Internal Error';
    protected string $name = 'Exeception';

    public function __construct(){
        $this->message = 'An exception occured when try to respond to your request !'; 
        $this->code = 500;
    }
    
    /**
     * getTemplateDir
     *
     * @return string
     */
    public function getTemplateDir(): string {
        if (APP_ENV !== 'dev') {
            if (defined('CUSTOM_ERROR_TEMPLATE_DIR') && $this->getFilesystem()->exists(CUSTOM_ERROR_TEMPLATE_DIR.DIRECTORY_SEPARATOR.'error.twig')) {
                return CUSTOM_ERROR_TEMPLATE_DIR;
            }
        }

        return ERROR_TEMPLATE_DIR;
    }
    
    /**
     * getTwig
     *
     * @return Environment
     */
    public function getTwig(): Environment
    {
       return (new Twig($this->getTemplateDir()))->getLoader();
    }
    
    /**
     * getRequest
     *
     * @return Request
     */
    public function getRequest(): Request 
    {
        return Request::createFromGlobals();
    }
    
    /**
     * render
     *
     * @return Response
     */
    public function render(): Response
    {
        $request = $this->getRequest();

        $context = [
            'message' => $this->getMessage(),
            'class' => static::class,
            'code' => $this->getCode(),
            'line' => $this->getLine(),
            'previous' => $this->getPrevious(),
            'trace' => $this->getTrace(),
            'file' => $this->getFile(),
            '__toString' => $this->__toString(),
            'traceAsString' => $this->getTraceAsString(),
            'name' => $this->name,
        ];

        $logger = new Logger();
        $logger->error($this->getMessage(), json_encode($context));

        if ($request->headers->get('content-type') === 'application/json') {
            $response = new JsonResponse($context, $this->getCode(), [
                'content-type' => 'application/json'
            ], false);
            $response->setStatusCode($this->code);

            $response->send();
            die;
        } 

        $response = new Response();
        $response->headers->set("Content-Type","text/html");
        $response->setContent($this->getTwig()->render($this->getTemplate(), $context));

        $response->send();
        die;
    }
    
    /**
     * getTemplate
     *
     * @return string
     */
    private function getTemplate(): string 
    {
        if ($this->getFilesystem()->exists($this->getTemplateDir() . 'error-'. $this->code . '.twig')) {
            return 'error-'. $this->code . '.twig';
        }
        
        if (APP_ENV === 'dev') {
            if (defined('CUSTOM_ERROR_TEMPLATE_DIR') && $this->getFilesystem()->exists(CUSTOM_ERROR_TEMPLATE_DIR.DIRECTORY_SEPARATOR.'error-dev.twig')) {
                return 'error-dev.twig';
            } else {
                return 'error-dev.twig';
            }
        }

        return 'error.twig';
    }
    
    /**
     * getFilesystem
     *
     * @return Filesystem
     */
    public function getFilesystem() :Filesystem 
    {
        return new Filesystem();
    }
    
}