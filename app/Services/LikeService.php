<?php

namespace App\Services;

use App\Repositories\LikeRepository;

class LikeService {
    private LikeRepository $likeRepository;

    public function __construct(LikeRepository $likeRepository) {
        $this->likeRepository = $likeRepository;
    }

    public function toggleLike(int $userId, string $targetId, string $type): bool {
        $like = $this->likeRepository->findLike($userId, $targetId, $type);
        if ($like) {
            $this->likeRepository->remove($like->userId, $like->targetId, $like->type);
            return false;
        } else {
            $this->likeRepository->add($userId, $targetId, $type);
            return true;
        }
    }
}