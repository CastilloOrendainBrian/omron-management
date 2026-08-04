<?php

declare(strict_types=1);

namespace App\Domain\SkinfoldMeasurement\DTOs;

final readonly class SkinfoldMeasurementDetailDTO
{
    public function __construct(
        public int $skinfoldSiteId,
        public float $valueMm,
    ) {
    }
}
