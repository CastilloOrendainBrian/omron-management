<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Application\SkinfoldProtocol\Commands\CreateSkinfoldProtocolUseCase;
use App\Application\SkinfoldProtocol\Commands\DeleteSkinfoldProtocolUseCase;
use App\Application\SkinfoldProtocol\Commands\UpdateSkinfoldProtocolUseCase;
use App\Application\SkinfoldProtocol\Queries\ListSkinfoldProtocolsUseCase;
use App\Application\SkinfoldProtocol\Queries\ShowSkinfoldProtocolUseCase;
use App\Http\Requests\SkinfoldProtocols\ListSkinfoldProtocolsRequest;
use App\Http\Requests\SkinfoldProtocols\StoreSkinfoldProtocolRequest;
use App\Http\Requests\SkinfoldProtocols\UpdateSkinfoldProtocolRequest;
use App\Http\Resources\SkinfoldProtocols\SkinfoldProtocolResource;
use App\Models\SkinfoldProtocol;
use Illuminate\Http\JsonResponse;

final class SkinfoldProtocolController extends Controller
{
    public function index(ListSkinfoldProtocolsRequest $request, ListSkinfoldProtocolsUseCase $useCase): JsonResponse
    {
        $protocols = $useCase->execute($request->toDto());

        return response()->json([
            'success' => true,
            'data' => SkinfoldProtocolResource::collection($protocols->items())->resolve(),
            'error' => null,
            'meta' => [
                'page' => $protocols->currentPage(),
                'per_page' => $protocols->perPage(),
                'total' => $protocols->total(),
                'last_page' => $protocols->lastPage(),
            ],
        ]);
    }

    public function store(StoreSkinfoldProtocolRequest $request, CreateSkinfoldProtocolUseCase $useCase): JsonResponse
    {
        $protocol = $useCase->execute($request->toDto());

        return response()->json([
            'success' => true,
            'data' => SkinfoldProtocolResource::make($protocol)->resolve(),
            'error' => null,
            'meta' => null,
        ], 201);
    }

    public function show(SkinfoldProtocol $skinfold_protocol, ShowSkinfoldProtocolUseCase $useCase): JsonResponse
    {
        $protocol = $useCase->execute($skinfold_protocol);

        return response()->json([
            'success' => true,
            'data' => SkinfoldProtocolResource::make($protocol)->resolve(),
            'error' => null,
            'meta' => null,
        ]);
    }

    public function update(SkinfoldProtocol $skinfold_protocol, UpdateSkinfoldProtocolRequest $request, UpdateSkinfoldProtocolUseCase $useCase): JsonResponse
    {
        $protocol = $useCase->execute($skinfold_protocol, $request->toDto());

        return response()->json([
            'success' => true,
            'data' => SkinfoldProtocolResource::make($protocol)->resolve(),
            'error' => null,
            'meta' => null,
        ]);
    }

    public function destroy(SkinfoldProtocol $skinfold_protocol, DeleteSkinfoldProtocolUseCase $useCase): JsonResponse
    {
        $useCase->execute($skinfold_protocol);

        return response()->json([
            'success' => true,
            'data' => null,
            'error' => null,
            'meta' => null,
        ], 204);
    }
}
