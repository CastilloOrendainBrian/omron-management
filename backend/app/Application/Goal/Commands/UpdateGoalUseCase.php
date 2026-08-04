<?php

declare(strict_types=1);

namespace App\Application\Goal\Commands;

use App\Domain\Goal\Contracts\GoalRepositoryInterface;
use App\Domain\Goal\DTOs\UpdateGoalDTO;
use App\Models\Goal;

final readonly class UpdateGoalUseCase
{
    public function __construct(
        private GoalRepositoryInterface $goals,
    ) {
    }

    public function execute(Goal $goal, UpdateGoalDTO $dto): Goal
    {
        return $this->goals->update($goal, $dto);
    }
}
