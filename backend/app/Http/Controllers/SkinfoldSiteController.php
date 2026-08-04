<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Application\SkinfoldSite\Commands\CreateSkinfoldSiteUseCase;
use App\Application\SkinfoldSite\Commands\DeleteSkinfoldSiteUseCase;
use App\Application\SkinfoldSite\Commands\UpdateSkinfoldSiteUseCase;
use App\Application\SkinfoldSite\Queries\ListSkinfoldSitesUseCase;
use App\Application\SkinfoldSite\Queries\ShowSkinfoldSiteUseCase;
use App\Http\Requests\SkinfoldSites\ListSkinfoldSitesRequest;
use App\Http\Requests\SkinfoldSites\StoreSkinfoldSiteRequest;
use App\Http\Requests\SkinfoldSites\UpdateSkinfoldSiteRequest;
use App\Http\Resources\SkinfoldSites\SkinfoldSiteResource;
use App\Models\SkinfoldSite;
use Illuminate\Http\JsonResponse;

final class SkinfoldSiteController extends Controller
{
    public function index(ListSkinfoldSitesRequest $request, ListSkinfoldSitesUseCase $useCase): JsonResponse
    {
        $sites = $useCase->execute($request->toDto());

        return response()->json([
            'success' => true,
            'data' => SkinfoldSiteResource::collection($sites->items())->resolve(),
            'error' => null,
            'meta' => [
                'page' => $sites->currentPage(),
                'per_page' => $sites->perPage(),
                'total' => $sites->total(),
                'last_page' => $sites->lastPage(),
            ],
        ]);
    }

    public function store(StoreSkinfoldSiteRequest $request, CreateSkinfoldSiteUseCase $useCase): JsonResponse
    {
        $site = $useCase->execute($request->toDto());

        return response()->json([
            'success' => true,
            'data' => SkinfoldSiteResource::make($site)->resolve(),
            'error' => null,
            'meta' => null,
        ], 201);
    }

    public function show(SkinfoldSite $skinfold_site, ShowSkinfoldSiteUseCase $useCase): JsonResponse
    {
        $site = $useCase->execute($skinfold_site);

        return response()->json([
            'success' => true,
            'data' => SkinfoldSiteResource::make($site)->resolve(),
            'error' => null,
            'meta' => null,
        ]);
    }

    public function update(SkinfoldSite $skinfold_site, UpdateSkinfoldSiteRequest $request, UpdateSkinfoldSiteUseCase $useCase): JsonResponse
    {
        $site = $useCase->execute($skinfold_site, $request->toDto());

        return response()->json([
            'success' => true,
            'data' => SkinfoldSiteResource::make($site)->resolve(),
            'error' => null,
            'meta' => null,
        ]);
    }

    public function destroy(SkinfoldSite $skinfold_site, DeleteSkinfoldSiteUseCase $useCase): JsonResponse
    {
        $useCase->execute($skinfold_site);

        return response()->json([
            'success' => true,
            'data' => null,
            'error' => null,
            'meta' => null,
        ], 204);
    }
}
