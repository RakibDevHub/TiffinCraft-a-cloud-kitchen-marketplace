<?php
namespace App\Core;

class Router
{
    private $routes = [];

    public function addRoute($path, $handler)
    {
        $this->routes[$path] = $handler;
    }

    public function dispatch($requestUri)
    {
        $path = parse_url($requestUri, PHP_URL_PATH);

        // Check for exact matches first
        if (isset($this->routes[$path])) {
            $this->callHandler($this->routes[$path]);
            return;
        }

        // Check for parameterized routes
        foreach ($this->routes as $route => $handler) {
            if (strpos($route, '{') !== false) {
                $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $route);
                $pattern = "@^" . $pattern . "$@";

                if (preg_match($pattern, $path, $matches)) {
                    array_shift($matches); // Remove full match
                    $this->callHandler($handler, $matches);
                    return;
                }
            }
        }

        // No route found
        http_response_code(404);
        echo "404 Not Found";
    }

    private function callHandler($handler, $params = [])
    {
        [$controllerName, $method] = explode('@', $handler);
        $controllerClass = "\\App\\Controllers\\$controllerName";

        if (!class_exists($controllerClass)) {
            http_response_code(500);
            echo "Controller $controllerClass not found.";
            return;
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $method)) {
            http_response_code(500);
            echo "Method $method not found in controller $controllerClass.";
            return;
        }

        call_user_func_array([$controller, $method], $params);
    }
}
