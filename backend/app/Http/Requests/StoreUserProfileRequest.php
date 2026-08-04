<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Domain\UserProfile\DTOs\CreateUserProfileDTO;
use App\Models\UserProfile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreUserProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', UserProfile::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id', 'unique:user_profiles,user_id'],
            'sex' => ['required', 'string', Rule::in(['male', 'female'])],
            'birth_date' => ['required', 'date'],
            'height_reference_cm' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:300'],
            'activity_level' => ['sometimes', 'nullable', 'string', Rule::in(['sedentary', 'light', 'moderate', 'active', 'very_active'])],
        ];
    }

    public function toDto(): CreateUserProfileDTO
    {
        return new CreateUserProfileDTO(
            userId: (int) $this->validated('user_id'),
            sex: (string) $this->validated('sex'),
            birthDate: (string) $this->validated('birth_date'),
            heightReferenceCm: $this->validated('height_reference_cm') !== null ? (float) $this->validated('height_reference_cm') : null,
            activityLevel: $this->validated('activity_level'),
        );
    }
}
