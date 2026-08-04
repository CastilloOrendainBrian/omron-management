<?php

declare(strict_types=1);

namespace App\Application\Goal\Commands;

use App\Domain\Goal\Contracts\GoalRepositoryInterface;
use App\Models\Goal;

final readonly class DeleteGoalUseCase
{
    public function __construct(
        private GoalRepositoryInterface $goals,
    ) {
    }

    public function execute(Goal $goal): void
    {
        $this->goals->delete($goal);
    }
}
