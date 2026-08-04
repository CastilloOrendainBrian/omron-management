<?php

declare(strict_types=1);

namespace App\Application\Device\Commands;

use App\Domain\Device\Contracts\DeviceRepositoryInterface;
use App\Domain\Device\DTOs\UpdateDeviceDTO;
use App\Models\Device;

final readonly class UpdateDeviceUseCase
{
    public function __construct(
        private DeviceRepositoryInterface $devices,
    ) {
    }

    public function execute(Device $device, UpdateDeviceDTO $dto): Device
    {
        return $this->devices->update($device, $dto);
    }
}
