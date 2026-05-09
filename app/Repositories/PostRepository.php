<?php

namespace App\Repositories;

use PDO;
use App\Models\Post;

class PostRepository {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getAll(): array {
        $stmt = $this->db->query("
            SELECT posts.*, 
                   users.username, 
                   (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) as like_count,
                   (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) as comment_count
            FROM posts
            JOIN users ON posts.user_id = users.id
            GROUP BY posts.id
            ORDER BY posts.created_at DESC
        ");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => new Post(
            $row['id'],
            $row['user_id'],
            $row['content'],
            $row['created_at'],
            $row['username'],
            (int) $row['like_count'],
            (int) $row['comment_count'],
        ), $rows);
    }

    public function getByUserId(int $userId): array {
        $stmt = $this->db->prepare("
            SELECT posts.*, 
                   users.username, 
                   (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) as like_count,
                   (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) as comment_count
            FROM posts
            JOIN users ON posts.user_id = users.id
            WHERE posts.user_id = :userId
            GROUP BY posts.id
            ORDER BY posts.created_at DESC
        ");
        $stmt->execute(['userId' => $userId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$rows) return [];
        return array_map(fn($row) => new Post(
            $row['id'],
            $row['user_id'],
            $row['content'],
            $row['created_at'],
            $row['username'],
            (int) $row['like_count'],
            (int) $row['comment_count'],
        ), $rows);
    }

    public function getById(int $id): ?Post {
        $stmt = $this->db->prepare("
            SELECT posts.*, 
                   users.username, 
                   (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) as like_count,
                   (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) as comment_count
            FROM posts
            JOIN users ON posts.user_id = users.id
            WHERE posts.id = :id
        ");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;
        return new Post(
            $row['id'],
            $row['user_id'],
            $row['content'],
            $row['created_at'],
            $row['username'],
            (int) $row['like_count'],
            (int) $row['comment_count'],
        );
    }

    public function create(int $userId, string $content): void {
        $stmt = $this->db->prepare(
            "INSERT INTO posts(user_id, content) VALUES (:user_id, :content)"
        );
        $stmt->execute([':user_id' => $userId, ':content' => $content]);
    }
}