<?php

declare(strict_types=1);

namespace App\Application\SkinfoldMeasurement\Commands;

use App\Domain\SkinfoldMeasurement\Contracts\SkinfoldMeasurementRepositoryInterface;
use App\Domain\SkinfoldMeasurement\DTOs\UpdateSkinfoldMeasurementDTO;
use App\Models\SkinfoldMeasurement;

final readonly class UpdateSkinfoldMeasurementUseCase
{
    public function __construct(
        private SkinfoldMeasurementRepositoryInterface $measurements,
    ) {
    }

    public function execute(SkinfoldMeasurement $measurement, UpdateSkinfoldMeasurementDTO $dto): SkinfoldMeasurement
    {
        return $this->measurements->update($measurement, $dto);
    }
}
