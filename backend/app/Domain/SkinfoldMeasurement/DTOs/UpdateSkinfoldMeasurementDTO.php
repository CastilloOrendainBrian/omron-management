<?php

declare(strict_types=1);

namespace App\Domain\SkinfoldMeasurement\DTOs;

final readonly class UpdateSkinfoldMeasurementDTO
{
    /**
     * @param SkinfoldMeasurementDetailDTO[]|null $details
     */
    public function __construct(
        public ?float $estimatedBodyFatPercentage = null,
        public ?array $details = null,
    ) {
    }
}
