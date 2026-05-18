<?php

namespace App\Controllers;

use App\Models\Comment;
use App\Models\Conversation;
use App\Models\Message;
use App\Services\CommentService;
use App\Services\LikeService;
use App\Services\MessageService;
use App\Services\UserService;

class MessageController {
    private MessageService $messageService;
    private UserService $userService;

    public function __construct(MessageService $messageService, UserService $userService) {
        $this->messageService = $messageService;
        $this->userService = $userService;
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

    public function messages(string $username): void {
        $otherUser = $this->userService->findByUsername($username);
        if (!$otherUser) {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
            return;
        }
        $messages = $this->messageService->getMessages($_SESSION['user_id'], $otherUser->id);

        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode(array_map(fn(Message $message) => [
            'id' => $message->id,
            'senderId' => $message->senderId,
            'receiverId' => $message->receiverId,
            'content' => htmlspecialchars($message->content),
            'createdAt' => $message->createdAt,
        ], $messages));
    }

    public function store(string $username): void {
        $data = json_decode(file_get_contents('php://input'), true);
        $content = $data['content'];

        $otherUser = $this->userService->findByUsername($username);
        if (!$otherUser) {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
            return;
        }

        try {
            $this->messageService->create($_SESSION['user_id'], $otherUser->id, $content);
        } catch (\InvalidArgumentException $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
            return;
        }

        http_response_code(201);
        echo json_encode(['message' => 'Message sent']);
    }
}
