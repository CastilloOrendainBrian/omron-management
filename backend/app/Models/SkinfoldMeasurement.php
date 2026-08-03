<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class SkinfoldMeasurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'measurement_session_id',
        'skinfold_protocol_id',
        'estimated_body_fat_percentage',
    ];

    protected function casts(): array
    {
        return [
            'estimated_body_fat_percentage' => 'decimal:1',
        ];
    }

    public function measurementSession(): BelongsTo
    {
        return $this->belongsTo(MeasurementSession::class);
    }

    public function protocol(): BelongsTo
    {
        return $this->belongsTo(SkinfoldProtocol::class, 'skinfold_protocol_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(SkinfoldMeasurementDetail::class);
    }
}
