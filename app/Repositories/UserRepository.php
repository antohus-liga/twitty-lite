<?php

namespace App\Repositories;

use App\Models\UserStats;
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

        return $this->toModel($row);
    }

    public function findById(int $id): ?User {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;
        return $this->toModel($row);
    }

    public function create(string $username, string $password): void {
        $stmt = $this->db->prepare(
            "INSERT INTO users (username, password) VALUES (:username, :password)"
        );
        $stmt->execute([':username' => $username, ':password' => $password]);
    }

    private function toModel($row): User {
        return new User(
            $row['id'],
            $row['username'],
            $row['password'],
            $row['created_at'],
            $row['bio'],
            $row['date_of_birth'],
            $row['location'],
            $row['website'],
            $row['occupation'],
        );
    }

    public function updateProfile(int $id, string $bio, string $dateOfBirth, string $location, string $website, string $occupation): void {
        $stmt = $this->db->prepare("
        UPDATE users SET 
        bio = :bio,
        date_of_birth = :dateOfBirth,
        location = :location,
        website = :website,
        occupation = :occupation
        WHERE id = :id");

        $stmt->execute([
            'bio' => $bio,
            'dateOfBirth' => $dateOfBirth,
            'location' => $location,
            'website' => $website,
            'occupation' => $occupation,
            'id' => $id,
        ]);
    }

    public function getLeaderboard(): array {
        $stmt = $this->db->prepare("
            SELECT
                users.username,
                COUNT(DISTINCT posts.id) AS total_posts,
                COUNT(DISTINCT comments.id) AS total_comments,
                COALESCE((SELECT COUNT(*) FROM likes 
                          JOIN posts p ON likes.target_id = p.id 
                          WHERE p.user_id = users.id AND likes.type = 'post'), 0) AS post_likes,
                COALESCE((SELECT COUNT(*) FROM likes 
                          JOIN comments c ON likes.target_id = c.id 
                          WHERE c.user_id = users.id AND likes.type = 'comment'), 0) AS comment_likes
            FROM users
            LEFT JOIN posts ON users.id = posts.user_id
            LEFT JOIN comments ON users.id = comments.user_id
            GROUP BY users.id, users.username
            ORDER BY (post_likes * 2 + comment_likes + total_posts + total_comments) DESC
        ");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn ($row) => new UserStats(
            $row['username'],
            (int) $row['post_likes'],
            (int) $row['comment_likes'],
            (int) $row['total_posts'],
            (int) $row['total_comments'],
        ), $rows);
    }
}