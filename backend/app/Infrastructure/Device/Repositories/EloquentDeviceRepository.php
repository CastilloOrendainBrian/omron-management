<?php

declare(strict_types=1);

namespace App\Infrastructure\Device\Repositories;

use App\Domain\Device\Contracts\DeviceRepositoryInterface;
use App\Domain\Device\DTOs\CreateDeviceDTO;
use App\Domain\Device\DTOs\ListDevicesDTO;
use App\Domain\Device\DTOs\UpdateDeviceDTO;
use App\Models\Device;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class EloquentDeviceRepository implements DeviceRepositoryInterface
{
    public function find(int $id): ?Device
    {
        return Device::find($id);
    }

    public function paginate(ListDevicesDTO $dto): LengthAwarePaginator
    {
        return Device::query()
            ->when($dto->userId !== null, fn ($q) => $q->where('user_id', $dto->userId))
            ->when($dto->brand !== null && $dto->brand !== '', fn ($q) => $q->where('brand', 'ilike', '%' . $dto->brand . '%'))
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate($dto->perPage);
    }

    public function create(CreateDeviceDTO $dto): Device
    {
        return Device::create([
            'user_id' => $dto->userId,
            'brand' => $dto->brand,
            'model' => $dto->model,
            'serial_number' => $dto->serialNumber,
        ]);
    }

    public function update(Device $device, UpdateDeviceDTO $dto): Device
    {
        $data = array_filter([
            'user_id' => $dto->userId,
            'brand' => $dto->brand,
            'model' => $dto->model,
            'serial_number' => $dto->serialNumber,
        ], static fn (mixed $value): bool => $value !== null);

        if ($data !== []) {
            $device->update($data);
        }

        return $device->fresh();
    }

    public function delete(Device $device): void
    {
        $device->delete();
    }
}
