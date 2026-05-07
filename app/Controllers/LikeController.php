<?php

namespace App\Controllers;

use App\Services\LikeService;

class LikeController {
    private LikeService $likeService;

    public function __construct(LikeService $likeService) {
        $this->likeService = $likeService;
    }

    public function toggle(int $postId): void {
        if ($this->likeService->toggleLike($_SESSION['user_id'], $postId)) {
            http_response_code(201);
            echo json_encode(['message' => 'Liked']);
        } else {
            http_response_code(200);
            echo json_encode(['message' => 'Unliked']);
        }
    }
}
