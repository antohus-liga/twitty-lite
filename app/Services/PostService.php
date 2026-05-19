<?php

namespace App\Services;

use App\Models\Post;
use App\Repositories\PostRepository;

class PostService {
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository) {
        $this->postRepository = $postRepository;
    }

    public function getAll(int $userId): array {
        return $this->postRepository->getAll($userId);
    }

    public function getByUserId(int $userId, int $currentUserId): array {
        return $this->postRepository->getByUserId($userId, $currentUserId);
    }

    public function getById(int $id, int $userId): ?Post {
        return $this->postRepository->getById($id, $userId);
    }

    public function create(int $userId, string $content): void {
        if (empty($content)) {
            throw new \InvalidArgumentException('O conteúdo não pode estar vazio');
        }

        if (strlen($content) > 280) {
            throw new \InvalidArgumentException('O conteúdo não pode ter mais de 280 caracteres');
        }

        $this->postRepository->create($userId, $content);
    }

    public function remove(int $postId, int $userId): void {
        $post = $this->postRepository->getById($postId, $userId);
        if (!$post) {
            throw new \InvalidArgumentException('Post not found');
        }
        if ($post->userId !== $userId) {
            throw new \InvalidArgumentException('Unauthorized');
        }
        $this->postRepository->remove($postId);
    }

    public function update(int $postId, int $userId, string $content): void {
        $post = $this->postRepository->getById($postId, $userId);
        if (!$post) {
            throw new \InvalidArgumentException('Post not found');
        }
        if ($post->userId !== $userId) {
            throw new \InvalidArgumentException('Unauthorized');
        }
        $this->postRepository->update($postId, $content);
    }
}