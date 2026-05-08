<?php

namespace App\Services;

use App\Models\User;

class AuthService {
    private UserService $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function getCurrentUser(int $id): ?User {
        return $this->userService->findById($id);
    }

    public function register(string $username, string $password): void {
        if (empty($username) || empty($password)) {
            throw new \InvalidArgumentException('Username and password cannot be empty');
        }

        if (strlen($username) > 50) {
            throw new \InvalidArgumentException('Username cannot exceed 50 characters');
        }

        if ($this->userService->findByUsername($username)) {
            throw new \InvalidArgumentException("Username $username is taken");
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $this->userService->create($username, $hashedPassword);
    }

    public function login(string $username, string $password): ?User {
        if (empty($username) || empty($password)) {
            throw new \InvalidArgumentException('Username and password cannot be empty');
        }

        $user = $this->userService->findByUsername($username);
        if (!$user) {
            return null;
        }

        if (!password_verify($password, $user->password)) {
            throw new \InvalidArgumentException('Invalid username or password');
        }

        return $user;
    }
}