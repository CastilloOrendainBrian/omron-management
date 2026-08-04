<?php

declare(strict_types=1);

namespace App\Domain\AnthropometricMeasurement\Contracts;

use App\Domain\AnthropometricMeasurement\DTOs\CreateAnthropometricMeasurementDTO;
use App\Domain\AnthropometricMeasurement\DTOs\ListAnthropometricMeasurementsDTO;
use App\Domain\AnthropometricMeasurement\DTOs\UpdateAnthropometricMeasurementDTO;
use App\Models\AnthropometricMeasurement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AnthropometricMeasurementRepositoryInterface
{
    public function find(int $id): ?AnthropometricMeasurement;

    public function paginate(ListAnthropometricMeasurementsDTO $dto): LengthAwarePaginator;

    public function create(CreateAnthropometricMeasurementDTO $dto): AnthropometricMeasurement;

    public function update(AnthropometricMeasurement $measurement, UpdateAnthropometricMeasurementDTO $dto): AnthropometricMeasurement;

    public function delete(AnthropometricMeasurement $measurement): void;
}
