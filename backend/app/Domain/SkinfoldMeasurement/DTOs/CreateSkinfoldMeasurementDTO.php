<?php

declare(strict_types=1);

namespace App\Domain\SkinfoldMeasurement\DTOs;

final readonly class CreateSkinfoldMeasurementDTO
{
    /**
     * @param SkinfoldMeasurementDetailDTO[] $details
     */
    public function __construct(
        public int $measurementSessionId,
        public int $skinfoldProtocolId,
        public ?float $estimatedBodyFatPercentage,
        public array $details,
    ) {
    }
}
