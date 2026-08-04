<?php

declare(strict_types=1);

namespace App\Application\SkinfoldMeasurement\Commands;

use App\Domain\SkinfoldMeasurement\Contracts\SkinfoldMeasurementRepositoryInterface;
use App\Models\SkinfoldMeasurement;

final readonly class DeleteSkinfoldMeasurementUseCase
{
    public function __construct(
        private SkinfoldMeasurementRepositoryInterface $measurements,
    ) {
    }

    public function execute(SkinfoldMeasurement $measurement): void
    {
        $this->measurements->delete($measurement);
    }
}
