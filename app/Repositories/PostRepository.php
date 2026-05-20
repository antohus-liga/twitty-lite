<?php

namespace App\Repositories;

use PDO;
use App\Models\Post;

class PostRepository {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getAll(int $userId): array {
        $stmt = $this->db->prepare("
            SELECT posts.*, 
                   users.username,
                   (SELECT COUNT(*) FROM likes WHERE likes.target_id = posts.id AND likes.type = 'post') as like_count,
                   (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) as comment_count,
                   (SELECT COUNT(*) FROM likes WHERE likes.target_id = posts.id AND likes.type = 'post' AND likes.user_id = :currentUserId) as is_liked
            FROM posts
            JOIN users ON posts.user_id = users.id
            GROUP BY posts.id
            ORDER BY posts.created_at DESC
        ");
        $stmt->execute(['currentUserId' => $userId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => $this->toModel($row), $rows);
    }

    public function getByUserId(int $userId, int $currentUserId): array {
        $stmt = $this->db->prepare("
            SELECT posts.*, 
                   users.username,
                   (SELECT COUNT(*) FROM likes WHERE likes.target_id = posts.id AND likes.type = 'post') as like_count,
                   (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) as comment_count,
                   (SELECT COUNT(*) FROM likes WHERE likes.target_id = posts.id AND likes.type = 'post' AND likes.user_id = :currentUserId) as is_liked
            FROM posts
            JOIN users ON posts.user_id = users.id
            WHERE posts.user_id = :userId
            GROUP BY posts.id
            ORDER BY posts.created_at DESC
        ");
        $stmt->execute(['userId' => $userId, 'currentUserId' => $currentUserId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$rows) return [];
        return array_map(fn($row) => $this->toModel($row), $rows);
    }

    public function getById(string $id, int $userId): ?Post {
        $stmt = $this->db->prepare("
            SELECT posts.*, 
                   users.username,
                   (SELECT COUNT(*) FROM likes WHERE likes.target_id = posts.id AND likes.type = 'post') as like_count,
                   (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) as comment_count,
                   (SELECT COUNT(*) FROM likes WHERE likes.target_id = posts.id AND likes.type = 'post' AND likes.user_id = :currentUserId) as is_liked
            FROM posts
            JOIN users ON posts.user_id = users.id
            WHERE posts.id = :id
        ");
        $stmt->execute(['id' => $id, 'currentUserId' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;
        return $this->toModel($row);
    }

    public function create(int $userId, string $content): void {
        $uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );

        $stmt = $this->db->prepare(
            "INSERT INTO posts(id, user_id, content) VALUES (:id, :user_id, :content)"
        );
        $stmt->execute(['id' => $uuid, ':user_id' => $userId, ':content' => $content]);
    }

    public function remove(string $id): void {
        $stmt = $this->db->prepare("DELETE FROM posts WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function update(string $id, string $content): void {
        $stmt = $this->db->prepare("UPDATE posts SET content = :content WHERE id = :id");
        $stmt->execute(['content' => $content, 'id' => $id]);
    }

    private function toModel($row): Post {
        return new Post(
            $row['id'],
            $row['user_id'],
            $row['content'],
            $row['created_at'],
            $row['username'],
            (int) $row['like_count'],
            (int) $row['comment_count'],
            (bool) $row['is_liked'],
        );
    }
}