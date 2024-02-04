<?php
namespace Fagathe\Framework\Exception;

use Exception as GlobalException;
use Fagathe\Framework\Twig\Twig;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class Exception extends GlobalException
{
    protected string $statusText = 'Internal Error';

    public function __construct(){
        $this->message = 'An exception occured when try to respond to your request !'; 
        $this->code = 500;
    }

    public function getTemplateDir(): string {
        if (APP_ENV !== 'dev') {
            if (defined('CUSTOM_ERROR_TEMPLATE_DIR') && $this->getFilesystem()->exists(CUSTOM_ERROR_TEMPLATE_DIR.DIRECTORY_SEPARATOR.'error.twig')) {
                return CUSTOM_ERROR_TEMPLATE_DIR;
            }
        }

        return ERROR_TEMPLATE_DIR;
    }

    public function getTwig(): Environment
    {
       return (new Twig($this->getTemplateDir()))->getLoader();
    }

    public function getRequest(): Request 
    {
        return Request::createFromGlobals();
    }

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
        ];

        if ($request->headers->get('content-type') === 'application/json') {
            $response = new JsonResponse($context, $this->getCode(), [
                'content-type' => 'application/json'
            ], false);

            return $response->send();
        } 

        $response = new Response();
        $response->headers->set("Content-Type","text/html");
        $response->setStatusCode($this->code);
        $template = $this->getFilesystem()->exists($this->getTemplateDir() . 'error-'. $this->code . '.twig') ? 'error-' . $this->code . '.twig' : 'error.twig';
        $response->setContent($this->getTwig()->render($template, $context));

        return $response->send();
    }

    public function getFilesystem() :Filesystem 
    {
        return new Filesystem();
    }
    
}