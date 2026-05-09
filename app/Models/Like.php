<?php

namespace App\Models;

class Like {
    public function __construct(
        public int $id,
        public int $userId,
        public int $targetId,
        public string $type,
    ) {}
}