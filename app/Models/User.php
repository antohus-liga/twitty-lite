<?php

namespace App\Models;

class User {
    public function __construct(
        public int $id,
        public string $username,
        public string $password,
        public string $createdAt,
        public ?string $bio = null,
        public ?string $dateOfBirth = null,
        public ?string $location = null,
        public ?string $website = null,
        public ?string $occupation = null,
    ) {}
}