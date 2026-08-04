<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Domain\SkinfoldSite\DTOs\ListSkinfoldSitesDTO;
use App\Models\SkinfoldSite;
use Illuminate\Foundation\Http\FormRequest;

final class ListSkinfoldSitesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', SkinfoldSite::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'code' => ['sometimes', 'string', 'max:50'],
            'name' => ['sometimes', 'string', 'max:255'],
        ];
    }

    public function toDto(): ListSkinfoldSitesDTO
    {
        return new ListSkinfoldSitesDTO(
            perPage: (int) ($this->validated('per_page') ?? 25),
            code: $this->validated('code'),
            name: $this->validated('name'),
        );
    }
}
