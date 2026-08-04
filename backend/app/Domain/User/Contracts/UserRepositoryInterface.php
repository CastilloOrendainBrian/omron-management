<?php

declare(strict_types=1);

namespace App\Domain\User\Contracts;

use App\Domain\User\DTOs\CreateUserDTO;
use App\Domain\User\DTOs\ListUsersDTO;
use App\Domain\User\DTOs\UpdateUserDTO;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function find(int $id): ?User;

    public function findByEmail(string $email): ?User;

    public function paginate(ListUsersDTO $dto): LengthAwarePaginator;

    public function create(CreateUserDTO $dto): User;

    public function update(User $user, UpdateUserDTO $dto): User;

    public function delete(User $user): void;
}
