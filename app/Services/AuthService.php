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
            throw new \InvalidArgumentException('O nome de utilizador e a palavra-passe não podem estar vazios');
        }

        if (strlen($username) > 50) {
            throw new \InvalidArgumentException('O nome de utilizador não pode ter mais de 50 caracteres');
        }

        if (strlen($username) < 6) {
            throw new \InvalidArgumentException('O nome de utilizador deve ter pelo menos 6 caracteres');
        }

        if (strlen($password) < 8) {
            throw new \InvalidArgumentException('A palavra-passe deve ter pelo menos 8 caracteres');
        }

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            throw new \InvalidArgumentException('O nome de utilizador só pode conter letras, números e underscores');
        }

        if ($this->userService->findByUsername($username)) {
            throw new \InvalidArgumentException('O nome de utilizador já está em uso');
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $this->userService->create($username, $hashedPassword);
    }

    public function login(string $username, string $password): ?User {
        if (empty($username) || empty($password)) {
            throw new \InvalidArgumentException('O nome de utilizador e a palavra-passe não podem estar vazios');
        }

        $user = $this->userService->findByUsername($username);
        if (!$user) {
            return null;
        }

        if (!password_verify($password, $user->password)) {
            throw new \InvalidArgumentException('Credenciais inválidas');
        }

        return $user;
    }
}