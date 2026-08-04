<?php

declare(strict_types=1);

namespace App\Domain\SkinfoldMeasurement\Contracts;

use App\Domain\SkinfoldMeasurement\DTOs\CreateSkinfoldMeasurementDTO;
use App\Domain\SkinfoldMeasurement\DTOs\ListSkinfoldMeasurementsDTO;
use App\Domain\SkinfoldMeasurement\DTOs\UpdateSkinfoldMeasurementDTO;
use App\Models\SkinfoldMeasurement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SkinfoldMeasurementRepositoryInterface
{
    public function find(int $id): ?SkinfoldMeasurement;

    public function paginate(ListSkinfoldMeasurementsDTO $dto): LengthAwarePaginator;

    public function create(CreateSkinfoldMeasurementDTO $dto): SkinfoldMeasurement;

    public function update(SkinfoldMeasurement $measurement, UpdateSkinfoldMeasurementDTO $dto): SkinfoldMeasurement;

    public function delete(SkinfoldMeasurement $measurement): void;
}
