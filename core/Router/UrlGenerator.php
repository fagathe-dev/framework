<?php
namespace Fagathe\Framework\Router;

use Fagathe\Framework\Router\Exception\UrlGeneratorException;
use Symfony\Component\HttpFoundation\Request;

final class UrlGenerator
{


    public const ABSOLUTE_URL = 0;

    public const RELATIVE_URL = 1;

    public Request $request;

    public function __construct()
    {
        $this->request = Request::createFromGlobals();
    }

    public function generate(string $name, array $parameters = [], int $referenceType = self::RELATIVE_URL): string
    {
        $routes = APP_ROUTES;
        $path = '';

        try {
            foreach ($routes as $route) {
                if ($route->getName() === $name) {
                    $path = $route->getPath();
                    $queryString = [];

                    if (count($parameters) > 0) {
                        foreach ($parameters as $k => $p) {
                            $pattern = '#{' . $k . '}#';
                            if (preg_match($pattern, $path)) {
                                $path = preg_replace($pattern, $p, $path);
                            } else {
                                $queryString = [...$queryString, is_int($k) ? $p : $k . '=' . $p];
                            }
                        }
                    }

                    $path .= count($queryString) > 0 ? '?' . join('&', $queryString) : '';
                }
            }
            if ($path === '') {
                throw new UrlGeneratorException($name, $parameters);
            }

            if ($referenceType === self::ABSOLUTE_URL) {
                return $this->request->getSchemeAndHttpHost() . $this->request->getBaseUrl() . $path;
            }

        } catch (UrlGeneratorException $e) {
            $e->render();
        }

        return $path;
    }

}