<?php

declare(strict_types=1);

namespace App\Domain\Auth\DTOs;

final readonly class LoginCredentialsDTO
{
    public function __construct(
        public string $email,
        public string $password,
        public string $deviceName = 'api',
    ) {
    }
}
