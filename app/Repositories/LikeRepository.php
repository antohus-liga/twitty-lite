<?php

namespace App\Repositories;

use App\Models\Like;
use PDO;

class LikeRepository {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function findLike(int $userId, string $targetId, string $type): ?Like {
        $stmt = $this->db->prepare("SELECT * FROM likes WHERE user_id = :user_id AND target_id = :target_id AND type = :type");
        $stmt->execute(['user_id' => $userId, 'target_id' => $targetId, 'type' => $type]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        return $this->toModel($row);
    }

    public function add(int $userId, string $targetId, string $type): void {
        $stmt = $this->db->prepare("INSERT INTO likes (user_id, target_id, type) VALUES (:user_id, :target_id, :type)");
        $stmt->execute(['user_id' => $userId, 'target_id' => $targetId, 'type' => $type]);
    }

    public function remove(int $userId, string $targetId, string $type): void {
        $stmt = $this->db->prepare("DELETE FROM likes WHERE user_id = :user_id AND target_id = :target_id AND type = :type");
        $stmt->execute(['user_id' => $userId, 'target_id' => $targetId, 'type' => $type]);
    }

    private function toModel(array $row): Like {
        return new Like(
            $row['id'],
            $row['user_id'],
            $row['target_id'],
            $row['type'],
        );
    }
}