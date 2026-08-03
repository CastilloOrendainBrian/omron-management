<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Goal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'target_weight_kg',
        'target_body_fat_percentage',
        'start_date',
        'target_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'target_weight_kg' => 'decimal:3',
            'target_body_fat_percentage' => 'decimal:1',
            'start_date' => 'date',
            'target_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
