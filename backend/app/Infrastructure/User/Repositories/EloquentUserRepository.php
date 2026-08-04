<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Repositories;

use App\Domain\User\Contracts\UserRepositoryInterface;
use App\Domain\User\DTOs\CreateUserDTO;
use App\Domain\User\DTOs\ListUsersDTO;
use App\Domain\User\DTOs\UpdateUserDTO;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class EloquentUserRepository implements UserRepositoryInterface
{
    public function find(int $id): ?User
    {
        return User::find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function paginate(ListUsersDTO $dto): LengthAwarePaginator
    {
        return User::query()
            ->when($dto->name !== null && $dto->name !== '', fn ($q) => $q->where('name', 'ilike', '%' . $dto->name . '%'))
            ->when($dto->email !== null && $dto->email !== '', fn ($q) => $q->where('email', 'ilike', '%' . $dto->email . '%'))
            ->orderByDesc('created_at')
            ->paginate($dto->perPage);
    }

    public function create(CreateUserDTO $dto): User
    {
        return User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => $dto->password,
        ]);
    }

    public function update(User $user, UpdateUserDTO $dto): User
    {
        $data = array_filter([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => $dto->password,
        ], static fn (mixed $value): bool => $value !== null);

        if ($data !== []) {
            $user->update($data);
        }

        return $user->fresh();
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
