<?php

declare(strict_types=1);

namespace App\Http\Requests\Goals;

use App\Domain\Goal\DTOs\CreateGoalDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreGoalRequest extends FormRequest
{
    public function authorize(): bool
    {
        $actor = $this->user();
        if ($actor === null) {
            return false;
        }

        if ($actor->hasRole(['admin', 'super-admin'])) {
            return true;
        }

        $userId = $this->input('user_id');

        return $userId !== null && (int) $userId === $actor->id;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'target_weight_kg' => ['nullable', 'numeric', 'min:0', 'max:700'],
            'target_body_fat_percentage' => ['nullable', 'numeric', 'min:0', 'max:99.9'],
            'start_date' => ['required', 'date'],
            'target_date' => ['nullable', 'date', 'after:start_date'],
            'status' => ['sometimes', 'string', Rule::in(['active', 'achieved', 'abandoned'])],
        ];
    }

    public function toDto(): CreateGoalDTO
    {
        return new CreateGoalDTO(
            userId: (int) $this->validated('user_id'),
            startDate: (string) $this->validated('start_date'),
            targetWeightKg: $this->validated('target_weight_kg') !== null ? (float) $this->validated('target_weight_kg') : null,
            targetBodyFatPercentage: $this->validated('target_body_fat_percentage') !== null ? (float) $this->validated('target_body_fat_percentage') : null,
            targetDate: $this->validated('target_date'),
            status: $this->validated('status'),
        );
    }
}
