<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class SkinfoldMeasurementDetail extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'skinfold_measurement_id',
        'skinfold_site_id',
        'value_mm',
    ];

    protected function casts(): array
    {
        return [
            'value_mm' => 'decimal:1',
        ];
    }

    public function skinfoldMeasurement(): BelongsTo
    {
        return $this->belongsTo(SkinfoldMeasurement::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(SkinfoldSite::class, 'skinfold_site_id');
    }
}
