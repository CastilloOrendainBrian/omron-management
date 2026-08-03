<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class SkinfoldSite extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
    ];

    public function skinfoldMeasurementDetails(): HasMany
    {
        return $this->hasMany(SkinfoldMeasurementDetail::class);
    }
}
