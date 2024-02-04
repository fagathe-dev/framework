<?php 
namespace Fagathe\Framework\Router;
use Fagathe\Framework\Helpers\Helpers;
use Fagathe\Framework\Router\Exception\HttpMethodNotFoundException;
use Fagathe\Framework\Router\Exception\HttpRouteNotFoundException;
use Symfony\Component\HttpFoundation\Request;

final class Router 
{

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

               foreach ( $this->routes as $k => $route ) {
                    if ( preg_match(Helpers::getUrlPattern($route->getPath()), $location) ) {
                         $path = $route->getPath();
                         if (in_array($this->request->getMethod(), $route->getMethods())) {
                              $this->action = $route->getAction();
                              $this->params = Helpers::getUrlParams( explode('/', $path), explode('/', $location) );
                              
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
          // echo "<p>This <b style='color:red;'>'{$this->request->server('REQUEST_URI')}'</b> is not valid.</p><a href='/'> Retourner à la page d'accueil </a>";
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