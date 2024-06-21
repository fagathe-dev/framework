<?php 
namespace Fagathe\Framework\Router;
use Fagathe\Framework\Helpers\Helpers;
use Fagathe\Framework\Router\Exception\HttpMethodNotFoundException;
use Fagathe\Framework\Router\Exception\HttpRouteNotFoundException;
use Symfony\Component\HttpFoundation\Request;

final class Router 
{

     use RouterTrait;

     private $routes;
     private $params;
     private $action;
     private $request;

     public function __construct(array $routes)
     {
          $this->routes = $routes;
          $this->request = Request::createFromGlobals();
     }

     public function match()
     {          
          // $security = new Security();
          // if($security() === "Unauthorized") return http_response_code(403);
          try {
               $location = $this->request->getPathInfo();

               foreach ( $this->routes as $route ) {
                    $urlPattern = $this->getUrlData($route);
                    if ( preg_match($urlPattern, $location) ) {
                         if (in_array($this->request->getMethod(), $route->getMethods())) {
                              $this->action = $route->getAction();
                              $this->params = $this->mapParams($this->getParams($route),$this->getUrlParams($urlPattern, $location));
                              
                              return $this->execute();
                         }

                         throw new HttpMethodNotFoundException($route->getMethods());
                    }
               }
               throw new HttpRouteNotFoundException();
          } catch (HttpRouteNotFoundException $e) {
               $e->render();
          }  catch (HttpMethodNotFoundException $e) {
               $e->render();
          }  
          return ;
     }

     public function execute()
     {

          $values = explode("@", $this->action);
          $controller = new $values[0]();
          $method = $values[1];
          
          return isset($this->params) ? $controller->$method($this->params) : $controller->$method(); 
     }

}