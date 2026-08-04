<?php

declare(strict_types=1);

namespace App\Infrastructure\UserProfile\Repositories;

use App\Domain\UserProfile\Contracts\UserProfileRepositoryInterface;
use App\Domain\UserProfile\DTOs\CreateUserProfileDTO;
use App\Domain\UserProfile\DTOs\ListUserProfilesDTO;
use App\Domain\UserProfile\DTOs\UpdateUserProfileDTO;
use App\Models\UserProfile;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class EloquentUserProfileRepository implements UserProfileRepositoryInterface
{
    public function find(int $id): ?UserProfile
    {
        return UserProfile::find($id);
    }

    public function paginate(ListUserProfilesDTO $dto): LengthAwarePaginator
    {
        return UserProfile::query()
            ->with('user')
            ->when($dto->sex !== null && $dto->sex !== '', fn ($q) => $q->where('sex', $dto->sex))
            ->when($dto->activityLevel !== null && $dto->activityLevel !== '', fn ($q) => $q->where('activity_level', $dto->activityLevel))
            ->when(
                $dto->heightMin !== null && $dto->heightMax !== null,
                fn ($q) => $q->whereBetween('height_reference_cm', [$dto->heightMin, $dto->heightMax]),
            )
            ->when(
                $dto->heightMin !== null && $dto->heightMax === null,
                fn ($q) => $q->where('height_reference_cm', '>=', $dto->heightMin),
            )
            ->when(
                $dto->heightMax !== null && $dto->heightMin === null,
                fn ($q) => $q->where('height_reference_cm', '<=', $dto->heightMax),
            )
            ->orderByDesc('created_at')
            ->paginate($dto->perPage);
    }

    public function create(CreateUserProfileDTO $dto): UserProfile
    {
        return UserProfile::create([
            'user_id' => $dto->userId,
            'sex' => $dto->sex,
            'birth_date' => $dto->birthDate,
            'height_reference_cm' => $dto->heightReferenceCm,
            'activity_level' => $dto->activityLevel,
        ]);
    }

    public function update(UserProfile $profile, UpdateUserProfileDTO $dto): UserProfile
    {
        $data = array_filter([
            'sex' => $dto->sex,
            'birth_date' => $dto->birthDate,
            'height_reference_cm' => $dto->heightReferenceCm,
            'activity_level' => $dto->activityLevel,
        ], static fn (mixed $value): bool => $value !== null);

        if ($data !== []) {
            $profile->update($data);
        }

        return $profile->fresh();
    }

    public function delete(UserProfile $profile): void
    {
        $profile->delete();
    }
}
