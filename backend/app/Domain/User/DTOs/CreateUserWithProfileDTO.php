<?php

declare(strict_types=1);

namespace App\Domain\User\DTOs;

final readonly class CreateUserWithProfileDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $sex,
        public string $birthDate,
        public ?float $heightReferenceCm = null,
        public ?string $activityLevel = null,
    ) {
    }
}
