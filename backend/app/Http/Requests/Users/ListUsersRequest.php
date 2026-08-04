<?php

declare(strict_types=1);

namespace App\Http\Requests\Users;

use App\Domain\User\DTOs\ListUsersDTO;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

final class ListUsersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', User::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'max:255'],
        ];
    }

    public function toDto(): ListUsersDTO
    {
        return new ListUsersDTO(
            perPage: (int) ($this->validated('per_page') ?? 25),
            name: $this->validated('name'),
            email: $this->validated('email'),
        );
    }
}
