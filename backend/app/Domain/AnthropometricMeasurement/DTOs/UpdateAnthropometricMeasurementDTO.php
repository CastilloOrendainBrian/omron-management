<?php

declare(strict_types=1);

namespace App\Domain\AnthropometricMeasurement\DTOs;

final readonly class UpdateAnthropometricMeasurementDTO
{
    public function __construct(
        public ?float $heightCm = null,
        public ?float $weightKg = null,
        public ?float $bmi = null,
    ) {
    }
}
