<?php

namespace App\Repositories;

use PDO;
use App\Models\User;

class UserRepository {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function findByUsername(string $username): ?User {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new User(
            $row['id'],
            $row['username'],
            $row['password'],
            $row['created_at']
        );
    }

    public function create(string $username, string $password): void {
        $stmt = $this->db->prepare(
            "INSERT INTO users (username, password) VALUES (:username, :password)"
        );
        $stmt->execute([':username' => $username, ':password' => $password]);
    }
}