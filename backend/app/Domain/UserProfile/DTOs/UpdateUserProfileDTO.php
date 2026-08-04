<?php

declare(strict_types=1);

namespace App\Domain\UserProfile\DTOs;

final readonly class UpdateUserProfileDTO
{
    public function __construct(
        public ?string $sex = null,
        public ?string $birthDate = null,
        public ?float $heightReferenceCm = null,
        public ?string $activityLevel = null,
    ) {
    }
}
