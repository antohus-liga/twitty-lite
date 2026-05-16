<?php

namespace App\Controllers;

use App\Models\Post;
use App\Services\PostService;
use App\Services\UserService;

class UserController {
    private PostService $postService;
    private UserService $userService;

    public function __construct(PostService $postService, UserService $userService) {
        $this->postService = $postService;
        $this->userService = $userService;
    }

    public function show(string $username): void {
        $user = $this->userService->findByUsername($username);
        if (!$user) {
            http_response_code(404);
            echo json_encode(['error' => 'User not found.']);
            return;
        }

        $posts = $this->postService->getByUserId($user->id);

        http_response_code(200);
        echo json_encode([
            'user' => [
                'username' => $user->username,
                'createdAt' => $user->createdAt
            ],
            'posts' => array_map(fn(Post $post) => [
                'id' => $post->id,
                'username' => $post->username,
                'content' => $post->content,
                'createdAt' => $post->createdAt,
                'likeCount' => $post->likeCount,
                'commentCount' => $post->commentCount,
            ], $posts)
        ]);
    }
}