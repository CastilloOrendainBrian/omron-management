<?php

declare(strict_types=1);

namespace App\Domain\BodyCompositionMeasurement\DTOs;

final readonly class UpdateBodyCompositionMeasurementDTO
{
    public function __construct(
        public ?float $bodyFatPercentage = null,
        public ?float $musclePercentage = null,
        public ?int $visceralFatLevel = null,
        public ?int $metabolicAge = null,
        public ?int $bmrKcal = null,
    ) {
    }
}
