<?php

declare(strict_types=1);

namespace App\Infrastructure\AnthropometricMeasurement\Repositories;

use App\Domain\AnthropometricMeasurement\Contracts\AnthropometricMeasurementRepositoryInterface;
use App\Domain\AnthropometricMeasurement\DTOs\CreateAnthropometricMeasurementDTO;
use App\Domain\AnthropometricMeasurement\DTOs\ListAnthropometricMeasurementsDTO;
use App\Domain\AnthropometricMeasurement\DTOs\UpdateAnthropometricMeasurementDTO;
use App\Models\AnthropometricMeasurement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class EloquentAnthropometricMeasurementRepository implements AnthropometricMeasurementRepositoryInterface
{
    public function find(int $id): ?AnthropometricMeasurement
    {
        return AnthropometricMeasurement::find($id);
    }

    public function paginate(ListAnthropometricMeasurementsDTO $dto): LengthAwarePaginator
    {
        return AnthropometricMeasurement::query()
            ->when($dto->measurementSessionId !== null, fn ($q) => $q->where('measurement_session_id', $dto->measurementSessionId))
            ->orderByDesc('created_at')
            ->paginate($dto->perPage);
    }

    public function create(CreateAnthropometricMeasurementDTO $dto): AnthropometricMeasurement
    {
        return AnthropometricMeasurement::create([
            'measurement_session_id' => $dto->measurementSessionId,
            'height_cm' => $dto->heightCm,
            'weight_kg' => $dto->weightKg,
            'bmi' => $dto->bmi,
        ]);
    }

    public function update(AnthropometricMeasurement $measurement, UpdateAnthropometricMeasurementDTO $dto): AnthropometricMeasurement
    {
        $data = array_filter([
            'height_cm' => $dto->heightCm,
            'weight_kg' => $dto->weightKg,
            'bmi' => $dto->bmi,
        ], static fn (mixed $value): bool => $value !== null);

        if ($data !== []) {
            $measurement->update($data);
        }

        return $measurement->fresh();
    }

    public function delete(AnthropometricMeasurement $measurement): void
    {
        $measurement->delete();
    }
}
