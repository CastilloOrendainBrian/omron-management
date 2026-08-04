<?php

declare(strict_types=1);

namespace App\Http\Requests\SkinfoldProtocols;

use App\Domain\SkinfoldProtocol\DTOs\CreateSkinfoldProtocolDTO;
use App\Models\SkinfoldProtocol;
use Illuminate\Foundation\Http\FormRequest;

final class StoreSkinfoldProtocolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', SkinfoldProtocol::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:skinfold_protocols,name'],
            'sites_count' => ['required', 'integer', 'min:1', 'max:50'],
            'description' => ['sometimes', 'nullable', 'string'],
        ];
    }

    public function toDto(): CreateSkinfoldProtocolDTO
    {
        return new CreateSkinfoldProtocolDTO(
            name: (string) $this->validated('name'),
            sitesCount: (int) $this->validated('sites_count'),
            description: $this->validated('description'),
        );
    }
}
