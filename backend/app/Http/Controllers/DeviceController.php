<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Application\Device\Commands\CreateDeviceUseCase;
use App\Application\Device\Commands\DeleteDeviceUseCase;
use App\Application\Device\Commands\UpdateDeviceUseCase;
use App\Application\Device\Queries\ListDevicesUseCase;
use App\Application\Device\Queries\ShowDeviceUseCase;
use App\Http\Requests\Devices\ListDevicesRequest;
use App\Http\Requests\Devices\StoreDeviceRequest;
use App\Http\Requests\Devices\UpdateDeviceRequest;
use App\Http\Resources\Devices\DeviceResource;
use App\Models\Device;
use Illuminate\Http\JsonResponse;

final class DeviceController extends Controller
{
    public function index(ListDevicesRequest $request, ListDevicesUseCase $useCase): JsonResponse
    {
        $devices = $useCase->execute($request->toDto());

        return response()->json([
            'success' => true,
            'data' => DeviceResource::collection($devices->items())->resolve(),
            'error' => null,
            'meta' => [
                'page' => $devices->currentPage(),
                'per_page' => $devices->perPage(),
                'total' => $devices->total(),
                'last_page' => $devices->lastPage(),
            ],
        ]);
    }

    public function store(StoreDeviceRequest $request, CreateDeviceUseCase $useCase): JsonResponse
    {
        $device = $useCase->execute($request->toDto());
        $device->load('user');

        return response()->json([
            'success' => true,
            'data' => DeviceResource::make($device)->resolve(),
            'error' => null,
            'meta' => null,
        ], 201);
    }

    public function show(Device $device, ShowDeviceUseCase $useCase): JsonResponse
    {
        $device = $useCase->execute($device);

        return response()->json([
            'success' => true,
            'data' => DeviceResource::make($device)->resolve(),
            'error' => null,
            'meta' => null,
        ]);
    }

    public function update(Device $device, UpdateDeviceRequest $request, UpdateDeviceUseCase $useCase): JsonResponse
    {
        $device = $useCase->execute($device, $request->toDto());

        return response()->json([
            'success' => true,
            'data' => DeviceResource::make($device)->resolve(),
            'error' => null,
            'meta' => null,
        ]);
    }

    public function destroy(Device $device, DeleteDeviceUseCase $useCase): JsonResponse
    {
        $useCase->execute($device);

        return response()->json([
            'success' => true,
            'data' => null,
            'error' => null,
            'meta' => null,
        ], 204);
    }
}
