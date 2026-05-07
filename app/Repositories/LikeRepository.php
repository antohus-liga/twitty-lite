<?php

namespace App\Repositories;

use App\Models\Like;
use PDO;

class LikeRepository {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function findLike(int $userId, int $postId): ?Like {
        $stmt = $this->db->prepare("SELECT * FROM likes WHERE user_id = :user_id AND post_id = :post_id");
        $stmt->execute(['user_id' => $userId, 'post_id' => $postId]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        return new Like(
            $row['id'],
            $row['user_id'],
            $row['post_id']
        );
    }

    public function add(int $userId, int $postId): void {
        $stmt = $this->db->prepare("INSERT INTO likes (user_id, post_id) VALUES (:user_id, :post_id)");
        $stmt->execute(['user_id' => $userId, 'post_id' => $postId]);
    }

    public function remove(int $userId, int $postId): void {
        $stmt = $this->db->prepare("DELETE FROM likes WHERE user_id = :user_id AND post_id = :post_id");
        $stmt->execute(['user_id' => $userId, 'post_id' => $postId]);
    }
}