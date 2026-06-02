<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reminder extends Model
{
    protected $fillable = ['user_id', 'title', 'body', 'remind_at', 'completed_at', 'visibility'];

    protected function casts(): array
    {
        return [
            'remind_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->whereNull('completed_at');
    }

    public function scopeDue(Builder $query): Builder
    {
        return $query->pending()->where('remind_at', '<=', now());
    }

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
