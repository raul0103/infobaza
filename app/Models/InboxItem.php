<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InboxItem extends Model
{
    protected $fillable = [
        'user_id',
        'content',
        'note_id',
        'book_id',
        'movie_id',
        'dictionary_entry_id',
        'processed_at',
    ];

    protected function casts(): array
    {
        return ['processed_at' => 'datetime'];
    }

    public function note(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }

    public function dictionaryEntry(): BelongsTo
    {
        return $this->belongsTo(DictionaryEntry::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isProcessed(): bool
    {
        return $this->processed_at !== null;
    }

    public function targetLabel(): ?string
    {
        if ($this->note_id) {
            return 'Запись';
        }
        if ($this->book_id) {
            return 'Книга';
        }
        if ($this->movie_id) {
            return 'Фильм';
        }
        if ($this->dictionary_entry_id) {
            return 'Слово';
        }

        return null;
    }
}
