<?php

namespace App\Models;

use App\Services\SpacedRepetition;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NoteQuestion extends Model
{
    protected $fillable = [
        'note_id', 'question', 'answer',
        'next_review_at', 'interval_days', 'review_count',
    ];

    protected function casts(): array
    {
        return ['next_review_at' => 'datetime'];
    }

    public function note(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    public function scopeDue(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereNull('next_review_at')->orWhere('next_review_at', '<=', now());
        });
    }

    public function recordReview(bool $known): void
    {
        SpacedRepetition::scheduleReview($this, $known);
    }
}
