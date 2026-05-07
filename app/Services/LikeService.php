<?php

namespace App\Services;

use App\Repositories\LikeRepository;

class LikeService {
    private LikeRepository $likeRepository;

    public function __construct(LikeRepository $likeRepository) {
        $this->likeRepository = $likeRepository;
    }

    public function toggleLike(int $userId, int $postId): bool {
        $like = $this->likeRepository->findLike($userId, $postId);
        if ($like) {
            $this->likeRepository->remove($like->userId, $like->postId);
            return false;
        } else {
            $this->likeRepository->add($userId, $postId);
            return true;
        }
    }
}