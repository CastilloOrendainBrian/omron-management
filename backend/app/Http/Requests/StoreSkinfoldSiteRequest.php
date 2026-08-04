<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Domain\SkinfoldSite\DTOs\CreateSkinfoldSiteDTO;
use App\Models\SkinfoldSite;
use Illuminate\Foundation\Http\FormRequest;

final class StoreSkinfoldSiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', SkinfoldSite::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', 'unique:skinfold_sites,code'],
            'name' => ['required', 'string', 'max:255'],
        ];
    }

    public function toDto(): CreateSkinfoldSiteDTO
    {
        return new CreateSkinfoldSiteDTO(
            code: (string) $this->validated('code'),
            name: (string) $this->validated('name'),
        );
    }
}
