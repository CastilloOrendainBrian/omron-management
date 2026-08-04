<?php

declare(strict_types=1);

namespace App\Application\Device\Queries;

use App\Domain\Device\Contracts\DeviceRepositoryInterface;
use App\Domain\Device\DTOs\ListDevicesDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final readonly class ListDevicesUseCase
{
    public function __construct(
        private DeviceRepositoryInterface $devices,
    ) {
    }

    public function execute(ListDevicesDTO $dto): LengthAwarePaginator
    {
        return $this->devices->paginate($dto);
    }
}
