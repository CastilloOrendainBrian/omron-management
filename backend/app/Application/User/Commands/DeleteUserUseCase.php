<?php

declare(strict_types=1);

namespace App\Application\User\Commands;

use App\Domain\User\Contracts\UserRepositoryInterface;
use App\Models\User;

final readonly class DeleteUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $users,
    ) {
    }

    public function execute(User $user): void
    {
        $this->users->delete($user);
    }
}
