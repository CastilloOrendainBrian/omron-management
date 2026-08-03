<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class AnthropometricMeasurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'measurement_session_id',
        'height_cm',
        'weight_kg',
        'bmi',
    ];

    protected function casts(): array
    {
        return [
            'height_cm' => 'decimal:2',
            'weight_kg' => 'decimal:3',
            'bmi' => 'decimal:1',
        ];
    }

    public function measurementSession(): BelongsTo
    {
        return $this->belongsTo(MeasurementSession::class);
    }
}
