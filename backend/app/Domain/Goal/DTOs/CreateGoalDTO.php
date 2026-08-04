<?php

declare(strict_types=1);

namespace App\Domain\Goal\DTOs;

final readonly class CreateGoalDTO
{
    public function __construct(
        public int $userId,
        public string $startDate,
        public ?float $targetWeightKg = null,
        public ?float $targetBodyFatPercentage = null,
        public ?string $targetDate = null,
        public ?string $status = null,
    ) {
    }
}
