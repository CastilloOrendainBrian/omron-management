<?php

declare(strict_types=1);

namespace App\Infrastructure\SkinfoldProtocol\Repositories;

use App\Domain\SkinfoldProtocol\Contracts\SkinfoldProtocolRepositoryInterface;
use App\Domain\SkinfoldProtocol\DTOs\CreateSkinfoldProtocolDTO;
use App\Domain\SkinfoldProtocol\DTOs\ListSkinfoldProtocolsDTO;
use App\Domain\SkinfoldProtocol\DTOs\UpdateSkinfoldProtocolDTO;
use App\Models\SkinfoldProtocol;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class EloquentSkinfoldProtocolRepository implements SkinfoldProtocolRepositoryInterface
{
    public function find(int $id): ?SkinfoldProtocol
    {
        return SkinfoldProtocol::find($id);
    }

    public function paginate(ListSkinfoldProtocolsDTO $dto): LengthAwarePaginator
    {
        return SkinfoldProtocol::query()
            ->when($dto->name !== null && $dto->name !== '', fn ($q) => $q->where('name', 'ilike', '%' . $dto->name . '%'))
            ->when($dto->sitesCount !== null, fn ($q) => $q->where('sites_count', $dto->sitesCount))
            ->orderBy('sites_count')
            ->orderBy('name')
            ->paginate($dto->perPage);
    }

    public function create(CreateSkinfoldProtocolDTO $dto): SkinfoldProtocol
    {
        return SkinfoldProtocol::create([
            'name' => $dto->name,
            'sites_count' => $dto->sitesCount,
            'description' => $dto->description,
        ]);
    }

    public function update(SkinfoldProtocol $protocol, UpdateSkinfoldProtocolDTO $dto): SkinfoldProtocol
    {
        $data = array_filter([
            'name' => $dto->name,
            'sites_count' => $dto->sitesCount,
            'description' => $dto->description,
        ], static fn (mixed $value): bool => $value !== null);

        if ($data !== []) {
            $protocol->update($data);
        }

        return $protocol->fresh();
    }

    public function delete(SkinfoldProtocol $protocol): void
    {
        $protocol->delete();
    }
}
