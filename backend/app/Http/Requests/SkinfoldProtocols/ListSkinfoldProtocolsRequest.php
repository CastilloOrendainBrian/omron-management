<?php

declare(strict_types=1);

namespace App\Http\Requests\SkinfoldProtocols;

use App\Domain\SkinfoldProtocol\DTOs\ListSkinfoldProtocolsDTO;
use App\Models\SkinfoldProtocol;
use Illuminate\Foundation\Http\FormRequest;

final class ListSkinfoldProtocolsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', SkinfoldProtocol::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'name' => ['sometimes', 'string', 'max:255'],
            'sites_count' => ['sometimes', 'integer', 'min:1', 'max:50'],
        ];
    }

    public function toDto(): ListSkinfoldProtocolsDTO
    {
        return new ListSkinfoldProtocolsDTO(
            perPage: (int) ($this->validated('per_page') ?? 25),
            name: $this->validated('name'),
            sitesCount: $this->validated('sites_count') !== null ? (int) $this->validated('sites_count') : null,
        );
    }
}
