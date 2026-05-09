<?php

namespace App\Services;

use App\Repositories\CommentRepository;

class CommentService {
    private CommentRepository $commentRepository;

    public function __construct(CommentRepository $commentRepository) {
        $this->commentRepository = $commentRepository;
    }

    public function getCommentsByPostId(int $postId): array {
        return $this->commentRepository->findByPostId($postId);
    }

    public function create(int $userId, int $postId, string $content): void {
        if (empty($content)) {
            throw new \InvalidArgumentException('Content cannot be empty');
        }

        if (strlen($content) > 280) {
            throw new \InvalidArgumentException('Content cannot exceed 280 characters');
        }

        $this->commentRepository->create($userId, $postId, $content);
    }
}