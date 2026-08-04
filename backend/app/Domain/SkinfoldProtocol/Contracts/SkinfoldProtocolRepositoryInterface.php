<?php

declare(strict_types=1);

namespace App\Domain\SkinfoldProtocol\Contracts;

use App\Domain\SkinfoldProtocol\DTOs\CreateSkinfoldProtocolDTO;
use App\Domain\SkinfoldProtocol\DTOs\ListSkinfoldProtocolsDTO;
use App\Domain\SkinfoldProtocol\DTOs\UpdateSkinfoldProtocolDTO;
use App\Models\SkinfoldProtocol;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SkinfoldProtocolRepositoryInterface
{
    public function find(int $id): ?SkinfoldProtocol;

    public function paginate(ListSkinfoldProtocolsDTO $dto): LengthAwarePaginator;

    public function create(CreateSkinfoldProtocolDTO $dto): SkinfoldProtocol;

    public function update(SkinfoldProtocol $protocol, UpdateSkinfoldProtocolDTO $dto): SkinfoldProtocol;

    public function delete(SkinfoldProtocol $protocol): void;
}
