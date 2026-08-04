<?php

declare(strict_types=1);

namespace App\Application\BodyCompositionMeasurement\Commands;

use App\Domain\BodyCompositionMeasurement\Contracts\BodyCompositionMeasurementRepositoryInterface;
use App\Models\BodyCompositionMeasurement;

final readonly class DeleteBodyCompositionMeasurementUseCase
{
    public function __construct(
        private BodyCompositionMeasurementRepositoryInterface $measurements,
    ) {
    }

    public function execute(BodyCompositionMeasurement $measurement): void
    {
        $this->measurements->delete($measurement);
    }
}
