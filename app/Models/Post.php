<?php

namespace App\Models;

class Post {
    public function __construct(
        public int $id,
        public int $userId,
        public string $content,
        public string $createdAt,
    ) {}
}