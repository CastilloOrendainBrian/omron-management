<?php

declare(strict_types=1);

namespace App\Domain\UserProfile\Contracts;

use App\Domain\UserProfile\DTOs\CreateUserProfileDTO;
use App\Domain\UserProfile\DTOs\ListUserProfilesDTO;
use App\Domain\UserProfile\DTOs\UpdateUserProfileDTO;
use App\Models\UserProfile;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserProfileRepositoryInterface
{
    public function find(int $id): ?UserProfile;

    public function paginate(ListUserProfilesDTO $dto): LengthAwarePaginator;

    public function create(CreateUserProfileDTO $dto): UserProfile;

    public function update(UserProfile $profile, UpdateUserProfileDTO $dto): UserProfile;

    public function delete(UserProfile $profile): void;
}
