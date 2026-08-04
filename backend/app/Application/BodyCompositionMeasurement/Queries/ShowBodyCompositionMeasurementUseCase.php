<?php

declare(strict_types=1);

namespace App\Application\BodyCompositionMeasurement\Queries;

use App\Models\BodyCompositionMeasurement;

final readonly class ShowBodyCompositionMeasurementUseCase
{
    public function execute(BodyCompositionMeasurement $measurement): BodyCompositionMeasurement
    {
        return $measurement->load('measurementSession');
    }
}
