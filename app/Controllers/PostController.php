<?php

namespace App\Controllers;

use App\Models\Post;
use App\Services\PostService;

class PostController {
    private PostService $postService;

    public function __construct(PostService $postService) {
        $this->postService = $postService;
    }

    public function index(): void {
        $posts = $this->postService->getAll($_SESSION['user_id']);

        header('Content-Type: application/json');
        echo json_encode(
            array_map(fn(Post $post) => [
                'id' => $post->id,
                'userId' => $post->userId,
                'content' => $post->content,
                'createdAt' => $post->createdAt,
                'username' => $post->username,
                'likeCount' => $post->likeCount,
                'commentCount' => $post->commentCount,
                'isLiked' => $post->isLiked,
            ], $posts)
        );
    }

    public function store(): void {
        $data = json_decode(file_get_contents("php://input"), true);

        try {
            $this->postService->create($_SESSION['user_id'], $data['content']);
        } catch (\InvalidArgumentException $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
            return;
        }

        http_response_code(201);
        echo json_encode(['message' => 'Post created']);
    }

    public function show(int $id): void {
        $post = $this->postService->getById($id, $_SESSION['user_id']);
        if (!$post) {
            http_response_code(404);
            echo json_encode(['error' => 'Post not found']);
            return;
        }

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode([
            'id' => $post->id,
            'userId' => $post->userId,
            'content' => htmlspecialchars($post->content),
            'createdAt' => $post->createdAt,
            'username' => htmlspecialchars($post->username),
            'likeCount' => $post->likeCount,
            'commentCount' => $post->commentCount,
            'isLiked' => $post->isLiked,
        ]);
    }

    public function remove(int $id): void {
        try {
            $this->postService->remove($id, $_SESSION['user_id']);
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
            $this->postService->update($id, $_SESSION['user_id'], $data['content']);
        } catch (\InvalidArgumentException $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
            return;
        }

        http_response_code(200);
        echo json_encode(['message' => 'Post updated']);
    }
}
