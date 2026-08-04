<?php

declare(strict_types=1);

namespace App\Application\AnthropometricMeasurement\Queries;

use App\Domain\AnthropometricMeasurement\Contracts\AnthropometricMeasurementRepositoryInterface;
use App\Domain\AnthropometricMeasurement\DTOs\ListAnthropometricMeasurementsDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final readonly class ListAnthropometricMeasurementsUseCase
{
    public function __construct(
        private AnthropometricMeasurementRepositoryInterface $measurements,
    ) {
    }

    public function execute(ListAnthropometricMeasurementsDTO $dto): LengthAwarePaginator
    {
        return $this->measurements->paginate($dto);
    }
}
