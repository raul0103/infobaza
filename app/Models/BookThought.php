<?php

namespace App\Models;

use App\Models\Concerns\OrdersByPage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookThought extends Model
{
    use OrdersByPage;

    protected $fillable = [
        'book_id',
        'movie_id',
        'content',
        'chapter',
        'page',
        'is_favorite',
    ];

    protected function casts(): array
    {
        return ['is_favorite' => 'boolean'];
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

    public function sourceUrl(): ?string
    {
        if ($this->book_id) {
            return route('books.show', $this->book_id);
        }
        if ($this->movie_id) {
            return route('movies.show', $this->movie_id);
        }

        return null;
    }

    public function editUrl(): string
    {
        if ($this->movie_id) {
            return route('movies.thoughts.edit', [$this->movie_id, $this]);
        }

        return route('books.thoughts.edit', [$this->book_id, $this]);
    }

    public function destroyUrl(): string
    {
        if ($this->movie_id) {
            return route('movies.thoughts.destroy', [$this->movie_id, $this]);
        }

        return route('books.thoughts.destroy', [$this->book_id, $this]);
    }
}
