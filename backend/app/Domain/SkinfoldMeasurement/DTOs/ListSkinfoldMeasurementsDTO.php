<?php

declare(strict_types=1);

namespace App\Domain\SkinfoldMeasurement\DTOs;

final readonly class ListSkinfoldMeasurementsDTO
{
    public function __construct(
        public int $perPage = 25,
        public ?int $measurementSessionId = null,
        public ?int $skinfoldProtocolId = null,
    ) {
    }
}
