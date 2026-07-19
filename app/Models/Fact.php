<?php

namespace App\Models;

use App\Services\SpacedRepetition;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fact extends Model
{
    protected $fillable = [
        'fact_group_id', 'title', 'text', 'source',
        'next_review_at', 'interval_days', 'review_count',
    ];

    protected function casts(): array
    {
        return ['next_review_at' => 'datetime'];
    }

    public function scopeDue(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereNull('next_review_at')->orWhere('next_review_at', '<=', now());
        });
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(FactGroup::class, 'fact_group_id');
    }

    public function recordReview(bool $known): void
    {
        SpacedRepetition::scheduleReview($this, $known);
    }
}
