<?php

namespace App\Controllers;

use App\Services\AuthService;

class AuthController {
    private AuthService $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    public function register(): void {
        $data = json_decode(file_get_contents("php://input"), true);

        try {
            $this->authService->register($data['username'], $data['password']);
        } catch (\InvalidArgumentException $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
            return;
        }

        http_response_code(201);
        echo json_encode(['message' => 'User registered']);
    }

    public function login(): void {
        $data = json_decode(file_get_contents("php://input"), true);

        try {
            $user = $this->authService->login($data['username'], $data['password']);
            if (!$user) {
                http_response_code(401);
                echo json_encode(['error' => 'Invalid username or password']);
                return;
            };

            $_SESSION['user_id'] = $user->id;
        } catch (\InvalidArgumentException $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
            return;
        }

        http_response_code(200);
        echo json_encode(['message' => 'User logged in']);
    }
}
