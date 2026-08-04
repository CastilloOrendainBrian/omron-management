<?php

declare(strict_types=1);

namespace App\Http\Requests\Devices;

use App\Domain\Device\DTOs\CreateDeviceDTO;
use Illuminate\Foundation\Http\FormRequest;

final class StoreDeviceRequest extends FormRequest
{
    public function authorize(): bool
    {
        $actor = $this->user();
        if ($actor === null) {
            return false;
        }

        if ($actor->hasRole(['admin', 'super-admin'])) {
            return true;
        }

        $userId = $this->input('user_id');

        return $userId !== null && (int) $userId === $actor->id;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'brand' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'serial_number' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function toDto(): CreateDeviceDTO
    {
        return new CreateDeviceDTO(
            brand: (string) $this->validated('brand'),
            model: (string) $this->validated('model'),
            userId: $this->validated('user_id') !== null ? (int) $this->validated('user_id') : null,
            serialNumber: $this->validated('serial_number'),
        );
    }
}
