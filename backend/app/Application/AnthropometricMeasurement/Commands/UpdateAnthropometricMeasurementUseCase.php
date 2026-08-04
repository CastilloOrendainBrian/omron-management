<?php

declare(strict_types=1);

namespace App\Application\AnthropometricMeasurement\Commands;

use App\Domain\AnthropometricMeasurement\Contracts\AnthropometricMeasurementRepositoryInterface;
use App\Domain\AnthropometricMeasurement\DTOs\UpdateAnthropometricMeasurementDTO;
use App\Models\AnthropometricMeasurement;

final readonly class UpdateAnthropometricMeasurementUseCase
{
    public function __construct(
        private AnthropometricMeasurementRepositoryInterface $measurements,
    ) {
    }

    public function execute(AnthropometricMeasurement $measurement, UpdateAnthropometricMeasurementDTO $dto): AnthropometricMeasurement
    {
        return $this->measurements->update($measurement, $dto);
    }
}
