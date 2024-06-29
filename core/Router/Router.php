<?php
namespace Fagathe\Framework\Router;

use Fagathe\Framework\Http\Session;
use Fagathe\Framework\Router\Exception\HttpMethodNotFoundException;
use Fagathe\Framework\Router\Exception\HttpRouteNotFoundException;
use Fagathe\Framework\Security\Security;
use Symfony\Component\HttpFoundation\Request;

final class Router
{

     use RouterTrait;

     private $routes;
     private $params;
     private $action;
     private $request;
     private Session $session;
     private Security $security;
     private array $routerInfos = [];

     public function __construct(array $routes)
     {
          $this->routes = $routes;
          $this->request = Request::createFromGlobals();
          $this->session = new Session();
          $this->security = new Security();
     }

     public function match(): mixed
     {
          // if($security() === "Unauthorized") return http_response_code(403);
          try {
               $location = $this->request->getPathInfo();

               foreach ($this->routes as $route) {
                    $this->setRouteInfos('route.url', $location);
                    $this->setRouteInfos('route.name', $route->getName());
                    $this->setRouteInfos('route.path', $route->getPath());

                    $urlPattern = $this->getUrlData($route);
                    if (preg_match($urlPattern, $location)) {
                         if (in_array($this->request->getMethod(), $route->getMethods())) {
                              $this->action = $route->getAction();
                              $this->params = $this->mapParams($this->getParams($route), $this->getUrlParams($urlPattern, $location));
                              $this->setRouteInfos('controller.class', explode('@', $this->action)[0]);
                              $this->setRouteInfos('controller.method', explode('@', $this->action)[1]);
                              $this->setRouteInfos('route.params', $this->params);

                              return $this->execute();
                         }

                         throw new HttpMethodNotFoundException($route->getMethods());
                    }
               }
               throw new HttpRouteNotFoundException();
          } catch (HttpRouteNotFoundException $e) {
               $e->render();
          } catch (HttpMethodNotFoundException $e) {
               $e->render();
          }
          return null;
     }

     /**
      * @param string $key
      * @param mixed $value
      * 
      * @return void
      */
     private function setRouteInfos(string $key, mixed $value): void
     {
          if (str_contains($key, '.')) {
               $parts = explode('.', $key);
               $this->routerInfos = [
                    ...$this->routerInfos,
                    $parts[0] => [$parts[1] => $value, ...($this->routerInfos[$parts[0]] ?? [])],
               ];
          } else {
               $this->routerInfos = [
                    ...$this->routerInfos,
                    $key => $value,
               ];
          }

          $this->session->set('router', $this->routerInfos);
     }

     /**
      * @return void
      */
     public function execute(): mixed
     {

          $values = explode("@", $this->action);
          $controller = new $values[0]();
          $method = $values[1];

          return isset($this->params) ? $controller->$method($this->params) : $controller->$method();
     }

}