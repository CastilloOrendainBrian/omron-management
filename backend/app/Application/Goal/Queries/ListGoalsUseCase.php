<?php

declare(strict_types=1);

namespace App\Application\Goal\Queries;

use App\Domain\Goal\Contracts\GoalRepositoryInterface;
use App\Domain\Goal\DTOs\ListGoalsDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final readonly class ListGoalsUseCase
{
    public function __construct(
        private GoalRepositoryInterface $goals,
    ) {
    }

    public function execute(ListGoalsDTO $dto): LengthAwarePaginator
    {
        return $this->goals->paginate($dto);
    }
}
