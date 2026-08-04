<?php

declare(strict_types=1);

namespace App\Domain\Device\Contracts;

use App\Domain\Device\DTOs\CreateDeviceDTO;
use App\Domain\Device\DTOs\ListDevicesDTO;
use App\Domain\Device\DTOs\UpdateDeviceDTO;
use App\Models\Device;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface DeviceRepositoryInterface
{
    public function find(int $id): ?Device;

    public function paginate(ListDevicesDTO $dto): LengthAwarePaginator;

    public function create(CreateDeviceDTO $dto): Device;

    public function update(Device $device, UpdateDeviceDTO $dto): Device;

    public function delete(Device $device): void;
}
