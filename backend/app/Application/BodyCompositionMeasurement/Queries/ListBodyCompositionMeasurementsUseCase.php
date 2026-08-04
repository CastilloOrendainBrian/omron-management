<?php

declare(strict_types=1);

namespace App\Application\BodyCompositionMeasurement\Queries;

use App\Domain\BodyCompositionMeasurement\Contracts\BodyCompositionMeasurementRepositoryInterface;
use App\Domain\BodyCompositionMeasurement\DTOs\ListBodyCompositionMeasurementsDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final readonly class ListBodyCompositionMeasurementsUseCase
{
    public function __construct(
        private BodyCompositionMeasurementRepositoryInterface $measurements,
    ) {
    }

    public function execute(ListBodyCompositionMeasurementsDTO $dto): LengthAwarePaginator
    {
        return $this->measurements->paginate($dto);
    }
}
