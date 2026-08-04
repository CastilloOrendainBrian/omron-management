<?php

declare(strict_types=1);

namespace App\Application\AnthropometricMeasurement\Queries;

use App\Models\AnthropometricMeasurement;

final readonly class ShowAnthropometricMeasurementUseCase
{
    public function execute(AnthropometricMeasurement $measurement): AnthropometricMeasurement
    {
        return $measurement->load('measurementSession');
    }
}
