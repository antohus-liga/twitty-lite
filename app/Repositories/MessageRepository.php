<?php

namespace App\Repositories;

use App\Models\Conversation;
use App\Models\Message;
use PDO;

class MessageRepository {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getConversations(int $userId): array {
        $stmt = $this->db->prepare("
            SELECT DISTINCT
                CASE WHEN sender_id = :userId THEN receiver_id ELSE sender_id END as other_user_id,
                users.username as other_username
            FROM messages
            JOIN users ON users.id = CASE WHEN sender_id = :userId THEN receiver_id ELSE sender_id END
            WHERE sender_id = :userId OR receiver_id = :userId");
        $stmt->execute(['userId' => $userId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn ($row) => $this->toConversationModel($row), $rows);
    }

    public function getMessages(int $userId, int $otherUserId): array {
        $stmt = $this->db->prepare("
            SELECT * FROM messages 
            WHERE sender_id = :userId AND receiver_id = :otherUserId 
            OR sender_id = :otherUserId AND receiver_id = :userId");
        $stmt->execute(['userId' => $userId, 'otherUserId' => $otherUserId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn ($row) => $this->toMessageModel($row), $rows);
    }

    public function create(int $senderId, int $receiverId, string $content): void {
        $stmt = $this->db->prepare("
        INSERT INTO messages(sender_id, receiver_id, content)
        VALUES(:senderId, :receiverId, :content)");
        $stmt->execute(['senderId' => $senderId, 'receiverId' => $receiverId, 'content' => $content]);
    }

    private function toMessageModel($row): Message {
        return new Message(
            $row['id'],
            $row['sender_id'],
            $row['receiver_id'],
            $row['content'],
            $row['created_at'],
        );
    }

    private function toConversationModel($row): Conversation {
        return new Conversation(
            $row['other_user_id'],
            $row['other_username'],
        );
    }
}