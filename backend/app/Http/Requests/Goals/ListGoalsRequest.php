<?php

declare(strict_types=1);

namespace App\Http\Requests\Goals;

use App\Domain\Goal\DTOs\ListGoalsDTO;
use App\Models\Goal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class ListGoalsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', Goal::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'user_id' => ['sometimes', 'integer', 'exists:users,id'],
            'status' => ['sometimes', 'string', Rule::in(['active', 'achieved', 'abandoned'])],
        ];
    }

    public function toDto(): ListGoalsDTO
    {
        return new ListGoalsDTO(
            perPage: (int) ($this->validated('per_page') ?? 25),
            userId: $this->validated('user_id') !== null ? (int) $this->validated('user_id') : null,
            status: $this->validated('status'),
        );
    }
}
