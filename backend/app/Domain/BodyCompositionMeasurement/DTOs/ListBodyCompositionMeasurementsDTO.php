<?php

declare(strict_types=1);

namespace App\Domain\BodyCompositionMeasurement\DTOs;

final readonly class ListBodyCompositionMeasurementsDTO
{
    public function __construct(
        public int $perPage = 25,
        public ?int $measurementSessionId = null,
    ) {
    }
}
