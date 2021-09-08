<?php

namespace Core\Router;

class Router
{
    /**
     * @var array Will hold the routing table
     */
    protected array $routes = [];

    /**
     * @var array Will hold the parameters for the matched route
     */
    protected array $params = [];

    /**
     * Add a route to the routing table
     * @param string $route The route string
     * @param array $params The route string parameters
     */
    public function add(string $route, array $params = []) : void
    {
        // Convert the route to a regular expression: escape forward slashes
        $route = preg_replace('/\//', '\\/', $route);

        // Convert variables e.g. {controller}
        $route = preg_replace('/{([a-z]+)}/', '(?P<\1>[a-z-]+)', $route);

        // Convert variables with custom regular expressions e.g {id:\d+}
        $route = preg_replace('/{([a-z]+):([^}]+)}/', '(?P<\1>\2)', $route);

        // Add start and end delimiters, and case-insensitive flag
        $route = '/^' . $route . '$/i';

        $this->routes[$route] = $params;
    }

    public function dispatch(string $url) : void
    {
        if ($this->match($url)) {
            $controller = $this->params['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            $controller = "App\\Controllers\\" . $controller;

            if (class_exists($controller)) {
                $controllerObject = new $controller();

                $action = $this->params['action'];
                $action = $this->convertToStudlyCaps($action);

                if (is_callable([$controllerObject, $action])) {
                    $controllerObject->$action();
                } else {
                    echo "Method $action (in controller $controller) not found.";
                }
            } else {
                echo "Controller $controller not found.";
            }
        } else {
            echo 'No route matched.';
        }
    }

    /** Match the route to the routes in the routing table, setting the
     * $params field if a route is found
     *
     * @param string $url The route URL
     *
     * @return bool true if a match is found, otherwise false
     */
    public function match(string $url) : bool
    {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    /**
     * Get the currently matched parameters
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Get all the routes from the routing table
     * @return array
     */
    public function getRoutes() : array
    {
        return $this->routes;
    }

    protected function convertToCamelCase(string $string) : string
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    protected function convertToStudlyCaps(string $string) : string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }




}