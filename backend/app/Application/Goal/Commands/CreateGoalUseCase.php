<?php

declare(strict_types=1);

namespace App\Application\Goal\Commands;

use App\Domain\Goal\Contracts\GoalRepositoryInterface;
use App\Domain\Goal\DTOs\CreateGoalDTO;
use App\Models\Goal;

final readonly class CreateGoalUseCase
{
    public function __construct(
        private GoalRepositoryInterface $goals,
    ) {
    }

    public function execute(CreateGoalDTO $dto): Goal
    {
        return $this->goals->create($dto);
    }
}
