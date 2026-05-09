<?php

namespace App\Models;

class Comment {
    public function __construct(
        public int $id,
        public int $userId,
        public int $postId,
        public string $content,
        public string $createdAt,
        public string $username,
    ) {}
}