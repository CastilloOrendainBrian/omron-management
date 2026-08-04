<?php

declare(strict_types=1);

namespace App\Domain\UserProfile\DTOs;

final readonly class CreateUserProfileDTO
{
    public function __construct(
        public int $userId,
        public string $sex,
        public string $birthDate,
        public ?float $heightReferenceCm = null,
        public ?string $activityLevel = null,
    ) {
    }
}
