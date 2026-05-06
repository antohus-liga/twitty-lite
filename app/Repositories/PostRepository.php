<?php

namespace App\Repositories;

use core\Database;
use PDO;
use App\Models\Post;

class PostRepository {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM posts");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => new Post(
            $row['id'],
            $row['user_id'],
            $row['content'],
            $row['created_at']
        ), $rows);
    }

    public function create(int $userId, string $content): void {
        $stmt = $this->db->prepare(
            "INSERT INTO posts(user_id, content) VALUES (:user_id, :content)"
        );
        $stmt->execute([':user_id' => $userId, ':content' => $content]);
    }
}