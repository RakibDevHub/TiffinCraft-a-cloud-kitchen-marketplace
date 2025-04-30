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

        if (!isset($this->routes[$path])) {
            http_response_code(404);
            echo "404 Not Found";
            return;
        }

        $handler = $this->routes[$path];
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

        $controller->$method();
    }
}
