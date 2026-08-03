<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

final class MeasurementSession extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'device_id',
        'measured_at',
        'source',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'measured_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function anthropometricMeasurement(): HasOne
    {
        return $this->hasOne(AnthropometricMeasurement::class);
    }

    public function bodyCompositionMeasurement(): HasOne
    {
        return $this->hasOne(BodyCompositionMeasurement::class);
    }

    public function skinfoldMeasurement(): HasOne
    {
        return $this->hasOne(SkinfoldMeasurement::class);
    }
}
