<?php

declare(strict_types=1);

namespace App\Application\SkinfoldSite\Commands;

use App\Domain\SkinfoldSite\Contracts\SkinfoldSiteRepositoryInterface;
use App\Models\SkinfoldSite;

final readonly class DeleteSkinfoldSiteUseCase
{
    public function __construct(
        private SkinfoldSiteRepositoryInterface $sites,
    ) {
    }

    public function execute(SkinfoldSite $site): void
    {
        $this->sites->delete($site);
    }
}
