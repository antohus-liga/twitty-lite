<?php

namespace App\Services;

use App\Repositories\MessageRepository;

class MessageService {
    private MessageRepository $messageRepository;

    public function __construct(MessageRepository $messageRepository) {
        $this->messageRepository = $messageRepository;
    }

    public function getConversations(int $userId): array {
        return $this->messageRepository->getConversations($userId);
    }

    public function getMessages(int $userId, int $otherUserId): array {
        return $this->messageRepository->getMessages($userId, $otherUserId);
    }

    public function create(int $senderId, int $receiverId, string $content): void {
        if (empty($content)) {
            throw new \InvalidArgumentException('A mensagem não pode estar vazia');
        }
        if (strlen($content) > 200) {
            throw new \InvalidArgumentException('A mensagem não pode ter mais de 200 caracteres');
        }
        if ($senderId === $receiverId) {
            throw new \InvalidArgumentException("You can't send messages to yourself");
        }

        $this->messageRepository->create($senderId, $receiverId, $content);
    }
}