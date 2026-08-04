<?php

declare(strict_types=1);

namespace App\Application\BodyCompositionMeasurement\Commands;

use App\Domain\BodyCompositionMeasurement\Contracts\BodyCompositionMeasurementRepositoryInterface;
use App\Domain\BodyCompositionMeasurement\DTOs\CreateBodyCompositionMeasurementDTO;
use App\Models\BodyCompositionMeasurement;

final readonly class CreateBodyCompositionMeasurementUseCase
{
    public function __construct(
        private BodyCompositionMeasurementRepositoryInterface $measurements,
    ) {
    }

    public function execute(CreateBodyCompositionMeasurementDTO $dto): BodyCompositionMeasurement
    {
        return $this->measurements->create($dto);
    }
}
