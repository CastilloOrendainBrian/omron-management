<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Application\AnthropometricMeasurement\Commands\CreateAnthropometricMeasurementUseCase;
use App\Application\AnthropometricMeasurement\Commands\DeleteAnthropometricMeasurementUseCase;
use App\Application\AnthropometricMeasurement\Commands\UpdateAnthropometricMeasurementUseCase;
use App\Application\AnthropometricMeasurement\Queries\ListAnthropometricMeasurementsUseCase;
use App\Application\AnthropometricMeasurement\Queries\ShowAnthropometricMeasurementUseCase;
use App\Http\Requests\AnthropometricMeasurements\ListAnthropometricMeasurementsRequest;
use App\Http\Requests\AnthropometricMeasurements\StoreAnthropometricMeasurementRequest;
use App\Http\Requests\AnthropometricMeasurements\UpdateAnthropometricMeasurementRequest;
use App\Http\Resources\AnthropometricMeasurements\AnthropometricMeasurementResource;
use App\Models\AnthropometricMeasurement;
use Illuminate\Http\JsonResponse;

final class AnthropometricMeasurementController extends Controller
{
    public function index(ListAnthropometricMeasurementsRequest $request, ListAnthropometricMeasurementsUseCase $useCase): JsonResponse
    {
        $measurements = $useCase->execute($request->toDto());

        return response()->json([
            'success' => true,
            'data' => AnthropometricMeasurementResource::collection($measurements->items())->resolve(),
            'error' => null,
            'meta' => [
                'page' => $measurements->currentPage(),
                'per_page' => $measurements->perPage(),
                'total' => $measurements->total(),
                'last_page' => $measurements->lastPage(),
            ],
        ]);
    }

    public function store(StoreAnthropometricMeasurementRequest $request, CreateAnthropometricMeasurementUseCase $useCase): JsonResponse
    {
        $measurement = $useCase->execute($request->toDto());
        $measurement->load('measurementSession');

        return response()->json([
            'success' => true,
            'data' => AnthropometricMeasurementResource::make($measurement)->resolve(),
            'error' => null,
            'meta' => null,
        ], 201);
    }

    public function show(AnthropometricMeasurement $anthropometric_measurement, ShowAnthropometricMeasurementUseCase $useCase): JsonResponse
    {
        $measurement = $useCase->execute($anthropometric_measurement);

        return response()->json([
            'success' => true,
            'data' => AnthropometricMeasurementResource::make($measurement)->resolve(),
            'error' => null,
            'meta' => null,
        ]);
    }

    public function update(AnthropometricMeasurement $anthropometric_measurement, UpdateAnthropometricMeasurementRequest $request, UpdateAnthropometricMeasurementUseCase $useCase): JsonResponse
    {
        $measurement = $useCase->execute($anthropometric_measurement, $request->toDto());

        return response()->json([
            'success' => true,
            'data' => AnthropometricMeasurementResource::make($measurement)->resolve(),
            'error' => null,
            'meta' => null,
        ]);
    }

    public function destroy(AnthropometricMeasurement $anthropometric_measurement, DeleteAnthropometricMeasurementUseCase $useCase): JsonResponse
    {
        $useCase->execute($anthropometric_measurement);

        return response()->json([
            'success' => true,
            'data' => null,
            'error' => null,
            'meta' => null,
        ], 204);
    }
}
