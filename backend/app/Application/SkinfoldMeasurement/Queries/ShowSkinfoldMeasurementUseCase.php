<?php

declare(strict_types=1);

namespace App\Application\SkinfoldMeasurement\Queries;

use App\Models\SkinfoldMeasurement;

final readonly class ShowSkinfoldMeasurementUseCase
{
    public function execute(SkinfoldMeasurement $measurement): SkinfoldMeasurement
    {
        return $measurement->load(['details.site', 'protocol']);
    }
}
