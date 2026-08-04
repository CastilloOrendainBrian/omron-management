<?php

declare(strict_types=1);

namespace App\Application\SkinfoldMeasurement\Queries;

use App\Domain\SkinfoldMeasurement\Contracts\SkinfoldMeasurementRepositoryInterface;
use App\Domain\SkinfoldMeasurement\DTOs\ListSkinfoldMeasurementsDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final readonly class ListSkinfoldMeasurementsUseCase
{
    public function __construct(
        private SkinfoldMeasurementRepositoryInterface $measurements,
    ) {
    }

    public function execute(ListSkinfoldMeasurementsDTO $dto): LengthAwarePaginator
    {
        return $this->measurements->paginate($dto);
    }
}
