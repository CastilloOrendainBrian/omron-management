<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Device extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'brand',
        'model',
        'serial_number',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function measurementSessions(): HasMany
    {
        return $this->hasMany(MeasurementSession::class);
    }
}
