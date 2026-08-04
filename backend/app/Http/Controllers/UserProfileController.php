<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Application\UserProfile\Commands\CreateUserProfileUseCase;
use App\Application\UserProfile\Commands\DeleteUserProfileUseCase;
use App\Application\UserProfile\Commands\UpdateUserProfileUseCase;
use App\Application\UserProfile\Queries\ListUserProfilesUseCase;
use App\Application\UserProfile\Queries\ShowUserProfileUseCase;
use App\Http\Requests\ListUserProfilesRequest;
use App\Http\Requests\StoreUserProfileRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Http\Resources\UserProfileResource;
use App\Models\UserProfile;
use Illuminate\Http\JsonResponse;

final class UserProfileController extends Controller
{
    public function index(ListUserProfilesRequest $request, ListUserProfilesUseCase $useCase): JsonResponse
    {
        $profiles = $useCase->execute($request->toDto());

        return response()->json([
            'success' => true,
            'data' => UserProfileResource::collection($profiles->items())->resolve(),
            'error' => null,
            'meta' => [
                'page' => $profiles->currentPage(),
                'per_page' => $profiles->perPage(),
                'total' => $profiles->total(),
                'last_page' => $profiles->lastPage(),
            ],
        ]);
    }

    public function store(StoreUserProfileRequest $request, CreateUserProfileUseCase $useCase): JsonResponse
    {
        $profile = $useCase->execute($request->toDto());

        return response()->json([
            'success' => true,
            'data' => UserProfileResource::make($profile)->resolve(),
            'error' => null,
            'meta' => null,
        ], 201);
    }

    public function show(UserProfile $profile, ShowUserProfileUseCase $useCase): JsonResponse
    {
        $profile = $useCase->execute($profile);

        return response()->json([
            'success' => true,
            'data' => UserProfileResource::make($profile)->resolve(),
            'error' => null,
            'meta' => null,
        ]);
    }

    public function update(UserProfile $profile, UpdateUserProfileRequest $request, UpdateUserProfileUseCase $useCase): JsonResponse
    {
        $profile = $useCase->execute($profile, $request->toDto());

        return response()->json([
            'success' => true,
            'data' => UserProfileResource::make($profile)->resolve(),
            'error' => null,
            'meta' => null,
        ]);
    }

    public function destroy(UserProfile $profile, DeleteUserProfileUseCase $useCase): JsonResponse
    {
        $useCase->execute($profile);

        return response()->json([
            'success' => true,
            'data' => null,
            'error' => null,
            'meta' => null,
        ], 204);
    }
}
