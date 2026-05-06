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
            if ($route['method'] === $method && $route['path'] === $path) {
                if ($route['protected']) {
                    if (!isset($_SESSION['user_id'])) {
                        http_response_code(401);
                        echo json_encode(['error' => 'Unauthorized']);
                        return;
                    }
                }
                [$controller, $methodName] = $route['action'];
                $controller->$methodName();
                return;
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'Route not found.']);
    }
}