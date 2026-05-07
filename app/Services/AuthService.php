<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class AuthService {
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function getCurrentUser(int $id): ?User {
        return $this->userRepository->findById($id);
    }

    public function register(string $username, string $password): void {
        if (empty($username) || empty($password)) {
            throw new \InvalidArgumentException('Username and password cannot be empty');
        }

        if (strlen($username) > 50) {
            throw new \InvalidArgumentException('Username cannot exceed 50 characters');
        }

        if ($this->userRepository->findByUsername($username)) {
            throw new \InvalidArgumentException("Username $username is taken");
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $this->userRepository->create($username, $hashedPassword);
    }

    public function login(string $username, string $password): ?User {
        if (empty($username) || empty($password)) {
            throw new \InvalidArgumentException('Username and password cannot be empty');
        }

        $user = $this->userRepository->findByUsername($username);
        if (!$user) {
            return null;
        }

        if (!password_verify($password, $user->password)) {
            throw new \InvalidArgumentException('Invalid username or password');
        }

        return $user;
    }
}