<?php

namespace App\Models;

class Post {
    public function __construct(
        public string $id,
        public int $userId,
        public string $content,
        public string $createdAt,
        public string $username,
        public int $likeCount,
        public int $commentCount,
        public bool $isLiked = false,
    ) {}
}