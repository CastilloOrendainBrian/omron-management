<?php

declare(strict_types=1);

namespace App\Domain\SkinfoldSite\Contracts;

use App\Domain\SkinfoldSite\DTOs\CreateSkinfoldSiteDTO;
use App\Domain\SkinfoldSite\DTOs\ListSkinfoldSitesDTO;
use App\Domain\SkinfoldSite\DTOs\UpdateSkinfoldSiteDTO;
use App\Models\SkinfoldSite;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SkinfoldSiteRepositoryInterface
{
    public function find(int $id): ?SkinfoldSite;

    public function paginate(ListSkinfoldSitesDTO $dto): LengthAwarePaginator;

    public function create(CreateSkinfoldSiteDTO $dto): SkinfoldSite;

    public function update(SkinfoldSite $site, UpdateSkinfoldSiteDTO $dto): SkinfoldSite;

    public function delete(SkinfoldSite $site): void;
}
