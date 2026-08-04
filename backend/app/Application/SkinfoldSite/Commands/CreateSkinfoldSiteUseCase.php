<?php

declare(strict_types=1);

namespace App\Application\SkinfoldSite\Commands;

use App\Domain\SkinfoldSite\Contracts\SkinfoldSiteRepositoryInterface;
use App\Domain\SkinfoldSite\DTOs\CreateSkinfoldSiteDTO;
use App\Models\SkinfoldSite;

final readonly class CreateSkinfoldSiteUseCase
{
    public function __construct(
        private SkinfoldSiteRepositoryInterface $sites,
    ) {
    }

    public function execute(CreateSkinfoldSiteDTO $dto): SkinfoldSite
    {
        return $this->sites->create($dto);
    }
}
