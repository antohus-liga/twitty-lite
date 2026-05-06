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
        $posts = $this->postService->getAll();

        header('Content-Type: application/json');
        echo json_encode(
            array_map(fn(Post $post) => [
                'id' => $post->id,
                'userId' => $post->userId,
                'content' => $post->content,
                'createdAt' => $post->createdAt
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
}
