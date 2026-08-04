<?php

declare(strict_types=1);

namespace App\Domain\Goal\Contracts;

use App\Domain\Goal\DTOs\CreateGoalDTO;
use App\Domain\Goal\DTOs\ListGoalsDTO;
use App\Domain\Goal\DTOs\UpdateGoalDTO;
use App\Models\Goal;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface GoalRepositoryInterface
{
    public function find(int $id): ?Goal;

    public function paginate(ListGoalsDTO $dto): LengthAwarePaginator;

    public function create(CreateGoalDTO $dto): Goal;

    public function update(Goal $goal, UpdateGoalDTO $dto): Goal;

    public function delete(Goal $goal): void;
}
