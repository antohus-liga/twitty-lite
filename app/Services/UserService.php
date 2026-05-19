<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class UserService {
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function create(string $username, string $password): void {
        $this->userRepository->create($username, $password);
    }

    public function findByUsername(string $username): ?User {
        return $this->userRepository->findByUsername($username);
    }

    public function findById(int $id): ?User {
        return $this->userRepository->findById($id);
    }

    public function updateProfile(int $id, string $bio, string $dateOfBirth, string $location, string $website, string $occupation): void {
        if (strlen($bio) > 160) {
            throw new \InvalidArgumentException("Bio cannot be longer than 160 characters");
        }
        if (!empty($website) && !filter_var($website, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException("Invalid website URL");
        }

        $this->userRepository->updateProfile($id, $bio, $dateOfBirth, $location, $website, $occupation);
    }

    public function getLeaderboard(): array {
        return $this->userRepository->getLeaderboard();
    }
}
