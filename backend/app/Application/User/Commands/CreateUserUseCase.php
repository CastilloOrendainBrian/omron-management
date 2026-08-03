<?php

declare(strict_types=1);

namespace App\Application\User\Commands;

use App\Domain\User\Contracts\UserRepositoryInterface;
use App\Domain\User\DTOs\CreateUserDTO;
use App\Models\User;

final readonly class CreateUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $users,
    ) {
    }

    public function execute(CreateUserDTO $dto): User
    {
        return $this->users->create($dto);
    }
}
