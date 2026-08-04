<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Application\SkinfoldMeasurement\Commands\CreateSkinfoldMeasurementUseCase;
use App\Application\SkinfoldMeasurement\Commands\DeleteSkinfoldMeasurementUseCase;
use App\Application\SkinfoldMeasurement\Commands\UpdateSkinfoldMeasurementUseCase;
use App\Application\SkinfoldMeasurement\Queries\ListSkinfoldMeasurementsUseCase;
use App\Application\SkinfoldMeasurement\Queries\ShowSkinfoldMeasurementUseCase;
use App\Http\Requests\SkinfoldMeasurements\ListSkinfoldMeasurementsRequest;
use App\Http\Requests\SkinfoldMeasurements\StoreSkinfoldMeasurementRequest;
use App\Http\Requests\SkinfoldMeasurements\UpdateSkinfoldMeasurementRequest;
use App\Http\Resources\SkinfoldMeasurements\SkinfoldMeasurementResource;
use App\Models\SkinfoldMeasurement;
use Illuminate\Http\JsonResponse;

final class SkinfoldMeasurementController extends Controller
{
    public function index(ListSkinfoldMeasurementsRequest $request, ListSkinfoldMeasurementsUseCase $useCase): JsonResponse
    {
        $measurements = $useCase->execute($request->toDto());

        return response()->json([
            'success' => true,
            'data' => SkinfoldMeasurementResource::collection($measurements->items())->resolve(),
            'error' => null,
            'meta' => [
                'page' => $measurements->currentPage(),
                'per_page' => $measurements->perPage(),
                'total' => $measurements->total(),
                'last_page' => $measurements->lastPage(),
            ],
        ]);
    }

    public function store(StoreSkinfoldMeasurementRequest $request, CreateSkinfoldMeasurementUseCase $useCase): JsonResponse
    {
        $measurement = $useCase->execute($request->toDto());
        $measurement->load(['details.site', 'protocol']);

        return response()->json([
            'success' => true,
            'data' => SkinfoldMeasurementResource::make($measurement)->resolve(),
            'error' => null,
            'meta' => null,
        ], 201);
    }

    public function show(SkinfoldMeasurement $skinfold_measurement, ShowSkinfoldMeasurementUseCase $useCase): JsonResponse
    {
        $measurement = $useCase->execute($skinfold_measurement);

        return response()->json([
            'success' => true,
            'data' => SkinfoldMeasurementResource::make($measurement)->resolve(),
            'error' => null,
            'meta' => null,
        ]);
    }

    public function update(SkinfoldMeasurement $skinfold_measurement, UpdateSkinfoldMeasurementRequest $request, UpdateSkinfoldMeasurementUseCase $useCase): JsonResponse
    {
        $measurement = $useCase->execute($skinfold_measurement, $request->toDto());

        return response()->json([
            'success' => true,
            'data' => SkinfoldMeasurementResource::make($measurement)->resolve(),
            'error' => null,
            'meta' => null,
        ]);
    }

    public function destroy(SkinfoldMeasurement $skinfold_measurement, DeleteSkinfoldMeasurementUseCase $useCase): JsonResponse
    {
        $useCase->execute($skinfold_measurement);

        return response()->json([
            'success' => true,
            'data' => null,
            'error' => null,
            'meta' => null,
        ], 204);
    }
}
