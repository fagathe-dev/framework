<?php

namespace Fagathe\Framework\Router;

trait RouterTrait 
{
    public const DEFAULT_ARG_PATTERN = '([\/A-Za-z0-9\-]+)';

    /**
     * @param array $params
     * @param array $values
     * 
     * @return array
     */
    public function mapParams(array $params, array $values): array
    {
        $mapped = [];
        foreach ($params as $k => $v) {
            $mapped[$v] = $values[$k];
        }
        
        return $mapped;
    }

    /**
     * @param string $pattern
     * @param Route $route
     * 
     * @return array
     */
    public function getParams(Route $route): array
    {
        $pattern = '/{([^}]+)}/'; // Matches anything between curly braces
        preg_match_all($pattern, $route->getPath(), $matches);
        return $matches[1];
    }

    /**
     * Get URL params 
    * @param string $pattern URL pattern
    * @param string $location Current URL
    * @return array
    */
    public static function getUrlParams(string $pattern, string $location):array 
    {
        preg_match_all($pattern, $location, $matches);
        
        if ($matches === false) {
            return [];
        }

        if (count($matches) > 0) {
            unset($matches[0]);
            $matched = [];
            foreach ($matches as $v) {
                $matched[] = join('', $v);
            }
            return $matched;
        }

        return $matches;
    } 


    /**
     * Get URL pattern
    * @param Route $route
    * @return string
    */
    public function getUrlData(Route $route): string
    {
        $pattern = '/{([^}]+)}/'; // Matches anything between curly braces
        $params = $this->getParams($route);
        $pattern  = preg_replace('/\{|\}/', '', $route->getPath());
        
        foreach ($params as $p) {
            if (str_contains($pattern, $p)) {
                if (array_key_exists($p, $route->getRequirements() ?? [])) {
                    $pattern = str_replace($p, '('. $route->getRequirements()[$p] .')', $pattern);
                } else {
                    $pattern = str_replace($p, self::DEFAULT_ARG_PATTERN, $pattern);
                }
            }
        }

        $pattern = "#^" . $pattern . "$#";

        return $pattern;
    }

}