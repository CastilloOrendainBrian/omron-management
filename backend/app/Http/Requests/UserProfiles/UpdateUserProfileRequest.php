<?php

declare(strict_types=1);

namespace App\Http\Requests\UserProfiles;

use App\Domain\UserProfile\DTOs\UpdateUserProfileDTO;
use App\Models\UserProfile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateUserProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        $profile = $this->route('profile');

        return $profile instanceof UserProfile
            ? ($this->user()?->can('update', $profile) ?? false)
            : false;
    }

    public function rules(): array
    {
        return [
            'sex' => ['sometimes', 'string', Rule::in(['male', 'female'])],
            'birth_date' => ['sometimes', 'date'],
            'height_reference_cm' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:300'],
            'activity_level' => ['sometimes', 'nullable', 'string', Rule::in(['sedentary', 'light', 'moderate', 'active', 'very_active'])],
        ];
    }

    public function toDto(): UpdateUserProfileDTO
    {
        return new UpdateUserProfileDTO(
            sex: $this->validated('sex'),
            birthDate: $this->validated('birth_date'),
            heightReferenceCm: $this->validated('height_reference_cm') !== null ? (float) $this->validated('height_reference_cm') : null,
            activityLevel: $this->validated('activity_level'),
        );
    }
}
