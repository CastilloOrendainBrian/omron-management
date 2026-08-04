<?php

declare(strict_types=1);

namespace App\Application\BodyCompositionMeasurement\Commands;

use App\Domain\BodyCompositionMeasurement\Contracts\BodyCompositionMeasurementRepositoryInterface;
use App\Domain\BodyCompositionMeasurement\DTOs\UpdateBodyCompositionMeasurementDTO;
use App\Models\BodyCompositionMeasurement;

final readonly class UpdateBodyCompositionMeasurementUseCase
{
    public function __construct(
        private BodyCompositionMeasurementRepositoryInterface $measurements,
    ) {
    }

    public function execute(BodyCompositionMeasurement $measurement, UpdateBodyCompositionMeasurementDTO $dto): BodyCompositionMeasurement
    {
        return $this->measurements->update($measurement, $dto);
    }
}
