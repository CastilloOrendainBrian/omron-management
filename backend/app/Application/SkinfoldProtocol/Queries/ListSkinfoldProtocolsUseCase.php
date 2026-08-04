<?php

declare(strict_types=1);

namespace App\Application\SkinfoldProtocol\Queries;

use App\Domain\SkinfoldProtocol\Contracts\SkinfoldProtocolRepositoryInterface;
use App\Domain\SkinfoldProtocol\DTOs\ListSkinfoldProtocolsDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final readonly class ListSkinfoldProtocolsUseCase
{
    public function __construct(
        private SkinfoldProtocolRepositoryInterface $protocols,
    ) {
    }

    public function execute(ListSkinfoldProtocolsDTO $dto): LengthAwarePaginator
    {
        return $this->protocols->paginate($dto);
    }
}
