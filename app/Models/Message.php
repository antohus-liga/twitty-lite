<?php

namespace App\Models;

class Message {
    public function __construct(
        public int $id,
        public int $senderId,
        public int $receiverId,
        public string $content,
        public string $createdAt,
    ) {}
}