<?php

declare(strict_types=1);

namespace App\Application\Auth\Queries;

use App\Domain\User\Contracts\UserRepositoryInterface;
use App\Models\User;

final readonly class MeQuery
{
    public function __construct(
        private UserRepositoryInterface $users,
    ) {
    }

    public function execute(int $userId): User
    {
        $user = $this->users->find($userId);

        if ($user === null) {
            throw new \RuntimeException("Authenticated user not found: id={$userId}");
        }

        $user->load(['roles', 'profile']);

        return $user;
    }
}
