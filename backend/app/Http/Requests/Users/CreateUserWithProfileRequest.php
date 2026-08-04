<?php

declare(strict_types=1);

namespace App\Http\Requests\Users;

use App\Domain\User\DTOs\CreateUserWithProfileDTO;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class CreateUserWithProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', User::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'sex' => ['required', 'string', Rule::in(['male', 'female'])],
            'birth_date' => ['required', 'date'],
            'height_reference_cm' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:300'],
            'activity_level' => ['sometimes', 'nullable', 'string', Rule::in(['sedentary', 'light', 'moderate', 'active', 'very_active'])],
        ];
    }

    public function toDto(): CreateUserWithProfileDTO
    {
        return new CreateUserWithProfileDTO(
            name: (string) $this->validated('name'),
            email: (string) $this->validated('email'),
            password: (string) $this->validated('password'),
            sex: (string) $this->validated('sex'),
            birthDate: (string) $this->validated('birth_date'),
            heightReferenceCm: $this->validated('height_reference_cm') !== null ? (float) $this->validated('height_reference_cm') : null,
            activityLevel: $this->validated('activity_level'),
        );
    }
}
