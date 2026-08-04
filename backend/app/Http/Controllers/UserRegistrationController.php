<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Application\User\Commands\CreateUserWithProfileUseCase;
use App\Application\User\Commands\UpdateUserWithProfileUseCase;
use App\Http\Requests\Users\CreateUserWithProfileRequest;
use App\Http\Requests\Users\UpdateUserWithProfileRequest;
use App\Http\Resources\Users\UserWithProfileResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

final class UserRegistrationController extends Controller
{
    public function register(CreateUserWithProfileRequest $request, CreateUserWithProfileUseCase $useCase): JsonResponse
    {
        $result = $useCase->execute($request->toDto());

        return response()->json([
            'success' => true,
            'data' => (new UserWithProfileResource($result))->toArray($request),
            'error' => null,
            'meta' => null,
        ], 201);
    }

    public function updateWithProfile(User $user, UpdateUserWithProfileRequest $request, UpdateUserWithProfileUseCase $useCase): JsonResponse
    {
        $result = $useCase->execute($user, $request->toDto());

        return response()->json([
            'success' => true,
            'data' => (new UserWithProfileResource($result))->toArray($request),
            'error' => null,
            'meta' => null,
        ]);
    }
}
