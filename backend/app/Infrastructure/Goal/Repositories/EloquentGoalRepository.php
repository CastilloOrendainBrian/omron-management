<?php

declare(strict_types=1);

namespace App\Infrastructure\Goal\Repositories;

use App\Domain\Goal\Contracts\GoalRepositoryInterface;
use App\Domain\Goal\DTOs\CreateGoalDTO;
use App\Domain\Goal\DTOs\ListGoalsDTO;
use App\Domain\Goal\DTOs\UpdateGoalDTO;
use App\Models\Goal;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class EloquentGoalRepository implements GoalRepositoryInterface
{
    public function find(int $id): ?Goal
    {
        return Goal::find($id);
    }

    public function paginate(ListGoalsDTO $dto): LengthAwarePaginator
    {
        return Goal::query()
            ->when($dto->userId !== null, fn ($q) => $q->where('user_id', $dto->userId))
            ->when($dto->status !== null && $dto->status !== '', fn ($q) => $q->where('status', $dto->status))
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate($dto->perPage);
    }

    public function create(CreateGoalDTO $dto): Goal
    {
        return Goal::create([
            'user_id' => $dto->userId,
            'target_weight_kg' => $dto->targetWeightKg,
            'target_body_fat_percentage' => $dto->targetBodyFatPercentage,
            'start_date' => $dto->startDate,
            'target_date' => $dto->targetDate,
            'status' => $dto->status ?? 'active',
        ]);
    }

    public function update(Goal $goal, UpdateGoalDTO $dto): Goal
    {
        $data = array_filter([
            'target_weight_kg' => $dto->targetWeightKg,
            'target_body_fat_percentage' => $dto->targetBodyFatPercentage,
            'start_date' => $dto->startDate,
            'target_date' => $dto->targetDate,
            'status' => $dto->status,
        ], static fn (mixed $value): bool => $value !== null);

        if ($data !== []) {
            $goal->update($data);
        }

        return $goal->fresh();
    }

    public function delete(Goal $goal): void
    {
        $goal->delete();
    }
}
