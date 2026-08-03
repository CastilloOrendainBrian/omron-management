<?php

declare(strict_types=1);

namespace App\Domain\User\DTOs;

final readonly class UpdateUserDTO
{
    public function __construct(
        public ?string $name = null,
        public ?string $email = null,
        public ?string $password = null,
    ) {
    }
}
