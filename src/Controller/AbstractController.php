<?php
namespace Fagathe\Framework\Controller;

use Fagathe\Framework\Form\Form;
use Fagathe\Framework\Http\Session;
use Fagathe\Framework\Logger\Logger;
use Fagathe\Framework\Router\UrlGenerator;
use Fagathe\Framework\Twig\Twig;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController
{
    /**
     * @return Logger
     */
    protected function getLogger(): Logger
    {
        return new Logger;
    }

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
     * @param string $name
     * @param array $parameters
     * @param bool $referenceType
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
    protected function render(string $path, array $data = [], int $status = Response::HTTP_OK): Response
    {

        $response = new Response();
        $response->headers->set("Content-Type", "text/html");
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

    /**
     * @param string $route
     * @param array $params
     * @param int $status
     * 
     * @return RedirectResponse
     */
    protected function redirectToRoute(string $route, array $params = [], int $status = Response::HTTP_FOUND): RedirectResponse
    {
        return $this->redirect($this->generateUrl($route, $params), $status);
    }

    /**
     * redirect
     *
     * @param  mixed $url
     * @param  mixed $status
     * 
     * @return Response
     */
    protected function redirect(string $url, int $status = Response::HTTP_FOUND): RedirectResponse
    {
        $response = new RedirectResponse($url, $status);
        return $response->send();
    }

    /**
     * @param string $class
     * @param mixed|null $data
     * 
     * @return Form
     */
    public function createForm(string $class, mixed $data = null): Form
    {
        $form = (new $class)->build();
        $form->setData($data ?? []);

        return $form;
    }

    /**
     * @param string $message
     * @param string $type
     * 
     * @return void
     */
    public function addFlash(string $message, string $type = "info"): void
    {
        $this->getSession()->addFlash($message, $type);
    }

    /**
     * @return Session
     */
    public function getSession(): Session
    {
        return new Session;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return Request::createFromGlobals();
    }

}