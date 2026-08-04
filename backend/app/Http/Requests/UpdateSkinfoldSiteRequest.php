<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Domain\SkinfoldSite\DTOs\UpdateSkinfoldSiteDTO;
use App\Models\SkinfoldSite;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateSkinfoldSiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        $site = $this->route('skinfold_site');

        return $site instanceof SkinfoldSite
            ? ($this->user()?->can('update', $site) ?? false)
            : false;
    }

    public function rules(): array
    {
        $site = $this->route('skinfold_site');
        $siteId = $site instanceof SkinfoldSite ? $site->id : (int) $site;

        return [
            'code' => ['sometimes', 'string', 'max:50', "unique:skinfold_sites,code,{$siteId}"],
            'name' => ['sometimes', 'string', 'max:255'],
        ];
    }

    public function toDto(): UpdateSkinfoldSiteDTO
    {
        return new UpdateSkinfoldSiteDTO(
            code: $this->validated('code'),
            name: $this->validated('name'),
        );
    }
}
