<?php

declare(strict_types=1);

namespace App\Application\Device\Commands;

use App\Domain\Device\Contracts\DeviceRepositoryInterface;
use App\Models\Device;

final readonly class DeleteDeviceUseCase
{
    public function __construct(
        private DeviceRepositoryInterface $devices,
    ) {
    }

    public function execute(Device $device): void
    {
        $this->devices->delete($device);
    }
}
