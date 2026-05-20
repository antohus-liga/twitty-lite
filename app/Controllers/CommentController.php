<?php

namespace App\Controllers;

use App\Models\Comment;
use App\Services\CommentService;
use App\Services\LikeService;

class CommentController {
    private CommentService $commentService;

    public function __construct(CommentService $commentService) {
        $this->commentService = $commentService;
    }

    public function index(string $postId): void {
        $comments = $this->commentService->getCommentsByPostId($postId, $_SESSION['user_id']);

        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode(array_map(fn(Comment $comment) => [
            'id' => $comment->id,
            'userId' => $comment->userId,
            'postId' => $comment->postId,
            'content' => htmlspecialchars($comment->content),
            'createdAt' => $comment->createdAt,
            'username' => htmlspecialchars($comment->username),
            'likeCount' => $comment->likeCount,
            'isLiked' => $comment->isLiked,
        ], $comments));
    }

    public function store(string $postId): void {
        $data = json_decode(file_get_contents('php://input'), true);
        $content = $data['content'];

        try {
            $this->commentService->create($_SESSION['user_id'], $postId, $content);
        } catch (\InvalidArgumentException $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
            return;
        }

        http_response_code(201);
        echo json_encode(['message' => 'Comment created']);
    }

    public function remove(int $id): void {
        try {
            $this->commentService->remove($id, $_SESSION['user_id']);
        } catch (\InvalidArgumentException $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
            return;
        }

        http_response_code(200);
        echo json_encode(['message' => 'Post removed']);
    }

    public function update(int $id): void {
        $data = json_decode(file_get_contents("php://input"), true);

        try {
            $this->commentService->update($id, $_SESSION['user_id'], $data['content']);
        } catch (\InvalidArgumentException $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
            return;
        }

        http_response_code(200);
        echo json_encode(['message' => 'Post updated']);
    }
}
