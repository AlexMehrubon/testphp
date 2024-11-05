<?php

namespace App;


class RouterAPI
{
    /**
     * @var array
     */
    private array $routes = [];

    /**
     * @param $method
     * @param $uri
     * @param $callback
     * @return void
     */
    public function addRoute($method, $uri, $callback): void
    {
        $this->routes[$method][$uri] = $callback;
    }

    public function deleteAllRoutes(): void
    {
        $this->routes = [];
    }

    /**
     * @return void
     */
    public function dispatch(): void
    {
        try {
            header('Content-Type: application/json');

            $method = $_SERVER['REQUEST_METHOD'];
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

            $routeFound = false;
            foreach ($this->routes[$method] as $route => $callback) {
                $routePattern = preg_replace('/\{[^\}]+\}/', '(\d+)', $route);
                if (preg_match("~^$routePattern$~", $uri, $matches)) {
                    $routeFound = true;
                    $callback = $this->routes[$method][$route];
                    [$controller, $action] = explode('@', $callback);
                    $controller = "App\\Controllers\\$controller";
                    $controller = new $controller();

                    if (isset($matches[1])) {
                        $_REQUEST['id'] = $matches[1];
                    }

                    $_REQUEST['jsonData'] = json_decode(file_get_contents('php://input'), true);
                    $_REQUEST['files'] = $_FILES;
                    echo $controller->$action($_REQUEST);
                    break;
                }
            }

            if (!$routeFound) {
                http_response_code(404);
                echo json_encode(['error' => 'Not Found']);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}