<?php

declare(strict_types=1);

namespace App\Application\SkinfoldSite\Queries;

use App\Models\SkinfoldSite;

final readonly class ShowSkinfoldSiteUseCase
{
    public function execute(SkinfoldSite $site): SkinfoldSite
    {
        return $site;
    }
}
