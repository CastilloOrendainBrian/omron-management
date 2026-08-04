<?php

declare(strict_types=1);

namespace App\Domain\BodyCompositionMeasurement\Contracts;

use App\Domain\BodyCompositionMeasurement\DTOs\CreateBodyCompositionMeasurementDTO;
use App\Domain\BodyCompositionMeasurement\DTOs\ListBodyCompositionMeasurementsDTO;
use App\Domain\BodyCompositionMeasurement\DTOs\UpdateBodyCompositionMeasurementDTO;
use App\Models\BodyCompositionMeasurement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BodyCompositionMeasurementRepositoryInterface
{
    public function find(int $id): ?BodyCompositionMeasurement;

    public function paginate(ListBodyCompositionMeasurementsDTO $dto): LengthAwarePaginator;

    public function create(CreateBodyCompositionMeasurementDTO $dto): BodyCompositionMeasurement;

    public function update(BodyCompositionMeasurement $measurement, UpdateBodyCompositionMeasurementDTO $dto): BodyCompositionMeasurement;

    public function delete(BodyCompositionMeasurement $measurement): void;
}
