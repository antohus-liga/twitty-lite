<?php

namespace App\Services;

use App\Models\Post;
use App\Repositories\PostRepository;

class PostService {
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository) {
        $this->postRepository = $postRepository;
    }

    public function getAll(): array {
        return $this->postRepository->getAll();
    }

    public function create(int $userId, string $content): void {
        if (empty($content)) {
            throw new \InvalidArgumentException('Content cannot be empty');
        }

        if (strlen($content) > 280) {
            throw new \InvalidArgumentException('Content cannot exceed 280 characters');
        }

        $this->postRepository->create($userId, $content);
    }
}