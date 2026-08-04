<?php

declare(strict_types=1);

namespace App\Application\User\Queries;

use App\Domain\User\Contracts\UserRepositoryInterface;
use App\Domain\User\DTOs\ListUsersDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final readonly class ListUsersUseCase
{
    public function __construct(
        private UserRepositoryInterface $users,
    ) {
    }

    public function execute(ListUsersDTO $dto): LengthAwarePaginator
    {
        return $this->users->paginate($dto);
    }
}
