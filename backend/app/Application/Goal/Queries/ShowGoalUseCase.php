<?php

declare(strict_types=1);

namespace App\Application\Goal\Queries;

use App\Models\Goal;

final readonly class ShowGoalUseCase
{
    public function execute(Goal $goal): Goal
    {
        return $goal->load('user');
    }
}
