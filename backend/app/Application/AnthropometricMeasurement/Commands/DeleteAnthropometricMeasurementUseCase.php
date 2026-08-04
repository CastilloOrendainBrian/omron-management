<?php

declare(strict_types=1);

namespace App\Application\AnthropometricMeasurement\Commands;

use App\Domain\AnthropometricMeasurement\Contracts\AnthropometricMeasurementRepositoryInterface;
use App\Models\AnthropometricMeasurement;

final readonly class DeleteAnthropometricMeasurementUseCase
{
    public function __construct(
        private AnthropometricMeasurementRepositoryInterface $measurements,
    ) {
    }

    public function execute(AnthropometricMeasurement $measurement): void
    {
        $this->measurements->delete($measurement);
    }
}
