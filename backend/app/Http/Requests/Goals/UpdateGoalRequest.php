<?php

declare(strict_types=1);

namespace App\Http\Requests\Goals;

use App\Domain\Goal\DTOs\UpdateGoalDTO;
use App\Models\Goal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateGoalRequest extends FormRequest
{
    public function authorize(): bool
    {
        $goal = $this->route('goal');

        return $goal instanceof Goal
            ? ($this->user()?->can('update', $goal) ?? false)
            : false;
    }

    public function rules(): array
    {
        return [
            'target_weight_kg' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:700'],
            'target_body_fat_percentage' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:99.9'],
            'start_date' => ['sometimes', 'date'],
            'target_date' => ['sometimes', 'nullable', 'date', 'after:start_date'],
            'status' => ['sometimes', 'string', Rule::in(['active', 'achieved', 'abandoned'])],
        ];
    }

    public function toDto(): UpdateGoalDTO
    {
        return new UpdateGoalDTO(
            targetWeightKg: $this->validated('target_weight_kg') !== null ? (float) $this->validated('target_weight_kg') : null,
            targetBodyFatPercentage: $this->validated('target_body_fat_percentage') !== null ? (float) $this->validated('target_body_fat_percentage') : null,
            startDate: $this->validated('start_date'),
            targetDate: $this->validated('target_date'),
            status: $this->validated('status'),
        );
    }
}
