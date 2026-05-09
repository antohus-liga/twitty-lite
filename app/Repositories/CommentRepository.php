<?php

namespace App\Repositories;

use App\Models\Comment;
use PDO;

class CommentRepository {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function findByPostId(int $postId): array {
        $stmt = $this->db->prepare("
            SELECT comments.*, 
                   users.username,
                   (SELECT COUNT(*) FROM likes WHERE likes.target_id = comments.id AND likes.type = 'comment') as like_count
            FROM comments 
            JOIN users ON users.id = comments.user_id
            WHERE post_id = :postId 
            ORDER BY created_at DESC");
        $stmt->execute(['postId' => $postId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$rows) return [];

        return array_map(fn($row) => new Comment(
            $row['id'],
            $row['user_id'],
            $row['post_id'],
            $row['content'],
            $row['created_at'],
            $row['username'],
            $row['like_count'],
        ), $rows);
    }

    public function create(int $userId, int $postId, string $content): void {
        $stmt = $this->db->prepare("INSERT INTO comments (user_id, post_id, content) VALUES (:user_id, :post_id, :content)");
        $stmt->execute(['user_id' => $userId, 'post_id' => $postId, 'content' => $content]);
    }
}