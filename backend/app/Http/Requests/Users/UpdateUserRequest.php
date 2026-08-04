<?php

declare(strict_types=1);

namespace App\Http\Requests\Users;

use App\Domain\User\DTOs\UpdateUserDTO;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateUserRequest extends FormRequest
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
        $user = $this->route('user');
        $userId = $user instanceof User ? $user->id : (int) $user;

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'email', 'max:255', "unique:users,email,{$userId}"],
            'password' => ['sometimes', 'string', 'min:8'],
        ];
    }

    public function toDto(): UpdateUserDTO
    {
        return new UpdateUserDTO(
            name: $this->validated('name'),
            email: $this->validated('email'),
            password: $this->validated('password'),
        );
    }
}
