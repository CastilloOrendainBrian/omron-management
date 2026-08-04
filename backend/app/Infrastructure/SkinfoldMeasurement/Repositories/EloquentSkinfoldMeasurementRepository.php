<?php

declare(strict_types=1);

namespace App\Infrastructure\SkinfoldMeasurement\Repositories;

use App\Domain\SkinfoldMeasurement\Contracts\SkinfoldMeasurementRepositoryInterface;
use App\Domain\SkinfoldMeasurement\DTOs\CreateSkinfoldMeasurementDTO;
use App\Domain\SkinfoldMeasurement\DTOs\ListSkinfoldMeasurementsDTO;
use App\Domain\SkinfoldMeasurement\DTOs\UpdateSkinfoldMeasurementDTO;
use App\Models\SkinfoldMeasurement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class EloquentSkinfoldMeasurementRepository implements SkinfoldMeasurementRepositoryInterface
{
    public function find(int $id): ?SkinfoldMeasurement
    {
        return SkinfoldMeasurement::find($id);
    }

    public function paginate(ListSkinfoldMeasurementsDTO $dto): LengthAwarePaginator
    {
        return SkinfoldMeasurement::query()
            ->when($dto->measurementSessionId !== null, fn ($q) => $q->where('measurement_session_id', $dto->measurementSessionId))
            ->when($dto->skinfoldProtocolId !== null, fn ($q) => $q->where('skinfold_protocol_id', $dto->skinfoldProtocolId))
            ->with(['details.site', 'protocol'])
            ->orderByDesc('created_at')
            ->paginate($dto->perPage);
    }

    public function create(CreateSkinfoldMeasurementDTO $dto): SkinfoldMeasurement
    {
        return DB::transaction(function () use ($dto) {
            $measurement = SkinfoldMeasurement::create([
                'measurement_session_id' => $dto->measurementSessionId,
                'skinfold_protocol_id' => $dto->skinfoldProtocolId,
                'estimated_body_fat_percentage' => $dto->estimatedBodyFatPercentage,
            ]);

            foreach ($dto->details as $detailDto) {
                $measurement->details()->create([
                    'skinfold_site_id' => $detailDto->skinfoldSiteId,
                    'value_mm' => $detailDto->valueMm,
                ]);
            }

            return $measurement->load('details');
        });
    }

    public function update(SkinfoldMeasurement $measurement, UpdateSkinfoldMeasurementDTO $dto): SkinfoldMeasurement
    {
        return DB::transaction(function () use ($measurement, $dto) {
            $data = [];
            if ($dto->estimatedBodyFatPercentage !== null) {
                $data['estimated_body_fat_percentage'] = $dto->estimatedBodyFatPercentage;
            }

            if ($data) {
                $measurement->update($data);
            }

            if ($dto->details !== null) {
                $measurement->details()->forceDelete();

                foreach ($dto->details as $detailDto) {
                    $measurement->details()->create([
                        'skinfold_site_id' => $detailDto->skinfoldSiteId,
                        'value_mm' => $detailDto->valueMm,
                    ]);
                }
            }

            return $measurement->fresh()->load('details');
        });
    }

    public function delete(SkinfoldMeasurement $measurement): void
    {
        $measurement->delete();
    }
}
