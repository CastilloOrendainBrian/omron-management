<?php

declare(strict_types=1);

namespace App\Domain\AnthropometricMeasurement\DTOs;

final readonly class CreateAnthropometricMeasurementDTO
{
    public function __construct(
        public int $measurementSessionId,
        public float $heightCm,
        public float $weightKg,
        public float $bmi,
    ) {
    }
}
