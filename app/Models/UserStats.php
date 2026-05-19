<?php

namespace App\Models;

class UserStats {
    public function __construct(
        public string $username,
        public int $totalPostLikes,
        public int $totalCommentLikes,
        public int $totalPosts,
        public int $totalComments,
    ) {}
}