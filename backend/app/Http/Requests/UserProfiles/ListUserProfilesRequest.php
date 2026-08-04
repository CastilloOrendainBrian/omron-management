<?php

declare(strict_types=1);

namespace App\Http\Requests\UserProfiles;

use App\Domain\UserProfile\DTOs\ListUserProfilesDTO;
use App\Models\UserProfile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class ListUserProfilesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', UserProfile::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'sex' => ['sometimes', 'string', Rule::in(['male', 'female'])],
            'activity_level' => ['sometimes', 'string', Rule::in(['sedentary', 'light', 'moderate', 'active', 'very_active'])],
            'height_min' => ['sometimes', 'numeric', 'min:0', 'max:300'],
            'height_max' => ['sometimes', 'numeric', 'min:0', 'max:300'],
        ];
    }

    public function toDto(): ListUserProfilesDTO
    {
        return new ListUserProfilesDTO(
            perPage: (int) ($this->validated('per_page') ?? 25),
            sex: $this->validated('sex'),
            activityLevel: $this->validated('activity_level'),
            heightMin: $this->validated('height_min') !== null ? (float) $this->validated('height_min') : null,
            heightMax: $this->validated('height_max') !== null ? (float) $this->validated('height_max') : null,
        );
    }
}
