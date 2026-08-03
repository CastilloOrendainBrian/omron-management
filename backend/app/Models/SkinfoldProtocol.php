<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class SkinfoldProtocol extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'sites_count',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'sites_count' => 'integer',
        ];
    }

    public function skinfoldMeasurements(): HasMany
    {
        return $this->hasMany(SkinfoldMeasurement::class);
    }
}
