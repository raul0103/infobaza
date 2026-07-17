<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quote extends Model
{
    protected $fillable = [
        'book_id', 'movie_id',
        'text', 'page', 'character', 'context',
    ];

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
}
