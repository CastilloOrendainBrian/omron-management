<?php

declare(strict_types=1);

namespace App\Http\Requests\SkinfoldProtocols;

use App\Domain\SkinfoldProtocol\DTOs\UpdateSkinfoldProtocolDTO;
use App\Models\SkinfoldProtocol;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateSkinfoldProtocolRequest extends FormRequest
{
    public function authorize(): bool
    {
        $protocol = $this->route('skinfold_protocol');

        return $protocol instanceof SkinfoldProtocol
            ? ($this->user()?->can('update', $protocol) ?? false)
            : false;
    }

    public function rules(): array
    {
        $protocol = $this->route('skinfold_protocol');
        $protocolId = $protocol instanceof SkinfoldProtocol ? $protocol->id : (int) $protocol;

        return [
            'name' => ['sometimes', 'string', 'max:255', "unique:skinfold_protocols,name,{$protocolId}"],
            'sites_count' => ['sometimes', 'integer', 'min:1', 'max:50'],
            'description' => ['sometimes', 'nullable', 'string'],
        ];
    }

    public function toDto(): UpdateSkinfoldProtocolDTO
    {
        return new UpdateSkinfoldProtocolDTO(
            name: $this->validated('name'),
            sitesCount: $this->validated('sites_count') !== null ? (int) $this->validated('sites_count') : null,
            description: $this->validated('description'),
        );
    }
}
