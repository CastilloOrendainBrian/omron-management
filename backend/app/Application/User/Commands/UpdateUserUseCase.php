<?php

declare(strict_types=1);

namespace App\Application\User\Commands;

use App\Domain\User\Contracts\UserRepositoryInterface;
use App\Domain\User\DTOs\UpdateUserDTO;
use App\Models\User;

final readonly class UpdateUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $users,
    ) {
    }

    public function execute(User $user, UpdateUserDTO $dto): User
    {
        return $this->users->update($user, $dto);
    }
}
