<?php

declare(strict_types=1);

namespace App\Domain\User\DTOs;

final readonly class UpdateUserWithProfileDTO
{
    public function __construct(
        public ?string $name = null,
        public ?string $email = null,
        public ?string $password = null,
        public ?string $sex = null,
        public ?string $birthDate = null,
        public ?float $heightReferenceCm = null,
        public ?string $activityLevel = null,
    ) {
    }
}
