<?php

namespace App\Services;

use App\Repositories\CommentRepository;

class CommentService {
    private CommentRepository $commentRepository;

    public function __construct(CommentRepository $commentRepository) {
        $this->commentRepository = $commentRepository;
    }

    public function getCommentsByPostId(int $postId, int $currentUserId): array {
        return $this->commentRepository->findByPostId($postId, $currentUserId);
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

    public function remove(int $commentId, int $userId): void {
        $comment = $this->commentRepository->getById($commentId);
        if (!$comment) {
            throw new \InvalidArgumentException('Comment not found');
        }
        if ($comment->userId !== $userId) {
            throw new \InvalidArgumentException('Unauthorized');
        }
        $this->commentRepository->remove($commentId);
    }

    public function update(int $commentId, int $userId, string $content): void {
        $comment = $this->commentRepository->getById($commentId);
        if (!$comment) {
            throw new \InvalidArgumentException('Comment not found');
        }
        if ($comment->userId !== $userId) {
            throw new \InvalidArgumentException('Unauthorized');
        }
        $this->commentRepository->update($commentId, $content);
    }
}