<?php

declare(strict_types=1);

namespace App\Domain\UserProfile\DTOs;

final readonly class ListUserProfilesDTO
{
    public function __construct(
        public int $perPage = 25,
        public ?string $sex = null,
        public ?string $activityLevel = null,
        public ?float $heightMin = null,
        public ?float $heightMax = null,
    ) {
    }
}
