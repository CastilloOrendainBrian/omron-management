<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Application\User\Commands\CreateUserUseCase;
use App\Application\User\Commands\DeleteUserUseCase;
use App\Application\User\Commands\UpdateUserUseCase;
use App\Application\User\Queries\ListUsersUseCase;
use App\Application\User\Queries\ShowUserUseCase;
use App\Http\Requests\Users\ListUsersRequest;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\Users\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

final class UserController extends Controller
{
    public function index(ListUsersRequest $request, ListUsersUseCase $useCase): JsonResponse
    {
        $users = $useCase->execute($request->toDto());

        return response()->json([
            'success' => true,
            'data' => UserResource::collection($users->items())->resolve(),
            'error' => null,
            'meta' => [
                'page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'last_page' => $users->lastPage(),
            ],
        ]);
    }

    public function store(StoreUserRequest $request, CreateUserUseCase $useCase): JsonResponse
    {
        $user = $useCase->execute($request->toDto());

        return response()->json([
            'success' => true,
            'data' => UserResource::make($user)->resolve(),
            'error' => null,
            'meta' => null,
        ], 201);
    }

    public function show(User $user, ShowUserUseCase $useCase): JsonResponse
    {
        $user = $useCase->execute($user);

        return response()->json([
            'success' => true,
            'data' => UserResource::make($user)->resolve(),
            'error' => null,
            'meta' => null,
        ]);
    }

    public function update(User $user, UpdateUserRequest $request, UpdateUserUseCase $useCase): JsonResponse
    {
        $user = $useCase->execute($user, $request->toDto());

        return response()->json([
            'success' => true,
            'data' => UserResource::make($user)->resolve(),
            'error' => null,
            'meta' => null,
        ]);
    }

    public function destroy(User $user, DeleteUserUseCase $useCase): JsonResponse
    {
        $useCase->execute($user);

        return response()->json([
            'success' => true,
            'data' => null,
            'error' => null,
            'meta' => null,
        ], 204);
    }
}
