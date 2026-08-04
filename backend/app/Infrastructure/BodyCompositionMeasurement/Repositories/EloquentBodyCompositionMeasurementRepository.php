<?php

declare(strict_types=1);

namespace App\Infrastructure\BodyCompositionMeasurement\Repositories;

use App\Domain\BodyCompositionMeasurement\Contracts\BodyCompositionMeasurementRepositoryInterface;
use App\Domain\BodyCompositionMeasurement\DTOs\CreateBodyCompositionMeasurementDTO;
use App\Domain\BodyCompositionMeasurement\DTOs\ListBodyCompositionMeasurementsDTO;
use App\Domain\BodyCompositionMeasurement\DTOs\UpdateBodyCompositionMeasurementDTO;
use App\Models\BodyCompositionMeasurement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class EloquentBodyCompositionMeasurementRepository implements BodyCompositionMeasurementRepositoryInterface
{
    public function find(int $id): ?BodyCompositionMeasurement
    {
        return BodyCompositionMeasurement::find($id);
    }

    public function paginate(ListBodyCompositionMeasurementsDTO $dto): LengthAwarePaginator
    {
        return BodyCompositionMeasurement::query()
            ->when($dto->measurementSessionId !== null, fn ($q) => $q->where('measurement_session_id', $dto->measurementSessionId))
            ->orderByDesc('created_at')
            ->paginate($dto->perPage);
    }

    public function create(CreateBodyCompositionMeasurementDTO $dto): BodyCompositionMeasurement
    {
        return BodyCompositionMeasurement::create([
            'measurement_session_id' => $dto->measurementSessionId,
            'body_fat_percentage' => $dto->bodyFatPercentage,
            'muscle_percentage' => $dto->musclePercentage,
            'visceral_fat_level' => $dto->visceralFatLevel,
            'metabolic_age' => $dto->metabolicAge,
            'bmr_kcal' => $dto->bmrKcal,
        ]);
    }

    public function update(BodyCompositionMeasurement $measurement, UpdateBodyCompositionMeasurementDTO $dto): BodyCompositionMeasurement
    {
        $data = array_filter([
            'body_fat_percentage' => $dto->bodyFatPercentage,
            'muscle_percentage' => $dto->musclePercentage,
            'visceral_fat_level' => $dto->visceralFatLevel,
            'metabolic_age' => $dto->metabolicAge,
            'bmr_kcal' => $dto->bmrKcal,
        ], static fn (mixed $value): bool => $value !== null);

        if ($data !== []) {
            $measurement->update($data);
        }

        return $measurement->fresh();
    }

    public function delete(BodyCompositionMeasurement $measurement): void
    {
        $measurement->delete();
    }
}
