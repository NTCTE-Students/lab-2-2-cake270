<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
  
    protected $fillable = [
        'title',
        'description',
        'status',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn(string $value): string => match($value) {
                'pending' => 'Pending',
                'in_progress' => 'In Progress',
                'completed' => 'Completed',
                default => 'Unknown',
            },
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
