<?php

declare(strict_types=1);

namespace App\Application\AnthropometricMeasurement\Commands;

use App\Domain\AnthropometricMeasurement\Contracts\AnthropometricMeasurementRepositoryInterface;
use App\Domain\AnthropometricMeasurement\DTOs\CreateAnthropometricMeasurementDTO;
use App\Models\AnthropometricMeasurement;

final readonly class CreateAnthropometricMeasurementUseCase
{
    public function __construct(
        private AnthropometricMeasurementRepositoryInterface $measurements,
    ) {
    }

    public function execute(CreateAnthropometricMeasurementDTO $dto): AnthropometricMeasurement
    {
        return $this->measurements->create($dto);
    }
}
