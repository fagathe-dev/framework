<?php 
namespace Fagathe\Framework\Controller;

use Fagathe\Framework\Router\UrlGenerator;
use Fagathe\Framework\Twig\Twig;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController 
{
    
    /**
     * getUser
     *
     * @return object
     */
    protected function getUser(): ?object
    {
        return null;
    }
    
    /**
     * generateUrl
     *
     * @param  mixed $route
     * @param  mixed $parameters
     * @param  mixed $referenceType
     * 
     * @return string
     */
    protected function generateUrl(string $name, array $parameters = [], bool $referenceType = true): string
    {
        return (new UrlGenerator())->generate($name, $parameters, $referenceType);
    }
    
    /**
     * Render HTML Response to user
     *
     * @param  mixed $path
     * @param  mixed $data
     * 
     * @return Response
     */
    protected function render(string $path, array $data = [], int $status = Response::HTTP_OK): Response {

        $response = new Response();
        $response->headers->set("Content-Type","text/html");
        $response->setStatusCode($status);

        $response->setContent((new Twig)->getLoader()->render($path, $data));

        return $response->send();
    }
    
    /**
     * json
     *
     * @param  mixed $data
     * @param  mixed $status
     * @param  mixed $headers
     * @param  mixed $json
     * 
     * @return JsonResponse
     */
    protected function json(mixed $data = [], int $status = Response::HTTP_OK, ?array $headers = [], bool $json = false): JsonResponse 
    {
        $response = new JsonResponse((array) $data, $status, $headers, $json);

        return $response->send();
    }

}