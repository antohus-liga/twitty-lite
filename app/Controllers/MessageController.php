<?php

namespace App\Controllers;

use App\Models\Comment;
use App\Models\Conversation;
use App\Models\Message;
use App\Services\CommentService;
use App\Services\LikeService;
use App\Services\MessageService;

class MessageController {
    private MessageService $messageService;

    public function __construct(MessageService $messageService) {
        $this->messageService = $messageService;
    }

    public function conversations(): void {
        $conversations = $this->messageService->getConversations($_SESSION['user_id']);

        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode(array_map(fn(Conversation $conversation) => [
            'otherUserId' => $conversation->otherUserId,
            'otherUsername' => $conversation->otherUsername,
        ], $conversations));
    }

    public function messages(int $otherUserId): void {
        $messages = $this->messageService->getMessages($_SESSION['user_id'], $otherUserId);

        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode(array_map(fn(Message $message) => [
            'senderId' => $message->senderId,
            'receiverId' => $message->receiverId,
            'content' => $message->content,
            'createdAt' => $message->createdAt,
        ], $messages));
    }

    public function store(int $otherUserId): void {
        $data = json_decode(file_get_contents('php://input'), true);
        $content = $data['content'];

        try {
            $this->messageService->create($_SESSION['user_id'], $otherUserId, $content);
        } catch (\InvalidArgumentException $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
            return;
        }

        http_response_code(201);
        echo json_encode(['message' => 'Message sent']);
    }
}
