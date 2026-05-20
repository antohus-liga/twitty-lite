<?php

namespace App\Repositories;

use App\Models\Comment;
use PDO;

class CommentRepository {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function findByPostId(string $postId, int $currentUserId): array {
        $stmt = $this->db->prepare("
            SELECT comments.*, 
                   users.username,
                   (SELECT COUNT(*) FROM likes WHERE likes.target_id = comments.id AND likes.type = 'comment') as like_count,
                   (SELECT COUNT(*) FROM likes WHERE likes.target_id = comments.id AND likes.type = 'comment' AND likes.user_id = :currentUserId) as is_liked
            FROM comments
            JOIN users ON users.id = comments.user_id
            WHERE comments.post_id = :postId
            ORDER BY comments.created_at DESC
        ");
        $stmt->execute(['postId' => $postId, 'currentUserId' => $currentUserId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$rows) return [];

        return array_map(fn($row) => $this->toModel($row), $rows);
    }

    public function getById(int $id): ?Comment {
        $stmt = $this->db->prepare("
            SELECT comments.*, 
                   users.username,
                   (SELECT COUNT(*) FROM likes WHERE likes.target_id = comments.id AND likes.type = 'comment') as like_count,
                   (SELECT COUNT(*) FROM likes WHERE likes.target_id = comments.id AND likes.type = 'comment' AND likes.user_id = 0) as is_liked
            FROM comments
            JOIN users ON users.id = comments.user_id
            WHERE comments.id = :id
        ");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return $this->toModel($row);
    }

    public function create(int $userId, string $postId, string $content): void {
        $stmt = $this->db->prepare("INSERT INTO comments (user_id, post_id, content) VALUES (:user_id, :post_id, :content)");
        $stmt->execute(['user_id' => $userId, 'post_id' => $postId, 'content' => $content]);
    }

    public function remove(int $id): void {
        $stmt = $this->db->prepare("DELETE FROM comments WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function update(int $id, string $content): void {
        $stmt = $this->db->prepare("UPDATE comments SET content = :content WHERE id = :id");
        $stmt->execute(['content' => $content, 'id' => $id]);
    }

    private function toModel($row): Comment {
        return new Comment(
            $row['id'],
            $row['user_id'],
            $row['post_id'],
            $row['content'],
            $row['created_at'],
            $row['username'],
            $row['like_count'],
            $row['is_liked'],
        );
    }
}