<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Application\Goal\Commands\CreateGoalUseCase;
use App\Application\Goal\Commands\DeleteGoalUseCase;
use App\Application\Goal\Commands\UpdateGoalUseCase;
use App\Application\Goal\Queries\ListGoalsUseCase;
use App\Application\Goal\Queries\ShowGoalUseCase;
use App\Http\Requests\Goals\ListGoalsRequest;
use App\Http\Requests\Goals\StoreGoalRequest;
use App\Http\Requests\Goals\UpdateGoalRequest;
use App\Http\Resources\Goals\GoalResource;
use App\Models\Goal;
use Illuminate\Http\JsonResponse;

final class GoalController extends Controller
{
    public function index(ListGoalsRequest $request, ListGoalsUseCase $useCase): JsonResponse
    {
        $goals = $useCase->execute($request->toDto());

        return response()->json([
            'success' => true,
            'data' => GoalResource::collection($goals->items())->resolve(),
            'error' => null,
            'meta' => [
                'page' => $goals->currentPage(),
                'per_page' => $goals->perPage(),
                'total' => $goals->total(),
                'last_page' => $goals->lastPage(),
            ],
        ]);
    }

    public function store(StoreGoalRequest $request, CreateGoalUseCase $useCase): JsonResponse
    {
        $goal = $useCase->execute($request->toDto());
        $goal->load('user');

        return response()->json([
            'success' => true,
            'data' => GoalResource::make($goal)->resolve(),
            'error' => null,
            'meta' => null,
        ], 201);
    }

    public function show(Goal $goal, ShowGoalUseCase $useCase): JsonResponse
    {
        $goal = $useCase->execute($goal);

        return response()->json([
            'success' => true,
            'data' => GoalResource::make($goal)->resolve(),
            'error' => null,
            'meta' => null,
        ]);
    }

    public function update(Goal $goal, UpdateGoalRequest $request, UpdateGoalUseCase $useCase): JsonResponse
    {
        $goal = $useCase->execute($goal, $request->toDto());

        return response()->json([
            'success' => true,
            'data' => GoalResource::make($goal)->resolve(),
            'error' => null,
            'meta' => null,
        ]);
    }

    public function destroy(Goal $goal, DeleteGoalUseCase $useCase): JsonResponse
    {
        $useCase->execute($goal);

        return response()->json([
            'success' => true,
            'data' => null,
            'error' => null,
            'meta' => null,
        ], 204);
    }
}
