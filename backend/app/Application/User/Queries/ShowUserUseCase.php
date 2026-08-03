<?php

declare(strict_types=1);

namespace App\Application\User\Queries;

use App\Domain\User\Contracts\UserRepositoryInterface;
use App\Models\User;

final readonly class ShowUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $users,
    ) {
    }

    public function execute(User $user): User
    {
        return $user->load(['profile', 'roles']);
    }
}
