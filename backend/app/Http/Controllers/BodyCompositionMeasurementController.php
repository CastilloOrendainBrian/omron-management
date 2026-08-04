<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Application\BodyCompositionMeasurement\Commands\CreateBodyCompositionMeasurementUseCase;
use App\Application\BodyCompositionMeasurement\Commands\DeleteBodyCompositionMeasurementUseCase;
use App\Application\BodyCompositionMeasurement\Commands\UpdateBodyCompositionMeasurementUseCase;
use App\Application\BodyCompositionMeasurement\Queries\ListBodyCompositionMeasurementsUseCase;
use App\Application\BodyCompositionMeasurement\Queries\ShowBodyCompositionMeasurementUseCase;
use App\Http\Requests\BodyCompositionMeasurements\ListBodyCompositionMeasurementsRequest;
use App\Http\Requests\BodyCompositionMeasurements\StoreBodyCompositionMeasurementRequest;
use App\Http\Requests\BodyCompositionMeasurements\UpdateBodyCompositionMeasurementRequest;
use App\Http\Resources\BodyCompositionMeasurements\BodyCompositionMeasurementResource;
use App\Models\BodyCompositionMeasurement;
use Illuminate\Http\JsonResponse;

final class BodyCompositionMeasurementController extends Controller
{
    public function index(ListBodyCompositionMeasurementsRequest $request, ListBodyCompositionMeasurementsUseCase $useCase): JsonResponse
    {
        $measurements = $useCase->execute($request->toDto());

        return response()->json([
            'success' => true,
            'data' => BodyCompositionMeasurementResource::collection($measurements->items())->resolve(),
            'error' => null,
            'meta' => [
                'page' => $measurements->currentPage(),
                'per_page' => $measurements->perPage(),
                'total' => $measurements->total(),
                'last_page' => $measurements->lastPage(),
            ],
        ]);
    }

    public function store(StoreBodyCompositionMeasurementRequest $request, CreateBodyCompositionMeasurementUseCase $useCase): JsonResponse
    {
        $measurement = $useCase->execute($request->toDto());
        $measurement->load('measurementSession');

        return response()->json([
            'success' => true,
            'data' => BodyCompositionMeasurementResource::make($measurement)->resolve(),
            'error' => null,
            'meta' => null,
        ], 201);
    }

    public function show(BodyCompositionMeasurement $body_composition_measurement, ShowBodyCompositionMeasurementUseCase $useCase): JsonResponse
    {
        $measurement = $useCase->execute($body_composition_measurement);

        return response()->json([
            'success' => true,
            'data' => BodyCompositionMeasurementResource::make($measurement)->resolve(),
            'error' => null,
            'meta' => null,
        ]);
    }

    public function update(BodyCompositionMeasurement $body_composition_measurement, UpdateBodyCompositionMeasurementRequest $request, UpdateBodyCompositionMeasurementUseCase $useCase): JsonResponse
    {
        $measurement = $useCase->execute($body_composition_measurement, $request->toDto());

        return response()->json([
            'success' => true,
            'data' => BodyCompositionMeasurementResource::make($measurement)->resolve(),
            'error' => null,
            'meta' => null,
        ]);
    }

    public function destroy(BodyCompositionMeasurement $body_composition_measurement, DeleteBodyCompositionMeasurementUseCase $useCase): JsonResponse
    {
        $useCase->execute($body_composition_measurement);

        return response()->json([
            'success' => true,
            'data' => null,
            'error' => null,
            'meta' => null,
        ], 204);
    }
}
