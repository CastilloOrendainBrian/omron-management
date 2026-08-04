<?php

declare(strict_types=1);

namespace App\Domain\User\DTOs;

final readonly class ListUsersDTO
{
    public function __construct(
        public int $perPage = 25,
        public ?string $name = null,
        public ?string $email = null,
    ) {
    }
}
