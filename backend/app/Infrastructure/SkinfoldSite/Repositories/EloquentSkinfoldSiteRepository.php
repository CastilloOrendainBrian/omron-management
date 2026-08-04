<?php

declare(strict_types=1);

namespace App\Infrastructure\SkinfoldSite\Repositories;

use App\Domain\SkinfoldSite\Contracts\SkinfoldSiteRepositoryInterface;
use App\Domain\SkinfoldSite\DTOs\CreateSkinfoldSiteDTO;
use App\Domain\SkinfoldSite\DTOs\ListSkinfoldSitesDTO;
use App\Domain\SkinfoldSite\DTOs\UpdateSkinfoldSiteDTO;
use App\Models\SkinfoldSite;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class EloquentSkinfoldSiteRepository implements SkinfoldSiteRepositoryInterface
{
    public function find(int $id): ?SkinfoldSite
    {
        return SkinfoldSite::find($id);
    }

    public function paginate(ListSkinfoldSitesDTO $dto): LengthAwarePaginator
    {
        return SkinfoldSite::query()
            ->when($dto->code !== null && $dto->code !== '', fn ($q) => $q->where('code', 'ilike', '%' . $dto->code . '%'))
            ->when($dto->name !== null && $dto->name !== '', fn ($q) => $q->where('name', 'ilike', '%' . $dto->name . '%'))
            ->orderBy('code')
            ->paginate($dto->perPage);
    }

    public function create(CreateSkinfoldSiteDTO $dto): SkinfoldSite
    {
        return SkinfoldSite::create([
            'code' => $dto->code,
            'name' => $dto->name,
        ]);
    }

    public function update(SkinfoldSite $site, UpdateSkinfoldSiteDTO $dto): SkinfoldSite
    {
        $data = array_filter([
            'code' => $dto->code,
            'name' => $dto->name,
        ], static fn (mixed $value): bool => $value !== null);

        if ($data !== []) {
            $site->update($data);
        }

        return $site->fresh();
    }

    public function delete(SkinfoldSite $site): void
    {
        $site->delete();
    }
}
