<?php

declare(strict_types=1);

namespace App\Domain\User\DTOs;

final readonly class CreateUserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) {
    }
}
