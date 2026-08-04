<?php

declare(strict_types=1);

namespace App\Application\SkinfoldSite\Commands;

use App\Domain\SkinfoldSite\Contracts\SkinfoldSiteRepositoryInterface;
use App\Domain\SkinfoldSite\DTOs\UpdateSkinfoldSiteDTO;
use App\Models\SkinfoldSite;

final readonly class UpdateSkinfoldSiteUseCase
{
    public function __construct(
        private SkinfoldSiteRepositoryInterface $sites,
    ) {
    }

    public function execute(SkinfoldSite $site, UpdateSkinfoldSiteDTO $dto): SkinfoldSite
    {
        return $this->sites->update($site, $dto);
    }
}
