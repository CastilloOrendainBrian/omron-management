<?php

declare(strict_types=1);

namespace App\Application\SkinfoldMeasurement\Commands;

use App\Domain\SkinfoldMeasurement\Contracts\SkinfoldMeasurementRepositoryInterface;
use App\Domain\SkinfoldMeasurement\DTOs\CreateSkinfoldMeasurementDTO;
use App\Models\SkinfoldMeasurement;

final readonly class CreateSkinfoldMeasurementUseCase
{
    public function __construct(
        private SkinfoldMeasurementRepositoryInterface $measurements,
    ) {
    }

    public function execute(CreateSkinfoldMeasurementDTO $dto): SkinfoldMeasurement
    {
        return $this->measurements->create($dto);
    }
}
