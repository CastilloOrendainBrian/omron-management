<?php

declare(strict_types=1);

namespace App\Http\Requests\Users;

use App\Domain\User\DTOs\UpdateUserWithProfileDTO;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateUserWithProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->route('user');

        return $user instanceof User
            ? ($this->user()?->can('update', $user) ?? false)
            : false;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'email', 'max:255', 'unique:users,email,' . $this->route('user')->id],
            'password' => ['sometimes', 'string', 'min:8'],
            'sex' => ['sometimes', 'string', Rule::in(['male', 'female'])],
            'birth_date' => ['sometimes', 'date'],
            'height_reference_cm' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:300'],
            'activity_level' => ['sometimes', 'nullable', 'string', Rule::in(['sedentary', 'light', 'moderate', 'active', 'very_active'])],
        ];
    }

    public function toDto(): UpdateUserWithProfileDTO
    {
        return new UpdateUserWithProfileDTO(
            name: $this->validated('name'),
            email: $this->validated('email'),
            password: $this->validated('password'),
            sex: $this->validated('sex'),
            birthDate: $this->validated('birth_date'),
            heightReferenceCm: $this->validated('height_reference_cm') !== null ? (float) $this->validated('height_reference_cm') : null,
            activityLevel: $this->validated('activity_level'),
        );
    }
}
