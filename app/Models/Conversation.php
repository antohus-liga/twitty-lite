<?php

namespace App\Models;

class Conversation {
    public function __construct(
        public int $otherUserId,
        public string $otherUsername,
    ) {}
}