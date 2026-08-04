<?php

declare(strict_types=1);

namespace App\Application\SkinfoldSite\Queries;

use App\Domain\SkinfoldSite\Contracts\SkinfoldSiteRepositoryInterface;
use App\Domain\SkinfoldSite\DTOs\ListSkinfoldSitesDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final readonly class ListSkinfoldSitesUseCase
{
    public function __construct(
        private SkinfoldSiteRepositoryInterface $sites,
    ) {
    }

    public function execute(ListSkinfoldSitesDTO $dto): LengthAwarePaginator
    {
        return $this->sites->paginate($dto);
    }
}
