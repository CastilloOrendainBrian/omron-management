<?php

declare(strict_types=1);

namespace App\Domain\AnthropometricMeasurement\DTOs;

final readonly class ListAnthropometricMeasurementsDTO
{
    public function __construct(
        public int $perPage = 25,
        public ?int $measurementSessionId = null,
    ) {
    }
}
