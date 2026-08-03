<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class BodyCompositionMeasurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'measurement_session_id',
        'body_fat_percentage',
        'muscle_percentage',
        'visceral_fat_level',
        'metabolic_age',
        'bmr_kcal',
    ];

    protected function casts(): array
    {
        return [
            'body_fat_percentage' => 'decimal:1',
            'muscle_percentage' => 'decimal:1',
            'visceral_fat_level' => 'integer',
            'metabolic_age' => 'integer',
            'bmr_kcal' => 'integer',
        ];
    }

    public function measurementSession(): BelongsTo
    {
        return $this->belongsTo(MeasurementSession::class);
    }
}
