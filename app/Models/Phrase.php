<?php

namespace App\Models;

use App\Models\Concerns\OrdersByPage;
use App\Services\SpacedRepetition;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Phrase extends Model
{
    use OrdersByPage;

    protected $fillable = [
        'book_id', 'movie_id',
        'text', 'note', 'page', 'character', 'is_favorite',
        'next_review_at', 'interval_days', 'review_count',
    ];

    protected function casts(): array
    {
        return [
            'is_favorite' => 'boolean',
            'next_review_at' => 'datetime',
        ];
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }

    public function sourceLabel(): string
    {
        if ($this->book) {
            return $this->book->title;
        }
        if ($this->movie) {
            return $this->movie->title;
        }

        return 'Без источника';
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
