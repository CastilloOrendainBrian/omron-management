<?php

declare(strict_types=1);

namespace App\Domain\Goal\DTOs;

final readonly class UpdateGoalDTO
{
    public function __construct(
        public ?float $targetWeightKg = null,
        public ?float $targetBodyFatPercentage = null,
        public ?string $startDate = null,
        public ?string $targetDate = null,
        public ?string $status = null,
    ) {
    }
}
