<?php

namespace App\Controllers;

use App\Services\LikeService;

class LikeController {
    private LikeService $likeService;

    public function __construct(LikeService $likeService) {
        $this->likeService = $likeService;
    }

    public function toggleOnPost(string $postId): void {
        if ($this->likeService->toggleLike($_SESSION['user_id'], $postId, 'post')) {
            http_response_code(201);
            echo json_encode(['message' => 'Liked']);
        } else {
            http_response_code(200);
            echo json_encode(['message' => 'Unliked']);
        }
    }

    public function toggleOnComment(string $commentId): void {
        if ($this->likeService->toggleLike($_SESSION['user_id'], $commentId, 'comment')) {
            http_response_code(201);
            echo json_encode(['message' => 'Liked']);
        } else {
            http_response_code(200);
            echo json_encode(['message' => 'Unliked']);
        }
    }
}
