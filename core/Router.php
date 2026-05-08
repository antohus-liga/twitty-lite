<?php

namespace Core;

class Router {
    private array $routes = [];

    public function add(string $method, string $path, array $action, bool $protected = false): void {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'action' => $action,
            'protected' => $protected,
        ];
    }

    public function dispatch(string $method, string $path): void {
        foreach ($this->routes as $route) {
            $pattern = '#^' . preg_replace(
                    ['/\{id}/', '/\{(\w+)}/'],
                    ['(\d+)', '([\w]+)'],
                    $route['path']
                ) . '$#';

            if ($route['method'] === $method && preg_match($pattern, $path, $matches)) {
                if ($route['protected']) {
                    if (!isset($_SESSION['user_id'])) {
                        http_response_code(401);
                        echo json_encode(['error' => 'Unauthorized']);
                        return;
                    }
                }
                array_shift($matches);
                [$controller, $methodName] = $route['action'];
                $controller->$methodName(...$matches);
                return;
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'Route not found.']);
    }
}